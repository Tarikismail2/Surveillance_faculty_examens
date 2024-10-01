<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\FiliereGp;
use App\Models\Etudiant;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use App\Models\SessionExam;
use Yajra\DataTables\DataTables;

class ModuleController extends Controller
{

    public function create($code_etape,$id_session)
    {
        $session = SessionExam::find($id_session);
        $filieres = Filiere::where('code_etape', $code_etape)->first();

        
        return view('module.create_module', compact('filieres', 'session'));
    }

    public function storeModule(Request $request)
    {
        // Log the received parameters
        // Log::info('code elp: ' . $request->code_elp . ', lib elp: ' . $request->lib_elp . ', id_session: ' . $request->id_session . ', code_etape: ' . $request->code_etape);
    
        // Validate the request data
        $request->validate([
            'code_elp' => 'required|string|max:255',
            'lib_elp' => 'required|string|max:255',
            'id_session' => 'required|integer',
            'code_etape' => 'required|string|max:255',
        ]);
    
        // Find the Filiere based on code_etape
        $filiere = Filiere::where('code_etape', $request->code_etape)->first();
    
        // Check if the Filiere was found
        if (!$filiere) {
            return redirect()->back()->with('error', 'Aucune filière trouvée avec ce code étape.');
        }
    
        // Check if the module already exists
        $existingModule = $filiere->modules()->where('code_elp', $request->code_elp)->first();
        
        if ($existingModule) {
            return redirect()->back()->with('error', 'Ce module existe déjà.');
        }
    
        // Create a new module associated with the Filiere
        $filiere->modules()->create([
            'code_elp' => $request->code_elp,
            'lib_elp' => $request->lib_elp,
            'id_session' => $request->id_session,
            'code_etape' => $request->code_etape,
        ]);
    

     
        // Redirect back to the Filiere's show page with a success message
   // Redirect back to the Filiere's show page with a success message
        return redirect()->route('filiere.show', [
            'code_etape' => $request->code_etape,
            'id_session' => $request->id_session,
        ])->with('success', 'Module ajouté avec succès.');

    }
    
    
// i changed this one
    public function editModule($id_module)
    {
        // Find the Filiere and Module by their IDs
        $module = Module::findOrFail($id_module);
     // This retrieves the first Filiere that matches the session of the module
$filiere = Filiere::where('id_session', $module->id_session)->first();
$session = SessionExam::findOrFail($module->id_session);

        // dd($module,$filiere);

        // Return the edit view with the Filiere and Module data
        return view('module.edit_module', compact('filiere', 'module','session'));
    }

    // i changed this one
    public function updateModule(Request $request, $id_module)
{
// dd($id_module);
    // Validate the request data
    $request->validate([
        'code_elp' => 'required|string|max:255',
        'lib_elp' => 'required|string|max:255',
        'id_session' => 'required|integer',
        'code_etape' => 'required|string|max:255',
    ]);

    // Find the module by its ID
    $module = Module::findOrFail($id_module);

    // Find the Filiere based on code_etape
    $filiere = Filiere::where('code_etape', $request->code_etape)->first();

    // Check if the Filiere was found
    if (!$filiere) {
        return redirect()->back()->with('error', 'Aucune filière trouvée avec ce code étape.');
    }

    // Check if a different module with the same 'code_elp' already exists in the same Filiere
    $existingModule = $filiere->modules()
        ->where('code_elp', $request->code_elp)
        ->where('id', '!=', $id_module) // Ensure we exclude the current module
        ->first();

    if ($existingModule) {
        return redirect()->back()->with('error', 'Un autre module avec ce code existe déjà.');
    }

    // Update the module's data
    $module->update([
        'code_elp' => $request->code_elp,
        'lib_elp' => $request->lib_elp,
        'id_session' => $request->id_session,
        'code_etape' => $request->code_etape,
    ]);
    // dd($module);
    // Redirect back to the Filiere's show page with a success message
    return redirect()->route('filiere.show', [
        'code_etape' => $request->code_etape,
        'id_session' => $request->id_session,
    ])->with('success', 'Module mis à jour avec succès.');
}


    public function destroyModule(Request $request,$code_etape, $code_elp)
    {

        log::info('this the id session of module dleeted'.$request->id_session);
        // Find the Module by ID and delete it
        $module = Module::where('code_elp', $code_elp)
        ->where('id_session', $request->id_session)
        ->where('code_etape', $code_etape)
        ->first(); // Example for fetching the first result


        $module->delete();

        // Redirect back to the Filiere's show page with a success message
        return redirect()->route('filiere.show', ['code_etape' => $code_etape, 'id_session' =>$request->id_session])
            ->with('success', 'Module supprimé avec succès.');
    }

    public function show_module($moduleIdentifier, $code_etape)
    {

      
        // Fetch the filiere by code_etape
        $filiere = Filiere::where('code_etape', $code_etape)->firstOrFail();
        // dd($moduleIdentifier, $code_etape ,$filiere);
        // Check the type of the filiere
        if ($filiere->type == 'old') {
            // Fetch the module by its identifier and code_etape
            $module = Module::with('etudiants')
                ->where('code_elp', $moduleIdentifier)
                ->where('code_etape', $code_etape)
                ->firstOrFail();
        } else {
            // Fetch the module associated with the filiere from the filiere_gp table
            $module = Module::join('filiere_gp', 'modules.id', '=', 'filiere_gp.id_module')
                ->where('filiere_gp.code_etape', $code_etape)
                ->where('modules.lib_elp', $moduleIdentifier)
                ->with('etudiants')
                ->firstOrFail();
        }

        return view('module.show_module', compact('module'));
    }

    // Retourne les étudiants inscrits au format JSON pour DataTables
    public function students($lib_elp, $code_etape)
    {
        // Check if the filière is new or old
        $filiere = Filiere::where('code_etape', $code_etape)->firstOrFail();

        if ($filiere->type == 'new') {
            // For new filières, fetch the modules and their related student counts
            $modules = DB::table('modules')
                ->join('filiere_gp', 'modules.id', '=', 'filiere_gp.id_module')
                ->leftJoin('inscriptions', 'modules.id', '=', 'inscriptions.id_module')
                ->where('filiere_gp.code_etape', $code_etape)
                ->where('modules.lib_elp', $lib_elp)
                ->select('modules.id as module_id', 'modules.lib_elp', DB::raw('COUNT(inscriptions.id) as number_of_inscriptions'))
                ->groupBy('modules.id', 'modules.lib_elp')
                ->orderBy('modules.lib_elp', 'asc')
                ->get();

            // Log the results for debugging
            Log::info('Modules with IDs and Inscription Counts:', $modules->toArray());

            // Iterate over modules to log details
            foreach ($modules as $module) {
                Log::info('Module ID:', ['module_id' => $module->module_id]);
                Log::info('Number of Inscriptions:', ['number_of_inscriptions' => $module->number_of_inscriptions]);
            }

            // Assuming you want to get students for all modules, you need to collect their IDs
            $moduleIds = $modules->pluck('module_id');

            // Fetch the students related to the fetched modules
            $etudiants = Etudiant::join('inscriptions', 'etudiants.id', '=', 'inscriptions.id_etudiant')
                ->whereIn('inscriptions.id_module', $moduleIds)
                ->select('etudiants.nom', 'etudiants.prenom')
                ->get();
        } else {
            // For old filières, fetch the module directly from the module table
            $module = Module::where('lib_elp', $lib_elp)
                ->where('code_etape', $code_etape)
                ->select('id')
                ->firstOrFail();

            $moduleId = $module->id;

            // Log the moduleId
            Log::info('Module ID:', ['module_id' => $moduleId]);

            // Fetch the students related to the fetched module
            $etudiants = Etudiant::join('inscriptions', 'etudiants.id', '=', 'inscriptions.id_etudiant')
                ->where('inscriptions.id_module', $moduleId)
                ->select('etudiants.nom', 'etudiants.prenom')
                ->get();
        }

        // Log the count of fetched students and their details
        Log::info('Fetched Students Count:', ['count' => $etudiants->count()]);
        Log::info('Fetched Students:', ['students' => $etudiants]);

        return DataTables::of($etudiants)
            ->make(true);
    }
}
