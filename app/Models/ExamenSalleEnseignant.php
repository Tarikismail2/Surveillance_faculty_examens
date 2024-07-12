<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamenSalleEnseignant extends Model
{
    use HasFactory;

    protected $table = 'examen_salle_enseignant';

    protected $fillable = ['id_examen', 'id_salle', 'id_enseignant'];
    
    public function salle()
    {
        return $this->belongsTo(Salle::class, 'id_salle', 'id');
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant', 'id');
    }

    public function surveillant()
    {
        return $this->belongsTo(Enseignant::class, 'id_enseignant', 'id'); 
    }

    public function examen() {
        return $this->belongsTo(Examen::class, 'id_examen');
    }

}
