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
        'id_session'
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
    return $this->hasManyThrough(Examen::class, Inscription::class, 'id_etudiant', 'id_module', 'id', 'id_module');
}

    public function sessions()
    {
        return $this->belongsToMany(SessionExam::class);
    }

    public function session()
    {
        return $this->belongsTo(SessionExam::class, 'id_session');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'code_etape');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

}
