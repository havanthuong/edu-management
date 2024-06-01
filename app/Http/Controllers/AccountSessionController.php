<?php

namespace App\Http\Controllers;

use App\Models\AccountSession;
use Illuminate\Http\Request;

class AccountSessionController extends Controller
{
    public function index()
    {
        return AccountSession::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'accountId' => 'required|exists:Account,id',
        ]);

        $accountSession = AccountSession::create($validatedData);
        return response()->json($accountSession, 201)->header('Content-Type', 'text/plain');
    }

    public function show($id)
    {
        $accountSession = AccountSession::findOrFail($id);
        return response()->json($accountSession);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'accountId' => 'required|exists:Account,id',
        ]);

        $accountSession = AccountSession::findOrFail($id);
        $accountSession->update($validatedData);
        return response()->json($accountSession);
    }

    public function destroy($id)
    {
        $accountSession = AccountSession::findOrFail($id);
        $accountSession->delete();
        return response()->json(null, 204)->header('Content-Type', 'text/plain');
    }
}
