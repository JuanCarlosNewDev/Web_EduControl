<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $schedules = CourseSchedule::with(['course.teacher', 'course.studyPlan'])
            ->when(! $user->isAdmin(), fn ($query) => $query->whereHas(
                'course',
                fn ($query) => $query->where('teacher_id', $user->id)
            ))
            ->orderBy('day_of_week')
            ->orderBy('starts_at')
            ->get();

        return view('schedules.index', [
            'schedules' => $schedules,
            'days' => CourseSchedule::DAYS,
        ]);
    }

    public function create(): View
    {
        return view('schedules.form', [
            'schedule' => new CourseSchedule,
            'courses' => Course::with('teacher')->orderBy('name')->get(),
            'days' => CourseSchedule::DAYS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->ensureNoConflict($data);
        CourseSchedule::create($data);

        return to_route('schedules.index')->with('success', 'Horario semanal registrado.');
    }

    public function edit(CourseSchedule $schedule): View
    {
        return view('schedules.form', [
            'schedule' => $schedule,
            'courses' => Course::with('teacher')->orderBy('name')->get(),
            'days' => CourseSchedule::DAYS,
        ]);
    }

    public function update(Request $request, CourseSchedule $schedule): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->ensureNoConflict($data, $schedule);
        $schedule->update($data);

        return to_route('schedules.index')->with('success', 'Horario semanal actualizado.');
    }

    public function destroy(CourseSchedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return to_route('schedules.index')->with('success', 'Sesión de horario eliminada.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'day_of_week' => ['required', 'integer', 'between:1,7'],
            'starts_at' => ['required', 'date_format:H:i'],
            'ends_at' => ['required', 'date_format:H:i', 'after:starts_at'],
            'classroom' => ['nullable', 'string', 'max:80'],
        ]);
    }

    private function ensureNoConflict(array $data, ?CourseSchedule $schedule = null): void
    {
        $course = Course::findOrFail($data['course_id']);
        if (! $course->teacher_id) {
            return;
        }

        $conflict = CourseSchedule::query()
            ->when($schedule, fn ($query) => $query->where('id', '!=', $schedule->id))
            ->where('day_of_week', $data['day_of_week'])
            ->where('starts_at', '<', $data['ends_at'])
            ->where('ends_at', '>', $data['starts_at'])
            ->whereHas('course', fn ($query) => $query->where('teacher_id', $course->teacher_id))
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'starts_at' => 'El docente ya tiene otro curso en ese horario.',
            ]);
        }
    }
}
