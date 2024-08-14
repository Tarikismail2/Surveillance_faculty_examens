<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id';

    protected $fillable = ['date', 'heure_debut', 'heure_fin', 'id_module', 'id_salle', 'id_enseignant', 'id_session', 'id_etudiant'];

    // Relation pour la salle principale
    public function sallePrincipale()
    {
        return $this->belongsTo(Salle::class, 'id_salle');
    }

    // Relation pour les salles supplémentaires
    public function sallesSupplementaires()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle')
                    ->withTimestamps();
    }

    public function additionalSalles()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle');
    }

    public function salles()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'examen_salle_enseignant', 'id_examen', 'id_enseignant')
                    ->withPivot('id_salle')
                    ->withTimestamps();
    }

    public function surveillants()
    {
        return $this->hasMany(ExamenSalleEnseignant::class, 'id_examen');
    }

    public function hasAssignedInvigilators()
    {
        return $this->surveillants()->exists();
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'version_etape');
    }

    public function session()
    {
        return $this->belongsTo(SessionExam::class, 'id_session'); 
    }

    public function responsable()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    public function teacher()
    {
        return $this->belongsTo(Enseignant::class);
    }
}



