<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudyPlan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $search = trim((string) ($filters['q'] ?? ''));
        $courses = Course::with(['teacher', 'studyPlan', 'schedules'])
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('term', 'like', "%{$search}%")
                    ->orWhere('section', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('courses.index', compact('courses', 'search'));
    }

    public function create(): View
    {
        return view('courses.form', [
            'course' => new Course,
            'teachers' => User::where('role', User::ROLE_TEACHER)->orderBy('name')->get(),
            'studyPlans' => StudyPlan::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Course::create($this->validatedData($request));

        return to_route('courses.index')->with('success', 'Curso creado correctamente.');
    }

    public function edit(Course $course): View
    {
        return view('courses.form', [
            'course' => $course,
            'teachers' => User::where('role', User::ROLE_TEACHER)->orderBy('name')->get(),
            'studyPlans' => StudyPlan::query()
                ->where('is_active', true)
                ->orWhereKey($course->study_plan_id)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $course->update($this->validatedData($request, $course));

        return to_route('courses.index')->with('success', 'Curso actualizado correctamente.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->syllabi()->exists()) {
            return to_route('courses.index')
                ->with('error', 'Elimina primero los sílabos asociados antes de eliminar este curso.');
        }

        $course->delete();

        return to_route('courses.index')->with('success', 'Curso eliminado.');
    }

    private function validatedData(Request $request, ?Course $course = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:30', Rule::unique('courses')->ignore($course)],
            'name' => ['required', 'string', 'max:150'],
            'section' => ['required', 'string', 'max:20'],
            'term' => ['required', 'string', 'max:40'],
            'study_plan_id' => ['required', 'integer', 'exists:study_plans,id'],
            'teacher_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', User::ROLE_TEACHER),
            ],
        ]);
    }
}
