<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\ClassStudent;
use App\Models\Session;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user.account', 'department'])->get();
        return response()->json($students, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'userId' => 'required|exists:User,id',
            'departmentId' => 'required|exists:Department,id',
        ]);

        $student = Student::create($validatedData);
        return response()->json($student, 201);
    }

    public function show($id)
    {
        $student = Student::with(['user', 'department'])->findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'userId' => 'required|exists:User,id',
            'departmentId' => 'required|exists:Department,id',
        ]);

        $student = Student::findOrFail($id);
        $student->update($validatedData);
        return response()->json($student);
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return response()->json(null, 204);
    }

    public function sessionCount(Request $request, $studentId, $classId)
    {
        $student = Student::find($studentId);
        $class = ClassModel::find($classId);

        if (!$student || !$class) {
            return response()->json(['error' => 'Student or Class not found'], 404);
        }

        $sessionCount = DB::table('attendance')
            ->join('session', 'attendance.sessionId', '=', 'session.id')
            ->where('attendance.studentId', $studentId)
            ->where('session.classId', $classId)
            ->where('attendance.status', '!=', -1)
            ->count();

        $totalSessions = Session::where('classId', $classId)->count();

        return response()->json([
            'sessionCount' => $sessionCount,
            'totalSessions' => $totalSessions
        ]);
    }

    public function getClassByStudent()
    {
        $currentAccount = auth()->user();

        if (!$currentAccount || $currentAccount->role !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::where('accountId', $currentAccount->id)->first();
        $student = Student::where('userId', $user->id)->first();

        $classStudents = ClassStudent::where('studentId', $student->id)
            ->with('class.teacher')
            ->get();

        return response()->json($classStudents);
    }
}
