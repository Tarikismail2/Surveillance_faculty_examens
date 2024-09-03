<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\SessionExam;
use App\Models\FiliereGp;
use Yajra\DataTables\DataTables;

class FiliereController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $filieres = Filiere::select(['id', 'code_etape', 'type', 'version_etape'])->get(); // Include 'version_etape'
    
            return DataTables::of($filieres)
                ->addColumn('action', function ($filiere) {
                    return '
                        <a href="' . route('filiere.edit', $filiere->id) . '" class="text-yellow-600 hover:text-yellow-700 ml-4" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                            </svg>
                        </a>
                        <form action="' . route('filiere.destroy', $filiere->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                        <a href="' . route('filiere.show', $filiere->id) . '" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out" title="Details">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    ';
                })
                ->make(true);
        }
    
        return view('filiere.index');
    }


public function show($id)
{
    // Fetch the filiere with its related modules
    $filiere = Filiere::with('modules')->findOrFail($id);

    // Check the type of the filiere
    if ($filiere->type == 'old') {
        // Fetch all modules using the code_etape
        $modules = Module::where('code_etape', $filiere->code_etape)
                         ->select('id', 'code_elp', 'lib_elp', 'version_etape', 'code_etape', 'id_session')
                         ->get();

        return view('filiere.show', compact('filiere', 'modules'));
    } else {
        // Fetch modules associated with the filiere and group them by lib_elp
        $filiereGps = FiliereGp::where('id_filiere', $filiere->id)->pluck('id_module');
        $modulesGrouped = Module::whereIn('id', $filiereGps)
                                ->groupBy('lib_elp')
                                ->select('lib_elp')
                                ->get();

        return view('filiere.show', compact('filiere', 'modulesGrouped'));
    }
}

    
    

    public function create()
    {
        $sessions = SessionExam::all();
        $filieres = Filiere::select(['id', 'code_etape', 'version_etape'])->get();

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
                    'id_filiere' => $filiere->id,
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


    public function edit($id)
    {
        $filiere = Filiere::findOrFail($id); // Using findOrFail for consistency
        $sessions = SessionExam::all();

        return view('filiere.edit', compact('filiere', 'sessions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code_etape' => 'required|string|max:255',
            'version_etape' => 'required|string|max:255',
            'id_session' => 'required|exists:session_exams,id',
        ]);

        $filiere = Filiere::findOrFail($id);
        $filiere->update($request->all());

        return redirect()->route('filiere.index')->with('success', 'Filière mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $filiere = Filiere::findOrFail($id);
        $filiere->delete();

        return redirect()->route('filiere.index')->with('success', 'Filière supprimée avec succès.');
    }
}