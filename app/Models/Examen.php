<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
        'date',
        'heure_debut',
        'heure_fin',
        'id_module',
        'id_enseignant',
        'id_session'
    ];

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

    // Relation pour les salles (inclut la salle principale et les salles supplémentaires)
    public function salles()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle')
            ->withTimestamps();
    }

    // Relation pour le module
    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

    // Ensiegnant Examen 
    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'examen_salle_enseignant', 'id_examen', 'id_enseignant');
    }


    // Relation pour l'enseignant
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    // Relation pour la session
    public function session()
    {
        return $this->belongsTo(SessionExam::class, 'id_session');
    }

    // Relation pour la filière
    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'code_etape');
    }

    // Relation pour les contraintes de l'enseignant
    public function contraintes()
    {
        return $this->hasMany(ContrainteEnseignant::class, 'id_enseignant', 'id_enseignant');
    }

    // Relation pour les surveillants associés
    public function surveillants()
    {
        return $this->hasMany(ExamenSalleEnseignant::class, 'id_examen');
    }

    // Vérifie si des surveillants sont assignés
    public function hasAssignedInvigilators()
    {
        return $this->surveillants()->exists();
    }
}
