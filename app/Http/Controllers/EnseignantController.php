<?php

// app/Http/Controllers/EnseignantController.php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Department;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    public function index()
    {
        $enseignants = Enseignant::all();

        $departments = Department::all()->keyBy('id_department');

        foreach ($enseignants as $enseignant) {
            $enseignant->department_name = $departments->has($enseignant->id_department) ? $departments[$enseignant->id_department]->name : 'N/A';
        }

        return view('enseignants.index', compact('enseignants'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('enseignants.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:enseignants',
            'id_department' => 'required|exists:departments,id_department',
        ]);

        Enseignant::create($request->all());
        return redirect()->route('enseignants.index')->with('success', 'Enseignant created successfully.');
    }

    public function edit(Enseignant $enseignant)
    {
        $departments = Department::all();
        return view('enseignants.edit', compact('enseignant', 'departments'));
    }

    public function update(Request $request, Enseignant $enseignant)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:enseignants,email,' . $enseignant->id,
            'id_department' => 'required|exists:departments,id_department',
        ]);

        $enseignant->update($request->all());
        return redirect()->route('enseignants.index')->with('success', 'Enseignant updated successfully.');
    }

    public function destroy(Enseignant $enseignant)
    {
        $enseignant->delete();
        return redirect()->route('enseignants.index')->with('success', 'Enseignant deleted successfully.');
    }
}
