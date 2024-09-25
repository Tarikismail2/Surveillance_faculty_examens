<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Examen;
use App\Models\ExamenSalleEnseignant;
use App\Models\SessionExam;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller
{
    //Emploi du temps pour enseignant
    public function selectEnseignant(Request $request)
    {
        // Récupérer les sessions et leurs types
        $sessions = SessionExam::select('id', 'type', 'date_debut', 'date_fin')->orderBy('type', 'asc')->get();
    
        // Récupérer les enseignants
        $enseignants = Enseignant::orderBy('name')->pluck('name', 'id');
    
        return view('planification.select_enseignant', compact('sessions', 'enseignants'));
    }
    
    public function displaySchedule(Request $request)
    {
        // Validate user input
        $request->validate([
            'id_enseignant' => 'required|exists:enseignants,id',
            'id_session' => 'required|exists:session_exams,id',
            'email' => 'required|email',
        ]);
    
        $idSession = $request->input('id_session');
        $idEnseignant = $request->input('id_enseignant');
        $email = $request->input('email');
    
        // Check if the enseignant exists
        $enseignant = Enseignant::where('id', $idEnseignant)->where('email', $email)->first();
    
        if (!$enseignant) {
            return redirect()->back()->with('error', 'Enseignant non trouvé ou email incorrect.');
        }
    
        // Retrieve and sort the schedule
        $schedule = ExamenSalleEnseignant::where('id_enseignant', $idEnseignant)
            ->whereHas('examen', function ($query) use ($idSession) {
                $query->where('id_session', $idSession);
            })
            ->with(['examen', 'salle'])
            ->get()
            ->sortBy([
                fn($a, $b) => $a->examen->date <=> $b->examen->date,
                fn($a, $b) => $a->examen->heure_debut <=> $b->examen->heure_debut,
            ]);
    
        $sessions = SessionExam::select('id', 'type', 'date_debut', 'date_fin')->orderBy('type', 'asc')->get();

        return view('planification.select_enseignant', [
            'sessions' => $sessions,
            'enseignants' => Enseignant::orderBy('name')->pluck('name', 'id'),
            'schedule' => $schedule,
            'selectedEnseignant' => $enseignant->name,
            'selectedEnseignantId' => $enseignant->id,
            'id_session' => $idSession,
        ]);
    }
    

    public function downloadSurveillancePDF(Request $request)
    {
        ini_set('max_execution_time', 600);
        $id_session = $request->input('id_session');
        $enseignant_id = $request->input('id_enseignant');

        if (!$id_session) {
            return redirect()->back()->with('error', 'Session ID is missing.');
        }

        $session = SessionExam::find($id_session);
        $enseignant = Enseignant::find($enseignant_id);
        $name_enseignant = $enseignant->name;
        // dd( $name_enseignant );

        if (!$session) {
            return redirect()->back()->with('error', 'Session not found.');
        }

        $dateDebut = Carbon::parse($session->date_debut);
        $dateFin = Carbon::parse($session->date_fin);

        $dates = [];
        $currentDate = $dateDebut->copy();

        while ($currentDate <= $dateFin) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        $exams = Examen::where('id_session', $id_session)->get();

        // Retrieve and sort schedule
        $schedule = ExamenSalleEnseignant::where('id_enseignant', $enseignant_id)
            ->whereHas('examen', function ($query) use ($id_session) {
                $query->where('id_session', $id_session);
            })
            ->with(['examen', 'salle'])
            ->get()
            ->sortBy([
                fn($a, $b) => $a->examen->date <=> $b->examen->date,
                fn($a, $b) => $a->examen->heure_debut <=> $b->examen->heure_debut,
            ]);

        $session_type = $session->type;


        $html = view('planification.show_schedule', compact('session_type', 'name_enseignant', 'dates', 'schedule', 'session'))->render();

        // Setup PDF options and generate PDF
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return $dompdf->stream('Surveillance Schedule.pdf', ['Attachment' => 0]);
    }


    // Emploi du temps pour étudiant
    public function selectStudent(Request $request)
    {
        // Validate the input for session and student
        $validated = $request->validate([
            'id_session' => 'nullable|exists:session_exams,id',
            'id_etudiant' => 'nullable|exists:etudiants,id',
        ]);
    
        // Fetch all sessions and students for dropdowns
        $sessions = SessionExam::select('id', 'type', 'date_debut', 'date_fin')->orderBy('type')->get();
        $students = Etudiant::orderBy('nom')->orderBy('prenom')->get()->mapWithKeys(function ($student) {
            return [$student->id => $student->nom . ' ' . $student->prenom];
        });
    
        // Get selected session and student from the request
        $selectedSession = $request->input('id_session');
        $selectedStudent = $request->input('id_etudiant');
    
        $examens = [];
        // Fetch exams if both session and student are selected
        if ($selectedSession && $selectedStudent) {
            $examens = DB::table('examens')
                ->join('exam_module', 'examens.id', '=', 'exam_module.exam_id')
                ->join('modules', 'exam_module.module_id', '=', 'modules.id')
                ->join('inscriptions', 'modules.id', '=', 'inscriptions.id_module')
                ->join('etudiants', 'etudiants.id', '=', 'inscriptions.id_etudiant')
                ->where('etudiants.id', $selectedStudent)
                ->where('examens.id_session', $selectedSession)
                ->orderBy('examens.date')
                ->orderBy('examens.heure_debut')
                ->select('examens.*', 'modules.lib_elp as module_nom')
                ->get();
        }
    
        return view('planification.select_student', compact('sessions', 'students', 'selectedSession', 'selectedStudent', 'examens'));
    }
    
    public function displayStudentSchedule(Request $request)
    {
        // Validate the input fields
        $validated = $request->validate([
            'id_session' => 'required|exists:session_exams,id',
            'cne' => 'nullable|exists:etudiants,cne',
            'id_etudiant' => 'nullable|exists:etudiants,id',
        ]);
    
        // Determine the selected student based on CNE or ID
        $selectedStudent = $request->filled('cne')
            ? Etudiant::where('cne', $request->input('cne'))->firstOrFail()->id
            : $request->input('id_etudiant');
    
        $selectedSession = $request->input('id_session');
    
        // Fetch the student by CNE if provided
        $etudiant = $request->filled('cne') ? Etudiant::where('cne', $request->input('cne'))->first() : null;
    
        // Fetch the exams for the selected student and session
        $examens = DB::table('examens')
            ->join('exam_module', 'examens.id', '=', 'exam_module.exam_id')
            ->join('modules', 'exam_module.module_id', '=', 'modules.id')
            ->whereExists(function ($query) use ($selectedStudent) {
                $query->select(DB::raw(1))
                    ->from('inscriptions')
                    ->whereColumn('inscriptions.id_module', 'modules.id')
                    ->where('inscriptions.id_etudiant', '=', $selectedStudent);
            })
            ->where('examens.id_session', $selectedSession)
            ->orderBy('examens.date', 'asc')
            ->orderBy('examens.heure_debut', 'asc')
            ->select('examens.*', 'modules.lib_elp as module_nom')
            ->get();
    
        // Fetch all students and sessions for dropdowns
        $students = Etudiant::orderBy('nom')->orderBy('prenom')->get()->mapWithKeys(function ($student) {
            return [$student->id => $student->nom . ' ' . $student->prenom];
        });
    
        $sessions = SessionExam::select('id', 'type', 'date_debut', 'date_fin')->orderBy('type', 'asc')->get();
    
        return view('planification.select_student', compact('sessions', 'students', 'selectedSession', 'selectedStudent', 'examens', 'etudiant'));
    }
    

    public function downloadStudentSchedulePDF(Request $request)
    {
        ini_set('max_execution_time', 600); // Increase execution time

        // Retrieve session and student IDs from request
        $id_session = $request->input('id_session');
        $id_etudiant = $request->input('id_etudiant');

        // Check for missing session and student IDs
        if (!$id_session) {
            return redirect()->back()->with('error', 'Session ID is missing.');
        }

        if (!$id_etudiant) {
            return redirect()->back()->with('error', 'Student ID is missing.');
        }

        // Find the session and student
        $session = SessionExam::find($id_session);
        $etudiant = Etudiant::find($id_etudiant);

        if (!$session) {
            return redirect()->back()->with('error', 'Session not found.');
        }

        if (!$etudiant) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        // Retrieve the exams for the selected student in the given session
        $schedule = Examen::whereHas('modules', function ($query) use ($id_etudiant) {
            $query->whereHas('etudiants', function ($query) use ($id_etudiant) {
                $query->where('etudiants.id', $id_etudiant);
            });
        })
            ->where('id_session', $id_session)
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get();

        // Prepare the view data
        $session_type = $session->type;
        $student_name = $etudiant->nom . ' ' . $etudiant->prenom;

        // Create the HTML view for the PDF
        $html = view('planification.show_student_schedule_pdf', compact('session_type', 'student_name', 'schedule'))->render();

        // Configure Dompdf options
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Initialize Dompdf and load the HTML content
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // Set the paper size and orientation

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF (inline)
        return $dompdf->stream('Student_Exam_Schedule.pdf', ['Attachment' => 0]);
    }
}
