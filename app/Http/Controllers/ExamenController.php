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
use Illuminate\Http\Request;

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
            })->exists();

        if ($overlappingExam) {
            return back()->withErrors(['error' => 'Il existe déjà un examen pour cette filière dans la même durée.']);
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
        $modules = Module::whereDoesntHave('examens', function ($query) use ($examen) {
            $query->where('id', '!=', $examen->id);
        })->get();
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
