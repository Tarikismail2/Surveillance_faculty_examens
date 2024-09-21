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

    // Relation for the main room
    public function sallePrincipale()
    {
        return $this->belongsTo(Salle::class, 'id_salle');
    }

    // Relation for additional rooms
    public function sallesSupplementaires()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle')
            ->withTimestamps();
    }

    // Relation for all rooms (including main and additional rooms)
    public function salles()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle')
            ->withTimestamps();
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'exam_module', 'exam_id', 'module_id');
    }
    // Relation for the exam's main teacher
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant');
    }

    // Relation for multiple teachers (if there are many teachers per exam)
    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'examen_salle_enseignant', 'id_examen', 'id_enseignant');
    }

    // Relation for the session
    public function session()
    {
        return $this->belongsTo(SessionExam::class, 'id_session');
    }

    // Relation for the filière
    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'code_etape');
    }

    // Relation for teacher constraints
    public function contraintes()
    {
        return $this->hasMany(ContrainteEnseignant::class, 'id_enseignant', 'id_enseignant');
    }

    // Relation for associated invigilators
    public function surveillants()
    {
        return $this->hasMany(ExamenSalleEnseignant::class, 'id_examen');
    }

    // Check if invigilators are assigned
    public function hasAssignedInvigilators()
    {
        return $this->surveillants()->exists();
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

    public function salle()
    {
        return $this->belongsToMany(Salle::class, 'examen_salle', 'id_examen', 'id_salle'); // Assuming many-to-many relation
    }
    
}
