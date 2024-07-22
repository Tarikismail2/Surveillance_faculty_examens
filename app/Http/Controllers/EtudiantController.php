<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Module;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Etudiant::select([
                'id',
                'nom',
                'prenom'
            ]);
    
            // Gestion de la recherche
            if ($request->has('search') && $request->search['value']) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'LIKE', "%$search%")
                      ->orWhere('prenom', 'LIKE', "%$search%");
                });
            }
    
            // Tri des résultats
            if ($request->has('order')) {
                $orderColumn = $request->order[0]['column']; // Numéro de colonne
                $orderDirection = $request->order[0]['dir']; // Direction du tri
    
                $columns = ['nom', 'prenom', 'fullName'];
                $orderBy = $columns[$orderColumn] ?? 'nom';
    
                $query->orderBy($orderBy, $orderDirection);
            }
    
            return DataTables::of($query)
                ->addColumn('fullName', function ($row) {
                    return $row->nom . ' ' . $row->prenom;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('etudiants.edit', $row->id);
                    $deleteUrl = route('etudiants.destroy', $row->id);
    
                    $csrfField = csrf_field();
                    $methodField = method_field('DELETE');
    
                    return <<<EOL
                        <div class="flex space-x-2">
                            <a href="{$editUrl}" class="text-yellow-600 hover:text-yellow-800 flex items-center">
                                <i class="fas fa-edit mr-1"></i>
                                <span class="sr-only">{{ __('Modifier') }}</span>
                            </a>
                            <form action="{$deleteUrl}" method="POST" class="inline">
                                {$csrfField}
                                {$methodField}
                                <button type="submit" class="text-red-600 hover:text-red-800 flex items-center">
                                    <i class="fas fa-trash mr-1"></i>
                                    <span class="sr-only">{{ __('Supprimer') }}</span>
                                </button>
                            </form>
                        </div>
                    EOL;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('etudiants.index');
    }
    
    
    

    

    public function create()
    {
        $modules = Module::all(); // Fetch all modules
        return view('etudiants.create', compact('modules'));
    }

    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'code_etudiant' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'cin' => 'nullable|string|max:255',
            'cne' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'modules' => 'required|array',
        ]);
    
        // Créer l'étudiant
        $etudiant = Etudiant::create([
            'code_etudiant' => $validatedData['code_etudiant'],
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'cin' => $validatedData['cin'],
            'cne' => $validatedData['cne'],
            'date_naissance' => $validatedData['date_naissance'],
        ]);
    
        // Attacher les modules à l'étudiant
        $etudiant->modules()->sync($validatedData['modules']);
    
        return redirect()->route('etudiants.index')->with('success', 'Étudiant créé avec succès.');
    }
    
    public function deleteModules(Request $request)
    {
        $validatedData = $request->validate([
            'delete_modules' => 'required|array',
        ]);
    
        // Supprimer les modules sélectionnés
        Module::destroy($validatedData['delete_modules']);
    
        return redirect()->route('etudiants.index')->with('success', 'Modules supprimés avec succès.');
    }

    public function show(Etudiant $etudiant)
    {
        $modules = $etudiant->modules;
        return view('etudiants.show', compact('etudiant', 'modules'));
    }

    public function edit(Etudiant $etudiant)
    {
        $modules = Module::all(); // Fetch all modules
        $selectedModules = $etudiant->modules->pluck('id')->toArray(); // Get selected modules
        // dd($selectedModules);
        return view('etudiants.edit', compact('etudiant', 'modules', 'selectedModules'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'cin' => 'nullable|string|max:255',
            'cne' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'modules' => 'nullable|array', // Validate module input
            'modules.*' => 'exists:modules,id', // Validate each module ID
        ]);

        $etudiant->update($request->only(['nom', 'prenom', 'cin', 'cne', 'date_naissance']));

        // Sync modules (update the modules list for the student)
        if ($request->has('modules')) {
            $etudiant->modules()->sync($request->input('modules'));
        } else {
            $etudiant->modules()->sync([]); // If no modules selected, detach all
        }

        return redirect()->route('etudiants.index')->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function destroy(Etudiant $etudiant)
    {
        $etudiant->modules()->detach(); // Detach all modules before deleting
        $etudiant->delete();

        return redirect()->route('etudiants.index')->with('success', 'Étudiant supprimé avec succès.');
    }
}
