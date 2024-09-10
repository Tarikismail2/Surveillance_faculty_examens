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

}