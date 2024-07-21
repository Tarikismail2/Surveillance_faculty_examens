<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionExamController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PlanificationController;
use App\Http\Controllers\EtudiantController;
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

    //route sessions
    Route::resource('sessions', SessionExamController::class);

    //route examens
    // Route::resource('examens', ExamenController::class);
    Route::get('/examens/{sessionId}', [ExamenController::class, 'index'])->name('examens.index');
    Route::get('/examens/create/{id}', [ExamenController::class, 'create'])->name('examens.create');
    Route::post('/examens', [ExamenController::class, 'store'])->name('examens.store');
    Route::get('/examens/edit/{id}', [ExamenController::class, 'edit'])->name('examens.editExamen');
    Route::put('/examens/{examen}', [ExamenController::class, 'update'])->name('examens.update');
    Route::delete('/examens/{examen}', [ExamenController::class, 'destroy'])->name('examens.destroy');
    Route::get('/examens/getModulesByFiliere/{id_filiere}', [ExamenController::class, 'getModulesByFiliere']);
    Route::post('/examens/getRooms', [ExamenController::class, 'getRooms'])->name('examens.getRooms');
    // Route::get('/examens/getEnseignantsByDepartment/{departmentId}', [ExamenController::class, 'getEnseignantsByDepartment'])->name('examens.getEnseignantsByDepartment');

    //Affectation des surveillants sur les locaux   
    Route::get('/examens/form/{examen}', [ExamenController::class, 'showForm'])->name('examens.showForm');
    Route::get('/examens/{id}/show-invigilators', [ExamenController::class, 'showAssignInvigilatorsForm'])->name('examens.showAssignInvigilatorsForm');
    Route::post('/examens/{id}/assign-invigilators', [ExamenController::class, 'assignInvigilators'])->name('examens.assignInvigilators');
    Route::get('/examens/{examen}/edit-invigilators', [ExamenController::class, 'editInvigilators'])->name('examens.editInvigilators');
    Route::post('/examens/{examen}/update-invigilators', [ExamenController::class, 'updateInvigilators'])->name('examens.updateInvigilators');


    //route departments
    Route::resource('departments', DepartmentController::class);
    Route::get('departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');

    //route enseignants
    Route::resource('enseignants', EnseignantController::class);

    //route salles
    Route::resource('salles', SalleController::class);



    // route etudiants 
    Route::get('/etudiants', [EtudiantController::class, 'index'])->name('etudiants.index');
    Route::get('/etudiants/create', [EtudiantController::class, 'create'])->name('etudiants.create');
    Route::post('/etudiants', [EtudiantController::class, 'store'])->name('etudiants.store');
    Route::get('/etudiants/{etudiant}/edit', [EtudiantController::class, 'edit'])->name('etudiants.edit');
    Route::put('/etudiants/{etudiant}', [EtudiantController::class, 'update'])->name('etudiants.update');
    Route::get('/etudiants/{etudiant}', [EtudiantController::class, 'show'])->name('etudiants.show');
    Route::delete('/etudiants/{etudiant}', [EtudiantController::class, 'destroy'])->name('etudiants.destroy');


    //route upload
    Route::get('/import', [ImportController::class, 'showForm'])->name('import.form');
    Route::post('/import', [ImportController::class, 'import'])->name('import.process');

    //Affichage globale de la planification des examens
    Route::get('/api/examens/{sessionId}/schedule', [PlanificationController::class, 'getExamsBySession']);
    Route::get('/examens/schedule', [PlanificationController::class, 'showExams'])->name('examens.schedule');
    Route::get('/global', [PlanificationController::class, 'showGlobalPlan'])->name('examens.global');


    //download du planification
    Route::get('/global/pdf', 'App\Http\Controllers\PlanificationController@downloadGlobalSchedulePDF')->name('examens.global.pdf');
    Route::get('/examens/global/pdf/{id_session}', [PlanificationController::class, 'downloadSurveillancePDF'])->name('examens_global.pdf');

    //prof planification
    Route::get('/select-enseignant', [ExportController::class, 'selectEnseignant'])->name('selectEnseignant');
    Route::get('/display-schedule', [ExportController::class, 'displaySchedule'])->name('displaySchedule');
    Route::post('/download-surveillance-pdf', [ExportController::class, 'downloadSurveillancePDF'])->name('downloadSurveillancePDF');

    //Studeent planification
    Route::get('/select-student', [ExportController::class, 'selectStudent'])->name('selectStudent');
    Route::get('/display-student-schedule', [ExportController::class, 'displayStudentSchedule'])->name('displayStudentSchedule');
    Route::post('/download-student-schedule-pdf', [ExportController::class, 'downloadStudentSchedulePDF'])->name('downloadStudentSchedulePDF');
});

require __DIR__ . '/auth.php';
