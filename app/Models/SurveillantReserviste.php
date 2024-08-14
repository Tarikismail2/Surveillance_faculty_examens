<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveillantReserviste extends Model
{
    protected $fillable = [
        'id_enseignant',
        'date',
        'demi_journee',
        'affecte',
    ];
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant'); // Specify the foreign key
    }
}