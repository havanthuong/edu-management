<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        return ClassModel::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'teacherId' => 'required|exists:teachers,id',
            'courseName' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'status' => 'required',
            'numberOfSession' => 'required|integer|min:1',
            'departmentId' => 'required|exists:departments,id',
        ]);

        $class = ClassModel::create($validatedData);
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
            'name' => 'required',
            'teacherId' => 'required|exists:teachers,id',
            'courseName' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'status' => 'required',
            'numberOfSession' => 'required|integer|min:1',
            'departmentId' => 'required|exists:departments,id',
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
}
