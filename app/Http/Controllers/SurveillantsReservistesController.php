<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\SessionExam;
use App\Models\SurveillantReserviste;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;

class SurveillantsReservistesController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');
        $demi_journee = $request->input('demi_journee', 'matin');
    
        // Récupération des réservistes en fonction de la date et de la demi-journée
        $reservistes = SurveillantReserviste::where('date', $date)
            ->where('demi_journee', $demi_journee)
            ->with('enseignant.department')
            ->get();
    
        return view('surveillance.surveillants_reservistes', compact('reservistes', 'date', 'demi_journee'));
    }

    public function downloadPDF(Request $request)
    {
        ini_set('max_execution_time', 600);
        $date = $request->input('date');
        $demi_journee = $request->input('demi_journee', 'matin');

        // Récupération des réservistes en fonction de la date et de la demi-journée pour le PDF
        $reservistes = SurveillantReserviste::where('date', $date)
            ->where('demi_journee', $demi_journee)
            ->with('enseignant.department') // Ajout du chargement du département
            ->get();

        // Récupération de la session
        $session = SessionExam::where('date_debut', '<=', $date)
            ->where('date_fin', '>=', $date)
            ->first();
            // dd($session);

        $options = new DompdfOptions();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Chargement de la vue pour le PDF
        $html = view('surveillance.surveillants_reservistes_pdf', compact('reservistes', 'date', 'demi_journee', 'session'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Génération du PDF
        return $dompdf->stream('surveillants_reservistes_' . $date . '_' . $demi_journee . '.pdf', ['Attachment' => 0]);
    }
}