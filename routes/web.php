<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudyPlanController;
use App\Http\Controllers\SyllabusController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! Auth::check()) {
        return to_route('login');
    }

    return Auth::user()->isAdmin()
        ? to_route('dashboard')
        : to_route('attendances.index');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::redirect('/docente', '/asistencias')->name('teacher.home');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/panel', DashboardController::class)->name('dashboard');

    Route::get('/planes-estudio', [StudyPlanController::class, 'index'])->name('study-plans.index');
    Route::get('/planes-estudio/crear', [StudyPlanController::class, 'create'])->name('study-plans.create');
    Route::post('/planes-estudio', [StudyPlanController::class, 'store'])->name('study-plans.store');
    Route::get('/planes-estudio/{studyPlan}/editar', [StudyPlanController::class, 'edit'])->name('study-plans.edit');
    Route::put('/planes-estudio/{studyPlan}', [StudyPlanController::class, 'update'])->name('study-plans.update');
    Route::delete('/planes-estudio/{studyPlan}', [StudyPlanController::class, 'destroy'])->name('study-plans.destroy');

    Route::patch('/silabos/{syllabus}/publicacion', [SyllabusController::class, 'updatePublication'])->name('syllabi.publication');
    Route::delete('/silabos/{syllabus}', [SyllabusController::class, 'destroy'])->name('syllabi.destroy');

    Route::get('/horarios/crear', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/horarios', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/horarios/{schedule}/editar', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/horarios/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/horarios/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::get('/estudiantes', [StudentController::class, 'index'])->name('students.index');
    Route::get('/estudiantes/crear', [StudentController::class, 'create'])->name('students.create');
    Route::post('/estudiantes', [StudentController::class, 'store'])->name('students.store');
    Route::get('/estudiantes/{student}/editar', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/estudiantes/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/estudiantes/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/cursos', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/cursos/crear', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/cursos', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/cursos/{course}/editar', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/cursos/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/cursos/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/silabos', [SyllabusController::class, 'index'])->name('syllabi.index');
    Route::get('/silabos/crear', [SyllabusController::class, 'create'])->name('syllabi.create');
    Route::post('/silabos', [SyllabusController::class, 'store'])->name('syllabi.store');
    Route::get('/silabos/{syllabus}/descargar', [SyllabusController::class, 'download'])->name('syllabi.download');
    Route::get('/horarios', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/notas', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/notas/cursos/{course}', [GradeController::class, 'show'])->name('grades.show');
    Route::post('/notas/cursos/{course}/evaluaciones', [GradeController::class, 'storeAssessment'])->name('grades.assessments.store');
    Route::put('/notas/cursos/{course}/calificaciones', [GradeController::class, 'saveGrades'])->name('grades.save');
    Route::delete('/notas/evaluaciones/{assessment}', [GradeController::class, 'destroyAssessment'])->name('grades.assessments.destroy');

    Route::get('/asistencias', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/asistencias/crear', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/asistencias', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/asistencias/{attendance}/editar', [AttendanceController::class, 'edit'])->name('attendances.edit');
    Route::put('/asistencias/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');
    Route::delete('/asistencias/{attendance}', [AttendanceController::class, 'destroy'])->name('attendances.destroy');
});
