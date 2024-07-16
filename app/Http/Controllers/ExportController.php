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
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Set PDF file name
        $pdfFileName = 'Surveillance Schedule.pdf';

        // Stream the PDF to the browser
        return $dompdf->stream($pdfFileName);
    }



   
    // Emploi du temps pour étudiant
    public function selectStudent(Request $request)
    {
        $sessions = SessionExam::orderBy('type')->pluck('type', 'id');
        $students = Etudiant::orderBy('nom')->get()->pluck('full_name', 'id');

        return view('planification.select_student', compact('sessions', 'students'));
    }

    public function displayStudentSchedule(Request $request)
    {
        $id_session = $request->input('id_session');
        $id_etudiant = $request->input('id_etudiant');

        $sessions = SessionExam::orderBy('type')->pluck('type', 'id');
        $students = Etudiant::orderBy('nom')->get()->pluck('full_name', 'id');

        $session = SessionExam::find($id_session);
        $etudiant = Etudiant::find($id_etudiant);

        if (!$etudiant) {
            return back()->withErrors(['error' => 'Étudiant non trouvé']);
        }

        // Retrieve exams associated with the student through their modules
        $examens = Examen::whereHas('module.etudiants', function ($query) use ($id_etudiant) {
                            $query->where('etudiants.id', $id_etudiant);
                        })
                        ->where('id_session', $id_session)
                        ->orderBy('date')
                        ->orderBy('heure_debut')
                        ->get();
                        // dd($etudiant);

        return view('planification.select_student', compact('sessions', 'students', 'session', 'etudiant', 'examens'));
    }

    public function downloadStudentSchedulePDF(Request $request)
    {
        $id_session = $request->input('id_session');
        $id_etudiant = $request->input('id_etudiant');

        if (!$id_session) {
            return redirect()->back()->with('error', 'Session ID is missing.');
        }

        $session = SessionExam::find($id_session);
        $etudiant = Etudiant::find($id_etudiant);

        if (!$session) {
            return redirect()->back()->with('error', 'Session not found.');
        }

        if (!$etudiant) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        $schedule = Examen::whereHas('module.etudiants', function ($query) use ($id_etudiant) {
                            $query->where('etudiants.id', $id_etudiant);
                        })
                        ->where('id_session', $id_session)
                        ->orderBy('date')
                        ->orderBy('heure_debut')
                        ->get();

        $session_type = $session->type;
        $student_name = $etudiant->full_name;

        $html = view('planification.show_student_schedule_pdf', compact('session_type', 'student_name', 'schedule'))->render();

        // Setup PDF options and generate PDF
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Set PDF file name
        $pdfFileName = 'Student_Exam_Schedule.pdf';

        // Stream the PDF to the browser
        return $dompdf->stream($pdfFileName);
    }
}
