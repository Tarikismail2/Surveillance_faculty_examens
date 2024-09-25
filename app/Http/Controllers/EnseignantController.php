<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Department;
use App\Models\Etudiant;
use App\Models\Examen;
use App\Models\SessionExam;
use App\Models\SurveillantReserviste;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Redirect;
use App\Mail\NodeMailer;
use App\Models\ExamenSalleEnseignant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class EnseignantController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $enseignants = Enseignant::with('department')->get();
            $departments = Department::all()->keyBy('id_department');

            foreach ($enseignants as $enseignant) {
                $enseignant->department_name = $departments->has($enseignant->id_department)
                    ? $departments[$enseignant->id_department]->name
                    : 'N/A';
            }

            return DataTables::of($enseignants)
                ->addColumn('actions', function ($enseignant) {
                    return view('partials.datatables-actions', [
                        'editUrl' => route('enseignants.edit', $enseignant->id),
                        'deleteUrl' => route('enseignants.destroy', $enseignant->id),
                        'confirmMessage' => 'Êtes-vous sûr de vouloir supprimer cet enseignant ?'
                    ])->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('enseignants.index');
    }

    public function create()
    {
        $departments = Department::all();
        return view('enseignants.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:enseignants,email',
            'id_department' => 'required|exists:departments,id_department',
        ]);

        Enseignant::create($request->all());
        return Redirect::route('enseignants.index')->with('status', ['type' => 'success', 'message' => 'Enseignant created successfully.']);
    }

    public function edit(Enseignant $enseignant)
    {
        $departments = Department::all();
        return view('enseignants.edit', compact('enseignant', 'departments'));
    }

    public function update(Request $request, Enseignant $enseignant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:enseignants,email,' . $enseignant->id,
            'id_department' => 'required|exists:departments,id_department',
        ]);

        $enseignant->update($request->all());
        return Redirect::route('enseignants.index')->with('status', ['type' => 'success', 'message' => 'Enseignant updated successfully.']);
    }

    public function destroy(Enseignant $enseignant)
    {
        $enseignant->delete();
        return Redirect::route('enseignants.index')->with('status', ['type' => 'success', 'message' => 'Enseignant deleted successfully.']);
    }

    public function generatePDFEnseignant($sessionId)
    {
        $session = SessionExam::findOrFail($sessionId);

        // Récupérer tous les examens associés à cette session
        $examens = Examen::with(['modules', 'salles', 'enseignants', 'surveillants'])
            ->where('id_session', $sessionId)
            ->get();
        // Grouper les examens par date et demi-journée (matin/après-midi) et par salle
        $groupedExams = [];

        foreach ($examens as $examen) {
            $date = $examen->date;
            $timeOfDay = $examen->heure_debut < '12:00:00' ? 'morning' : 'afternoon';

            // Si la date n'existe pas dans le tableau, l'initialiser
            if (!isset($groupedExams[$date])) {
                $groupedExams[$date] = ['morning' => [], 'afternoon' => []];
            }

            // Grouper par salle
            foreach ($examen->salles as $salle) {
                $groupedExams[$date][$timeOfDay][$salle->name][] = $examen;
            }
        }

        // Récupérer les surveillants réservistes par date
        $reservists = SurveillantReserviste::where('affecte', false)
            ->whereIn('date', $examens->pluck('date'))
            ->get()
            ->groupBy('date');

        // Charger la vue dans Dompdf
        $pdf = new Dompdf();
        $pdf->loadHtml(view('enseignants.global_pdf', compact('session', 'groupedExams', 'reservists'))->render());
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream('Examen_Enseignant.pdf', ['Attachment' => 0]);
    }


    //envoi des emails
    public function sendEmailsByDepartment(Request $request)
    {
        // Validation des paramètres de la requête
        $request->validate([
            'id_department' => 'required|exists:departments,id_department',
            'id_session' => 'required|exists:session_exams,id',
        ]);
    
        // Récupérer le département et la session sélectionnés
        $idDepartment = $request->input('id_department');
        $idSession = $request->input('id_session');
    
        // Vérifier si le département et la session existent
        $department = Department::where('id_department', $idDepartment)->first();
        $session = SessionExam::find($idSession);
    
        // Si le département ou la session n'existent pas
        if (!$department || !$session) {
            return redirect()->back()->with('error', 'Département ou session non trouvé.');
        }
    
        // Récupérer les enseignants du département
        $enseignants = Enseignant::where('id_department', $idDepartment)->get();
    
        if ($enseignants->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun enseignant trouvé pour ce département.');
        }
    
        // Générer la plage de dates entre le début et la fin de la session
        $dateDebut = Carbon::parse($session->date_debut);
        $dateFin = Carbon::parse($session->date_fin);
        $dates = [];
        $currentDate = $dateDebut->copy();
    
        while ($currentDate <= $dateFin) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }
    
        // Récupérer le planning des enseignants pour la session
        $schedule = ExamenSalleEnseignant::whereIn('id_enseignant', $enseignants->pluck('id'))
            ->whereHas('examen', function ($query) use ($idSession) {
                $query->where('id_session', $idSession);
            })
            ->with(['examen', 'salle', 'enseignant'])
            ->get();
    
        // Récupérer les réservistes de la session
        $reservistes = SurveillantReserviste::whereIn('id_enseignant', $enseignants->pluck('id'))
            ->whereBetween('date', [$dateDebut, $dateFin])
            ->get();
        
        // Charger la vue PDF dans Dompdf
        $pdf = new Dompdf();
        $pdf->loadHtml(view('emploi.schedule', [
            'department' => $department,
            'session' => $session,
            'schedule' => $schedule, 
            'enseignants' => $enseignants, 
            'dates' => $dates, 
            'reservistes' => $reservistes,
            'id_session' => $idSession,
            'id_department' => $idDepartment
        ])->render());
    
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $pdfContent = $pdf->output();
    
        // Envoyer l'email à chaque enseignant
        foreach ($enseignants as $enseignant) {
            Mail::to($enseignant->email)->send(new NodeMailer($department, $session, $pdfContent, $schedule, $enseignants, $dates, $reservistes, $idDepartment, $idSession));
        }
    
        return redirect()->back()->with('success', 'Les emails ont été envoyés aux enseignants du département.');
    }
}
