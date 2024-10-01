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
use App\Http\Controllers\FiliereController;
use App\Mail\NodeMailer;
use App\Models\Examen;
use App\Models\Module;
use App\Models\SessionExam;
use Illuminate\Support\Facades\Mail;
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
});

//midleware Etudiant
Route::middleware('role:etudiant')->group(function () {

    //Studeent planification
    Route::get('/planification/select_student', [ExportController::class, 'selectStudent'])->name('selectStudent');
    Route::get('/planification/display_student_schedule', [ExportController::class, 'displayStudentSchedule'])->name('displayStudentSchedule');
    Route::get('/planification/download_student_schedule_pdf', [ExportController::class, 'downloadStudentSchedulePDF'])->name('downloadStudentSchedulePDF');
});

//midleware Enseignant
Route::middleware('role:enseignant')->group(function () {

    //prof planification
    Route::get('/select-enseignant', [ExportController::class, 'selectEnseignant'])->name('selectEnseignant');
    Route::get('/display-schedule', [ExportController::class, 'displaySchedule'])->name('displaySchedule');
    Route::post('/download-surveillance-pdf', [ExportController::class, 'downloadSurveillancePDF'])->name('downloadSurveillancePDF');

    // Route::resource('contrainte_enseignants')->names('contrainte_enseignants');
    Route::get('/contraintes', [ContrainteEnseignantController::class, 'index'])->name('contrainte_enseignants.index');
    Route::get('/contrainte_enseignants/create', [ContrainteEnseignantController::class, 'create'])->name('contrainte_enseignants.create');
    Route::post('/contrainte_enseignants', [ContrainteEnseignantController::class, 'store'])->name('contrainte_enseignants.store');
});

//midleware Admin
Route::middleware(['role:admin'])->group(function () {

    //route sessions
    Route::resource('sessions', SessionExamController::class);

    //route examens
    Route::get('/examens/{sessionId}', [ExamenController::class, 'index'])->name('examens.index');
    Route::get('/examens/create/{id}', [ExamenController::class, 'create'])->name('examens.create');
    Route::post('/examens', [ExamenController::class, 'store'])->name('examens.store');
    Route::get('/examens/edit/{id}', [ExamenController::class, 'edit'])->name('examens.editExamen');
    Route::put('/examens/{examen}', [ExamenController::class, 'update'])->name('examens.update');
    Route::delete('/examens/{examen}', [ExamenController::class, 'destroy'])->name('examens.destroy');
    Route::get('/examens/getModulesByFiliere/{filiereId}', [ExamenController::class, 'getModulesByFiliere']);
    Route::post('/examens/getRooms', [ExamenController::class, 'getRooms'])->name('examens.getRooms');

    //Affectation des surveillants sur les locaux   
    Route::get('/examens/form/{examen}', [ExamenController::class, 'showForm'])->name('examens.showForm');
    Route::get('/examens/{id}/show-invigilators', [ExamenController::class, 'showAssignInvigilatorsForm'])->name('examens.showAssignInvigilatorsForm');
    Route::post('/examens/{id}/assign-invigilators', [ExamenController::class, 'assignInvigilators'])->name('examens.assignInvigilators');
    Route::get('/examens/{examen}/edit-invigilators', [ExamenController::class, 'editInvigilators'])->name('examens.editInvigilators');
    Route::post('/examens/{examen}/update-invigilators', [ExamenController::class, 'updateInvigilators'])->name('examens.updateInvigilators');

    // Affectation automatique des surveillants
    Route::post('/examens/assign-invigilators-to-all', [ExamenController::class, 'assignInvigilatorsToAll'])->name('examens.assignInvigilatorsToAll');

    //surveiallance reservistes
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
    // Route::get('/filiere/module/Ajout_etudiant/etudiant_import', [EtudiantController::class, 'Etudiants_import'])->name('etudiant.import');
    Route::get('/filiere/module/{id_module}/Ajout_etudiant/etudiant_import', [EtudiantController::class, 'Etudiants_import'])->name('etudiant.import');
    Route::post('/import/{id_session}/{id_module}/{code_etape}', [EtudiantController::class, 'import'])->name('import.store');

    // i chnaged this route 
    Route::get('/etudiants/create/{id_module}', [EtudiantController::class, 'create'])->name('etudiants.create');
    // i changed this one 
    Route::post('/etudiants/store/{id_module}', [EtudiantController::class, 'store'])->name('etudiants.store'); 

    Route::get('/etudiants/{etudiant}/edit', [EtudiantController::class, 'edit'])->name('etudiants.edit');
    Route::put('/etudiants/{etudiant}', [EtudiantController::class, 'update'])->name('etudiants.update');
    Route::get('/etudiants/{etudiant}', [EtudiantController::class, 'show'])->name('etudiants.show');
    Route::delete('/etudiants/{etudiant}', [EtudiantController::class, 'destroy'])->name('etudiants.destroy');
    Route::delete('/etudiants/delete-modules', [EtudiantController::class, 'deleteModules'])->name('etudiants.deleteModules');
    Route::get('/test-pdf/{sessionId}', [EtudiantController::class, 'generatePdf'])->name('test.pdf');
    Route::get('/filiere/{filiereId}/modules', [EtudiantController::class, 'getModulesByFiliere'])->name('getModulesByFiliere');

    //route upload
    Route::get('/import/{sessionId}', [ImportController::class, 'showForm'])->name('import.form');
    Route::post('/import/{sessionId}', [ImportController::class, 'import'])->name('import.process');
    Route::post('/import/cancel', [ImportController::class, 'cancelImport'])->name('import.cancel');

    //Affichage globale de la planification des examens
    Route::get('/api/examens/{sessionId}/schedule', [PlanificationController::class, 'getExamsBySession']);
    Route::get('/examens/schedule', [PlanificationController::class, 'showExams'])->name('examens.schedule');
    Route::get('/global', [PlanificationController::class, 'showGlobalPlan'])->name('examens.global');

    Route::get('/generate-pdf/{sessionId}', [EnseignantController::class, 'generatePDFEnseignant'])->name('examens.global.pdf');

    Route::get('/examens/{sessionId}/{codeEtape}/download-pdf', [EtudiantController::class, 'downloadPDF'])
        ->name('examens.downloadPDF');

    //validation des contraintes enseignants
    Route::get('/contraintes_admin', [ContrainteEnseignantController::class, 'index_admin'])->name('contrainte_enseignants.index_admin');
    Route::patch('contraintes_admin/{id}/valider', [ContrainteEnseignantController::class, 'valider'])->name('contraintes.valider');
    Route::delete('contraintes_admin/{id}/annuler', [ContrainteEnseignantController::class, 'annuler'])->name('contraintes.annuler');

    // Route::resource('contrainte_salles', ContrainteSalleController::class);
    Route::get('/contrainte_salles', [ContrainteSalleController::class, 'index'])->name('contrainte_salles.index');
    Route::get('/contrainte_salles/create', [ContrainteSalleController::class, 'create'])->name('contrainte_salles.create');
    Route::post('/contrainte_salles', [ContrainteSalleController::class, 'store'])->name('contrainte_salles.store');
    Route::patch('contraintes/{id}/valider', [ContrainteSalleController::class, 'valider'])->name('contrainte_salles.valider');
    Route::delete('contraintes/{id}/annuler', [ContrainteSalleController::class, 'annuler'])->name('contrainte_salles.annuler');

    //Enseignants emploi selon department
    Route::get('/select-department', [TimetableController::class, 'selectDepartment'])->name('selectDepartment');
    Route::get('/displayScheduleByDepartment', [TimetableController::class, 'displayScheduleByDepartment'])->name('displayScheduleByDepartment');
    Route::get('/download-schedule/{id_department}/{id_session}', [TimetableController::class, 'downloadSchedule'])->name('download-schedule');

    // Define routes for Filiere
    Route::get('/filiere', [FiliereController::class, 'index_session'])->name('filiere.indexSession');

    // i changed this 
    Route::get('/filiere/index/{id_session}', [FiliereController::class, 'index'])->name('filiere.index');
    
    Route::get('/filiere/create', [FiliereController::class, 'create'])->name('filiere.create');
    Route::post('/filiere', [FiliereController::class, 'store'])->name('filiere.store');
    Route::get('/filiere/show/{code_etape}/{id_session}', [FiliereController::class, 'show'])->name('filiere.show');

    Route::get('/filiere/{code_etape}/session/{id_session}/edit', [FiliereController::class, 'edit'])->name('filiere.edit');
    // i changed this
    Route::put('/filiere/{code_etape}/session{id_session}/update', [FiliereController::class, 'update'])->name('filiere.update');

    // i changed this one 
    Route::delete('/filiere/{id_session}/{code_etape}', [FiliereController::class, 'destroy'])->name('filiere.destroy');

    // Define routes for module management
    Route::get('filiere/filiere/modules/create/{code_etape}/{session_id}', [ModuleController::class, 'create'])->name('modules.create');
    // Route::get('filiere/{filiere}/modules/create', [ModuleController::class, 'addModule'])->name('modules.create');
    Route::post('filiere/modules', [ModuleController::class, 'storeModule'])->name('modules.store');

    Route::get('modules/{id_module}/{code_etape}', [ModuleController::class, 'show_module'])->name('modules.show');

    route::get('modules/{lib_elp}/{code_etape}/students', [ModuleController::class, 'students'])->name('modules.students');
    Route::get('filiere/modules/edit/{id_module}', [ModuleController::class, 'editModule'])->name('modules.edit');
  // ichanged this one 
    Route::put('filiere/modules/{id_module}/update', [ModuleController::class, 'updateModule'])->name('modules.update');

    Route::delete('filiere/{code_etape}/modules/{code_elp}/delete', [ModuleController::class, 'destroyModule'])->name('modules.destroy');


    //Route pour afficher emploi des etudiants
    Route::get('/select-filiere', [EtudiantController::class, 'selectFiliere'])->name('selectFiliere');
    Route::get('/etudiants/{sessionId}/{code_etape}/download-pdf', [EtudiantController::class, 'downloadStudentsPDF'])
              ->name('downloadStudentsPDF');
    // Route pour envoyer les emails
    Route::post('/send-emails', [EnseignantController::class, 'sendEmailsByDepartment'])->name('sendEmailsByDepartment');

    Route::get('/api/modules/{filiereId}', function($filiereId) {
        // Get the currently selected session
        $selected_session = SessionExam::find(1); // Adjust this
    
        // Get modules for the filiere that do not have exams scheduled
        $scheduled_modules = Examen::where('id_session', $selected_session->id)
            ->pluck('id_module')
            ->toArray();
    
        $modules = Module::where('code_etape', $filiereId)
            ->whereNotIn('id', $scheduled_modules)
            ->get();
    
        return response()->json(['modules' => $modules]);
    });
    
});

require __DIR__ . '/auth.php';
