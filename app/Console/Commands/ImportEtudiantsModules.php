<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Inscription;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportEtudiantsModules extends Command
{
    protected $signature = 'import:etudiants-modules {file}';
    protected $description = 'Import students and modules from an Excel file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = $this->argument('file');
        // $spreadsheet = IOFactory::load($file);
        // $sheet = $spreadsheet->getSheetByName('All');
        // $rows = $sheet->toArray();

        // foreach ($rows as $index => $row) {
        //     if ($index == 0) continue; // Skip header

        //     $etudiant = Etudiant::updateOrCreate(
        //         ['code_etudiant' => $row[0]],
        //         [
        //             'nom' => $row[1],
        //             'prenom' => $row[2],
        //             'cin' => $row[3],
        //             'cne' => $row[4],
        //             // 'date_naissance' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]),
        //         ]
        //     );

        //     $module = Module::updateOrCreate(
        //         ['code_elp' => $row[6]],
        //         [
        //             'lib_elp' => $row[7],
        //             'version_etape' => $row[8],
        //             'code_etape' => $row[9],
        //         ]
        //     );

        //     Inscription::updateOrCreate(
        //         [
        //             'etudiant_id' => $etudiant->id,
        //             'module_id' => $module->id,
        //         ]
        //     );
        // }

        // $this->info('Importation terminée avec succès.');
    }
}
