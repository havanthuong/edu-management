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
            'status' => 'required|in:-1,0,1',
        ]);

        $existingAttendance = Attendance::where('student_id', $validatedData['student_id'])
            ->where('session_id', $validatedData['session_id'])
            ->first();

        if ($existingAttendance) {
            return response()->json(['error' => 'Attendance for this student and session already exists.'], 422)->header('Content-Type', 'text/plain');
        }

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

    public function getByStudentAndSession(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'session_id' => 'required|exists:sessions,id',
        ]);

        $attendance = Attendance::where('student_id', $validatedData['student_id'])
            ->where('session_id', $validatedData['session_id'])
            ->first();

        if (!$attendance) {
            $attendance = Attendance::create([
                'student_id' => $validatedData['student_id'],
                'session_id' => $validatedData['session_id'],
                'status' => -1,
            ]);
        }

        return response()->json($attendance);
    }
}
