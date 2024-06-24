<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom'
    ];

    public function examens()
    {
        return $this->hasMany(Examen::class, 'id_personne');
    }
}
