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
        SessionExam::create($request->all());
        return redirect()->route('sessions.index');
    }
    

    public function show($id)
    {
        $session = SessionExam::findOrFail($id);
        return view('sessions.show', compact('session'));
    }

    public function edit($id)
    {
        $session = SessionExam::findOrFail($id);
        return view('sessions.edit', compact('session'));
    }

    public function update(Request $request, $id)
    {
        $session = SessionExam::findOrFail($id);
        $session->update($request->all());
        return redirect()->route('sessions.index');
    }

    public function destroy($id)
    {
        $session = SessionExam::findOrFail($id);
        $session->delete();
        return redirect()->route('sessions.index');
    }
}
