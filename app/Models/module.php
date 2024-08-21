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
        'version_etape',
        'code_etape',
        'id_session',
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'id_module');
    }

    public function examens()
    {
        return $this->hasMany(Examen::class, 'id_module');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'version_etape', 'version_etape');
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'inscriptions', 'id_module', 'id_etudiant');
    }

    public function session()
    {
        return $this->belongsTo(SessionExam::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'id_filiere');
    }
}
