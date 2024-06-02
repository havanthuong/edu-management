<?php

namespace App\Http\Controllers;

use App\Models\AccountSession;
use App\Models\Department;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('userName', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 400);
        }

        $account = Auth::user();
        AccountSession::create([
            'accountId' => $account->id,
        ]);

        return response()->json([
            'token' => $token,
            'role' => $account->role,
        ]);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['success' => 'User logged out successfully']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }
    }

    public function me()
    {
        $account = auth()->user();
        $user = User::where('accountId', $account->id)->first();
        $deparment = '';

        if ($account->role === 1) {
            $student = Student::where('userId', $user->id)->first();
            if ($student) {
                $deparment = Department::where('id', $student->departmentId)->first();
            } else {
                return response()->json(['error' => 'Student not found'], 404);
            }
        } else if ($account->role === 2) {
            $teacher = Teacher::where('userId', $user->id)->first();
            if ($teacher) {
                $deparment = Department::where('id', $teacher->departmentId)->first();
            } else {
                return response()->json(['error' => 'Teacher not found'], 404);
            }
        }

        return response()->json([
            'account' => Auth::user(),
            'deparment' => $deparment
        ]);

        return response()->json(Auth::user());
    }
}
