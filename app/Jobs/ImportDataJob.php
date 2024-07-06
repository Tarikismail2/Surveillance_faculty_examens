<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Inscription;

class ImportDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function handle()
    {
        foreach ($this->rows as $index => $row) {
            if ($index == 0) continue; // Skip header

            $etudiant = Etudiant::updateOrCreate(
                ['code_etudiant' => $row[0]],
                [
                    'nom' => $row[1],
                    'prenom' => $row[2],
                    'cin' => $row[3],
                    'cne' => $row[4],
                    'date_naissance' => Date::excelToDateTimeObject($row[5]),
                ]
            );

            $module = Module::updateOrCreate(
                ['code_elp' => $row[6]],
                [
                    'lib_elp' => $row[7],
                    'version_etape' => $row[8],
                    'code_etape' => $row[9],
                    'id_department' => 1, // Default value or dynamically set value
                ]
            );

            Inscription::updateOrCreate(
                [
                    'id_etudiant' => $etudiant->id,
                    'id_module' => $module->id,
                ]
            );
        }
    }
}
