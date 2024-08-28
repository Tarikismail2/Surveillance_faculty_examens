<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\SessionExam;
use Yajra\DataTables\DataTables;

class FiliereController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $filieres = Filiere::select(['id', 'code_etape', 'version_etape'])->get();
    
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
        if (request()->ajax()) {
            $filiere = Filiere::with('modules')->findOrFail($id);
            $modules = $filiere->modules;
    
            return DataTables::of($modules)
                ->addColumn('action', function ($module) use ($filiere) {
                    return '
                        <a href="' . route('modules.edit', [$filiere->id, $module->id]) . '" class="text-yellow-600 hover:text-yellow-700" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L5 12.172V15h2.828l9.586-9.586a2 2 0 000-2.828zM4 13H3v4a1 1 0 001 1h4v-1H4v-3z" />
                            </svg>
                        </a>
                        <form action="' . route('modules.destroy', [$filiere->id, $module->id]) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-600" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                        <a href="' . route('modules.show', $module->id) . '" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300 ease-in-out" title="Details">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 8a1 1 0 011-1h6a1 1 0 011 1v9a1 1 0 11-2 0v-1H8v1a1 1 0 11-2 0V8zm3-3a1 1 0 00-1-1V3a1 1 0 112 0v1a1 1 0 00-1 1z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    ';
                })
                ->make(true);
        }
    
        // Si ce n'est pas une requête AJAX, renvoyez la vue normale
        $filiere = Filiere::with('modules')->findOrFail($id);
        return view('filiere.show', compact('filiere'));
    }
    

    public function create()
    {
        $sessions = SessionExam::all();
        return view('filiere.create', compact('sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code_etape' => 'required|string|max:255',
            'version_etape' => 'required|string|max:255',
            'id_session' => 'required|exists:session_exams,id',
        ]);

        Filiere::create($request->all());

        return redirect()->route('filiere.index')->with('success', 'Filière créée avec succès.');
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
