<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'id_department'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department');
    }
}
