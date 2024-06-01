<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $department = Department::create($validatedData);
        return response()->json($department, 201)->header('Content-Type', 'text/plain');
    }

    public function show($id)
    {
        $department = Department::findOrFail($id);
        return response()->json($department);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $department = Department::findOrFail($id);
        $department->update($validatedData);
        return response()->json($department);
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return response()->json(null, 204)->header('Content-Type', 'text/plain');
    }
}
