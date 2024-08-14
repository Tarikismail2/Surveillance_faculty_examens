<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Enseignant;
use App\Models\Examen;
use App\Models\ExamenSalleEnseignant;
use App\Models\SessionExam;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;

class TimetableController extends Controller
{
    public function selectDepartment()
    {
        $departements = Department::orderBy('name')->pluck('name', 'id_department');
        $sessions = SessionExam::orderBy('type')->pluck('type', 'id');

        return view('emploi.select_department', compact('departements', 'sessions'));
    }

    public function displayScheduleByDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_department' => 'required|exists:departments,id_department',
            'id_session' => 'required|exists:session_exams,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $idDepartment = $request->input('id_department');
        $idSession = $request->input('id_session');

        $departement = Department::find($idDepartment);

        $enseignants = Enseignant::where('id_department', $idDepartment)->pluck('id');
        $schedule = ExamenSalleEnseignant::whereIn('id_enseignant', $enseignants)
            ->whereHas('examen', function ($query) use ($idSession) {
                $query->where('id_session', $idSession);
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

        return view('emploi.select_department', [
            'departement' => $departement,
            'schedule' => $schedule,
            'departements' => Department::orderBy('name')->pluck('name', 'id_department'),
            'sessions' => SessionExam::orderBy('type')->pluck('type', 'id'),
        ]);
    }

    public function downloadSchedule(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id_department' => 'required|exists:departments,id_department',
        'id_session' => 'required|exists:session_exams,id',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $idDepartment = $request->input('id_department');
    $idSession = $request->input('id_session');

    $enseignants = Enseignant::where('id_department', $idDepartment)->pluck('id');
    $schedule = ExamenSalleEnseignant::whereIn('id_enseignant', $enseignants)
        ->whereHas('examen', function ($query) use ($idSession) {
            $query->where('id_session', $idSession);
        })
        ->with(['examen', 'salle', 'enseignant'])
        ->get()
        ->sortBy([
            fn ($a, $b) => $a->examen->date <=> $b->examen->date,
            fn ($a, $b) => $a->examen->heure_debut <=> $b->examen->heure_debut,
        ]);

    // Generate PDF
    $html = view('emploi.schedule', compact('schedule', 'idDepartment', 'idSession'))->render();

    // Setup PDF options and generate PDF
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    return $dompdf->stream('Surveillance Schedule by Department.pdf', ['Attachment' => 0]);
}
}
