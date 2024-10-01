<?php

namespace App\Imports;

use App\Models\Etudiant;
use App\Models\Inscription;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Row;

class StudentsImport implements OnEachRow, WithStartRow, WithBatchInserts
{
    protected $sessionId; // Session ID property
    protected $moduleId; // Module ID property
    protected $filiereId; // Filiere ID property

    public function __construct($sessionId, $moduleId, $filiereId)
    {
        $this->sessionId = $sessionId;
        $this->moduleId = $moduleId;
        $this->filiereId = $filiereId;
    }

    /**
     * Process each row and handle data insertion.
     *
     * @param Row $row
     * @return void
     */
    public function onRow(Row $row)
    {
        // Convert the row data to an array
        $rowData = $row->toArray();
        
        // Log the row data for debugging
        Log::info('Row Data:', $rowData);
    
        // Extract data using specific indices
        $codeEtudiant = trim($rowData[0]); // First column: code_etudiant
        $nom = trim($rowData[1]); // Second column: nom
        $prenom = trim($rowData[2]); // Third column: prenom
        $dateNaissance = isset($rowData[3]) ? \Carbon\Carbon::createFromFormat('d/m/Y', trim($rowData[3])) : null; // Fourth column: date_naissance
    
        // Log to check if data is retrieved correctly
        Log::info('Student Data:', [
            'code_etudiant' => $codeEtudiant,
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naissance' => $dateNaissance
        ]);
    
        // Check for empty code_etudiant
        if (empty($codeEtudiant)) {
            Log::warning('Empty code_etudiant for row:', $rowData);
            return; // Skip this row
        }
    
        // Insert or update the student record
        $etudiant = Etudiant::updateOrCreate(
            ['code_etudiant' => $codeEtudiant],
            [
                'nom' => $nom,
                'prenom' => $prenom,
                'date_naissance' => $dateNaissance,
                'id_session' => $this->sessionId,
            ]
        );
    
        // Prepare the inscription data
        $inscriptionData = [
            'id_etudiant' => $etudiant->id,
            'id_module' => $this->moduleId,
            'id_session' => $this->sessionId,
        ];
    
        // Insert or update the inscription record
        Inscription::updateOrCreate(
            [
                'id_etudiant' => $inscriptionData['id_etudiant'],
                'id_module' => $inscriptionData['id_module'],
                'id_session' => $inscriptionData['id_session'],
            ],
            []
        );
    }
    
    /**
     * Specify the starting row for the import.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 35; // Assuming students start from row 36 in your file.
    }

    /**
     * Specify the batch size for batch inserts.
     *
     * @return int
     */
    public function batchSize(): int
    {
        return 100; // Adjust batch size as needed
    }
}
