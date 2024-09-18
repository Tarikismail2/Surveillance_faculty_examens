<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamModule extends Model
{
    use HasFactory;
    protected $table = 'exam_module';

    // Fillable fields in the table
    protected $fillable = [
        'exam_id',
        'module_id',
    ];
}
