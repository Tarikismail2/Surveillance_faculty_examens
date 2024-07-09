<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'heure_debut', 'heure_fin', 'id_module', 'id_salle', 'id_enseignant', 'id_session'];


    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

    // public function salle()
    // {
    //     return $this->belongsTo(Salle::class, 'id_salle');
    // }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    public function salles()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle');
    }
}
