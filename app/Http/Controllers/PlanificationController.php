<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Examen;
use App\Models\ExamenSalleEnseignant;
use App\Models\Salle;
use App\Models\SessionExam;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Dompdf\Options as DompdfOptions;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanificationController extends Controller
{

    public function showGlobalPlan(Request $request)
    {
        // Get selected session ID from the request
        $selectedSessionId = $request->query('id_session');

        // Fetch exams for the selected session
        $exams = [];
        if ($selectedSessionId) {
            $exams = Examen::where('id_session', $selectedSessionId)
                ->with(['module.filiere', 'salles', 'sallesSupplementaires', 'enseignant', 'session', 'enseignants'])
                ->get();
        }

        // Fetch all sessions
        $sessions = SessionExam::all();

        return view('examens.global', [
            'sessions' => $sessions,
            'selectedSessionId' => $selectedSessionId,
            'exams' => $exams,
        ]);
    }


    public function getExamsBySession($sessionId)
    {
        $exams = Examen::where('id_session', $sessionId)
            ->with(['module.filiere', 'salle', 'sallesSupplementaires', 'enseignant', 'session', 'enseignants'])
            ->get()
            ->map(function ($exam) {
                return [
                    'date' => $exam->date,
                    'heure_debut' => $exam->heure_debut,
                    'heure_fin' => $exam->heure_fin,
                    'filiere' => $exam->module->filiere->version_etape,
                    'module' => $exam->module->lib_elp,
                    'additionalSalles' => $exam->sallesSupplementaires->pluck('name')->toArray(),
                    'enseignant' => $exam->enseignant->name,
                    'session' => $exam->session->type,
                    'enseignants' => $exam->enseignants->pluck('name')->toArray(),
                ];
            });

        return response()->json($exams);
    }

    public function showExams(Request $request)
    {
        $sessions = SessionExam::all();
        $selectedSessionId = $request->query('id_session', null);

        $exams = [];
        if ($selectedSessionId) {
            $exams = Examen::where('id_session', $selectedSessionId)
                ->with(['module.filiere', 'salle', 'sallesSupplementaires', 'enseignant', 'session', 'enseignants'])
                ->get();

            if ($exams->isEmpty()) {
                // If no exams found for the selected session, return a 404 response
                abort(404, 'No exams scheduled for the selected session.');
            }
        }

        return view('examens.schedule', compact('sessions', 'exams', 'selectedSessionId'));
    }


    public function downloadGlobalSchedulePDF(Request $request)
    {
        ini_set('max_execution_time', 600);
        // Fetch data needed for PDF generation
        $selectedSessionId = $request->input('id_session');
        $exams = Examen::where('id_session', $selectedSessionId)->get();

        // Fetch session details
        $session = SessionExam::findOrFail($selectedSessionId);

        // Configure Dompdf options
        $options = new DompdfOptions();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true); // Enable remote content (images in base64)
        $dompdf = new Dompdf($options);
        $totalPages = $exams->count();

        // Load HTML view file
        $html = view('examens.global_pdf', compact(['exams', 'session']))->render();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        return $dompdf->stream('global_exam_schedule.pdf', ['Attachment' => 0]);
    }

}
