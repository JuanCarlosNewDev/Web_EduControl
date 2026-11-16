<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'status' => ['nullable', Rule::in(array_keys(Attendance::STATUSES))],
            'date' => ['nullable', 'date'],
        ]);
        $user = $request->user();
        $attendances = Attendance::with(['student', 'course'])
            ->when(! $user->isAdmin(), fn ($query) => $query->whereHas(
                'course',
                fn ($query) => $query->where('teacher_id', $user->id)
            ))
            ->when(($filters['q'] ?? '') !== '', fn ($query) => $query->whereHas(
                'student',
                fn ($query) => $query->where('first_name', 'like', '%'.$filters['q'].'%')
                    ->orWhere('last_name', 'like', '%'.$filters['q'].'%')
                    ->orWhere('student_code', 'like', '%'.$filters['q'].'%')
            ))
            ->when(isset($filters['course_id']), fn ($query) => $query->where('course_id', $filters['course_id']))
            ->when(isset($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->when(isset($filters['date']), fn ($query) => $query->whereDate('attendance_date', $filters['date']))
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('attendances.index', [
            'attendances' => $attendances,
            'courses' => $this->availableCourses($user),
            'statuses' => Attendance::STATUSES,
        ]);
    }

    public function create(Request $request): View
    {
        return $this->formView($request, new Attendance);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->ensureCourseIsAssigned($request, (int) $data['course_id']);
        $data['marked_by'] = $request->user()->id;
        Attendance::create($data);

        return to_route('attendances.index')->with('success', 'Asistencia registrada correctamente.');
    }

    public function edit(Request $request, Attendance $attendance): View
    {
        $this->ensureAttendanceIsAccessible($request, $attendance);

        return $this->formView($request, $attendance);
    }

    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        $this->ensureAttendanceIsAccessible($request, $attendance);
        $data = $this->validatedData($request, $attendance);
        $this->ensureCourseIsAssigned($request, (int) $data['course_id']);
        $attendance->update($data);

        return to_route('attendances.index')->with('success', 'Asistencia actualizada correctamente.');
    }

    public function destroy(Request $request, Attendance $attendance): RedirectResponse
    {
        $this->ensureAttendanceIsAccessible($request, $attendance);
        $attendance->delete();

        return to_route('attendances.index')->with('success', 'Registro de asistencia eliminado.');
    }

    private function formView(Request $request, Attendance $attendance): View
    {
        $user = $request->user();
        $courses = $this->availableCourses($user);

        return view('attendances.form', [
            'attendance' => $attendance,
            'courses' => $courses,
            'students' => Student::where('is_active', true)->orderBy('last_name')->orderBy('first_name')->get(),
            'statuses' => Attendance::STATUSES,
        ]);
    }

    private function availableCourses(User $user): Collection
    {
        return Course::query()
            ->when(! $user->isAdmin(), fn ($query) => $query->where('teacher_id', $user->id))
            ->orderBy('name')
            ->get();
    }

    private function validatedData(Request $request, ?Attendance $attendance = null): array
    {
        $uniqueAttendance = Rule::unique('attendances', 'student_id')
            ->where(fn ($query) => $query
                ->where('course_id', $request->input('course_id'))
                ->whereDate('attendance_date', $request->input('attendance_date')));
        if ($attendance) {
            $uniqueAttendance->ignore($attendance);
        }

        return $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'attendance_date' => ['required', 'date', 'before_or_equal:today'],
            'status' => ['required', Rule::in(array_keys(Attendance::STATUSES))],
            'notes' => ['nullable', 'string', 'max:1000'],
            'student_id' => ['required', 'exists:students,id', $uniqueAttendance],
        ], [
            'student_id.unique' => 'Ya existe una asistencia para este estudiante, curso y fecha.',
        ]);
    }

    private function ensureCourseIsAssigned(Request $request, int $courseId): void
    {
        if (! $request->user()->isAdmin()) {
            abort_unless(
                Course::whereKey($courseId)->where('teacher_id', $request->user()->id)->exists(),
                403
            );
        }
    }

    private function ensureAttendanceIsAccessible(Request $request, Attendance $attendance): void
    {
        if (! $request->user()->isAdmin()) {
            abort_unless($attendance->course()->where('teacher_id', $request->user()->id)->exists(), 403);
        }
    }
}
