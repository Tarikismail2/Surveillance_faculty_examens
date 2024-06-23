<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionExamController;
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
    // Route::get('/sessions', [SessionExamController::class, 'index'])->name('sessions.index');
});

require __DIR__.'/auth.php';
