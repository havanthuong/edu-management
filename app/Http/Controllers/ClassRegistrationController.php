<?php

namespace App\Http\Controllers;

use App\Models\ClassRegistration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class ClassRegistrationController extends Controller
{
    public function index()
    {
        $registers = ClassRegistration::with(['student.user', 'class'])->get();
        return response()->json($registers, 200);
    }

    public function store(Request $request)
    {

        $currentAccount = auth()->user();

        if (!$currentAccount || $currentAccount->role !== 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::where('accountId', $currentAccount->id)->first();
        $student = Student::where('userId', $user->id)->first();

        $validatedData = $request->validate([
            'classId' => 'required|exists:Class,id',
        ]);

        $registerData = array_merge($validatedData, [
            'studentId' => $student->id
        ]);

        $registration = ClassRegistration::create($registerData);
        return response()->json($registration, 201);
    }

    public function show($id)
    {
        $registration = ClassRegistration::with(['student.user', 'class'])->findOrFail($id);
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
        return response()->json(null, 204);
    }
}
