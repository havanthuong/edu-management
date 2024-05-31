<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return Attendance::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'studentId' => 'required|exists:students,id',
            'sessionId' => 'required|exists:sessions,id',
        ]);

        $attendance = Attendance::create($validatedData);
        return response()->json($attendance, 201);
    }

    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);
        return response()->json($attendance);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'studentId' => 'required|exists:students,id',
            'sessionId' => 'required|exists:sessions,id',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($validatedData);
        return response()->json($attendance);
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return response()->json(null, 204);
    }
}
