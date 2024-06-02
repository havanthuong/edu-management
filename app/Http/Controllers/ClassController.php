<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index()
    {
        return ClassModel::all();
    }

    public function store(Request $request)
    {

        $currentAccount = auth()->user();

        if (!$currentAccount || $currentAccount->role !== 2) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $teacher = Teacher::where('userId', $currentAccount->id)->first();

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
            'status' => 'Unpprove'
        ]);

        $class = ClassModel::create($classData);
        return response()->json($class, 201);
    }

    public function show($id)
    {
        $class = ClassModel::findOrFail($id);
        return response()->json($class);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required',
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
        $student = Auth::user();
        $departmentId = $student->departmentId;

        $unopenedClasses = ClassModel::where('status', 'Unopen')
            ->where('departmentId', $departmentId)
            ->get();

        return response()->json($unopenedClasses);
    }
}
