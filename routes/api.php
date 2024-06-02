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
    Route::apiResource('users', UserController::class);

    // start session
    Route::post('start-session/{sessionId}', [SessionController::class, 'startSession']);

    // get students in class
    Route::get('classes/{classId}/students', [ClassStudentController::class, 'studentsInClass']);
    // get class un-open with student's department
    Route::get('classes/unopened',  [ClassController::class, 'getUnopenedClasses']);
    // approve student to class
    Route::post('teachers/approve-student/{classRegistrationId}/{studentId}', [TeacherController::class, 'approveStudent']);
    // check status student in session
    Route::get('attendances/get-by-student-session', [AttendanceController::class, 'getByStudentAndSession']);

    // summary sesions of student in class
    Route::get('student/{studentId}/class/{classId}/count', [StudentController::class, 'sessionCount']);
    // summary students in session
    Route::get('sessions/{sessionId}/attendance/count', [SessionController::class, 'attendanceCount']);
    // get classes by teacher
    Route::get('classes/teacher', [ClassController::class, 'getClassByTeacher']);

    // get sesion by classId
    Route::get('sessions-class/{classId}', [SessionController::class, 'getSessionByClassId']);
});
