<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Module;
use App\Models\Salle;
use App\Models\Personne;
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
        $modules = Module::all();
        $salles = Salle::all();
        $personnes = Personne::all();
        return view('examens.create', compact('modules', 'salles', 'personnes'));
    }

    public function store(Request $request)
    {
        Examen::create($request->all());
        return redirect()->route('examens.index');
    }

    public function edit(Examen $examen)
    {
        $modules = Module::all();
        $salles = Salle::all();
        $personnes = Personne::all();
        return view('examens.edit', compact('examen', 'modules', 'salles', 'personnes'));
    }

    public function update(Request $request, Examen $examen)
    {
        $examen->update($request->all());
        return redirect()->route('examens.index');
    }

    public function destroy(Examen $examen)
    {
        $examen->delete();
        return redirect()->route('examens.index');
    }
}
