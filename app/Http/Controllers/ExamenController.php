<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Module;
use App\Models\Salle;
use App\Models\Enseignant;
use App\Models\Inscription;
use App\Models\SessionExam;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class ExamenController extends Controller
{
    public function index()
    {
        $examens = Examen::all();
        return view('examens.index', compact('examens'));
    }

    public function create()
    {
        // Filtrer les modules dont les examens sont déjà planifiés
        $modules = Module::whereDoesntHave('examens')->get();
        $salles = Salle::all();
        $enseignants = Enseignant::all();
        $sessions = SessionExam::all(); // Récupérer toutes les sessions d'examen
        return view('examens.create', compact('modules', 'salles', 'enseignants', 'sessions'));
    }

    public function store(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:18:00',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut|before_or_equal:18:00',
            'id_module' => 'required|exists:modules,id',
            'id_salle' => 'required|exists:salles,id',
            'id_enseignant' => 'required|exists:enseignants,id',
            'id_session' => 'required|exists:session_exams,id', // Ajouter la validation de la session
        ]);

        $module = Module::findOrFail($request->id_module);
        $nombreInscrits = Inscription::where('id_module', $module->id)->count();

        $salle = Salle::where('capacite', '>=', $nombreInscrits)->first();
        if (!$salle) {
            return back()->withErrors(['error' => 'Aucune salle disponible pour accueillir cet examen.']);
        }

        // Valider que la date de l'examen est incluse dans la durée de la session d'examen
        $session = SessionExam::findOrFail($request->id_session);
        if ($request->date < $session->date_debut || $request->date > $session->date_fin) {
            return back()->withErrors(['error' => 'La date de l\'examen doit être incluse dans la durée de la session d\'examen.']);
        }

        Examen::create([
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'id_salle' => $salle->id,
            'id_module' => $module->id,
            'id_enseignant' => $request->id_enseignant,
            'id_session' => $request->id_session, // Enregistrer la session d'examen
        ]);

        return redirect()->route('examens.index')->with('success', 'Examen créé avec succès.');
    }

    public function edit(Examen $examen)
    {
        // Filtrer les modules dont les examens sont déjà planifiés
        $modules = Module::whereDoesntHave('examens', function($query) use ($examen) {
            $query->where('id', '!=', $examen->id);
        })->get();
        $salles = Salle::all();
        $enseignants = Enseignant::all();
        $sessions = SessionExam::all(); // Récupérer toutes les sessions d'examen
        return view('examens.edit', compact('examen', 'modules', 'salles', 'enseignants', 'sessions'));
    }

    public function update(Request $request, Examen $examen)
    {
        // Valider les données du formulaire
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i|after_or_equal:08:00|before_or_equal:18:00',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut|before_or_equal:18:00',
            'id_module' => 'required|exists:modules,id',
            'id_salle' => 'required|exists:salles,id',
            'id_enseignant' => 'required|exists:enseignants,id',
            'id_session' => 'required|exists:session_exams,id', // Ajouter la validation de la session
        ]);

        $module = Module::findOrFail($request->id_module);
        $nombreInscrits = Inscription::where('id_module', $module->id)->count();

        $salle = Salle::where('capacite', '>=', $nombreInscrits)->first();
        if (!$salle) {
            return back()->withErrors(['error' => 'Aucune salle disponible pour accueillir cet examen.']);
        }

        // Valider que la date de l'examen est incluse dans la durée de la session d'examen
        $session = SessionExam::findOrFail($request->id_session);
        if ($request->date < $session->date_debut || $request->date > $session->date_fin) {
            return back()->withErrors(['error' => 'La date de l\'examen doit être incluse dans la durée de la session d\'examen.']);
        }

        $examen->update([
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'id_salle' => $salle->id,
            'id_module' => $module->id,
            'id_enseignant' => $request->id_enseignant,
            'id_session' => $request->id_session, // Enregistrer la session d'examen
        ]);

        return redirect()->route('examens.index')->with('success', 'Examen mis à jour avec succès.');
    }

    public function destroy(Examen $examen)
    {
        $examen->delete();
        return redirect()->route('examens.index')->with('success', 'Examen supprimé avec succès.');
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
