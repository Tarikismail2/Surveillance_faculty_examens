<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Inscription;
use App\Models\Filiere;
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

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                if ($index == 0) continue; // Skip header

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

                $etudiant = Etudiant::updateOrCreate(
                    ['code_etudiant' => $row[0]],
                    [
                        'nom' => $row[1],
                        'prenom' => $row[2],
                        'cin' => $cin,
                        'cne' => $row[4],
                        'date_naissance' => $dateNaissance ? $dateNaissance->format('Y-m-d') : null, // Format to Y-m-d if valid
                    ]
                );

                // Ensure filiere exists and get its ID
                $filiere = Filiere::firstOrCreate(
                    ['code_etape' => $row[9]]
                );

                $module = Module::updateOrCreate(
                    ['code_elp' => $row[6]],
                    [
                        'lib_elp' => $row[7],
                        'version_etape' => $row[8],
                        'code_etape' => $row[9],
                        'id_department' => 1, // Replace 1 with the default department ID
                        'id_filiere' => $filiere->id, // Assign the filiere ID
                    ]
                );

                Inscription::updateOrCreate(
                    [
                        'id_etudiant' => $etudiant->id,
                        'id_module' => $module->id,
                    ]
                );
            }

            DB::commit();

            return back()->with('success', 'Importation terminée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    return back()->withErrors(['error' => 'Le fichier n\'a pas pu être téléchargé.']);
}
}
