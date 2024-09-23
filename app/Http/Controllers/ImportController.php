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
    public function showForm($sessionId)
    {
        $session = SessionExam::findOrFail($sessionId);
        return view('import-form', compact('session'));
    }

    public function import(Request $request, $sessionId)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '600');

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

                // Initialize import status
                Session::put('import_status', 'in_progress');

                DB::transaction(function () use ($rows, $batchSize, $totalRows, $sessionId) {
                    $filiereCache = [];
                    $moduleCache = [];
                    $cinSet = [];

                    for ($i = 1; $i < $totalRows; $i += $batchSize) {
                        if (Session::get('import_status') === 'cancelled') {
                            throw new \Exception('Importation annulée par l\'utilisateur.');
                        }

                        $batch = array_slice($rows, $i, $batchSize);
                        $this->processBatch($batch, $filiereCache, $moduleCache, $cinSet, $sessionId);
                    }
                });

                return back()->with('success', 'Importation terminée avec succès.');
            } catch (\Exception $e) {
                return back()->withErrors(['error' => $e->getMessage()]);
            }
        }

        return back()->withErrors(['error' => 'Le fichier n\'a pas pu être téléchargé.']);
    }

    private function processBatch(array $batch, array &$filiereCache, array &$moduleCache, array &$cinSet, $sessionId)
    {
        $etudiants = [];
        $inscriptions = [];

        foreach ($batch as $row) {
            $dateNaissance = null;
            if (isset($row[5]) && !empty($row[5])) {
                try {
                    $dateNaissance = \DateTime::createFromFormat('m/d/Y', $row[5]);
                    $dateNaissance = $dateNaissance->format('Y-m-d');
                } catch (\Exception $e) {
                    $dateNaissance = null;
                }
            }

            $cin = isset($row[3]) && !empty($row[3]) ? $row[3] : null;
            if ($cin !== null && Etudiant::where('cin', $cin)->exists()) {
                $cin = null;
            }

            if ($cin === null) {
                continue;
            }

            $etudiants[] = [
                'code_etudiant' => $row[0],
                'nom' => $row[1],
                'prenom' => $row[2],
                'cin' => $cin,
                'cne' => $row[4],
                'date_naissance' => $dateNaissance,
                'id_session' => $sessionId,
            ];

            // Vérifier que 'code_etape' et les informations de module sont présentes
            if (empty($row[9]) || empty($row[6]) || empty($row[7])) {
                Log::error('Skipping row due to missing code_etape or module information', $row);
                continue;
            }

            // Si la filière n'existe pas dans le cache, la créer ou la récupérer de la base de données
            if (!isset($filiereCache[$row[9]])) {
                $filiere = Filiere::where('code_etape', $row[9])->first();

                if (!$filiere) {
                    $filiere = Filiere::create([
                        'version_etape' => $row[8],
                        'code_etape' => $row[9],
                        'id_session' => $sessionId,
                    ]);
                }

                $filiereCache[$row[9]] = $filiere->id;
            }

            // Assurer que chaque module est associé à la filière correcte
            $moduleKey = $row[6] . '_' . $row[9]; // Unique key combining module code and filiere

            if (!isset($moduleCache[$moduleKey])) {
                // Créer ou récupérer le module pour la filière donnée
                $module = Module::where('code_elp', $row[6])
                    ->where('code_etape', $row[9])
                    ->first();

                if (!$module) {
                    $module = Module::create([
                        'code_elp' => $row[6],
                        'lib_elp' => $row[7],
                        'code_etape' => $row[9],
                        'id_session' => $sessionId,
                        'filiere_id' => $filiereCache[$row[9]], // Associer le module à la filière
                    ]);
                }

                $moduleCache[$moduleKey] = $module->id;
            }

            $inscriptions[] = [
                'id_etudiant' => $row[0],
                'id_module' => $moduleCache[$moduleKey],
                'id_session' => $sessionId,
            ];
        }

        // Insertion en masse des étudiants
        Etudiant::upsert($etudiants, ['code_etudiant'], ['nom', 'prenom', 'cin', 'cne', 'date_naissance']);

        // Récupération des IDs des étudiants après l'insertion
        $studentIds = Etudiant::whereIn('code_etudiant', array_column($etudiants, 'code_etudiant'))
            ->pluck('id', 'code_etudiant');

        // Mapper les IDs des étudiants aux inscriptions
        foreach ($inscriptions as &$inscription) {
            $inscription['id_etudiant'] = $studentIds[$inscription['id_etudiant']];
        }

        // Insertion en masse des inscriptions
        Inscription::insert($inscriptions);
    }

    public function cancelImport(Request $request)
    {
        Session::put('import_status', 'cancelled');
        return back()->with('success', 'Importation annulée.');
    }
}
