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
use App\Http\Controllers\ContrainteEnseignantController;
use App\Http\Controllers\SurveillantsReservistesController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\ContrainteSalleController;
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

    // Affectation automatique des surveillants
    Route::post('/examens/assign-invigilators-to-all', [ExamenController::class, 'assignInvigilatorsToAll'])->name('examens.assignInvigilatorsToAll');

    Route::get('/surveillants-reservistes', [SurveillantsReservistesController::class, 'index'])->name('surveillants_reservistes.index');
    Route::get('/surveillants-reservistes/download', [SurveillantsReservistesController::class, 'downloadPDF'])->name('surveillants_reservistes.download');


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
    Route::delete('/etudiants/delete-modules', [EtudiantController::class, 'deleteModules'])->name('etudiants.deleteModules');
    // Route::get('/test-pdf',  [EtudiantController::class, 'generatePdf'])->name('test.pdf');
    Route::get('/test-pdf/{sessionId}', [EtudiantController::class, 'generatePdf'])->name('test.pdf');


    //route upload
    Route::get('/import/{sessionId}', [ImportController::class, 'showForm'])->name('import.form');
    Route::post('/import/{sessionId}', [ImportController::class, 'import'])->name('import.process');
    Route::post('/import/cancel', [ImportController::class, 'cancelImport'])->name('import.cancel');



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
    Route::get('/planification/select_student', [ExportController::class, 'selectStudent'])->name('selectStudent');
    Route::get('/planification/display_student_schedule', [ExportController::class, 'displayStudentSchedule'])->name('displayStudentSchedule');
    Route::get('/planification/download_student_schedule_pdf', [ExportController::class, 'downloadStudentSchedulePDF'])->name('downloadStudentSchedulePDF');



    // Route::resource('contrainte_enseignants')->names('contrainte_enseignants');
    Route::get('/contraintes', [ContrainteEnseignantController::class, 'index'])->name('contrainte_enseignants.index');
    Route::get('/contrainte_enseignants/create', [ContrainteEnseignantController::class, 'create'])->name('contrainte_enseignants.create');
    Route::post('/contrainte_enseignants', [ContrainteEnseignantController::class, 'store'])->name('contrainte_enseignants.store');
    Route::patch('contraintes/{id}/valider', [ContrainteEnseignantController::class, 'valider'])->name('contraintes.valider');
    Route::delete('contraintes/{id}/annuler', [ContrainteEnseignantController::class, 'annuler'])->name('contraintes.annuler');



    // Route::resource('contrainte_salles', ContrainteSalleController::class);
    Route::get('/contrainte_salles', [ContrainteSalleController::class, 'index'])->name('contrainte_salles.index');
    Route::get('/contrainte_salles/create', [ContrainteSalleController::class, 'create'])->name('contrainte_salles.create');
    Route::post('/contrainte_salles', [ContrainteSalleController::class, 'store'])->name('contrainte_salles.store');
    Route::patch('contraintes/{id}/valider', [ContrainteSalleController::class, 'valider'])->name('contrainte_salles.valider');
    Route::delete('contraintes/{id}/annuler', [ContrainteSalleController::class, 'annuler'])->name('contrainte_salles.annuler');

    //Enseignants emploi selon department
    Route::get('/select-department', [TimetableController::class, 'selectDepartment'])->name('selectDepartment');
    Route::get('/displayScheduleByDepartment/{id_department}/{id_session}', [TimetableController::class, 'displayScheduleByDepartment'])->name('displayScheduleByDepartment');
    Route::get('/download-schedule/{id_department}/{id_session}', [TimetableController::class, 'downloadSchedule'])->name('download-schedule');
});

require __DIR__ . '/auth.php';
