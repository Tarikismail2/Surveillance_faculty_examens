<?php

namespace App\Http\Controllers;

use App\Models\ContrainteSalle;
use App\Models\Salle;
use App\Models\SessionExam;
use Illuminate\Http\Request;

class ContrainteSalleController extends Controller
{
    public function index(Request $request)
    {
        $sessions = SessionExam::all();
        $query = ContrainteSalle::query();

        if ($request->filled('id_session')) {
            $query->where('id_session', $request->input('id_session'));
        }

        $contraintes = $query->get();

        return view('contrainte_salles.index', compact('contraintes', 'sessions'));
    }

    public function create()
    {
        $salles = Salle::all();
        $sessions = SessionExam::all();
        return view('contrainte_salles.create', compact('salles', 'sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_salle' => 'required|exists:salles,id',
            'id_session' => 'required|exists:session_exams,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'validee' => 'required|boolean',
        ]);

        ContrainteSalle::create($validated);

        return redirect()->route('contrainte_salles.index')->with('success', 'Contrainte créée avec succès.');
    }

    public function valider($id)
    {
        $contrainte = ContrainteSalle::findOrFail($id);
        $contrainte->validee = true;
        $contrainte->save();

        return redirect()->route('contrainte_salles.index')->with('success', 'Contrainte validée avec succès.');
    }

    public function annuler($id)
    {
        $contrainte = ContrainteSalle::findOrFail($id);
        $contrainte->delete();

        return redirect()->route('contrainte_salles.index')->with('success', 'Contrainte annulée avec succès.');
    }
}
