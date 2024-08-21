<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SessionExam;

class SessionExamController extends Controller
{
    public function index()
    {
        $sessions = SessionExam::all();
        return view('sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'type' => 'required|string|max:255',
        ]);

        $dateDebut = new \DateTime($request->date_debut);
        $dateFin = new \DateTime($request->date_fin);
        $interval = $dateDebut->diff($dateFin)->days;

        if ($interval > 6) {
            return back()->withErrors(['date_fin' => 'La durée entre la date de début et la date de fin ne doit pas dépasser 6 jours.'])->withInput();
        }

        SessionExam::create($request->all());
        return redirect()->route('sessions.index')->with('success', 'Session créée avec succès.');
    }

    public function show($id)
    {
        $session = SessionExam::findOrFail($id);
        // dd($session);
        return view('sessions.show', compact('session'));
    }

    public function edit($id)
    {
        $session = SessionExam::findOrFail($id);
        return view('sessions.edit', compact('session'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'type' => 'required|string|max:255',
        ]);

        $dateDebut = new \DateTime($request->date_debut);
        $dateFin = new \DateTime($request->date_fin);
        $interval = $dateDebut->diff($dateFin)->days;

        if ($interval > 6) {
            return back()->withErrors(['date_fin' => 'La durée entre la date de début et la date de fin ne doit pas dépasser 6 jours.'])->withInput();
        }

        $session = SessionExam::findOrFail($id);
        $session->update($request->all());
        return redirect()->route('sessions.index')->with('success', 'Session mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $session = SessionExam::findOrFail($id);
        $session->delete();
        return redirect()->route('sessions.index')->with('success', 'Session supprimée avec succès.');
    }
}
