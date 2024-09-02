<?php

namespace App\Imports;
namespace App\Imports;

use App\Models\Filiere;
use App\Models\Module;
use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DataImport implements ToModel, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        // Traitement de chaque ligne
        // Note : Ajustez les index de ligne en fonction de votre fichier
        $dateNaissance = isset($row[5]) ? \DateTime::createFromFormat('m/d/Y', $row[5]) : null;
    
        // Vérifiez si la date a été correctement analysée
        $formattedDateNaissance = $dateNaissance ? $dateNaissance->format('Y-m-d') : null;
    
        // Insérer ou mettre à jour Filiere
        $filiere = Filiere::firstOrCreate(['code_etape' => $row[9]], [
            'version_etape' => $row[8],
            'id_session' => $row[10]
        ]);
    
        // Insérer ou mettre à jour Module
        $module = Module::updateOrCreate(['code_elp' => $row[6]], [
            'lib_elp' => $row[7],
            'code_etape' => $row[9],
            'id_session' => $row[10]
        ]);
    
        // Insérer ou mettre à jour Etudiant
        Etudiant::updateOrCreate(['code_etudiant' => $row[0]], [
            'nom' => $row[1],
            'prenom' => $row[2],
            'cin' => $row[3],
            'cne' => $row[4],
            'date_naissance' => $formattedDateNaissance,
            'id_session' => $row[10]
        ]);
    
        return null;
    }

    public function batchSize(): int
    {
        return 1000; // Batch size for insertions
    }

    public function chunkSize(): int
    {
        return 1000; // Chunk size for reading rows
    }
}
