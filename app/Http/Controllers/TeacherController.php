<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

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
}
