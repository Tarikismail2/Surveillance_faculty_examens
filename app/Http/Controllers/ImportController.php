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

                $batchSize = 1000;
                $totalRows = count($rows);

                // Initialize import status
                Session::put('import_status', 'in_progress');

                DB::transaction(function () use ($rows, $batchSize, $totalRows) {
                    $filiereCache = [];
                    $moduleCache = [];

                    for ($i = 1; $i < $totalRows; $i += $batchSize) {
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

    private function processBatch(array $batch, array &$filiereCache, array &$moduleCache, $sessionId)
    {
        $etudiants = [];
        $inscriptions = [];

        foreach ($batch as $row) {
            $dateNaissance = null;
            if (isset($row[5]) && !empty($row[5])) {
                try {
                    $dateNaissance = \DateTime::createFromFormat('m/d/Y', $row[5]);
                } catch (\Exception $e) {
                    $dateNaissance = null;
                }
            }

            $cin = isset($row[3]) && !empty($row[3]) ? $row[3] : null;

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
                'id_session' => $sessionId,
            ];

            // Cache or create Filiere
            if (!isset($filiereCache[$row[8]])) {
                $filiere = Filiere::firstOrCreate(
                    ['version_etape' => $row[8]],
                    ['code_etape' => $row[9]]
                );
                $filiereCache[$row[8]] = $filiere->id;
            }

            // Cache or create Module and associate it with the correct Filiere
            if (!isset($moduleCache[$row[6]])) {
                $module = Module::updateOrCreate(
                    [
                        'code_elp' => $row[6],
                        'lib_elp' => $row[7],
                        'version_etape' => $row[8],
                        'code_etape' => $row[9],
                    ]
                );
                // Associate the Module with the Filiere using the correct relationship
                $module->filiere()->associate(Filiere::find($filiereCache[$row[8]]));
                $module->save();
                $moduleCache[$row[6]] = $module->id;
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
