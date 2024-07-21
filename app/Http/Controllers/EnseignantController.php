<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Redirect;

class EnseignantController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $enseignants = Enseignant::with('department')->get();
            $departments = Department::all()->keyBy('id_department');

            foreach ($enseignants as $enseignant) {
                $enseignant->department_name = $departments->has($enseignant->id_department)
                    ? $departments[$enseignant->id_department]->name
                    : 'N/A';
            }

            return DataTables::of($enseignants)
                ->addColumn('actions', function ($enseignant) {
                    return view('partials.datatables-actions', [
                        'editUrl' => route('enseignants.edit', $enseignant->id),
                        'deleteUrl' => route('enseignants.destroy', $enseignant->id),
                        'confirmMessage' => 'Êtes-vous sûr de vouloir supprimer cet enseignant ?'
                    ])->render();
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:enseignants,email',
            'id_department' => 'required|exists:departments,id_department',
        ]);

        Enseignant::create($request->all());
        return Redirect::route('enseignants.index')->with('status', ['type' => 'success', 'message' => 'Enseignant created successfully.']);
    }

    public function edit(Enseignant $enseignant)
    {
        $departments = Department::all();
        return view('enseignants.edit', compact('enseignant', 'departments'));
    }

    public function update(Request $request, Enseignant $enseignant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:enseignants,email,' . $enseignant->id,
            'id_department' => 'required|exists:departments,id_department',
        ]);

        $enseignant->update($request->all());
        return Redirect::route('enseignants.index')->with('status', ['type' => 'success', 'message' => 'Enseignant updated successfully.']);
    }

    public function destroy(Enseignant $enseignant)
    {
        $enseignant->delete();
        return Redirect::route('enseignants.index')->with('status', ['type' => 'success', 'message' => 'Enseignant deleted successfully.']);
    }
}
