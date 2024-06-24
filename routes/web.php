<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionExamController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EnseignantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('sessions', SessionExamController::class);
    Route::resource('examens', ExamenController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('enseignants', EnseignantController::class);
});

require __DIR__.'/auth.php';
