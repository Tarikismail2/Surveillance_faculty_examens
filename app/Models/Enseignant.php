<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'id_department'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department');
    }

    public function salles()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle_enseignant')
            ->withPivot('id_examen')
            ->withTimestamps();
    }

    public function examens()
    {
        return $this->belongsToMany(Examen::class, 'examen_salle_enseignant', 'id_enseignant', 'id_examen')
            ->withPivot('id_salle')
            ->withTimestamps();
    }

    public function reservations()
    {
        return $this->hasMany(SurveillantReserviste::class, 'id_enseignant');
    }
}
