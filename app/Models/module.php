<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'id_department'
    ];

    public function examens()
    {
        return $this->hasMany(Examen::class, 'id_module');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department');
    }
}
