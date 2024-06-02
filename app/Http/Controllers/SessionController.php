<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function index()
    {
        return Session::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'classId' => 'required|exists:Class,id',
            'sessionDate' => 'required|date',
            'sessionLocation' => 'required',
        ]);

        $sessionData = array_merge($validatedData, [
            'status' => 0
        ]);

        $session = Session::create($sessionData);
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
            'classId' => 'required|exists:Class,id',
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

    public function attendanceCount($sessionId)
    {
        $session = Session::findOrFail($sessionId);

        $attendanceCount = Attendance::where('sessionId', $session->id)->count();

        return response()->json(['attendanceCount' => $attendanceCount]);
    }

    public function startSession($sessionId)
    {
        $session = Session::findOrFail($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        if ($session->status === 1) {
            return response()->json(['error' => "Can't start thí session"], 400);
        }

        $session->status = 1;
        $session->save();

        $classId = $session->classId;
        $students = DB::table('classstudent')
            ->where('classId', $classId)
            ->join('student', 'classstudent.studentId', '=', 'student.id')
            ->select('student.id as studentId')
            ->get();

        foreach ($students as $student) {
            Attendance::create([
                'studentId' => $student->studentId,
                'sessionId' => $sessionId,
                'status' => -1,
            ]);
        }

        return response()->json(['message' => 'Bắt đầu buổi học thành công.'], 201);
    }

    public function getSessionByClassId($classId)
    {
        $classes = Session::where('classId', $classId)->get();
        return response()->json($classes, 200);
    }
}
