<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\SessionExam;
use Yajra\DataTables\DataTables;

class ModuleController extends Controller
{
    public function addModule(Request $request, $filiere_id)
    {
        // Validate the request data
        $request->validate([
            'code_elp' => 'required|string|max:255',
            'lib_elp' => 'required|string|max:255',
            'version_etape' => 'required|string|max:255',
            'code_etape' => 'required|string|max:255',
        ]);
    
        // Find the Filiere by ID
        $filiere = Filiere::findOrFail($filiere_id);
    
        // Create a new module associated with the Filiere
        $filiere->modules()->create($request->only([
            'code_elp',
            'lib_elp',
            'version_etape',
            'code_etape'
        ]));
    
        // Redirect back to the Filiere's show page with a success message
        return redirect()->route('filiere.show', $filiere_id)
                         ->with('success', 'Module ajouté avec succès.');
    }
    
    public function editModule($filiere_id, $module_id)
    {
        // Find the Filiere and Module by their IDs
        $filiere = Filiere::findOrFail($filiere_id);
        $module = Module::findOrFail($module_id);
    
        // Return the edit view with the Filiere and Module data
        return view('module.edit_module', compact('filiere', 'module'));
    }
    
    public function updateModule(Request $request, $filiere_id, $module_id)
    {
        // Validate the request data
        $request->validate([
            'code_elp' => 'required|string|max:255',
            'lib_elp' => 'required|string|max:255',
            'version_etape' => 'required|string|max:255',
            'code_etape' => 'required|string|max:255',
        ]);
    
        // Find the Module by ID and update it with the request data
        $module = Module::findOrFail($module_id);
        $module->update($request->only([
            'code_elp',
            'lib_elp',
            'version_etape',
            'code_etape'
        ]));
    
        // Redirect back to the Filiere's show page with a success message
        return redirect()->route('filiere.show', $filiere_id)
                         ->with('success', 'Module mis à jour avec succès.');
    }
    
    public function destroyModule($filiere_id, $module_id)
    {
        // Find the Module by ID and delete it
        $module = Module::findOrFail($module_id);
        $module->delete();
    
        // Redirect back to the Filiere's show page with a success message
        return redirect()->route('filiere.show', $filiere_id)
                         ->with('success', 'Module supprimé avec succès.');
    }
    
     // Affiche les détails du module
     public function show_module($id)
     {
         $module = Module::with('etudiants')->findOrFail($id);
         return view('module.show_module', compact('module'));
     }
 
     // Retourne les étudiants inscrits au format JSON pour DataTables
     public function students($id)
     {
         $module = Module::with('etudiants')->findOrFail($id);
         $etudiants = $module->etudiants;
 
         return DataTables::of($etudiants)
             ->addColumn('nom', function ($etudiant) {
                 return $etudiant->nom;
             })
             ->addColumn('prenom', function ($etudiant) {
                 return $etudiant->prenom;
             })
             ->make(true);
     }
}
