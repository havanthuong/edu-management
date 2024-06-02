<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['student.user'])->get();
        return response()->json($attendances, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'studentId' => 'required|exists:Student,id',
            'sessionId' => 'required|exists:Session,id',
            'status' => 'required|in:-1,0,1',
        ]);

        $existingAttendance = Attendance::where('studentId', $validatedData['studentId'])
            ->where('sessionId', $validatedData['sessionId'])
            ->first();

        if ($existingAttendance) {
            return response()->json(['error' => 'Attendance for this student and session already exists.'], 422);
        }

        $attendance = Attendance::create($validatedData);
        return response()->json($attendance, 201);
    }

    public function show($id)
    {
        $attendance = Attendance::with(['student.user'])->findOrFail($id);
        return response()->json($attendance);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:-1,0,1',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->status = $validatedData['status'];
        $attendance->save();
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
            'studentId' => 'required|exists:Student,id',
            'sessionId' => 'required|exists:Session,id',
        ]);

        $attendance = Attendance::where('studentId', $validatedData['studentId'])
            ->where('sessionId', $validatedData['sessionId'])
            ->first();

        if (!$attendance) {
            $attendance = Attendance::create([
                'studentId' => $validatedData['studentId'],
                'sessionId' => $validatedData['sessionId'],
                'status' => -1,
            ]);
        }

        return response()->json($attendance);
    }
}
