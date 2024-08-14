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
        $sessions = SessionExam::orderBy('type')->pluck('type', 'id');
        $enseignants = Enseignant::orderBy('name')->pluck('name', 'id');

        return view('planification.select_enseignant', compact('sessions', 'enseignants'));
    }

    public function displaySchedule(Request $request)
    {
        $idSession = $request->input('id_session');
        $idEnseignant = $request->input('id_enseignant');

        $enseignant = Enseignant::find($idEnseignant);

        if (!$enseignant) {
            return redirect()->back()->with('error', 'Enseignant non trouvé.');
        }

        // Retrieve and sort schedule
        $schedule = ExamenSalleEnseignant::where('id_enseignant', $idEnseignant)
            ->whereHas('examen', function ($query) use ($idSession) {
                $query->where('id_session', $idSession);
            })
            ->with(['examen', 'salle'])
            ->get()
            ->sortBy([
                fn ($a, $b) => $a->examen->date <=> $b->examen->date,
                fn ($a, $b) => $a->examen->heure_debut <=> $b->examen->heure_debut,
            ]);

        return view('planification.select_enseignant', [
            'sessions' => SessionExam::orderBy('type')->pluck('type', 'id'),
            'enseignants' => Enseignant::all()->pluck('name', 'id'),
            'id_session' => $idSession,
            'selectedEnseignant' => $enseignant->name,
            'selectedEnseignantId' => $idEnseignant,
            // 'userName' => $userName,
            'schedule' => $schedule,
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

        $creneauxHoraires = ['08:00:00-10:00:00', '10:00:00-12:00:00', '12:00:00-14:00:00', '14:00:00-16:00:00', '16:00:00-18:00:00'];

        // Retrieve and sort schedule
        $schedule = ExamenSalleEnseignant::where('id_enseignant', $enseignant_id)
            ->whereHas('examen', function ($query) use ($id_session) {
                $query->where('id_session', $id_session);
            })
            ->with(['examen', 'salle'])
            ->get()
            ->sortBy([
                fn ($a, $b) => $a->examen->date <=> $b->examen->date,
                fn ($a, $b) => $a->examen->heure_debut <=> $b->examen->heure_debut,
            ]);

        $session_type = $session->type;

        $html = view('planification.show_schedule', compact('session_type', 'name_enseignant', 'dates', 'creneauxHoraires', 'schedule'))->render();

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
        // Get all sessions from the database
        $sessions = SessionExam::all()->pluck('type', 'id');

        // Get all students from the database and concatenate nom and prenom
        $students = Etudiant::all()->mapWithKeys(function ($student) {
            return [$student->id => $student->nom . ' ' . $student->prenom];
        });

        // Get selected session and student from request
        $selectedSession = $request->input('id_session');
        $selectedStudent = $request->input('id_etudiant');

        // Retrieve exams for the selected student and session if provided
        $examens = [];
        if ($selectedSession && $selectedStudent) {
            $examens = Examen::where('session_id', $selectedSession)
                ->where('student_id', $selectedStudent)
                ->get();
        }

        return view('planification.select_student', compact('sessions', 'students', 'selectedSession', 'selectedStudent', 'examens'));
    }




    public function displayStudentSchedule(Request $request)
    {
        $sessions = SessionExam::orderBy('type')->pluck('type', 'id');

        // Get all students and concatenate nom and prenom
        $students = Etudiant::orderBy('nom')->orderBy('prenom')->get()->mapWithKeys(function ($student) {
            return [$student->id => $student->nom . ' ' . $student->prenom];
        });

        $selectedSession = $request->input('id_session', null);
        $selectedStudent = $request->input('id_etudiant', null);

        // Retrieve exams for the selected student and session if provided
        $examens = [];
        if ($selectedSession && $selectedStudent) {
            $examens = Examen::whereHas('module', function ($query) use ($selectedStudent) {
                $query->whereHas('inscriptions', function ($query) use ($selectedStudent) {
                    $query->where('id_etudiant', $selectedStudent);
                });
            })->where('id_session', $selectedSession)
                ->orderBy('date')
                ->orderBy('heure_debut')
                ->get();
        }

        return view('planification.select_student', compact('sessions', 'students', 'selectedSession', 'selectedStudent', 'examens'));
    }



    public function downloadStudentSchedulePDF(Request $request)
    {
        ini_set('max_execution_time', 600);
        $id_session = $request->input('id_session');
        $id_etudiant = $request->input('id_etudiant');

        // Vérifier la présence des identifiants
        if (!$id_session) {
            return redirect()->back()->with('error', 'Session ID is missing.');
        }

        if (!$id_etudiant) {
            return redirect()->back()->with('error', 'Student ID is missing.');
        }

        // Trouver la session et l'étudiant
        $session = SessionExam::find($id_session);
        $etudiant = Etudiant::find($id_etudiant);

        if (!$session) {
            return redirect()->back()->with('error', 'Session not found.');
        }

        if (!$etudiant) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        // Récupérer les examens de l'étudiant pour la session donnée
        $schedule = Examen::whereHas('module.etudiants', function ($query) use ($id_etudiant) {
            $query->where('etudiants.id', $id_etudiant);
        })
            ->where('id_session', $id_session)
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get();

        // Créer une vue HTML avec les horaires
        $session_type = $session->type;
        $student_name = $etudiant->nom . ' ' . $etudiant->prenom;
        $html = view('planification.show_student_schedule_pdf', compact('session_type', 'student_name', 'schedule'))->render();

        // Configurer Dompdf pour générer le PDF
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return $dompdf->stream('Student_Exam_Schedule.pdf', ['Attachment' => 0]);
    }
}
