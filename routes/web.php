<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionExamController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ModuleController;
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

    Route::get('/examens', [ExamenController::class, 'index'])->name('examens.index');
    Route::get('/examens/create', [ExamenController::class, 'create'])->name('examens.create');
    Route::post('/examens', [ExamenController::class, 'store'])->name('examens.store');
    Route::get('/examens/{examen}/edit', [ExamenController::class, 'edit'])->name('examens.edit');
    Route::put('/examens/{examen}', [ExamenController::class, 'update'])->name('examens.update');
    Route::delete('/examens/{examen}', [ExamenController::class, 'destroy'])->name('examens.destroy');
    Route::get('/examens/pdf/{idEnseignant}', [ExamenController::class, 'generatePdfForEnseignant'])
        ->name('examens_pdf');

    Route::resource('departments', DepartmentController::class);
    Route::get('departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');

    Route::resource('enseignants', EnseignantController::class);

    Route::resource('salles', SalleController::class);

    Route::get('/import', [ImportController::class, 'showForm'])->name('import.form');
    Route::post('/import', [ImportController::class, 'import'])->name('import.process');
    // Route::post('/upload', [ImportController::class, 'process'])->name('upload.process');

    Route::get('/examens/getModulesByFiliere/{id_filiere}', [ExamenController::class, 'getModulesByFiliere']);

});

require __DIR__ . '/auth.php';
