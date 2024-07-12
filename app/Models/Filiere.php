<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = ['code_etape'];

    public function modules()
    {
        return $this->hasMany(Module::class, 'code_etape', 'code_etape');
    }

    
}
