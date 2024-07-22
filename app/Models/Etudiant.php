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
    protected $casts = [
        'date_naissance' => 'datetime', // Ensure date_naissance is cast to DateTime
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'id_etudiant');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'inscriptions', 'id_etudiant', 'id_module');
    }

    public function getFullNameAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function examens()
    {
        return $this->hasManyThrough(Examen::class, Module::class, 'id', 'id_module', 'id', 'id');
    }
}
