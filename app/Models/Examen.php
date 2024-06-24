<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'heure_debut',
        'heure_fin',
        'id_module',
        'id_salle',
        'id_personne'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'id_salle');
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'id_personne');
    }
}
