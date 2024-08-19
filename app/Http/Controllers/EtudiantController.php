<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Examen;
use App\Models\Module;
use App\Models\SessionExam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Collection;
use Dompdf\Options;


class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer toutes les sessions disponibles
        $sessions = SessionExam::all();
    
        // Si la requête est une requête AJAX
        if ($request->ajax()) {
            $sessionId = $request->input('session_id');
    
            if (!$sessionId) {
                // Renvoyer une réponse vide si aucune session n'est sélectionnée
                return response()->json([
                    'data' => []
                ]);
            }
    
            // Requête pour récupérer les étudiants en fonction de la session sélectionnée
            $query = Etudiant::where('id_session', $sessionId);
    
            return DataTables::of($query)
                ->addColumn('fullName', function (Etudiant $etudiant) {
                    return $etudiant->nom . ' ' . $etudiant->prenom;
                })
                ->addColumn('action', function (Etudiant $etudiant) {
                    return '<a href="/etudiants/' . $etudiant->id . '/edit" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        // Définir une valeur par défaut pour la variable selectedSessionId
        $selectedSessionId = $request->input('session_id', null);
    
        // Passer la variable selectedSessionId à la vue
        return view('etudiants.index', compact('sessions', 'selectedSessionId'));
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


    public function generatePdf($sessionId)
{
    ini_set('max_execution_time', 600);
    $options = new DompdfOptions();
    $options->set('defaultFont', 'Arial');
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $dompdf = new Dompdf($options);
    dd($sessionId);

    // Fetch data needed for PDF generation, filtering by the selected session
    $exams = Examen::with(['module.etudiants', 'salles', 'responsable'])
        ->where('id_session', $sessionId)  // Assuming 'session_id' is the foreign key in the 'exams' table
        ->get();

    // Load HTML view file with data
    $html = view('etudiants.pdf', ['exams' => $exams])->render();

    // Load HTML to Dompdf
    $dompdf->loadHtml($html);

    // Render the PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    return $dompdf->stream('liste_etudiants.pdf', ['Attachment' => 0]);
}

}
