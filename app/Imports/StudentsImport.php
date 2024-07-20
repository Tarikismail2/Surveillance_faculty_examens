<?php

namespace App\Imports;

use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Inscription;
use App\Models\Filiere;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class StudentsImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    public function model(array $row)
    {
        Log::info('Processing row: ', $row);

        $etudiant = Etudiant::updateOrCreate(
            ['code_etudiant' => $row['code_etudiant']],
            [
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'cin' => $row['cin'] ?? null,
                'cne' => $row['cne'],
                'date_naissance' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_naissance'])->format('Y-m-d'),
            ]
        );

        $filiere = Filiere::firstOrCreate(
            ['version_etape' => $row['version_etape']],
            ['code_etape' => $row['code_etape']]
        );

        $module = Module::updateOrCreate(
            [
                'code_elp' => $row['code_elp'],
                'lib_elp' => $row['lib_elp'],
                'version_etape' => $row['version_etape'],
                'code_etape' => $row['code_etape'],
            ]
        );

        Inscription::updateOrCreate(
            [
                'id_etudiant' => $etudiant->id,
                'id_module' => $module->id,
            ]
        );

        return null;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
