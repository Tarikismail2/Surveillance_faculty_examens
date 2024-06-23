<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionExam extends Model
{

    use HasFactory;

    protected $primaryKey = 'id_session';

    protected $fillable = [
        'type',
        'date_debut',
        'date_fin',
        'id_session',
    ];

}

