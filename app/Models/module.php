<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_elp',
        'lib_elp',
        'version_etape',
        'code_etape',
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'id_module');
    }

    public function examens()
    {
        return $this->hasMany(Examen::class, 'id_module');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'code_etape');
    }

}
