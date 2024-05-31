<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassRegistrationController;
use App\Http\Controllers\ClassStudentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('users', UserController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('teachers', TeacherController::class);
Route::resource('students', StudentController::class);
Route::resource('classes', ClassController::class);
Route::resource('class-registrations', ClassRegistrationController::class);
Route::resource('class-students', ClassStudentController::class);
Route::resource('sessions', SessionController::class);
Route::resource('attendances', AttendanceController::class);
Route::resource('accounts', AccountController::class);
Route::resource('account-sessions', AccountSessionController::class);
