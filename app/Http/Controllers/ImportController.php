<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Inscription;
use App\Models\Filiere;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function showForm()
    {
        return view('import-form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

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

                $batchSize = 1000; // Adjust the batch size if necessary
                $totalRows = count($rows);

                // Initialize import status
                Session::put('import_status', 'in_progress');

                DB::transaction(function () use ($rows, $batchSize, $totalRows) {
                    $filiereCache = [];
                    $moduleCache = [];

                    for ($i = 1; $i < $totalRows; $i += $batchSize) {
                        // Check import status
                        if (Session::get('import_status') === 'cancelled') {
                            throw new \Exception('Importation annulée par l\'utilisateur.');
                        }

                        $batch = array_slice($rows, $i, $batchSize);
                        $this->processBatch($batch, $filiereCache, $moduleCache);
                    }
                });

                return back()->with('success', 'Importation terminée avec succès.');
            } catch (\Exception $e) {
                return back()->withErrors(['error' => $e->getMessage()]);
            }
        }

        return back()->withErrors(['error' => 'Le fichier n\'a pas pu être téléchargé.']);
    }

    private function processBatch(array $batch, array &$filiereCache, array &$moduleCache)
    {
        $etudiants = [];
        $inscriptions = [];

        foreach ($batch as $row) {
            // Validate and convert the date
            $dateNaissance = null;
            if (isset($row[5]) && !empty($row[5])) {
                try {
                    $dateNaissance = \DateTime::createFromFormat('m/d/Y', $row[5]);
                } catch (\Exception $e) {
                    $dateNaissance = null;
                }
            }

            // Set CIN to null if it is empty or duplicate
            $cin = isset($row[3]) && !empty($row[3]) ? $row[3] : null;

            // Check for duplicate CIN
            if ($cin !== null && Etudiant::where('cin', $cin)->exists()) {
                $cin = null;
            }

            $etudiants[] = [
                'code_etudiant' => $row[0],
                'nom' => $row[1],
                'prenom' => $row[2],
                'cin' => $cin,
                'cne' => $row[4],
                'date_naissance' => $dateNaissance ? $dateNaissance->format('Y-m-d') : null,
            ];

            // Cache or fetch Filiere
            if (!isset($filiereCache[$row[8]])) {
                $filiereCache[$row[8]] = Filiere::firstOrCreate(
                    ['version_etape' => $row[8]],
                    ['code_etape' => $row[9]]
                )->id;
            }

            // Cache or fetch Module
            if (!isset($moduleCache[$row[6]])) {
                $moduleCache[$row[6]] = Module::updateOrCreate(
                    [
                        'code_elp' => $row[6],
                        'lib_elp' => $row[7],
                        'version_etape' => $row[8],
                        'code_etape' => $row[9],
                    ]
                )->id;
            }

            $inscriptions[] = [
                'id_etudiant' => $row[0], // Use the student code as a temporary key
                'id_module' => $moduleCache[$row[6]],
            ];
        }

        // Bulk insert students
        Etudiant::upsert($etudiants, ['code_etudiant'], ['nom', 'prenom', 'cin', 'cne', 'date_naissance']);

        // Resolve student IDs after bulk insert
        $studentIds = Etudiant::whereIn('code_etudiant', array_column($etudiants, 'code_etudiant'))
            ->pluck('id', 'code_etudiant');

        // Map the student IDs to inscriptions
        foreach ($inscriptions as &$inscription) {
            $inscription['id_etudiant'] = $studentIds[$inscription['id_etudiant']];
        }

        // Bulk insert inscriptions
        Inscription::insert($inscriptions);
    }

    public function cancelImport(Request $request)
    {
        Session::put('import_status', 'cancelled');
        return back()->with('success', 'Importation annulée.');
    }
}
