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
        return Teacher::all();
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
        $teacher = Teacher::findOrFail($id);
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
        $teacher = Auth::user(); // Lấy thông tin giáo viên đang đăng nhập
        $classRegistration = ClassRegistration::find($classRegistrationId);

        // Kiểm tra xem bản ghi ClassRegistration tồn tại và có thuộc lớp của giáo viên không
        if (!$classRegistration || $classRegistration->class->teacherId !== $teacher->id) {
            return response()->json(['error' => 'Không thể duyệt sinh viên này.'], 403);
        }

        // Thêm sinh viên vào bảng ClassStudent
        $classStudent = new ClassStudent();
        $classStudent->studentId = $studentId;
        $classStudent->classId = $classRegistration->classId;
        $classStudent->save();

        // Xoá bản ghi trong bảng ClassRegistration
        $classRegistration->delete();

        return response()->json(['message' => 'Duyệt sinh viên thành công.']);
    }
}
