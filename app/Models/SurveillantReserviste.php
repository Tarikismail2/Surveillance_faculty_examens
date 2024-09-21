<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveillantReserviste extends Model
{
    protected $fillable = [
        'id_enseignant',
        'id_session',
        'date',
        'demi_journee',
        'affecte',
    ];

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant'); 
    }

     // Relation for the session
     public function session()
     {
         return $this->belongsTo(SessionExam::class, 'id_session');
     }
}