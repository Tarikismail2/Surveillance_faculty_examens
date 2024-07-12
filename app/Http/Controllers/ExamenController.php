<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Module;
use App\Models\Salle;
use App\Models\Enseignant;
use App\Models\Inscription;
use App\Models\SessionExam;
use App\Models\Filiere;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;

class ExamenController extends Controller
{
    public function index()
    {
        $examens = Examen::with('salles', 'module', 'enseignant')->get();
        return view('examens.index', compact('examens'));
    }


    public function create()
    {
        $salles = Salle::all();
        $enseignants = Enseignant::all();
        $sessions = SessionExam::all();
        $filieres = Filiere::all();

        return view('examens.create', compact('salles', 'enseignants', 'sessions', 'filieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i|before_or_equal:18:30',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut|before_or_equal:18:30',
            'id_module' => 'required|exists:modules,id',
            'id_salle' => 'required|exists:salles,id',
            'additional_salles.*' => 'nullable|exists:salles,id',
            'id_enseignant' => 'required|exists:enseignants,id',
            'id_session' => 'required|exists:session_exams,id',
            'id_filiere' => 'required|exists:filieres,code_etape',
        ]);

        // Validation supplémentaire pour les horaires des examens
        $heure_debut = strtotime($request->heure_debut);
        $heure_fin = strtotime($request->heure_fin);

        $matin_start = strtotime('08:00');
        $matin_end = strtotime('12:30');
        $apres_midi_start = strtotime('14:00');
        $apres_midi_end = strtotime('18:30');

        if (!(($heure_debut >= $matin_start && $heure_fin <= $matin_end && $heure_debut < $heure_fin) ||
            ($heure_debut >= $apres_midi_start && $heure_fin <= $apres_midi_end && $heure_debut < $heure_fin))) {
            return back()->withErrors(['error' => 'La durée de l\'examen doit être entre 08:00 et 12:30 pour le matin ou entre 14:00 et 18:30 pour l\'après-midi.']);
        }

        // Validation pour empêcher la création d'un examen pour le même module de la même filière
        $existingExam = Examen::where('id_module', $request->id_module)
            ->whereHas('module', function ($query) use ($request) {
                $query->where('code_etape', $request->id_filiere);
            })->exists();

        if ($existingExam) {
            return back()->withErrors(['error' => 'Un examen pour ce module et cette filière existe déjà.']);
        }

        // Validation pour empêcher la création de deux examens pour la même filière à la même durée
        $overlappingExam = Examen::where('date', $request->date)
            ->where(function ($query) use ($request, $heure_debut, $heure_fin) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->whereHas('module', function ($query) use ($request) {
                $query->where('code_etape', $request->id_filiere);
            })
            ->whereHas('salles', function ($query) use ($request) {
                $query->where('salles.id', $request->id_salle);
            })
            ->exists();

        if ($overlappingExam) {
            return back()->withErrors(['error' => 'Il existe déjà un examen pour cette filière dans la même durée.']);
        }

        // Validation pour empêcher l'affectation d'une salle déjà occupée
        $occupiedSalle = Examen::where('date', $request->date)
            ->where(function ($query) use ($request, $heure_debut, $heure_fin) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->whereHas('salles', function ($query) use ($request) {
                $query->where('salles.id', $request->id_salle);
            })->exists();

        if ($occupiedSalle) {
            return back()->withErrors(['error' => 'Cette salle est déjà occupée pendant cette période.']);
        }

        $salles_ids = array_filter(array_merge([$request->id_salle], $request->additional_salles ?? []));
        $capacite_totale = Salle::whereIn('id', $salles_ids)->sum('capacite');
        $module = Module::findOrFail($request->id_module);
        $nombreInscrits = Inscription::where('id_module', $module->id)->count();

        if ($capacite_totale < $nombreInscrits) {
            return back()->withErrors(['error' => 'La capacité totale des salles sélectionnées est insuffisante pour accueillir cet examen.']);
        }

        $session = SessionExam::findOrFail($request->id_session);
        if ($request->date < $session->date_debut || $request->date > $session->date_fin) {
            return back()->withErrors(['error' => 'La date de l\'examen doit être incluse dans la durée de la session d\'examen.']);
        }

        $examen = Examen::create([
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'id_module' => $module->id,
            'id_enseignant' => $request->id_enseignant,
            'id_session' => $request->id_session,
            'id_salle' => $request->id_salle,
        ]);

        // Attacher les salles supplémentaires
        $examen->salles()->sync($salles_ids);

        return redirect()->route('examens.index')->with('success', 'Examen créé avec succès.');
    }


    public function edit(Examen $examen)
    {
        $examen->load('filiere', 'module');

        // Autres chargements de données nécessaires
        $modules = Module::all();
        $salles = Salle::all();
        $enseignants = Enseignant::all();
        $sessions = SessionExam::all();
        $filieres = Filiere::all();

        return view('examens.edit', compact('examen', 'modules', 'salles', 'enseignants', 'sessions', 'filieres'));
    }


    public function update(Request $request, Examen $examen)
    {
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i|before_or_equal:18:30',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut|before_or_equal:18:30',
            'id_module' => 'required|exists:modules,id',
            'id_salle' => 'required|exists:salles,id',
            'additional_salles.*' => 'nullable|exists:salles,id',
            'id_enseignant' => 'required|exists:enseignants,id',
            'id_session' => 'required|exists:session_exams,id',
            'id_filiere' => 'required|exists:filieres,code_etape',
        ]);

        // Validation supplémentaire pour les horaires des examens
        $heure_debut = strtotime($request->heure_debut);
        $heure_fin = strtotime($request->heure_fin);

        $matin_start = strtotime('08:00');
        $matin_end = strtotime('12:30');
        $apres_midi_start = strtotime('14:00');
        $apres_midi_end = strtotime('18:30');

        if (!(($heure_debut >= $matin_start && $heure_fin <= $matin_end && $heure_debut < $heure_fin) ||
            ($heure_debut >= $apres_midi_start && $heure_fin <= $apres_midi_end && $heure_debut < $heure_fin))) {
            return back()->withErrors(['error' => 'La durée de l\'examen doit être entre 08:00 et 12:30 pour le matin ou entre 14:00 et 18:30 pour l\'après-midi.']);
        }

        // Validation pour empêcher la création d'un examen pour le même module de la même filière
        $existingExam = Examen::where('id_module', $request->id_module)
            ->where('id', '!=', $examen->id)
            ->whereHas('module', function ($query) use ($request) {
                $query->where('code_etape', $request->id_filiere);
            })->exists();

        if ($existingExam) {
            return back()->withErrors(['error' => 'Un examen pour ce module et cette filière existe déjà.']);
        }

        // Validation pour empêcher la création de deux examens pour la même filière à la même durée
        $overlappingExam = Examen::where('date', $request->date)
            ->where('id', '!=', $examen->id)
            ->where(function ($query) use ($request, $heure_debut, $heure_fin) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->whereHas('module', function ($query) use ($request) {
                $query->where('code_etape', $request->id_filiere);
            })->exists();

        if ($overlappingExam) {
            return back()->withErrors(['error' => 'Il existe déjà un examen pour cette filière dans la même durée.']);
        }

        // Validation pour empêcher l'affectation d'une salle déjà occupée
        $occupiedSalle = Examen::where('date', $request->date)
            ->where('id', '!=', $examen->id)
            ->where(function ($query) use ($request, $heure_debut, $heure_fin) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->whereHas('salles', function ($query) use ($request) {
                $query->where('salles.id', $request->id_salle);
            })->exists();

        if ($occupiedSalle) {
            return back()->withErrors(['error' => 'Cette salle est déjà occupée pendant cette période.']);
        }

        $salles_ids = array_filter(array_merge([$request->id_salle], $request->additional_salles ?? []));
        $capacite_totale = Salle::whereIn('id', $salles_ids)->sum('capacite');
        $module = Module::findOrFail($request->id_module);
        $nombreInscrits = Inscription::where('id_module', $module->id)->count();

        if ($capacite_totale < $nombreInscrits) {
            return back()->withErrors(['error' => 'La capacité totale des salles sélectionnées est insuffisante pour accueillir cet examen.']);
        }

        $session = SessionExam::findOrFail($request->id_session);
        if ($request->date < $session->date_debut || $request->date > $session->date_fin) {
            return back()->withErrors(['error' => 'La date de l\'examen doit être incluse dans la durée de la session d\'examen.']);
        }

        $examen->update([
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'id_module' => $module->id,
            'id_enseignant' => $request->id_enseignant,
            'id_session' => $request->id_session,
        ]);

        // Attacher les salles supplémentaires
        $examen->salles()->sync($salles_ids);

        return redirect()->route('examens.index')->with('success', 'Examen mis à jour avec succès.');
    }


    public function destroy(Examen $examen)
    {
        $examen->delete();
        return redirect()->route('examens.index')->with('success', 'Examen supprimé avec succès.');
    }

    public function getModulesByFiliere($filiere_id)
    {
        $modules = Module::where('code_etape', $filiere_id)
            ->whereDoesntHave('examens')
            ->withCount('inscriptions')
            ->get();
        return response()->json($modules);
    }



    // Distribution the surveillants
    public function show($id)
    {
        $examen = Examen::findOrFail($id);

        // Charger les salles affectées avec leurs enseignants pour cet examen
        $sallesAffectees = Salle::whereExists(function ($query) use ($id) {
            $query->select('*')
                ->from('examens')
                ->join('examen_salle_enseignant', 'examens.id', '=', 'examen_salle_enseignant.id_examen')
                ->whereColumn('salles.id', 'examen_salle_enseignant.id_salle')
                ->where('examens.id', $id);
        })->get();

        // Charger les enseignants pour chaque salle
        foreach ($sallesAffectees as $salle) {
            $salle->enseignants = $salle->enseignants($id)->get();
        }

        return view('examens.show', [
            'examen' => $examen,
            'sallesAffectees' => $sallesAffectees,
        ]);
    }


    public function showAssignInvigilatorsForm($id)
    {
        $examen = Examen::findOrFail($id);
        $salles = Salle::all();
        $enseignants = DB::table('enseignants')->get(); // Assume you have a table named 'enseignants'

        return view('examens.assign-invigilators', compact('examen', 'salles', 'enseignants'));
    }

    public function assignInvigilators(Request $request, $id)
    {
        $examen = Examen::findOrFail($id);
        $date = $examen->date;
        $heure_debut = strtotime($examen->heure_debut);
        $heure_fin = strtotime($examen->heure_fin);

        $errors = [];

        // Vérifier les conditions pour chaque enseignant et chaque salle avant toute affectation
        foreach ($request->enseignants as $salleId => $enseignantIds) {
            foreach ($enseignantIds as $enseignantId) {
                // Check if the invigilator is already assigned to the same room for this exam
                $existingRoomAssignment = DB::table('examen_salle_enseignant')
                    ->where('id_enseignant', $enseignantId)
                    ->where('id_examen', $id)
                    ->where('id_salle', $salleId)
                    ->exists();

                if ($existingRoomAssignment) {
                    $errors[] = "L'enseignant {$enseignantId} est déjà affecté à la salle {$salleId} pour cet examen.";
                    continue;
                }

                // Check if the invigilator is already assigned to another room for this exam
                $existingAssignment = DB::table('examen_salle_enseignant')
                    ->where('id_enseignant', $enseignantId)
                    ->where('id_examen', $id)
                    ->exists();

                if ($existingAssignment) {
                    $errors[] = "L'enseignant {$enseignantId} est déjà affecté à une autre salle pour cet examen.";
                    continue;
                }

                // Check for overlapping assignments
                $overlappingAssignments = DB::table('examen_salle_enseignant')
                    ->join('examens', 'examen_salle_enseignant.id_examen', '=', 'examens.id')
                    ->where('examen_salle_enseignant.id_enseignant', $enseignantId)
                    ->where('examens.date', $date)
                    ->where(function ($query) use ($heure_debut, $heure_fin) {
                        $query->whereBetween('examens.heure_debut', [date('H:i', $heure_debut), date('H:i', $heure_fin)])
                            ->orWhereBetween('examens.heure_fin', [date('H:i', $heure_debut), date('H:i', $heure_fin)])
                            ->orWhere(function ($query) use ($heure_debut, $heure_fin) {
                                $query->where('examens.heure_debut', '<=', date('H:i', $heure_debut))
                                    ->where('examens.heure_fin', '>=', date('H:i', $heure_fin));
                            });
                    })
                    ->exists();

                if ($overlappingAssignments) {
                    $errors[] = "L'enseignant {$enseignantId} est déjà affecté à une autre salle pendant cette période.";
                    continue;
                }

                // Check daily assignments limit
                $dailyAssignments = DB::table('examen_salle_enseignant')
                    ->join('examens', 'examen_salle_enseignant.id_examen', '=', 'examens.id')
                    ->where('examen_salle_enseignant.id_enseignant', $enseignantId)
                    ->where('examens.date', $date)
                    ->count();

                if ($dailyAssignments >= 2) {
                    $errors[] = "L'enseignant {$enseignantId} ne peut pas superviser plus de deux examens par jour.";
                    continue;
                }
            }
        }

        // Si des erreurs sont trouvées, retourner en arrière avec les erreurs
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        // Si aucune erreur, procéder à l'affectation des surveillants
        foreach ($request->enseignants as $salleId => $enseignantIds) {
            foreach ($enseignantIds as $enseignantId) {
                // Insérer seulement si l'enseignant n'est pas déjà affecté à cette salle pour cet examen
                if (!DB::table('examen_salle_enseignant')
                    ->where('id_examen', $examen->id)
                    ->where('id_salle', $salleId)
                    ->where('id_enseignant', $enseignantId)
                    ->exists()) {
                    DB::table('examen_salle_enseignant')->insert([
                        'id_examen' => $examen->id,
                        'id_salle' => $salleId,
                        'id_enseignant' => $enseignantId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('examens.show', $examen->id)->with('success', 'Les surveillants ont été assignés avec succès.');
    }



    public function editInvigilators($id)
    {
        $examen = Examen::findOrFail($id);
        $salles = Salle::whereHas('examens', function ($query) use ($id) {
            $query->where('examens.id', $id);
        })->get();

        // Loop through the salles to fetch enseignants for the specific examen
        foreach ($salles as $salle) {
            $salle->enseignants = $salle->enseignants($id)->get();
        }

        $enseignants = Enseignant::all(); // Fetch all enseignants to populate the select options

        return view('examens.edit_invigilators', compact('examen', 'salles', 'enseignants'));
    }

    public function updateInvigilators(Request $request, $id)
    {
        $examen = Examen::findOrFail($id);
        $date = $examen->date;
        $heure_debut = strtotime($examen->heure_debut);
        $heure_fin = strtotime($examen->heure_fin);

        $errors = [];

        // Vérifier les conditions pour chaque enseignant et chaque salle avant toute affectation
        foreach ($request->enseignants as $id_salle => $enseignantIds) {
            foreach ($enseignantIds as $id_enseignant) {
                // Check if the invigilator is already assigned to the same room for this exam
                $existingRoomAssignment = DB::table('examen_salle_enseignant')
                    ->where('id_enseignant', $id_enseignant)
                    ->where('id_examen', $id)
                    ->where('id_salle', $id_salle)
                    ->exists();

                if ($existingRoomAssignment) {
                    $errors[] = "L'enseignant {$id_enseignant} est déjà affecté à la salle {$id_salle} pour cet examen.";
                    continue;
                }

                // Check if the invigilator is already assigned to another room for this exam
                $existingAssignment = DB::table('examen_salle_enseignant')
                    ->where('id_enseignant', $id_enseignant)
                    ->where('id_examen', $id)
                    ->exists();

                if ($existingAssignment) {
                    $errors[] = "L'enseignant {$id_enseignant} est déjà affecté à une autre salle pour cet examen.";
                    continue;
                }

                // Check for overlapping assignments
                $overlappingAssignments = DB::table('examen_salle_enseignant')
                    ->join('examens', 'examen_salle_enseignant.id_examen', '=', 'examens.id')
                    ->where('examen_salle_enseignant.id_enseignant', $id_enseignant)
                    ->where('examens.date', $date)
                    ->where(function ($query) use ($heure_debut, $heure_fin) {
                        $query->whereBetween('examens.heure_debut', [date('H:i', $heure_debut), date('H:i', $heure_fin)])
                            ->orWhereBetween('examens.heure_fin', [date('H:i', $heure_debut), date('H:i', $heure_fin)])
                            ->orWhere(function ($query) use ($heure_debut, $heure_fin) {
                                $query->where('examens.heure_debut', '<=', date('H:i', $heure_debut))
                                    ->where('examens.heure_fin', '>=', date('H:i', $heure_fin));
                            });
                    })
                    ->exists();

                if ($overlappingAssignments) {
                    $errors[] = "L'enseignant {$id_enseignant} est déjà affecté à une autre salle pendant cette période.";
                    continue;
                }

                // Check daily assignments limit
                $dailyAssignments = DB::table('examen_salle_enseignant')
                    ->join('examens', 'examen_salle_enseignant.id_examen', '=', 'examens.id')
                    ->where('examen_salle_enseignant.id_enseignant', $id_enseignant)
                    ->where('examens.date', $date)
                    ->count();

                if ($dailyAssignments >= 2) {
                    $errors[] = "L'enseignant {$id_enseignant} ne peut pas superviser plus de deux examens par jour.";
                    continue;
                }
            }
        }

        // Si des erreurs sont trouvées, retourner en arrière avec les erreurs
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        // Supprimer les affectations existantes pour cet examen
        DB::table('examen_salle_enseignant')->where('id_examen', $examen->id)->delete();

        // Réinsérer les nouvelles affectations
        foreach ($request->enseignants as $id_salle => $invigilator_ids) {
            foreach ($invigilator_ids as $id_enseignant) {
                DB::table('examen_salle_enseignant')->insert([
                    'id_examen' => $examen->id,
                    'id_salle' => $id_salle,
                    'id_enseignant' => $id_enseignant,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('examens.show', $examen->id)->with('success', 'Les surveillants ont été mis à jour avec succès.');
    }


    public function generatePdfForEnseignant($idEnseignant)
    {
        $enseignant = Enseignant::findOrFail($idEnseignant);
        $examens = Examen::where('id_enseignant', $idEnseignant)->get();

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $pdfContent = view('examens.pdf', compact('enseignant', 'examens'))->render();
        $dompdf->loadHtml($pdfContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream("examens_enseignant_{$enseignant->name}.pdf");
    }
    
}
