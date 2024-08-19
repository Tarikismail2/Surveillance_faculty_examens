<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionExam extends Model
{

    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'date_debut',
        'date_fin',
        'id',
    ];

    public function examens()
    {
        return $this->hasMany(Examen::class);
    }
    
    public function etudiants()
    {
        return $this->hasMany(Etudiant::class, 'id_session');
    }
}

