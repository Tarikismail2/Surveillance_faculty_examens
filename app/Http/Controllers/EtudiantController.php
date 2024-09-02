<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Examen;
use App\Models\Module;
use App\Models\SessionExam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;


class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        $sessions = SessionExam::all();
        $selectedSessionId = $request->input('session_id'); // Récupérer l'ID de la session sélectionnée
    
        if ($request->ajax()) {
            if (!$selectedSessionId) {
                return response()->json(['data' => []]);
            }
            $query = Etudiant::where('id_session', $selectedSessionId);
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
    
        return view('etudiants.index', compact('sessions', 'selectedSessionId'));
    }
    
    public function create()
    {
        $modules = Module::all(); // Récupérer tous les modules
        $sessions = SessionExam::all(); // Récupérer toutes les sessions
        return view('etudiants.create', compact('modules', 'sessions'));
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
            'session_id' => 'required|exists:session_exams,id', // Valider la session
            'modules' => 'required|array',
        ]);

        // Créer l'étudiant avec session_id
        $etudiant = Etudiant::create([
            'code_etudiant' => $validatedData['code_etudiant'],
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'cin' => $validatedData['cin'],
            'cne' => $validatedData['cne'],
            'date_naissance' => $validatedData['date_naissance'],
            'session_id' => $validatedData['session_id'], // Ajouter session_id
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

        $session = $etudiant->session;

        return view('etudiants.show', compact('etudiant', 'modules', 'session'));
    }
    

    public function edit(Etudiant $etudiant)
    {
        $modules = Module::all(); // Récupère tous les modules
        $selectedModules = $etudiant->modules->pluck('id')->toArray();
        $sessions = SessionExam::all(); // Récupère toutes les sessions

        return view('etudiants.edit', compact('etudiant', 'modules', 'selectedModules', 'sessions'));
    }


    public function update(Request $request, Etudiant $etudiant)
    {
        // Valider les données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'cin' => 'nullable|string|max:255',
            'cne' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'session_id' => 'required|exists:session_exams,id', // Valider la session (correctement nommée)
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
        ]);

        // Mettre à jour l'étudiant avec session_id
        $etudiant->update($request->only(['nom', 'prenom', 'cin', 'cne', 'date_naissance', 'session_id']));

        // Synchroniser les modules
        if ($request->has('modules')) {
            $etudiant->modules()->sync($request->input('modules'));
        } else {
            $etudiant->modules()->sync([]); // Désassocier tous les modules si aucun n'est sélectionné
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
        // dd($sessionId);

        // Fetch data needed for PDF generation, filtering by the selected session
        $exams = Examen::with(['module.etudiants', 'salles', 'enseignant'])
            ->where('id_session', $sessionId)
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
