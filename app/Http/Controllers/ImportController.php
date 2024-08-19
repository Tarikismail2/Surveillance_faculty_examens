<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Inscription;
use App\Models\Filiere;
use App\Models\SessionExam;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function showForm($id)
    {
        // Assurez-vous que vous utilisez l'ID correctement
        $session = SessionExam::findOrFail($id);
        return view('import-form', compact('session'));
    }

    public function import(Request $request, $sessionId)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls|max:2048',
    ]);

    // Vérifiez si les étudiants pour cette session ont déjà été importés
    $existingStudents = Etudiant::where('id_session', $sessionId)->exists();
    if ($existingStudents) {
        return back()->withErrors(['error' => 'Les données pour cette session ont déjà été importées.']);
    }

    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 600);

    if ($request->file('file')->isValid()) {
        try {
            $fileName = time() . '.' . $request->file('file')->extension();
            $request->file('file')->move(public_path('uploads'), $fileName);

            $filePath = public_path('uploads') . '/' . $fileName;
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $batchSize = 1000;
            $totalRows = count($rows);

            Session::put('import_status', 'in_progress');

            DB::transaction(function () use ($rows, $batchSize, $totalRows, $sessionId) {
                $filiereCache = [];
                $moduleCache = [];

                for ($i = 1; $i < $totalRows; $i += $batchSize) {
                    if (Session::get('import_status') === 'cancelled') {
                        throw new \Exception('Importation annulée par l\'utilisateur.');
                    }

                    $batch = array_slice($rows, $i, $batchSize);
                    $this->processBatch($batch, $filiereCache, $moduleCache, $sessionId);
                }
            });

            return back()->with('success', 'Importation terminée avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    return back()->withErrors(['error' => 'Le fichier n\'a pas pu être téléchargé.']);
}

private function processBatch(array $batch, array &$filiereCache, array &$moduleCache, $sessionId)
{
    $etudiants = [];
    $inscriptions = [];

    foreach ($batch as $row) {
        // Log de la ligne en cours de traitement
        Log::info('Processing row: ', $row);

        $dateNaissance = null;
        if (isset($row[5]) && !empty($row[5])) {
            try {
                $dateNaissance = \DateTime::createFromFormat('m/d/Y', $row[5]);
            } catch (\Exception $e) {
                Log::error('Erreur de formatage de la date de naissance: ' . $row[5]);
                $dateNaissance = null;
            }
        }

        $etudiants[] = [
            'code_etudiant' => $row[0],
            'nom' => $row[1],
            'prenom' => $row[2],
            'cin' => $row[3],
            'cne' => $row[4],
            'date_naissance' => $dateNaissance ? $dateNaissance->format('Y-m-d') : null,
            'id_session' => $sessionId,
        ];

        // Utilisation de version_etape + code_etape comme clé unique pour le cache
        $filiereKey = $row[8] . '_' . $row[9];

        if (!isset($filiereCache[$filiereKey])) {
            $filiere = Filiere::firstOrCreate(
                ['version_etape' => $row[8]],
                ['code_etape' => $row[9]]
            );
            $filiereCache[$filiereKey] = $filiere->id;

            // Log de la création d'une nouvelle filière
            Log::info('Nouvelle filière créée ou trouvée: ' . $filiereKey . ' avec ID ' . $filiere->id);
        } else {
            // Log de l'utilisation d'une filière existante dans le cache
            Log::info('Filière récupérée du cache: ' . $filiereKey . ' avec ID ' . $filiereCache[$filiereKey]);
        }

        if (!isset($moduleCache[$row[6]])) {
            $module = Module::updateOrCreate(
                [
                    'code_elp' => $row[6],
                    'lib_elp' => $row[7],
                    'version_etape' => $row[8],
                    'code_etape' => $row[9],
                ],
                ['id_filiere' => $filiereCache[$filiereKey]]
            );
            $moduleCache[$row[6]] = $module->id;

            // Log de la création ou mise à jour d'un module
            Log::info('Module créé ou mis à jour: ' . $row[6] . ' avec ID ' . $module->id);
        } else {
            // Log de l'utilisation d'un module existant dans le cache
            Log::info('Module récupéré du cache: ' . $row[6] . ' avec ID ' . $moduleCache[$row[6]]);
        }

        $inscriptions[] = [
            'code_etudiant' => $row[0], // Utilisez le code ici temporairement
            'id_module' => $moduleCache[$row[6]],
        ];
    }

    // Insertion ou mise à jour des étudiants
    Etudiant::upsert($etudiants, ['code_etudiant'], ['nom', 'prenom', 'cin', 'cne', 'date_naissance', 'id_session']);

    // Récupérer les ID des étudiants en fonction de leurs codes étudiants
    $studentIds = Etudiant::whereIn('code_etudiant', array_column($etudiants, 'code_etudiant'))
        ->pluck('id', 'code_etudiant');

    // Mise à jour des inscriptions avec les ID réels des étudiants
    foreach ($inscriptions as &$inscription) {
        $inscription['id_etudiant'] = $studentIds[$inscription['code_etudiant']];
        unset($inscription['code_etudiant']); // Supprimez le code une fois l'ID ajouté
    }

    // Log avant l'insertion des inscriptions
    Log::info('Inscriptions à insérer: ', $inscriptions);

    // Insérer les inscriptions
    Inscription::insert($inscriptions);
}




    public function cancelImport(Request $request)
    {
        Session::put('import_status', 'cancelled');
        return back()->with('success', 'Importation annulée.');
    }
}
