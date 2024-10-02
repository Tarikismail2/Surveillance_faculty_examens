<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Enseignant;
use App\Models\ExamenSalleEnseignant;
use App\Models\SessionExam;
use App\Models\SurveillantReserviste;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;

class TimetableController extends Controller
{
    public function selectDepartment()
    {
        $departements = Department::orderBy('name')->pluck('name', 'id_department');
        $sessions = SessionExam::select('id', 'type', 'date_debut', 'date_fin')->orderBy('type', 'asc')->get();

        return view('emploi.select_department', compact('departements', 'sessions'));
    }

    public function displayScheduleByDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_department' => 'required|exists:departments,id_department',
            'id_session' => 'required|exists:session_exams,id',
            // 'id_enseignant' => 'required|exists:enseignants,id', // Ajouté pour valider l'enseignant
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id_department = $request->input('id_department');
        $id_session = $request->input('id_session');
        $id_enseignant = $request->input('id_enseignant');

        // Récupération de l'enseignant sélectionné pour l'afficher
        $selectedEnseignant = Enseignant::find($id_enseignant);

        $departement = Department::find($id_department);
        $enseignants = Enseignant::where('id_department', $id_department)->pluck('id');

        $schedule = ExamenSalleEnseignant::whereIn('id_enseignant', $enseignants)
            ->whereHas('examen', function ($query) use ($id_session) {
                $query->where('id_session', $id_session);
            })
            ->with(['examen', 'salle', 'enseignant'])
            ->get()
            ->map(function ($entry) {
                $entry->examen->date = \Carbon\Carbon::parse($entry->examen->date);
                $entry->examen->heure_debut = \Carbon\Carbon::parse($entry->examen->heure_debut);
                $entry->examen->heure_fin = \Carbon\Carbon::parse($entry->examen->heure_fin);
                return $entry;
            })
            ->sortBy([
                fn($a, $b) => $a->examen->date <=> $b->examen->date,
                fn($a, $b) => $a->examen->heure_debut <=> $b->examen->heure_debut,
            ]);

        $departements = Department::orderBy('name')->pluck('name', 'id_department');
        $sessions = SessionExam::select('id', 'type', 'date_debut', 'date_fin')->orderBy('type', 'asc')->get();

        return view('emploi.select_department', compact(
            'id_department',
            'id_session',
            'selectedEnseignant', // Ajouté pour la vue
            'departement',
            'schedule',
            'departements',
            'sessions'
        ));
    }


    public function downloadSchedule($id_department, $id_session)
    {
        $enseignants = Enseignant::where('id_department', $id_department)->get();

        $session = SessionExam::find($id_session);

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

        // Retrieve the schedule and group it by teacher, date, and time
        $schedule = ExamenSalleEnseignant::whereIn('id_enseignant', $enseignants->pluck('id'))
            ->whereHas('examen', function ($query) use ($id_session) {
                $query->where('id_session', $id_session);
            })
            ->with(['examen', 'salle', 'enseignant'])
            ->get();

        // Retrieve the reservists for the session and dates
        $reservistes = SurveillantReserviste::whereIn('id_enseignant', $enseignants->pluck('id'))
            ->where('id_session', $id_session)
            ->get();

        // Generate PDF
        $html = view('emploi.schedule', compact('schedule', 'id_department', 'id_session', 'enseignants', 'dates', 'reservistes'))->render();

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('Surveillance_Schedule_by_Department.pdf', ['Attachment' => 0]);
    }
}
