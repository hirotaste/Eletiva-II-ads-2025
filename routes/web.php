<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassroomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    \App\Services\DataService::initializeData();
    return view('dashboard');
})->name('dashboard');

// Teachers Routes
Route::get('/teachers', [TeacherController::class, 'webIndex'])->name('teachers.index');
Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
Route::post('/teachers', [TeacherController::class, 'webStore'])->name('teachers.store');
Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
Route::put('/teachers/{id}', [TeacherController::class, 'webUpdate'])->name('teachers.update');
Route::delete('/teachers/{id}', [TeacherController::class, 'webDestroy'])->name('teachers.destroy');

// Students Routes
Route::get('/students', [StudentController::class, 'webIndex'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'webStore'])->name('students.store');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{id}', [StudentController::class, 'webUpdate'])->name('students.update');
Route::delete('/students/{id}', [StudentController::class, 'webDestroy'])->name('students.destroy');

// Disciplines Routes
Route::get('/disciplines', [DisciplineController::class, 'webIndex'])->name('disciplines.index');
Route::get('/disciplines/create', [DisciplineController::class, 'create'])->name('disciplines.create');
Route::post('/disciplines', [DisciplineController::class, 'webStore'])->name('disciplines.store');
Route::get('/disciplines/{id}/edit', [DisciplineController::class, 'edit'])->name('disciplines.edit');
Route::put('/disciplines/{id}', [DisciplineController::class, 'webUpdate'])->name('disciplines.update');
Route::delete('/disciplines/{id}', [DisciplineController::class, 'webDestroy'])->name('disciplines.destroy');

// Classrooms Routes
Route::get('/classrooms', [ClassroomController::class, 'webIndex'])->name('classrooms.index');
Route::get('/classrooms/create', [ClassroomController::class, 'create'])->name('classrooms.create');
Route::post('/classrooms', [ClassroomController::class, 'webStore'])->name('classrooms.store');
Route::get('/classrooms/{id}/edit', [ClassroomController::class, 'edit'])->name('classrooms.edit');
Route::put('/classrooms/{id}', [ClassroomController::class, 'webUpdate'])->name('classrooms.update');
Route::delete('/classrooms/{id}', [ClassroomController::class, 'webDestroy'])->name('classrooms.destroy');
