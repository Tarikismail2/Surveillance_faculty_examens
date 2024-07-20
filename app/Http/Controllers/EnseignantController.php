<?php

// app/Http/Controllers/EnseignantController.php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EnseignantController extends Controller
{
    // public function index()
    // {
    //     $enseignants = Enseignant::all();

    //     $departments = Department::all()->keyBy('id_department');

    //     foreach ($enseignants as $enseignant) {
    //         $enseignant->department_name = $departments->has($enseignant->id_department) ? $departments[$enseignant->id_department]->name : 'N/A';
    //     }

    //     return view('enseignants.index', compact('enseignants'));
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $enseignants = Enseignant::with('department')->get();

            $departments = Department::all()->keyBy('id_department');

            foreach ($enseignants as $enseignant) {
                $enseignant->department_name = $departments->has($enseignant->id_department) ? $departments[$enseignant->id_department]->name : 'N/A';
            }

            return DataTables::of($enseignants)
                ->addColumn('actions', function ($enseignant) {
                    return '
                        <a href="'.route('enseignants.edit', $enseignant->id).'" class="text-yellow-600 hover:text-yellow-900">Modifier</a>
                        <form action="'.route('enseignants.destroy', $enseignant->id).'" method="POST" class="inline-block" style="display:inline;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet enseignant ?\')">Supprimer</button>
                        </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('enseignants.index');
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
