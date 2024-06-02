<?php

namespace App\Http\Controllers;

use App\Models\ClassRegistration;
use App\Models\ClassStudent;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user.account', 'department'])->get();
        return response()->json($teachers, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'userId' => 'required|exists:User,id',
            'departmentId' => 'required|exists:Department,id',
        ]);

        $teacher = Teacher::create($validatedData);
        return response()->json($teacher, 201);
    }

    public function show($id)
    {
        $teacher = Teacher::with(['user', 'department'])->findOrFail($id);
        return response()->json($teacher);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'userId' => 'required|exists:User,id',
            'departmentId' => 'required|exists:Department,id',
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->update($validatedData);
        return response()->json($teacher);
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return response()->json(null, 204);
    }

    public function approveStudent($classRegistrationId, $studentId)
    {
        $classRegistration = ClassRegistration::find($classRegistrationId);

        if (!$classRegistration) {
            return response()->json(['error' => 'Không thể duyệt sinh viên này.'], 403);
        }

        $classStudent = new ClassStudent();
        $classStudent->studentId = $studentId;
        $classStudent->classId = $classRegistration->classId;
        $classStudent->score = 0;
        $classStudent->save();

        $classRegistration->delete();

        return response()->json(['message' => 'Duyệt sinh viên thành công.']);
    }
}
