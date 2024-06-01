<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

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
        return response()->json($account, 201);
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
        return response()->json(null, 204);
    }
}
