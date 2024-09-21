<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_elp',
        'lib_elp',
        'code_etape',
        'id_session',
        'id_enseignant',
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'id_module');
    }

    public function examens()
    {
        return $this->belongsToMany(Examen::class, 'exam_module', 'module_id', 'exam_id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'code_etape', 'code_etape');
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'inscriptions', 'id_module', 'id_etudiant');
    }

    public function session()
    {
        return $this->belongsTo(SessionExam::class);
    }
    // Add this relationship for the responsible teacher
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }
    // public function modules()
    // {
    //     return $this->hasMany(Module::class, 'code_etape');
    // }
    public function filiereGp()
{
    return $this->belongsTo(FiliereGp::class, 'id_module', 'id');
}
public function getVersionEtape()
{
    $filiereGp = FiliereGp::where('id_module', $this->id)->first();

    if ($filiereGp) {
        return $filiereGp->version_etape;
    }

    $filiere = Filiere::where('code_etape', $this->code_etape)->first();

    if ($filiere) {
        return $filiere->version_etape;
    }

    return 'Version etape not found';
}
public function getCodeEtape()
    {
        $filiereGp = FiliereGp::where('id_module', $this->id)->first();

        if ($filiereGp) {
            return $filiereGp->code_etape;
        }

        $filiere = Filiere::where('code_etape', $this->code_etape)->first();

        if ($filiere) {
            return $filiere->code_etape;
        }

        return 'Code etape not found';
    }


    public function getEtudiantsByExamen($sessionId, $codeEtape)
    {
        // Check if the code_etape exists in FiliereGp
        $filiereGp = FiliereGp::where('code_etape', $codeEtape);
          

            if ($filiereGp) {
                $moduleIds = FiliereGp::where('code_etape', $codeEtape)
                                    ->pluck('id_module');

                 // Get students related to these module IDs
                 $etudiants = Etudiant::whereIn('id', function ($query) use ($moduleIds) {
                     $query->select('id_etudiant')
                           ->from('inscriptions')
                           ->whereIn('id_module', $moduleIds);
                 })->orderBy('nom')->get();
             } else {
                 // If not found in FiliereGp, check in Filiere
                 $modules = Module::where('code_etape', $codeEtape)->get();
         
                 if ($modules->isEmpty()) {
                     return collect(); // Return an empty collection if no modules are found
                 }
         
                 // Get students related to these module IDs
                 $etudiants = Etudiant::whereIn('id', function ($query) use ($modules) {
                     $query->select('id_etudiant')
                           ->from('inscriptions')
                           ->whereIn('id_module', $modules->pluck('id'));
                 })->orderBy('nom')->get();
             }
         
             return $etudiants;
    }
    
    
}
