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
        $validatedData = $request->validate([
            'userName' => 'required|unique:Account',
            'password' => 'required',
            'role' => 'required',
        ]);

        $account = Account::create($validatedData);
        return response()->json($account, 201)->header('Content-Type', 'text/plain');
    }

    public function show($id)
    {
        $account = Account::findOrFail($id);
        return response()->json($account);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'userName' => 'required|unique:Account,userName,' . $id,
            'password' => 'required',
            'role' => 'required',
        ]);

        $account = Account::findOrFail($id);
        $account->update($validatedData);
        return response()->json($account);
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return response()->json(null, 204)->header('Content-Type', 'text/plain');
    }

    public function createAccount(Request $request,)
    {

        $currentAccount = auth()->user();

        if (!$currentAccount || $currentAccount->role !== 3) {
            return response()->json(['error' => 'Unauthorized'], 403)->header('Content-Type', 'text/plain');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:User',
            'gender' => 'required|string',
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:Account',
            'password' => 'required|string|min:8',
            'departmentId' => 'required|exists:Department,id',
            'role' => 'required|integer|in:1,2,3',
        ]);

        if (Account::where('userName', $validatedData['username'])->exists()) {
            return response()->json(['error' => 'Username already exists'], 400)->header('Content-Type', 'text/plain');
        }

        if (User::where('email', $validatedData['email'])->exists()) {
            return response()->json(['error' => 'Email already exists'], 400)->header('Content-Type', 'text/plain');
        }

        $account = Account::create([
            'userName' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
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

        return response()->json(['message' => 'Account created successfully'], 201)->header('Content-Type', 'text/plain');
    }
}
