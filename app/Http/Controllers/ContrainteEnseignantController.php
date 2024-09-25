<?php

namespace App\Http\Controllers;

use App\Models\ContrainteEnseignant;
use App\Models\Enseignant;
use App\Models\SessionExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContrainteEnseignantController extends Controller
{

    public function index(Request $request)
    {
        $sessions = SessionExam::all(); // Récupérer toutes les sessions
        $contrainteQuery = ContrainteEnseignant::query();
    
        if ($request->has('id_session') && $request->input('id_session')) {
            $contrainteQuery->where('id_session', $request->input('id_session'));
        }
    
        $contraintes = $contrainteQuery->with('enseignant')->get();
        
        return view('contraintes.index', compact('contraintes', 'sessions'));
    }

    public function index_admin(Request $request)
    {
        $sessions = SessionExam::all();
        $contrainteQuery = ContrainteEnseignant::query();
    
        if ($request->has('id_session') && $request->input('id_session')) {
            $contrainteQuery->where('id_session', $request->input('id_session'));
        }
    
        $contraintes = $contrainteQuery->with('enseignant')->get();
        
        return view('contraintes.index_admin', compact('contraintes', 'sessions'));
    }
    
    public function create()
    {
        $enseignants = Enseignant::all();
        $sessions = SessionExam::all();

        return view('contraintes.create', compact('enseignants', 'sessions'));
    }

    public function store(Request $request)
    {
        // Validation initiale
        $validator = Validator::make($request->all(), [
            'id_enseignant' => 'required|exists:enseignants,id',
            'email' => 'required|email|exists:enseignants,email', // Validation de l'email
            'id_session' => 'required|exists:session_exams,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'validee' => 'required|boolean',
        ]);
    
        // Récupération de la session
        $session = SessionExam::find($request->input('id_session'));
        if (!$session) {
            return redirect()->back()->withErrors(['id_session' => 'Session invalide.'])->withInput();
        }
    
        // Validation supplémentaire pour la plage de dates et les horaires
        $validator->after(function ($validator) use ($request, $session) {
            $date = $request->input('date');
            $heure_debut = $request->input('heure_debut');
            $heure_fin = $request->input('heure_fin');
    
            // Validation de la plage de dates
            if ($date < $session->date_debut || $date > $session->date_fin) {
                $validator->errors()->add('date', 'La date doit être dans la plage de la session choisie.');
            }
    
            // Validation des plages horaires
            $valid_hours = [
                ['08:00', '12:30'],
                ['14:00', '18:30']
            ];
    
            $is_valid_hour = false;
            foreach ($valid_hours as $range) {
                if ($heure_debut >= $range[0] && $heure_debut < $range[1] &&
                    $heure_fin > $range[0] && $heure_fin <= $range[1]) {
                    $is_valid_hour = true;
                    break;
                }
            }
    
            if (!$is_valid_hour) {
                $validator->errors()->add('heure_debut', 'Les heures doivent être dans les plages 08:00-12:30 ou 14:00-18:30 et l\'heure de fin doit être supérieure à l\'heure de début.');
            }
        });
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Création de la contrainte
        ContrainteEnseignant::create([
            'id_enseignant' => $request->input('id_enseignant'),
            'email' => $request->input('email'),
            'id_session' => $request->input('id_session'),
            'date' => $request->input('date'),
            'heure_debut' => $request->input('heure_debut'),
            'heure_fin' => $request->input('heure_fin'),
            'validee' => $request->input('validee'),
        ]);
    
        return redirect()->route('contrainte_enseignants.index')->with('success', 'Contrainte créée avec succès.');
    }
    

    public function valider($id)
    {
        $contrainte = ContrainteEnseignant::findOrFail($id);
        $contrainte->validee = true;
        $contrainte->save();

        return redirect()->route('contrainte_enseignants.index_admin')->with('success', 'Contrainte validée avec succès.');
    }

    public function annuler($id)
    {
        $contrainte = ContrainteEnseignant::findOrFail($id);
        $contrainte->delete();

        return redirect()->route('contrainte_enseignants.index_admin')->with('success', 'Contrainte annulée avec succès.');
    }
}
