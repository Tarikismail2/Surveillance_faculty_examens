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
        'id_department',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'id_module');
    }

    public function examens()
    {
        return $this->hasMany(Examen::class, 'id_module');
    }
}
