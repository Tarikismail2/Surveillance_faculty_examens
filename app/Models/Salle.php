<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacite'
    ];

    public function examens()
    {
        return $this->belongsToMany(Examen::class, 'examen_salle', 'id_salle', 'id_examen');
    }

    public function enseignants($examenId)
    {
        return $this->belongsToMany(Enseignant::class, 'examen_salle_enseignant', 'id_salle', 'id_enseignant')
                    ->withPivot('id_examen')
                    ->wherePivot('id_examen', $examenId);
    }
    
    public function surveillants()
    {
        return $this->belongsToMany(Enseignant::class, 'examen_salle_enseignant', 'id_salle', 'id_enseignant');
    }

    
    
}

