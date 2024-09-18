<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    // Attributs de la table inscriptions
    protected $fillable = [
        'id_etudiant',
        'id_module',
        'id_session',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'id_etudiant');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

    public function session()
    {
        return $this->belongsTo(SessionExam::class);
    }
}