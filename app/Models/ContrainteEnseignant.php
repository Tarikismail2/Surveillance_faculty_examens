<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContrainteEnseignant extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_enseignant',
        'date',
        'heure_debut',
        'heure_fin',
        'validee'
    ];

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }
}