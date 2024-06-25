<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        $salles = Salle::all();
        return view('salles.index', compact('salles'));
    }

    public function create()
    {
        return view('salles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'capacite' => 'required|integer',
        ]);

        Salle::create($request->all());
        return redirect()->route('salles.index')->with('success', 'Salle créée avec succès');
    }

    public function show(Salle $salle)
    {
        return view('salles.show', compact('salle'));
    }

    public function edit(Salle $salle)
    {
        return view('salles.edit', compact('salle'));
    }

    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'name' => 'required',
            'capacite' => 'required|integer',
        ]);

        $salle->update($request->all());
        return redirect()->route('salles.index')->with('success', 'Salle mise à jour avec succès');
    }

    public function destroy(Salle $salle)
    {
        $salle->delete();
        return redirect()->route('salles.index')->with('success', 'Salle supprimée avec succès');
    }
}
