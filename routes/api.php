<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassRegistrationController;
use App\Http\Controllers\ClassStudentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth.jwt');
Route::get('me', [AuthController::class, 'me'])->middleware('auth.jwt');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('account-sessions', AccountSessionController::class);
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('classes', ClassController::class);
    Route::apiResource('class-registrations', ClassRegistrationController::class);
    Route::apiResource('class-students', ClassStudentController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('sessions', SessionController::class);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('users', TeacherController::class);
});

// create account
Route::get('/create-account', [AccountController::class, 'createAccount'])->middleware('auth.jwt');
// start session
Route::get('/start-session', [SessionController::class, 'startSession'])->middleware('auth.jwt');

// get students in class
Route::get('/classes/{classId}/students', [ClassStudentController::class, 'studentsInClass'])->middleware('auth.jwt');
// get class un-open with student's department
Route::get('/classes/unopened',  [ClassController::class, 'getUnopenedClasses'])->middleware('auth.jwt');
// approve student to class
Route::post('/teachers/approve-student/{classRegistrationId}/{studentId}', [TeacherController::class, 'approveStudent'])->middleware('auth.jwt');
// check status student in session
Route::post('/attendances/get-by-student-session', [AttendanceController::class, 'getByStudentAndSession'])->middleware('auth.jwt');

// summary
// summary sesions of student in class
Route::get('/student/{studentId}/class/{classId}/count', [StudentController::class, 'sessionCount'])->middleware('auth.jwt');
// summary students in session
Route::get('/sessions/{sessionId}/attendance/count', [SessionController::class, 'attendanceCount'])->middleware('auth.jwt');
