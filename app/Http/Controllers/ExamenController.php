<?php

namespace App\Http\Controllers;

use App\Models\ContrainteEnseignant;
use App\Models\Department;
use App\Models\Examen;
use App\Models\Module;
use App\Models\Salle;
use App\Models\Enseignant;
use App\Models\ExamenSalleEnseignant;
use App\Models\Inscription;
use App\Models\SessionExam;
use App\Models\Filiere;
use App\Models\SurveillantReserviste;
use Carbon\Carbon;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exact;
use Psy\Readline\Hoa\Console;

class ExamenController extends Controller
{

    public function index(Request $request, $sessionId)
    {
        $examens = Examen::where('id_session', $sessionId)
            ->when($request->input('module_id'), function ($query, $moduleId) {
                return $query->where('id_module', $moduleId);
            })
            ->when($request->input('filiere_id'), function ($query, $filiereId) {
                return $query->whereHas('module', function ($query) use ($filiereId) {
                    $query->where('version_etape', $filiereId);
                });
            })
            ->with(['sallePrincipale', 'sallesSupplementaires', 'module', 'enseignant'])
            ->get();

        $modules = Module::all();
        $filieres = Filiere::all();
        $session = SessionExam::findOrFail($sessionId);
        // dd($examens);

        return view('examens.index', compact('examens', 'modules', 'filieres', 'session'));
    }


    public function create($id)
    {
        $salles = Salle::all();
        $enseignants = Enseignant::all();
        $selected_session = SessionExam::findOrFail($id);
        $filieres = Filiere::all();
        $departments = Department::all();

        $examen = new Examen();

        return view('examens.create', compact('salles', 'selected_session', 'filieres', 'departments', 'enseignants', 'examen'));
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
            'id_filiere' => 'required|exists:filieres,version_etape',
        ]);

        $heure_debut = new \DateTime($request->heure_debut);
        $heure_fin = new \DateTime($request->heure_fin);

        $matin_start = new \DateTime('08:00');
        $matin_end = new \DateTime('12:30');
        $apres_midi_start = new \DateTime('14:00');
        $apres_midi_end = new \DateTime('18:30');

        if (
            !($heure_debut >= $matin_start && $heure_fin <= $matin_end) &&
            !($heure_debut >= $apres_midi_start && $heure_fin <= $apres_midi_end)
        ) {
            return back()->withErrors(['error' => 'La durée de l\'examen doit être entre 08:00 et 12:30 pour le matin ou entre 14:00 et 18:30 pour l\'après-midi.'])->withInput();
        }

        $existingExam = Examen::where('id_module', $request->id_module)
            ->whereHas('module', function ($query) use ($request) {
                $query->where('id_filiere', $request->id_filiere);
            })->exists();

        if ($existingExam) {
            return back()->withErrors(['error' => 'Un examen pour ce module et cette filière existe déjà.'])->withInput();
        }

        $overlappingExam = Examen::where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->whereHas('module', function ($query) use ($request) {
                $query->where('id_filiere', $request->id_filiere);
            })
            ->exists();

        if ($overlappingExam) {
            return back()->withErrors(['error' => 'Il existe déjà un examen pour cette filière dans la même durée.'])->withInput();
        }

        $occupiedSalle = Examen::where('date', $request->date)
            ->where(function ($query) use ($request) {
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
            return back()->withErrors(['error' => 'Cette salle est déjà occupée pendant cette période.'])->withInput();
        }

        $conflictingExam = Examen::where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->where('id_enseignant', $request->id_enseignant)
            ->exists();

        if ($conflictingExam) {
            return back()->withErrors(['error' => 'L\'enseignant est déjà affecté à un autre examen à cette date et heure.'])->withInput();
        }

        $conflictingConstraint = ContrainteEnseignant::where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->where('id_enseignant', $request->id_enseignant)
            ->where('validee', '1')
            ->exists();

        if ($conflictingConstraint) {
            return back()->withErrors(['error' => 'L\'enseignant a déjà une contrainte validée à cette date et heure.'])->withInput();
        }

        $salles_ids = array_filter(array_merge([$request->id_salle], $request->additional_salles ?? []));
        $capacite_totale = Salle::whereIn('id', $salles_ids)->sum('capacite');
        $module = Module::findOrFail($request->id_module);
        $nombreInscrits = Inscription::where('id_module', $module->id)->count();

        if ($capacite_totale < $nombreInscrits) {
            return back()->withErrors(['error' => 'La capacité totale des salles sélectionnées est insuffisante pour accueillir cet examen.'])->withInput();
        }

        $session = SessionExam::findOrFail($request->id_session);
        $date_examen = new \DateTime($request->date);
        $date_debut_session = new \DateTime($session->date_debut);
        $date_fin_session = new \DateTime($session->date_fin);

        if ($date_examen < $date_debut_session || $date_examen > $date_fin_session) {
            return back()->withErrors(['error' => 'La date de l\'examen doit être incluse dans la durée de la session d\'examen.'])->withInput();
        }

        $examen = Examen::create([
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'id_module' => $request->id_module,
            'id_salle' => $request->id_salle,
            'id_enseignant' => $request->id_enseignant,
            'id_session' => $request->id_session,
            'id_filiere' => $request->id_filiere,
        ]);

        foreach ($salles_ids as $salle_id) {
            $examen->salles()->attach($salle_id);
        }

        return redirect()->route('examens.index', ['sessionId' => $request->id_session])->with('success', 'Examen créé avec succès.');
    }






    public function edit($id)
    {
        $examen = Examen::findOrFail($id);
        $examen->load('module', 'additionalSalles');

        $modules = Module::all();
        $salles = Salle::all();
        $enseignants = Enseignant::all();
        $filieres = Filiere::all();
        $selected_session = SessionExam::findOrFail($examen->id_session);

        // Fetch the ID of the primary room
        $primaryRoomId = $examen->id_salle;

        $additionalSalles = $examen->additionalSalles->pluck('id')->filter(function ($id) use ($primaryRoomId) {
            return $id !== $primaryRoomId;
        })->toArray();

        $departements = Department::all();

        // Format the start and end times
        $examen->heure_debut = \Carbon\Carbon::parse($examen->heure_debut)->format('H:i');
        $examen->heure_fin = \Carbon\Carbon::parse($examen->heure_fin)->format('H:i');

        return view('examens.edit', compact('examen', 'modules', 'salles', 'enseignants', 'selected_session', 'filieres', 'additionalSalles', 'departements'));
    }

    public function update(Request $request, Examen $examen)
    {
        // Validate the incoming request
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i|before_or_equal:18:30',
            'heure_fin' => 'required|date_format:H:i|before_or_equal:18:30',
            'id_module' => 'required|exists:modules,id',
            'id_salle' => 'required|exists:salles,id',
            'additional_salles.*' => 'nullable|exists:salles,id',
            'id_enseignant' => 'required|exists:enseignants,id',
            'id_session' => 'required|exists:session_exams,id',
            'id_filiere' => 'required|exists:filieres,version_etape',
        ]);

        // Convert times to timestamps for comparison
        $heure_debut = strtotime($request->heure_debut);
        $heure_fin = strtotime($request->heure_fin);
        $matin_start = strtotime('08:00');
        $matin_end = strtotime('12:30');
        $apres_midi_start = strtotime('14:00');
        $apres_midi_end = strtotime('18:30');

        // Ensure the exam falls within allowed time ranges
        if (!(($heure_debut >= $matin_start && $heure_fin <= $matin_end && $heure_debut < $heure_fin) ||
            ($heure_debut >= $apres_midi_start && $heure_fin <= $apres_midi_end && $heure_debut < $heure_fin))) {
            return back()->withErrors(['error' => 'La durée de l\'examen doit être entre 08:00 et 12:30 pour le matin ou entre 14:00 et 18:30 pour l\'après-midi.']);
        }

        // Ensure the exam is unique for the module and filiere
        $existingExam = Examen::where('id_module', $request->id_module)
            ->where('id', '!=', $examen->id)
            ->whereHas('module', function ($query) use ($request) {
                $query->where('version_etape', $request->id_filiere);
            })->exists();

        if ($existingExam) {
            return back()->withErrors(['error' => 'Un examen pour ce module et cette filière existe déjà.']);
        }

        // Ensure no overlapping exams for the same filiere
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
                $query->where('version_etape', $request->id_filiere);
            })->exists();

        if ($overlappingExam) {
            return back()->withErrors(['error' => 'Il existe déjà un examen pour cette filière dans la même durée.']);
        }

        // Ensure the selected salle is not occupied
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

        // Ensure additional salles are not occupied
        if (!empty($request->additional_salles)) {
            foreach ($request->additional_salles as $additional_salle) {
                $occupiedAdditionalSalle = Examen::where('date', $request->date)
                    ->where('id', '!=', $examen->id)
                    ->where(function ($query) use ($request, $heure_debut, $heure_fin) {
                        $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                            ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                            ->orWhere(function ($query) use ($request) {
                                $query->where('heure_debut', '<=', $request->heure_debut)
                                    ->where('heure_fin', '>=', $request->heure_fin);
                            });
                    })
                    ->whereHas('salles', function ($query) use ($additional_salle) {
                        $query->where('salles.id', $additional_salle);
                    })->exists();

                if ($occupiedAdditionalSalle) {
                    return back()->withErrors(['error' => 'La salle supplémentaire sélectionnée est déjà occupée pendant cette période.']);
                }
            }
        }

        // Calculate the total capacity of selected salles
        $salles_ids = array_filter(array_merge([$request->id_salle], $request->additional_salles ?? []));
        $capacite_totale = Salle::whereIn('id', $salles_ids)->sum('capacite');
        $module = Module::findOrFail($request->id_module);
        $nombreInscrits = Inscription::where('id_module', $module->id)->count();

        if ($capacite_totale < $nombreInscrits) {
            return back()->withErrors(['error' => 'La capacité totale des salles sélectionnées est insuffisante pour accueillir cet examen.']);
        }

        // Ensure the exam date is within the session duration
        $session = SessionExam::findOrFail($request->id_session);
        if ($request->date < $session->date_debut || $request->date > $session->date_fin) {
            return back()->withErrors(['error' => 'La date de l\'examen doit être incluse dans la durée de la session d\'examen.']);
        }

        // Ensure the enseignant is available during the specified time
        $conflictingExam = Examen::where('date', $request->date)
            ->where('id', '!=', $examen->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->where('id_enseignant', $request->id_enseignant)
            ->exists();

        if ($conflictingExam) {
            return back()->withErrors(['error' => 'L\'enseignant est déjà affecté à un autre examen à cette date et heure.']);
        }

        // Validation pour empêcher l'enseignant d'avoir un contrainte validée
        $conflictingConstraint = ContrainteEnseignant::where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                    ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('heure_debut', '<=', $request->heure_debut)
                            ->where('heure_fin', '>=', $request->heure_fin);
                    });
            })
            ->where('id_enseignant', $request->id_enseignant)
            ->where('validee', '1')
            ->exists();

        if ($conflictingConstraint) {
            return back()->withErrors(['error' => 'L\'enseignant a déjà une contrainte validée à cette date et heure.']);
        }

        // Update the exam details
        $examen->update([
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'id_module' => $request->id_module,
            'id_salle' => $request->id_salle,
            'id_enseignant' => $request->id_enseignant,
            'id_session' => $request->id_session,
        ]);

        // Attach additional salles
        if ($request->filled('additional_salles')) {
            $examen->salles()->sync($request->additional_salles);
        } else {
            $examen->salles()->sync([]);
        }

        // Redirect with success message
        return redirect()->route('examens.index', ['sessionId' => $request->id_session])->with('success', 'Examen mis à jour avec succès.');
    }





    public function destroy(Examen $examen)
    {
        $id_session = SessionExam::findOrFail($examen->id_session);
        $examen->delete();
        return redirect()->route('examens.index', ['sessionId' => $id_session])->with('success', 'Examen supprimé avec succès.');
    }

    public function getRooms(Request $request)
    {
        $id = $request->input('id_examen');

        // Fetch the primary room ID for the given exam
        $primaryRoomId = DB::table('examens')->where('id', $id)->value('id_salle');

        // Fetch additional rooms excluding the primary room and already assigned additional rooms
        $additionalRooms = Salle::whereNotIn('id', function ($query) use ($id) {
            $query->select('id_salle')
                ->from('examen_salle')
                ->where('id_examen', $id);
        })->where('id', '!=', $primaryRoomId)->get();

        return response()->json([
            'rooms' => $additionalRooms
        ]);
    }


    public function getModulesByFiliere($filiere_id)
    {
        $modules = Module::where('version_etape', $filiere_id)
            ->withCount('inscriptions')
            ->get();
        return response()->json($modules);
    }


    public function getEnseignantsByDepartment($departmentId)
    {
        $enseignants = Enseignant::where('id_department', $departmentId)->get();
        return response()->json($enseignants);
    }





    public function showForm($id)
    {
        $examen = Examen::findOrFail($id);

        // Récupérer la salle principale
        $sallePrincipale = $examen->sallePrincipale;

        // Charger les salles additionnelles affectées avec leurs enseignants pour cet examen
        $sallesAdditionnelles = Salle::whereExists(function ($query) use ($id) {
            $query->select('*')
                ->from('examens')
                ->join('examen_salle_enseignant', 'examens.id', '=', 'examen_salle_enseignant.id_examen')
                ->whereColumn('salles.id', 'examen_salle_enseignant.id_salle')
                ->where('examens.id', $id);
        })->get();

        // Charger les enseignants pour chaque salle
        foreach ($sallesAdditionnelles as $salle) {
            $salle->enseignants = $salle->enseignants($id)->get();
        }

        return view('examens.show', [
            'examen' => $examen,
            'sallePrincipale' => $sallePrincipale,
            'sallesAdditionnelles' => $sallesAdditionnelles,
        ]);
    }



    public function showAssignInvigilatorsForm($id)
    {
        $examen = Examen::findOrFail($id);
        $salles = Salle::all(); // Ou une autre méthode pour récupérer les salles
        $enseignants = Enseignant::all(); // Ou une autre méthode pour récupérer les enseignants

        return view('examens.assign-invigilators', compact('examen', 'salles', 'enseignants'));
    }

    public function assignInvigilatorsMAnuelle(Request $request, $id)
    {
        $examen = Examen::findOrFail($id);
        $date = $examen->date;
        $heure_debut = strtotime($examen->heure_debut);
        $heure_fin = strtotime($examen->heure_fin);

        $errors = [];

        // Check conditions for each invigilator and room before assignment
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

                // Check for validated constraints
                $conflictingConstraint = ContrainteEnseignant::where('date', $date)
                    ->where(function ($query) use ($heure_debut, $heure_fin) {
                        $query->whereBetween('heure_debut', [date('H:i', $heure_debut), date('H:i', $heure_fin)])
                            ->orWhereBetween('heure_fin', [date('H:i', $heure_debut), date('H:i', $heure_fin)])
                            ->orWhere(function ($query) use ($heure_debut, $heure_fin) {
                                $query->where('heure_debut', '<=', date('H:i', $heure_debut))
                                    ->where('heure_fin', '>=', date('H:i', $heure_fin));
                            });
                    })
                    ->where('id_enseignant', $enseignantId)
                    ->where('validee', '1')
                    ->exists();

                if ($conflictingConstraint) {
                    $errors[] = "L'enseignant {$enseignantId} a déjà une contrainte validée à cette date et heure.";
                    continue;
                }
            }
        }

        // If errors are found, return back with errors
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        // If no errors, proceed with assigning invigilators
        foreach ($request->enseignants as $salleId => $enseignantIds) {
            foreach ($enseignantIds as $enseignantId) {
                // Insert only if the invigilator is not already assigned to this room for this exam
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

        // Redirect to the exam details view with a success message
        return redirect()->route('examens.showForm', ['examen' => $examen->id])->with('success', 'Les surveillants ont été assignés avec succès.');
    }


    public function editInvigilators($id)
    {
        $examen = Examen::findOrFail($id);
        $salles = $examen->salles;

        foreach ($salles as $salle) {
            $salle->enseignants = $salle->enseignants($id)->get();
        }

        // Exclude enseignants who are responsible for the module of the exam
        $enseignantId = $examen->id_enseignant;
        $enseignants = Enseignant::whereNotIn('id', [$enseignantId])->get(); // Fetch all enseignants to populate the select options

        return view('examens.edit_invigilators', compact('examen', 'salles', 'enseignants'));
    }

    public function updateInvigilators(Request $request, $id)
    {
        $examen = Examen::findOrFail($id);
        $date = $examen->date;
        $heure_debut = strtotime($examen->heure_debut);
        $heure_fin = strtotime($examen->heure_fin);

        $errors = [];

        // Collect all existing assignments for the exam
        $existingAssignments = DB::table('examen_salle_enseignant')
            ->where('id_examen', $id)
            ->get()
            ->groupBy('id_enseignant')
            ->mapWithKeys(fn($items, $enseignantId) => [
                $enseignantId => $items->map->id_salle->toArray()
            ]);

        // Collect all existing assignments by teacher and date, excluding the current exam
        $dailyAssignments = DB::table('examen_salle_enseignant')
            ->join('examens', 'examen_salle_enseignant.id_examen', '=', 'examens.id')
            ->where('examens.date', $date)
            ->where('examens.id', '!=', $id) // Exclude the current exam
            ->select('examen_salle_enseignant.id_enseignant')
            ->groupBy('examen_salle_enseignant.id_enseignant')
            ->havingRaw('COUNT(examen_salle_enseignant.id_examen) >= 2')
            ->pluck('examen_salle_enseignant.id_enseignant')
            ->toArray(); // Convert Collection to array

        // Collect available teachers
        $availableTeachers = Enseignant::all()->keyBy('id');

        // Collect new assignments to avoid duplicate entries
        $newAssignments = [];

        foreach ($request->enseignants as $salleId => $enseignantIds) {
            foreach ($enseignantIds as $enseignantId) {
                // Check if the teacher is already assigned to this exam and room
                $isExistingAssignment = isset($existingAssignments[$enseignantId]) && in_array($salleId, $existingAssignments[$enseignantId]);

                // Check if the teacher is assigned to more than two rooms on the same day, excluding current exam
                if (!$isExistingAssignment && in_array($enseignantId, $dailyAssignments)) {
                    $errors[] = "L'enseignant {$availableTeachers[$enseignantId]->name} ne peut pas superviser plus de deux examens par jour.";
                    continue;
                }

                // Check for overlapping assignments with other exams (excluding the current exam)
                $overlappingAssignments = DB::table('examen_salle_enseignant')
                    ->join('examens', 'examen_salle_enseignant.id_examen', '=', 'examens.id')
                    ->where('examen_salle_enseignant.id_enseignant', $enseignantId)
                    ->where('examens.date', $date)
                    ->where('examens.id', '!=', $id) // Exclude the current exam
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
                    $errors[] = "L'enseignant {$availableTeachers[$enseignantId]->name} est déjà affecté à une autre salle pendant cette période.";
                    continue;
                }

                // Check if the teacher is already assigned to another room for this exam
                if (isset($newAssignments[$enseignantId])) {
                    $errors[] = "L'enseignant {$availableTeachers[$enseignantId]->name} est déjà affecté à une autre salle pour cet examen.";
                    continue;
                }

                // Prepare new assignment if it's not a duplicate
                if (!isset($newAssignments[$salleId])) {
                    $newAssignments[$salleId] = [];
                }
                $newAssignments[$salleId][] = $enseignantId;
                $newAssignments[$enseignantId] = $salleId;
            }
        }

        // If errors found, redirect back with errors
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        // If no errors, proceed with updating assignments
        DB::table('examen_salle_enseignant')->where('id_examen', $id)->delete();

        foreach ($request->enseignants as $salleId => $enseignantIds) {
            foreach ($enseignantIds as $enseignantId) {
                DB::table('examen_salle_enseignant')->insert([
                    'id_examen' => $examen->id,
                    'id_salle' => $salleId,
                    'id_enseignant' => $enseignantId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Redirect back to exam details with success message
        return redirect()->route('examens.showForm', ['examen' => $examen->id])->with('success', 'Les surveillants ont été mis à jour avec succès.');
    }


    // public function assignInvigilators(Request $request, $id)
    // {
    //     // Récupérer l'examen
    //     $examen = Examen::findOrFail($id);
    //     $date = $examen->date;
    //     $heure_debut = strtotime($examen->heure_debut);
    //     $heure_fin = strtotime($examen->heure_fin);

    //     // Récupérer le responsable du module
    //     $responsableModuleId = $examen->module->id_enseignant;

    //     // Récupérer les surveillants disponibles en excluant le responsable du module
    //     $enseignantsDisponibles = Enseignant::where('id', '!=', $responsableModuleId)->get();

    //     $errors = [];

    //     foreach ($examen->salles as $salle) {
    //         // Déterminer le nombre de surveillants nécessaires
    //         $nombreSurveillants = 2; // par défaut
    //         if ($salle->capacite >= 100) {
    //             $nombreSurveillants = 4;
    //         } elseif ($salle->capacite >= 80) {
    //             $nombreSurveillants = 3;
    //         }

    //         // Filtrer les surveillants disponibles en vérifiant les contraintes
    //         $surveillantsAffectes = 0;
    //         foreach ($enseignantsDisponibles as $enseignant) {
    //             // Vérifier si l'enseignant a déjà deux surveillances pour cette journée
    //             $nombreSurveillancesJour = DB::table('examen_salle_enseignant')
    //                 ->join('examens', 'examen_salle_enseignant.id_examen', '=', 'examens.id')
    //                 ->where('examen_salle_enseignant.id_enseignant', $enseignant->id)
    //                 ->where('examens.date', $date)
    //                 ->count();

    //             if ($nombreSurveillancesJour >= 2) {
    //                 continue;
    //             }

    //             // Vérifier si l'enseignant est déjà affecté à une autre salle pour ce créneau horaire
    //             $chevauchement = DB::table('examen_salle_enseignant')
    //                 ->join('examens', 'examen_salle_enseignant.id_examen', '=', 'examens.id')
    //                 ->where('examen_salle_enseignant.id_enseignant', $enseignant->id)
    //                 ->where('examens.date', $date)
    //                 ->where(function ($query) use ($heure_debut, $heure_fin) {
    //                     $query->whereBetween('examens.heure_debut', [date('H:i', $heure_debut), date('H:i', $heure_fin)])
    //                           ->orWhereBetween('examens.heure_fin', [date('H:i', $heure_debut), date('H:i', $heure_fin)])
    //                           ->orWhere(function ($query) use ($heure_debut, $heure_fin) {
    //                               $query->where('examens.heure_debut', '<=', date('H:i', $heure_debut))
    //                                     ->where('examens.heure_fin', '>=', date('H:i', $heure_fin));
    //                           });
    //                 })
    //                 ->exists();

    //             if ($chevauchement) {
    //                 continue;
    //             }

    //             // Assigner l'enseignant à la salle
    //             DB::table('examen_salle_enseignant')->insert([
    //                 'id_examen' => $examen->id,
    //                 'id_salle' => $salle->id,
    //                 'id_enseignant' => $enseignant->id,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             $surveillantsAffectes++;

    //             // Si le nombre requis de surveillants est atteint, passer à la salle suivante
    //             if ($surveillantsAffectes >= $nombreSurveillants) {
    //                 break;
    //             }
    //         }

    //         // Si le nombre requis de surveillants n'est pas atteint, essayer d'assigner des réservistes
    //         if ($surveillantsAffectes < $nombreSurveillants) {
    //             $this->assignReservistIfNeeded($date, $salle->id, $examen->id);
    //         }

    //         // Si après avoir essayé les réservistes, il manque encore des surveillants, ajouter une erreur
    //         if ($surveillantsAffectes < $nombreSurveillants) {
    //             $errors[] = "Il n'y a pas assez de surveillants disponibles pour la salle {$salle->name}.";
    //         }
    //     }

    //     // Si des erreurs sont détectées, les retourner à la vue
    //     if (!empty($errors)) {
    //         return back()->withErrors($errors);
    //     }

    //     return redirect()->route('examens.showForm', ['examen' => $examen->id])->with('success', 'Les surveillants ont été assignés automatiquement avec succès.');
    // }


    protected function canAssignInvigilator($enseignant, $date, $heure_debut, $heure_fin)
    {
        // Check if the invigilator has already been assigned to this day
        $nombreSurveillancesJour = $enseignant->examens()
            ->where('date', $date)
            ->count();

        // If already assigned twice on the same day, they should not be available
        if ($nombreSurveillancesJour >= 2) {
            return false;
        }

        // Check for time conflicts
        $chevauchement = $enseignant->examens()
            ->where('date', $date)
            ->where(function ($query) use ($heure_debut, $heure_fin) {
                $query->whereBetween('heure_debut', [$heure_debut, $heure_fin])
                    ->orWhereBetween('heure_fin', [$heure_debut, $heure_fin])
                    ->orWhere(function ($query) use ($heure_debut, $heure_fin) {
                        $query->where('heure_debut', '<=', $heure_debut)
                            ->where('heure_fin', '>=', $heure_fin);
                    });
            })
            ->exists();

        return !$chevauchement;
    }

    public function assignInvigilators($examenId)
    {
        DB::beginTransaction();

        try {
            $examen = Examen::findOrFail($examenId);
            $date = $examen->date;
            $heure_debut = $examen->heure_debut;
            $heure_fin = $examen->heure_fin;

            $responsableModuleId = $examen->module->id_enseignant;

            // Vérifier si les surveillants ont déjà été affectés à cet examen
            if ($examen->surveillants()->exists()) {
                DB::rollBack();
                return redirect()->route('examens.showForm', ['examen' => $examen->id])
                    ->with('error', 'Les surveillants ont déjà été affectés pour cet examen.');
            }

            // Select available invigilators who are not responsible for the module
            $enseignantsDisponibles = Enseignant::where('id', '!=', $responsableModuleId)
                ->whereDoesntHave('examens', function ($query) use ($date) {
                    $query->where('date', $date);
                })
                ->inRandomOrder()
                ->get();

            $errors = [];

            foreach ($examen->salles as $salle) {
                $nombreSurveillants = $this->determineSurveillantsCount($salle->capacite);
                $surveillantsAffectes = 0;

                foreach ($enseignantsDisponibles as $enseignant) {
                    if ($this->canAssignInvigilator($enseignant, $date, $heure_debut, $heure_fin)) {
                        DB::table('examen_salle_enseignant')->insert([
                            'id_examen' => $examen->id,
                            'id_salle' => $salle->id,
                            'id_enseignant' => $enseignant->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $surveillantsAffectes++;
                    }

                    if ($surveillantsAffectes >= $nombreSurveillants) {
                        break;
                    }
                }

                if ($surveillantsAffectes < $nombreSurveillants) {
                    $errors[] = "Il n'y a pas assez de surveillants disponibles pour la salle {$salle->name}.";
                }
            }

            if (!empty($errors)) {
                DB::rollBack();
                return back()->withErrors($errors);
            }

            DB::commit();
            return redirect()->route('examens.showForm', ['examen' => $examen->id])
                ->with('success', 'Les surveillants ont été assignés automatiquement avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'assignation des surveillants : ' . $e->getMessage()]);
        }
    }


    protected function determineSurveillantsCount($capacite)
    {
        if ($capacite >= 100) {
            return 4;
        } elseif ($capacite >= 80) {
            return 3;
        } else {
            return 2;
        }
    }


    public function assignInvigilatorsToAll(Request $request)
    {
        $sessionId = $request->input('id_session');

        $examens = Examen::where('id_session', $sessionId)->get();

        if ($examens->isEmpty()) {
            return redirect()->route('examens.index', ['sessionId' => $sessionId])
                ->with('error', 'Aucun examen trouvé pour cette session.');
        }

        DB::beginTransaction();

        try {
            $this->createReservistsForSession($sessionId);

            foreach ($examens as $examen) {
                $this->assignInvigilators($examen->id);
            }

            DB::commit();

            return redirect()->route('examens.index', ['sessionId' => $sessionId])
                ->with('success', 'Les surveillants ont été assignés à tous les examens, et les listes de réservistes ont été créées pour chaque demi-journée.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('examens.index', ['sessionId' => $sessionId])
                ->with('error', 'Une erreur est survenue lors de l\'assignation des surveillants : ' . $e->getMessage());
        }
    }

    protected function createReservistsForSession($sessionId)
    {
        $examDates = Examen::where('id_session', $sessionId)
            ->distinct()
            ->pluck('date');

        foreach ($examDates as $date) {
            $this->createReservists($date, '08:00:00');
            $this->createReservists($date, '13:00:00');
        }
    }

    protected function createReservists($date, $heure_debut)
    {
        $isMorning = (new \DateTime($heure_debut))->format('H') < 12;
        $demiJournee = $isMorning ? 'matin' : 'apres-midi';

        // Vérifier si les réservistes existent déjà pour cette date et demi-journée
        $reservistsExist = SurveillantReserviste::where('date', $date)
            ->where('demi_journee', $demiJournee)
            ->exists();

        if (!$reservistsExist) {
            // Récupérer tous les enseignants disponibles pour cette date et demi-journée
            $enseignantsDisponibles = Enseignant::whereNotIn('id', function ($query) use ($date, $demiJournee) {
                $query->select('id_enseignant')
                    ->from('surveillant_reservistes')
                    ->where('date', $date)
                    ->where('demi_journee', $demiJournee);
            })
                ->whereNotIn('id', function ($query) use ($date) {
                    $query->select('id_enseignant')
                        ->from('examens')
                        ->where('date', $date);
                })
                ->get();

            // Filtrer les enseignants réservistes pour l'autre demi-journée
            $enseignantsAutresDemiJournee = SurveillantReserviste::where('date', $date)
                ->where('demi_journee', $demiJournee === 'matin' ? 'apres-midi' : 'matin')
                ->pluck('id_enseignant');

            // Exclure les enseignants déjà réservistes pour l'autre demi-journée
            $enseignantsDisponibles = $enseignantsDisponibles->filter(function ($enseignant) use ($enseignantsAutresDemiJournee) {
                return !$enseignantsAutresDemiJournee->contains($enseignant->id);
            });

            // Éviter les doublons et limiter à un nombre raisonnable
            $enseignantsÀAjouter = $enseignantsDisponibles->unique('id')->random(min(10, $enseignantsDisponibles->count()));

            foreach ($enseignantsÀAjouter as $enseignant) {
                // Ajouter chaque enseignant comme réserviste
                SurveillantReserviste::create([
                    'date' => $date,
                    'demi_journee' => $demiJournee,
                    'id_enseignant' => $enseignant->id,
                    'affecte' => false,
                ]);
            }
        }
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
