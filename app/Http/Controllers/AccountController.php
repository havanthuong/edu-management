<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        return Account::all();
    }

    public function store(Request $request)
    {
        $currentAccount = auth()->user();

        if (!$currentAccount || $currentAccount->role !== 3) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'gender' => 'required|string',
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:Account',
            'password' => 'required|string|min:8',
            'departmentId' => 'required|exists:Department,id',
            'role' => 'required|integer|in:1,2,3',
        ]);

        if (Account::where('userName', $validatedData['username'])->exists()) {
            return response()->json(['error' => 'Username already exists'], 400);
        }

        if (User::where('email', $validatedData['email'])->exists()) {
            return response()->json(['error' => 'Email already exists'], 400);
        }

        $account = Account::create([
            'userName' => $validatedData['username'],
            'password' => $validatedData['password'],
            'role' => $validatedData['role'],
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'accountId' => $account->id,
        ]);

        if ($validatedData['role'] == 1) {
            Student::create([
                'userId' => $user->id,
                'departmentId' => $validatedData['departmentId'],
            ]);
        } elseif ($validatedData['role'] == 2) {
            Teacher::create([
                'userId' => $user->id,
                'departmentId' => $validatedData['departmentId'],
            ]);
        }

        return response()->json(['message' => 'Account created successfully'], 201);
    }

    public function show($id)
    {
        $account = Account::findOrFail($id);
        return response()->json($account);
    }

    public function update(Request $request, $id)
    {
        $currentAccount = auth()->user();

        if (!$currentAccount || $currentAccount->role === 1) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'address' => 'required|string|max:255',
            'departmentId' => 'required|exists:Department,id',
            'isStudent' => 'required|integer|in:0,1',
        ]);

        $account = Account::findOrFail($id);
        $user = User::where('accountId', $account->id)->firstOrFail();

        $user->update([
            'name' => $validatedData['name'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
        ]);

        if ($validatedData['isStudent'] === 1) {
            $student = Student::where('userId', $user->id)->first();
            if ($student) {
                $student->update(['departmentId' => $validatedData['departmentId']]);
            } else {
                return response()->json(['error' => 'Student not found'], 404);
            }
        } else {
            $teacher = Teacher::where('userId', $user->id)->first();
            if ($teacher) {
                $teacher->update(['departmentId' => $validatedData['departmentId']]);
            } else {
                return response()->json(['error' => 'Teacher not found'], 404);
            }
        }

        return response()->json(['message' => 'Account updated successfully'], 200);
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return response()->json(null, 204);
    }

    // public function updateProfile(Request $request)
    // {
    //     $currentAccount = auth()->user();

    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'current_password' => 'required|string|min:8',
    //         'new_password' => 'nullable|string|min:8|confirmed',
    //     ]);

    //     if (!Hash::check($validatedData['current_password'], $currentAccount->password)) {
    //         return response()->json(['error' => 'Current password is incorrect'], 400);
    //     }

    //     $currentAccount->name = $validatedData['name'];

    //     if (!empty($validatedData['new_password'])) {
    //         $currentAccount->password = Hash::make($validatedData['new_password']);
    //     }

    //     $currentAccount->save();

    //     return response()->json(['message' => 'Profile updated successfully'], 200);
    // }
}
