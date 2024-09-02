<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $primaryKey = 'code_etape';
    protected $fillable = ['code_etape','version_etape', 'id_session'];

    protected $casts = [
        'code_etape' => 'string', 
    ];

    public function modules()
    {
        return $this->hasMany(Module::class, 'code_etape', 'code_etape');
    }

    public function session()
    {
        return $this->belongsTo(SessionExam::class);
    }
    
    
}
