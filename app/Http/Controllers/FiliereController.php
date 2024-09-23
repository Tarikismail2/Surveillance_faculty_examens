<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\SessionExam;
use App\Models\FiliereGp;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

class FiliereController extends Controller
{

    public function index_session(){
        $sessions = SessionExam::all();
        return view('filiere.index_session', compact('sessions'));

    }
    public function index($id_session)
    {

        Log::info("message. $id_session");
        if (request()->ajax()) {
            $filieres = Filiere::select(['code_etape', 'version_etape', 'type'])
            ->where('id_session', $id_session) 
            ->get();
    
            return DataTables::of($filieres)
            ->addColumn('action', function ($filiere) use ($id_session) {
                return '
                    <a href="' . route('filiere.edit',  ['code_etape' => $filiere->code_etape, 'id_session' => $id_session]) . '" class="text-yellow-600 hover:text-yellow-700 ml-4" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                        </svg>
                    </a>
                    <form action="' . route('filiere.destroy', $filiere->code_etape) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure?\')">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="text-red-600 hover:text-red-900 ml-4" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>
                    <a href="' . route('filiere.show', ['code_etape' => $filiere->code_etape, 'id_session' => $id_session]) . '" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out" title="Details">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                        </svg>
                    </a>
                ';
            })            
                ->make(true);
        }
    
        return view('filiere.index', ['id_session' => $id_session]);
    }

    public function show($code_etape, $id_session)
    {
        // Fetch the filiere by code_etape
        $filiere = Filiere::where('code_etape', $code_etape)
                          ->where('id_session', $id_session)  // Ensure the filiere matches the session
                          ->firstOrFail();
    
        // Check the type of the filiere and retrieve modules accordingly
        if ($filiere->type == 'old') {
            // Fetch modules with inscription count for old type
            $modules = Module::where('code_etape', $filiere->code_etape)
                             ->select('id','code_elp', 'lib_elp')
                             ->withCount(['inscriptions as total_inscriptions'])
                             ->orderBy('total_inscriptions', 'DESC')
                             ->get();
        } else {
            // Fetch modules and their inscription count for the new type
            $modules = Module::join('filiere_gp', 'modules.id', '=', 'filiere_gp.id_module')
                             ->leftJoin('inscriptions', 'modules.id', '=', 'inscriptions.id_module')
                             ->where('filiere_gp.code_etape', $filiere->code_etape)
                             ->select('modules.lib_elp', DB::raw('COUNT(inscriptions.id) as number_of_inscriptions'))
                             ->groupBy('modules.lib_elp')
                             ->orderBy('number_of_inscriptions', 'DESC')
                             ->get();
        }
    
        return view('filiere.show', compact('filiere', 'modules','id_session'));
    }
    

    
    

    public function create()
    {
        $sessions = SessionExam::all();
        $filieres = Filiere::select(['code_etape', 'version_etape'])
        ->where('type', 'old')
        ->get();
    

        return view('filiere.create', compact('sessions', 'filieres'));
    }

 public function store(Request $request)
{
    // Validate request data
    $validatedData = $request->validate([
        'code_etape' => 'required|string|max:255',
        'version_etape' => 'required|string|max:255',
        'id_session' => 'required|exists:session_exams,id',
        'filieres' => 'nullable|array',
        'filieres.*' => 'string|distinct' // Ensure each item in the array is a string and unique
    ]);

    // Create the Filiere
    $filiere = Filiere::create([
        'code_etape' => $validatedData['code_etape'],
        'version_etape' => $validatedData['version_etape'],
        'id_session' => $validatedData['id_session'],
        'type' => 'new',
    ]);

    // Initialize a flag to check if any modules were added
    $modulesAdded = false;

    // Check and handle the FiliereGp entries if 'filieres' are provided
    if (!empty($validatedData['filieres'])) {
        // Gather all modules related to the selected codes in one query
        $moduleIds = Module::whereIn('code_etape', $validatedData['filieres'])
            ->pluck('id')
            ->toArray();

        // Check if there are any modules found
        if (empty($moduleIds)) {
            return redirect()->route('filiere.index')->with('error', 'Aucun module trouvé pour les codes fournis.');
        }

        // Create or update FiliereGp entries
        foreach ($moduleIds as $moduleId) {
            FiliereGp::updateOrCreate(
                [
                    'id_module' => $moduleId,
                    'id_session' => $filiere->id_session
                ],
                [
                    'version_etape' => $filiere->version_etape,
                    'code_etape' => $filiere->code_etape
                ]
            );

            // Set the flag to true if at least one module was added
            $modulesAdded = true;
        }
    }

    // Check if any modules were added and provide feedback
    if ($modulesAdded) {
        return redirect()->route('filiere.index')->with('success', 'Filière créée avec succès.');
    } else {
        return redirect()->route('filiere.index')->with('error', 'Aucun module ajouté.');
    }
}

    public function fetchModules(Request $request, $filiereId)
    {
        $modules = Module::where('filiere_id', $filiereId)->get();

        return response()->json(['modules' => $modules]);
    }


    public function edit($code_etape, $id_session)
    {
        $sessions=SessionExam::all();
        // Fetch the filiere by code_etape
        $filiere = Filiere::where('code_etape', $code_etape)
                          ->where('id_session', $id_session)  
                          ->firstOrFail();
                          $filieres = Filiere::where('type', 'old')->get();

                // return $filieres;          
                // dd($filiere);
      
        $distinctCodeEtapes = [];
        if ($filiere->type == 'new') { 
            $distinctCodeEtapes = FiliereGp::join('modules', 'filiere_gp.id_module', '=', 'modules.id')
                ->where('filiere_gp.code_etape', $code_etape)
                ->where('modules.id_session', $id_session)
                ->distinct()
                ->pluck('modules.code_etape')
                ->toArray(); // Convert to array here
        }
        
            // dd($distinctCodeEtapes);

            return view('filiere.edit', compact('filiere','distinctCodeEtapes','sessions','filieres'));
    }

    public function update(Request $request, $code_etape, $id_session)
{
    try {
      // Validate request data
    $validatedData = $request->validate([
        'code_etape' => 'required|string|max:255',
        'version_etape' => 'required|string|max:255',
        'id_session' => 'required|exists:session_exams,id',
        'filieres' => 'nullable|array',
        'filieres.*' => 'string|distinct' // Ensure each item in the array is a string and unique
    ]);
// dd($validatedData);
    // Fetch the Filiere to update
    $filiere = Filiere::where('code_etape', $code_etape)
                      ->where('id_session', $id_session)
                      ->firstOrFail();

    // Update the Filiere
    $filiere->update([
        'code_etape' => $validatedData['code_etape'],
        'version_etape' => $validatedData['version_etape'],
        'id_session' => $validatedData['id_session'],
        // You may want to keep the type as is, depending on your requirements
    ]);
// dd($filiere);
    // Initialize a flag to check if any modules were added
    $modulesAdded = false;

    // Check and handle the FiliereGp entries if 'filieres' are provided
    if (!empty($validatedData['filieres'])) {
        // Gather all modules related to the selected codes in one query
        $moduleIds = Module::whereIn('code_etape', $validatedData['filieres'])
            ->pluck('id')
            ->toArray();
// dd($moduleIds);
        // Check if there are any modules found
        if (empty( $moduleIds )) {
            return redirect()->route('filiere.index')->with('error', 'Aucun module trouvé pour les codes fournis.');
        }
// First, delete existing records that match the criteria
FiliereGp::where('id_session', $id_session)
    ->whereIn('id_module',  $moduleIds )
    ->delete();

// Then, create new records for each module ID
foreach ($moduleIds as $moduleId) {
    // Check if the module ID is valid or exists in your intended context
    if ($moduleId) {
        FiliereGp::create([
            'id_module' => $moduleId,
            'id_session' => $filiere->id_session,
            'version_etape' => $filiere->version_etape,
            'code_etape' => $filiere->code_etape
        ]);
    }
    $modulesAdded = true;
}
        
    }
    } catch (\Exception $e) {
        Log::error('Error updating FiliereGp: ' . $e->getMessage());
    }
    
    

    // Check if any modules were added or updated and provide feedback
    if ($modulesAdded) {
        return redirect()->route('filiere.index',$id_session)->with('success', 'Filière mise à jour avec succès.');
    } else {
        return redirect()->route('filiere.index',$id_session)->with('error', 'Aucun module ajouté ou mis à jour.');
    }
}


    public function destroy($id)
    {
        $filiere = Filiere::findOrFail($id);
        $filiere->delete();

        return redirect()->route('filiere.index')->with('success', 'Filière supprimée avec succès.');
    }
}