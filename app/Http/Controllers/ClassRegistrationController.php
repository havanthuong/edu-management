<?php

namespace App\Http\Controllers;

use App\Models\ClassRegistration;
use Illuminate\Http\Request;

class ClassRegistrationController extends Controller
{
    public function index()
    {
        return ClassRegistration::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'studentId' => 'required|exists:Student,id',
            'classId' => 'required|exists:Class,id',
        ]);

        $registration = ClassRegistration::create($validatedData);
        return response()->json($registration, 201)->header('Content-Type', 'text/plain');
    }

    public function show($id)
    {
        $registration = ClassRegistration::findOrFail($id);
        return response()->json($registration);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'studentId' => 'required|exists:Student,id',
            'classId' => 'required|exists:Class,id',
        ]);

        $registration = ClassRegistration::findOrFail($id);
        $registration->update($validatedData);
        return response()->json($registration);
    }

    public function destroy($id)
    {
        $registration = ClassRegistration::findOrFail($id);
        $registration->delete();
        return response()->json(null, 204)->header('Content-Type', 'text/plain');
    }
}
