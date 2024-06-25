<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Module;
use App\Models\Salle;
use App\Models\Enseignant;
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
        $enseignants = Enseignant::all();
        return view('examens.create', compact('modules', 'salles', 'enseignants'));
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
        $enseignants = Enseignant::all();
        return view('examens.edit', compact('examen', 'modules', 'salles', 'enseignants'));
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
