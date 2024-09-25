<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Examen;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\FiliereGp;
use App\Models\SessionExam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class EtudiantController extends Controller
{
    public function index(Request $request)
    {
        $sessions = SessionExam::all();
        $selectedSessionId = $request->input('session_id');

        // Récupérer les étudiants de la session sélectionnée, ou un tableau vide si aucune session sélectionnée
        $etudiants = $selectedSessionId ? Etudiant::where('id_session', $selectedSessionId)->get() : [];

        return view('etudiants.index', compact('sessions', 'selectedSessionId', 'etudiants'));
    }

    public function create()
    {
        $modules = Module::all(); // Fetch all modules
        $sessions = SessionExam::all(); // Fetch all sessions

        return view('etudiants.create', compact('modules', 'sessions'));
    }

    public function store(Request $request)
{
    // Validation des données
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'code_etudiant' => 'required|string|unique:etudiants',
        'cin' => 'nullable|string|max:255',
        'cne' => 'required|string|max:255',
        'date_naissance' => 'required|date',
        'id_session' => 'required|exists:session_exams,id', // Assurez-vous que id_session est requis et valide
        'modules' => 'required|array', // Valider que 'modules' est un tableau
        'modules.*' => 'required|exists:modules,id', // Vérifier que chaque module existe
    ]);

    // Création de l'étudiant
    $etudiant = Etudiant::create([
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'code_etudiant' => $request->code_etudiant,
        'cin' => $request->cin,
        'cne' => $request->cne,
        'date_naissance' => $request->date_naissance,
        'id_session' => $request->id_session, // Assurez-vous que id_session est bien inclus ici
    ]);

    // Insertion des inscriptions pour chaque module
    foreach ($request->modules as $moduleId) {
        DB::table('inscriptions')->insert([
            'id_etudiant' => $etudiant->id, // Utiliser l'id de l'étudiant créé
            'id_module' => $moduleId,
            'id_session' => $request->id_session, // Assurez-vous d'inclure id_session
        ]);
    }

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
        $modules = Module::all();
        $selectedModules = $etudiant->modules->pluck('id')->toArray();
        $sessions = SessionExam::all();

        return view('etudiants.edit', compact('etudiant', 'modules', 'selectedModules', 'sessions'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'cin' => 'nullable|string|max:255',
            'cne' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'session_id' => 'required|exists:session_exams,id',
            'modules' => 'nullable|array',
            'modules.*' => 'exists:modules,id',
        ]);

        try {
            // Mettre à jour l'étudiant avec les données validées
            $etudiant->update($validatedData);

            // Synchroniser les modules avec l'ajout de l'id_session
            if ($request->has('modules')) {
                foreach ($request->input('modules') as $moduleId) {
                    // Créer ou mettre à jour l'inscription
                    $etudiant->modules()->attach($moduleId, ['id_session' => $validatedData['session_id']]);
                }
            } else {
                // Désassocier tous les modules si aucun n'est sélectionné
                $etudiant->modules()->sync([]);
            }

            return redirect()->route('etudiants.index')->with('success', 'Étudiant mis à jour avec succès.');
        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->back()->withErrors(['update_error' => 'Erreur lors de la mise à jour de l\'étudiant : ' . $e->getMessage()]);
        }
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

    public function selectFiliere()
    {
        $filieres = Filiere::select('code_etape', 'version_etape', 'type')
            ->orderBy('version_etape')
            ->get();

        // $sessions = SessionExam::orderBy('type')->pluck('type', 'id');
        $sessions = SessionExam::all(); // Récupère toutes les sessions avec leurs informations


        $code_etape = request('code_etape', '');
        $id_session = request('id_session', '');

        return view('etudiants.select_filiere', compact('filieres', 'sessions', 'code_etape', 'id_session'));
    }

    public function downloadStudentsPDF($sessionId, $code_etape)
    {
        // Récupérer la session et la filière
        $session = SessionExam::findOrFail($sessionId);
        $filiere = Filiere::where('code_etape', $code_etape)->firstOrFail();

        // Récupérer tous les modules de la filière
        $modules = Module::where('code_etape', $code_etape)->get();

        // Récupérer les étudiants inscrits dans les modules de la filière pour la session, triés par nom et prénom
        $students = Etudiant::whereHas('inscriptions', function ($query) use ($code_etape, $sessionId) {
            $query->whereHas('module', function ($q) use ($code_etape) {
                $q->where('code_etape', $code_etape);
            })->where('id_session', $sessionId);
        })->orderBy('nom')->orderBy('prenom')->get();

        // Vérifier si des étudiants sont trouvés
        if ($students->isEmpty()) {
            return response()->json(['message' => 'Aucun étudiant trouvé pour cette filière et session.'], 404);
        }

        // Récupérer les examens de la session et de la filière avec le module associé
        $exams = DB::table('examens')
            ->select('examens.*', 'salles.name as salle_name', 'exam_module.module_id as id_module')
            ->join('examen_salle', 'examens.id', '=', 'examen_salle.id_examen')
            ->join('salles', 'examen_salle.id_salle', '=', 'salles.id')
            ->join('exam_module', 'examens.id', '=', 'exam_module.exam_id')
            ->where('examens.id_session', $sessionId)
            ->whereExists(function ($query) use ($code_etape) {
                $query->select(DB::raw(1))
                    ->from('modules')
                    ->whereRaw('exam_module.module_id = modules.id')
                    ->where('modules.code_etape', '=', $code_etape);
            })
            ->get();

        // Générer le PDF avec Dompdf
        $pdf = new Dompdf();
        $pdf->loadHtml(view('etudiants.students_pdf', compact('session', 'filiere', 'students', 'exams', 'modules'))->render());
        $pdf->setPaper('A3', 'portrait');
        $pdf->render();

        return $pdf->stream('Examen_Etudiants.pdf', ['Attachment' => 0]);
    }




    public function downloadPDF($sessionId, $codeEtape)
    {
        ini_set('max_execution_time', 600);
        $options = new DompdfOptions();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        $filiere = Filiere::where('code_etape', $codeEtape)->first();

        if ($filiere && $filiere->type == 'new') {
            $moduleIds = FiliereGp::where('code_etape', $codeEtape)
                ->pluck('id_module');

            // Retrieve exams with related modules, teachers, and rooms
            $exams = Examen::with(['modules.etudiants', 'enseignant'])
                ->whereHas('modules', function ($query) use ($moduleIds) {
                    $query->whereIn('modules.id', $moduleIds); // Specify 'modules.id' to avoid ambiguity
                })
                ->where('id_session', $sessionId)
                ->get();
        } else {
            $exams = Examen::with(['modules.etudiants', 'enseignant'])
                ->whereHas('modules', function ($query) use ($codeEtape) {
                    $query->where('code_etape', $codeEtape);
                })
                ->where('id_session', $sessionId)
                ->get();
        }
        // return $exams;
        $student = $exams->flatMap(function ($exam) {
            return $exam->modules->flatMap(function ($module) {
                return $module->etudiants;
            });
        });
        // Ensure all students are correctly retrieved
        $students = $student->filter(function ($student) {
            return !empty($student->nom);
        });

        // Sort students by 'nom' in ascending order
        $students = $students->sortBy('nom');

        // Optionally reset keys if needed
        $students = $students->values();
        $salleNames = $exams->flatMap(function ($exam) {
            // Ensure `sallesSupplementaires` is a collection
            return $exam->sallesSupplementaires;
        });

        // return $salleNames;
        $html = view('etudiants.pdf', ['exams' => $exams, "students" => $students, "salles" => $salleNames])->render();

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        return $dompdf->stream('liste_etudiants_module.pdf', ['Attachment' => 0]);
    }
}
