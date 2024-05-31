<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        return Session::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'classId' => 'required|exists:classes,id',
            'sessionDate' => 'required|date',
            'sessionLocation' => 'required',
        ]);

        $session = Session::create($validatedData);
        return response()->json($session, 201);
    }

    public function show($id)
    {
        $session = Session::findOrFail($id);
        return response()->json($session);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'classId' => 'required|exists:classes,id',
            'sessionDate' => 'required|date',
            'sessionLocation' => 'required',
        ]);

        $session = Session::findOrFail($id);
        $session->update($validatedData);
        return response()->json($session);
    }

    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();
        return response()->json(null, 204);
    }
}
