<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        return Session::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'classId' => 'required|exists:classes,id',
            'sessionDate' => 'required|date',
            'sessionLocation' => 'required',
        ]);

        $class = ClassModel::findOrFail($validatedData['classId']);

        if ($class->status !== 'đã duyệt') {
            return response()->json(['error' => 'Không thể tạo session cho lớp học chưa được duyệt.'], 403)->header('Content-Type', 'text/plain');
        }

        $session = Session::create($validatedData);
        return response()->json($session, 201);
    }

    public function show($id)
    {
        $session = Session::findOrFail($id);
        return response()->json($session);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'classId' => 'required|exists:classes,id',
            'sessionDate' => 'required|date',
            'sessionLocation' => 'required',
        ]);

        $session = Session::findOrFail($id);
        $session->update($validatedData);
        return response()->json($session);
    }

    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();
        return response()->json(null, 204);
    }

    public function attendanceCount($sessionId)
    {
        $session = Session::findOrFail($sessionId);

        // Đếm số lượng attendance cho session
        $attendanceCount = Attendance::where('sessionId', $session->id)->count();

        return response()->json(['attendance_count' => $attendanceCount]);
    }
}
