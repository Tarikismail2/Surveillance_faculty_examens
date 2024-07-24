<?php

namespace App\Http\Controllers;

use App\Models\ContrainteEnseignant;
use Illuminate\Http\Request;
use Auth;

class ContrainteEnseignantController extends Controller
{
    public function create()
    {
        return view('contraintes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);

        ContrainteEnseignant::create([
            // 'id_enseignant' => Auth::id(),
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'validee' => false,
        ]);

        return redirect()->route('contraintes.create')->with('success', 'Contrainte ajoutée avec succès.');
    }

    public function index()
    {
        $contraintes = ContrainteEnseignant::with('enseignant')->get();
        return view('contraintes.index', compact('contraintes'));
    }

    public function valider($id)
    {
        $contrainte = ContrainteEnseignant::findOrFail($id);
        $contrainte->validee = true;
        $contrainte->save();

        return redirect()->route('contraintes.index')->with('success', 'Contrainte validée avec succès.');
    }

    public function annuler($id)
    {
        $contrainte = ContrainteEnseignant::findOrFail($id);
        $contrainte->delete();

        return redirect()->route('contraintes.index')->with('success', 'Contrainte annulée avec succès.');
    }
}