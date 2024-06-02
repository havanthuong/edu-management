<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        $students = ClassModel::with(['teacher.user', 'department'])->get();
        return response()->json($students, 200);
    }

    public function store(Request $request)
    {
        $currentAccount = auth()->user();
        $user = User::where('accountId', $currentAccount->id)->first();

        if (!$currentAccount || $currentAccount->role !== 2) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $teacher = Teacher::where('userId', $user->id)->first();

        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'courseName' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'numberOfSession' => 'required|integer|min:1',
            'departmentId' => 'required|exists:Department,id',
        ]);

        $classData = array_merge($validatedData, [
            'teacherId' => $teacher->id,
            'status' => 'Unapprove'
        ]);

        $class = ClassModel::create($classData);
        return response()->json($class, 201);
    }

    public function show($id)
    {
        $class = ClassModel::with(['teacher.user', 'department'])->findOrFail($id);
        return response()->json($class);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required', // Unapprove, Unopen, Open, Finish
        ]);

        $class = ClassModel::findOrFail($id);
        $class->update($validatedData);
        return response()->json($class);
    }

    public function destroy($id)
    {
        $class = ClassModel::findOrFail($id);
        $class->delete();
        return response()->json(null, 204);
    }

    public function getUnopenedClassesByDepartment()
    {
        $currentAccount = auth()->user();

        if (!$currentAccount || $currentAccount->role !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::where('accountId', $currentAccount->id)->first();
        $student = Student::where('userId', $user->id)->first();
        $departmentId = $student->departmentId;

        $unopenedClasses = ClassModel::where('status', 'Unopen')
            ->where('departmentId', $departmentId)
            ->with('teacher.user', 'department')
            ->get();

        return response()->json($unopenedClasses);
    }

    public function getClassByTeacher()
    {
        $currentAccount = auth()->user();
        $user = User::where('accountId', $currentAccount->id)->first();

        if (!$currentAccount || $currentAccount->role !== 2) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $teacher = Teacher::where('userId', $user->id)->first();

        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }

        $classes = ClassModel::where('teacherId', $teacher->id)->with('teacher.user', 'department')->get();
        return response()->json($classes, 200);
    }
}
