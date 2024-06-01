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

        $class = ClassModel::findOrFail($validatedData['classId']);

        if ($class->status !== 'đã duyệt') {
            return response()->json(['error' => 'Không thể tạo session cho lớp học chưa được duyệt.'], 403)->header('Content-Type', 'text/plain');
        }

        $session = Session::create($validatedData);
        return response()->json($session, 201)->header('Content-Type', 'text/plain');
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
        return response()->json(null, 204)->header('Content-Type', 'text/plain');
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
            return response()->json(['error' => 'Session not found'], 404)->header('Content-Type', 'text/plain');
        }


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

        return response()->json(['message' => 'Bắt đầu buổi học thành công.'], 201)->header('Content-Type', 'text/plain');
    }
}
