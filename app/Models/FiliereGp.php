<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiliereGp extends Model
{
    use HasFactory;
    protected $table = 'filiere_gp'; // Update this line
    protected $fillable = ['version_etape', 'code_etape', 'id_module', 'id_session'];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'code_etape');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'id_module');
    }

    public function session()
    {
        return $this->belongsTo(SessionExam::class, 'id_session');
    }
}