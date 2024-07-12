<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'heure_debut', 'heure_fin', 'id_module', 'id_salle', 'id_enseignant', 'id_session'];

    // Define relationships
    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }
    
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    public function salles()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle')
                    ->withTimestamps(); // Assuming you have timestamps in your pivot table
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'examen_salle_enseignant', 'id_examen', 'id_enseignant')
                    ->withPivot('id_salle')
                    ->withTimestamps(); // Assuming you have timestamps in your pivot table
    }

    public function surveillants()
    {
        return $this->hasMany(ExamenSalleEnseignant::class, 'id_examen', 'id_enseignant');
    }

    public function hasAssignedInvigilators()
    {
        return $this->surveillants()->exists();
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'code_etape');
    }

    public function session()
    {
        return $this->belongsTo(SessionExam::class, 'id_session'); 
    }

    public function additionalSalles()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle');
    }
}
