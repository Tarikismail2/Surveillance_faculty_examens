<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContrainteSalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_salle',
        'id_session',
        'date',
        'heure_debut',
        'heure_fin',
        'validee',
    ];

    // Relation avec le modèle Salle
    public function salle()
    {
        return $this->belongsTo(Salle::class, 'id_salle');
    }

    // Relation avec le modèle SessionExam
    public function sessionExam()
    {
        return $this->belongsTo(SessionExam::class, 'id_session');
    }
}
