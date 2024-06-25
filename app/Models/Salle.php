<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacite'
    ];

    public function examens()
    {
        return $this->hasMany(Examen::class, 'id_salle');
    }
}

