<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\PeriodoLetivoController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Middleware\NivelAdmMiddleware;
use App\Http\Middleware\NivelProfessorMiddleware;
use App\Http\Middleware\NivelEstudanteMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Email Verification Routes
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard - redirects to level-specific dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Only Routes
    Route::middleware(NivelAdmMiddleware::class)->group(function () {
        // Teachers Management (Admin Only)
        Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
        Route::post('/teachers', [TeacherController::class, 'webStore'])->name('teachers.store');
        Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
        Route::put('/teachers/{id}', [TeacherController::class, 'webUpdate'])->name('teachers.update');
        Route::delete('/teachers/{id}', [TeacherController::class, 'webDestroy'])->name('teachers.destroy');

        // Students Management (Admin Only)
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'webStore'])->name('students.store');
        Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{id}', [StudentController::class, 'webUpdate'])->name('students.update');
        Route::delete('/students/{id}', [StudentController::class, 'webDestroy'])->name('students.destroy');

        // Disciplines Management (Admin Only)
        Route::get('/disciplines/create', [DisciplineController::class, 'create'])->name('disciplines.create');
        Route::post('/disciplines', [DisciplineController::class, 'webStore'])->name('disciplines.store');
        Route::get('/disciplines/{id}/edit', [DisciplineController::class, 'edit'])->name('disciplines.edit');
        Route::put('/disciplines/{id}', [DisciplineController::class, 'webUpdate'])->name('disciplines.update');
        Route::delete('/disciplines/{id}', [DisciplineController::class, 'webDestroy'])->name('disciplines.destroy');

        // Classrooms Management (Admin Only)
        Route::get('/classrooms/create', [ClassroomController::class, 'create'])->name('classrooms.create');
        Route::post('/classrooms', [ClassroomController::class, 'webStore'])->name('classrooms.store');
        Route::get('/classrooms/{id}/edit', [ClassroomController::class, 'edit'])->name('classrooms.edit');
        Route::put('/classrooms/{id}', [ClassroomController::class, 'webUpdate'])->name('classrooms.update');
        Route::delete('/classrooms/{id}', [ClassroomController::class, 'webDestroy'])->name('classrooms.destroy');

        // Cursos Management (Admin Only)
        Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create');
        Route::post('/cursos', [CursoController::class, 'webStore'])->name('cursos.store');
        Route::get('/cursos/{id}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
        Route::put('/cursos/{id}', [CursoController::class, 'webUpdate'])->name('cursos.update');
        Route::delete('/cursos/{id}', [CursoController::class, 'webDestroy'])->name('cursos.destroy');

        // Salas Management (Admin Only)
        Route::get('/salas/create', [SalaController::class, 'create'])->name('salas.create');
        Route::post('/salas', [SalaController::class, 'webStore'])->name('salas.store');
        Route::get('/salas/{id}/edit', [SalaController::class, 'edit'])->name('salas.edit');
        Route::put('/salas/{id}', [SalaController::class, 'webUpdate'])->name('salas.update');
        Route::delete('/salas/{id}', [SalaController::class, 'webDestroy'])->name('salas.destroy');

        // Períodos Letivos Management (Admin Only)
        Route::get('/periodos-letivos/create', [PeriodoLetivoController::class, 'create'])->name('periodos-letivos.create');
        Route::post('/periodos-letivos', [PeriodoLetivoController::class, 'webStore'])->name('periodos-letivos.store');
        Route::get('/periodos-letivos/{id}/edit', [PeriodoLetivoController::class, 'edit'])->name('periodos-letivos.edit');
        Route::put('/periodos-letivos/{id}', [PeriodoLetivoController::class, 'webUpdate'])->name('periodos-letivos.update');
        Route::delete('/periodos-letivos/{id}', [PeriodoLetivoController::class, 'webDestroy'])->name('periodos-letivos.destroy');

        // Turmas Management (Admin Only)
        Route::get('/turmas/create', [TurmaController::class, 'create'])->name('turmas.create');
        Route::post('/turmas', [TurmaController::class, 'webStore'])->name('turmas.store');
        Route::get('/turmas/{id}/edit', [TurmaController::class, 'edit'])->name('turmas.edit');
        Route::put('/turmas/{id}', [TurmaController::class, 'webUpdate'])->name('turmas.update');
        Route::delete('/turmas/{id}', [TurmaController::class, 'webDestroy'])->name('turmas.destroy');

        // Matrículas Management (Admin Only)
        Route::get('/matriculas/create', [MatriculaController::class, 'create'])->name('matriculas.create');
        Route::post('/matriculas', [MatriculaController::class, 'webStore'])->name('matriculas.store');
        Route::get('/matriculas/{id}/edit', [MatriculaController::class, 'edit'])->name('matriculas.edit');
        Route::put('/matriculas/{id}', [MatriculaController::class, 'webUpdate'])->name('matriculas.update');
        Route::delete('/matriculas/{id}', [MatriculaController::class, 'webDestroy'])->name('matriculas.destroy');
    });

    // Professor and Admin Routes
    Route::middleware(NivelProfessorMiddleware::class)->group(function () {
        // Professors can view all lists but not create/edit/delete
        Route::get('/teachers', [TeacherController::class, 'webIndex'])->name('teachers.index');
        Route::get('/students', [StudentController::class, 'webIndex'])->name('students.index');
        Route::get('/disciplines', [DisciplineController::class, 'webIndex'])->name('disciplines.index');
        Route::get('/classrooms', [ClassroomController::class, 'webIndex'])->name('classrooms.index');
        Route::get('/cursos', [CursoController::class, 'webIndex'])->name('cursos.index');
        Route::get('/salas', [SalaController::class, 'webIndex'])->name('salas.index');
        Route::get('/periodos-letivos', [PeriodoLetivoController::class, 'webIndex'])->name('periodos-letivos.index');
        Route::get('/turmas', [TurmaController::class, 'webIndex'])->name('turmas.index');
        Route::get('/matriculas', [MatriculaController::class, 'webIndex'])->name('matriculas.index');
    });

    // Student Routes - Can only view
    Route::middleware(NivelEstudanteMiddleware::class)->group(function () {
        // Students can view all lists in read-only mode
        // The middleware already allows access, controllers should handle read-only logic
    });
});
