<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_etudiant',
        'nom',
        'prenom',
        'cin',
        'cne',
        'date_naissance',
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }
}
