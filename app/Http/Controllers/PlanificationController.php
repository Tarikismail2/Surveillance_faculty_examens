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
use App\Models\EmailLog;

use function PHPUnit\Framework\returnSelf;

class PlanificationController extends Controller
{

    public function showGlobalPlan(Request $request)
    {
        // Get selected session ID from the request
        $selectedSessionId = $request->query('id_session');

        log::info('this the exam id  '.$selectedSessionId   );
        // Fetch exams for the selected session
        $exams = [];
        if ($selectedSessionId) {
            $exams = Examen::where('id_session', $selectedSessionId)
                ->with(['modules.filiere', 'salles', 'sallesSupplementaires', 'enseignant', 'session', 'enseignants', 'modules'])
                ->get();
                // return $exams;
        }
        
        // dd($exams);
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
    
        $selectedSessionId = $request->input('id_session');
        $exams = Examen::with('modules', 'sallesSupplementaires', 'enseignants', 'enseignant', 'session')
            ->where('id_session', $selectedSessionId)
            ->get();
        $session = SessionExam::findOrFail($selectedSessionId);
    
        $options = new DompdfOptions();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
    
        $html = view('enseignants.global_pdf', compact(['exams', 'session']))->render();
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
    
        return $dompdf->stream('global_exam_schedule.pdf', ['Attachment' => 0]);
    }
    
    public function getScheduleByDepartmentAndSession($departmentId, $sessionId)
    {
        // Récupérer les examens associés à la session et au département
        $schedule = Examen::where('id_session', $sessionId)
            ->whereHas('enseignant', function ($query) use ($departmentId) {
                $query->where('id_department', $departmentId);  // Assurez-vous que la clé de relation est correcte
            })
            ->with(['salles', 'enseignant', 'surveillants', 'session'])
            ->get();
    
        return $schedule;
    }
    
    public function sendScheduleEmails(Request $request)
    {
        $departmentId = $request->input('id_department');
        $sessionId = $request->input('id_session');
        
        // Récupérer le planning pour ce département et cette session
        $schedule = $this->getScheduleByDepartmentAndSession($departmentId, $sessionId);
    
        // Vérifier s'il y a des emplois du temps à envoyer
        if ($schedule->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun emploi du temps disponible pour ce département et cette session.');
        }
    
        // Parcourir les enseignants et leur envoyer un email
        foreach ($schedule as $entry) {
            $enseignant = $entry->enseignant;
    
            if ($enseignant && $enseignant->email) {
                $enseignantEmail = $enseignant->email;
    
                // Contenu de l'email
                $subject = "Votre emploi du temps pour la session " . $entry->session->type;
                $text = "Bonjour " . $enseignant->name . ",\n\nVoici votre emploi du temps pour la session " . $entry->session->type . " :\n"
                        . "Date: " . $entry->date->format('d/m/Y') . "\n"
                        . "Heure début: " . $entry->heure_debut->format('H:i') . "\n"
                        . "Heure fin: " . $entry->heure_fin->format('H:i') . "\n"
                        . "Salle: " . $entry->salles->pluck('name')->implode(', ') . "\n\n"
                        . "Cordialement,\nVotre administration.";
    
                // Formatage correct des arguments pour exec()
                $subjectEscaped = escapeshellarg($subject);
                $textEscaped = escapeshellarg($text);
                $emailEscaped = escapeshellarg($enseignantEmail);
    
                // Utiliser base_path() pour le chemin vers email.js
                $nodeScriptPath = base_path('email.js');
                exec("node $nodeScriptPath $emailEscaped $subjectEscaped $textEscaped");
    
                // Enregistrer l'email dans la table email_logs
                EmailLog::create([
                    'recipient_email' => $enseignantEmail,
                    'subject' => $subject,
                    'body' => $text,
                    'sent_at' => now(),
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Emploi du temps envoyé à tous les enseignants.');
    }
    

    
}
