<?php

namespace App\Http\Controllers;

use App\Models\ClassStudent;
use Illuminate\Http\Request;

class ClassStudentController extends Controller
{
    public function index()
    {
        return ClassStudent::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'studentId' => 'required|exists:Student,id',
            'classId' => 'required|exists:Class,id',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $classStudent = ClassStudent::create($validatedData);
        return response()->json($classStudent, 201)->header('Content-Type', 'text/plain');
    }

    public function show($id)
    {
        $classStudent = ClassStudent::findOrFail($id);
        return response()->json($classStudent);
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'score' => 'required',
        ]);

        $classStudent = ClassStudent::findOrFail($id);
        $classStudent->update($validatedData);
        return response()->json($classStudent);
    }

    public function destroy($id)
    {
        $classStudent = ClassStudent::findOrFail($id);
        $classStudent->delete();
        return response()->json(null, 204)->header('Content-Type', 'text/plain');
    }

    public function studentsInClass($classId)
    {
        $students = ClassStudent::where('classId', $classId)
            ->with('student')
            ->get();

        return response()->json($students);
    }
}
