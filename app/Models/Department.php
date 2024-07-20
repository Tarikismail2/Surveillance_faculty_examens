<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    protected $primaryKey = 'id_department';

    protected $fillable = ['name'];

    public function enseignants()
    {
        return $this->hasMany(Enseignant::class, 'id_department');
    }
}
