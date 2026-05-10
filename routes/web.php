<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dosen\ExamController;
use App\Http\Controllers\Dosen\SubjectController;
use App\Http\Controllers\Dosen\ResultController;
use App\Http\Controllers\Dosen\StudentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Mahasiswa\CourseController;

Route::view('/', 'welcome')->name('home');
Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/subjects', [AdminController::class, 'subjectsIndex'])->name('subjects.index');
    Route::get('/subjects/{subject}/assign', [AdminController::class, 'assignTutor'])->name('subjects.assign');
    Route::put('/subjects/{subject}/update-tutor', [AdminController::class, 'updateTutor'])->name('subjects.update-tutor');
    Route::get('/subjects/create', [AdminController::class, 'createSubject'])->name('subjects.create');
    Route::post('/subjects/store', [AdminController::class, 'storeSubject'])->name('subjects.store');
});

Route::middleware(['auth', 'dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create/{subject}', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams/store', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
    Route::delete('/exams/{exam}', [ExamController::class, 'destroy'])->name('exams.destroy');
    Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('exams.edit');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('exams.update');
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results/{exam}', [ResultController::class, 'show'])->name('results.show');
    Route::get('/results/{exam}/student/{student}', [ResultController::class, 'showStudentAnswers'])->name('results.student');
    Route::post('/results/{exam}/student/{student}/publish', [ResultController::class, 'publishScore'])->name('results.publish');
});

Route::middleware(['auth', 'mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/subjects', [CourseController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/available', [CourseController::class, 'available'])->name('subjects.available');
    Route::post('/subjects/{subject}/join', [CourseController::class, 'join'])->name('subjects.join');
    Route::get('/subjects/{subject}/exams', [CourseController::class, 'exams'])->name('subjects.exams');
    Route::get('/exams/{exam}/take', [CourseController::class, 'takeExam'])->name('exams.take');
    Route::post('/exams/{exam}/submit', [CourseController::class, 'submitExam'])->name('exams.submit');
    Route::get('/exams/{exam}/result', [CourseController::class, 'examResult'])->name('exams.result');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
