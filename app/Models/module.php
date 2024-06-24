<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'departement_id'
    ];

    public function examens()
    {
        return $this->hasMany(Examen::class, 'id_module');
    }
}
