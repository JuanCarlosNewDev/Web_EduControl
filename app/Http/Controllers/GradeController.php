<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $courses = Course::query()
            ->with('studyPlan')
            ->withCount('assessments')
            ->when(! $user->isAdmin(), fn ($query) => $query->where('teacher_id', $user->id))
            ->orderBy('name')
            ->get();

        return view('grades.index', compact('courses'));
    }

    public function show(Request $request, Course $course): View
    {
        $this->ensureCourseIsAccessible($request->user(), $course);

        $assessments = $course->assessments()->with('grades')->orderBy('assessment_date')->orderBy('id')->get();
        $students = $course->study_plan_id
            ? Student::where('study_plan_id', $course->study_plan_id)
                ->where('is_active', true)
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get()
            : collect();
        $averages = Grade::query()
            ->selectRaw('student_id, AVG(score) as average')
            ->whereIn('assessment_id', $assessments->modelKeys())
            ->groupBy('student_id')
            ->pluck('average', 'student_id');

        return view('grades.show', compact('course', 'assessments', 'students', 'averages'));
    }

    public function storeAssessment(Request $request, Course $course): RedirectResponse
    {
        $this->ensureCourseIsAccessible($request->user(), $course);
        $this->ensureCourseHasStudyPlan($course);

        $data = $request->validate([
            'title' => [
                'required',
                'string',
                'max:120',
                Rule::unique('assessments', 'title')->where('course_id', $course->id),
            ],
            'assessment_date' => ['required', 'date'],
        ]);
        $course->assessments()->create($data);

        return to_route('grades.show', $course)->with('success', 'Evaluación creada. Ya puedes registrar las notas.');
    }

    public function saveGrades(Request $request, Course $course): RedirectResponse
    {
        $this->ensureCourseIsAccessible($request->user(), $course);
        $this->ensureCourseHasStudyPlan($course);

        $data = $request->validate([
            'scores' => ['sometimes', 'array'],
            'scores.*' => ['array'],
            'scores.*.*' => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);
        $scores = $data['scores'] ?? [];
        $allowedAssessmentIds = $course->assessments()->pluck('id')->all();
        $allowedStudentIds = Student::where('study_plan_id', $course->study_plan_id)
            ->where('is_active', true)
            ->pluck('id')
            ->all();

        $submittedAssessmentIds = array_map('intval', array_keys($scores));
        if (array_diff($submittedAssessmentIds, $allowedAssessmentIds) !== []) {
            throw ValidationException::withMessages(['scores' => 'La evaluación no pertenece a este curso.']);
        }

        foreach ($scores as $assessmentId => $studentScores) {
            if (array_diff(array_map('intval', array_keys($studentScores)), $allowedStudentIds) !== []) {
                throw ValidationException::withMessages(['scores' => 'Solo puedes calificar estudiantes activos de este plan.']);
            }
        }

        DB::transaction(function () use ($scores): void {
            foreach ($scores as $assessmentId => $studentScores) {
                foreach ($studentScores as $studentId => $score) {
                    if ($score === null || $score === '') {
                        Grade::where('assessment_id', $assessmentId)->where('student_id', $studentId)->delete();
                    } else {
                        Grade::updateOrCreate(
                            ['assessment_id' => $assessmentId, 'student_id' => $studentId],
                            ['score' => $score],
                        );
                    }
                }
            }
        });

        return to_route('grades.show', $course)->with('success', 'Notas guardadas correctamente.');
    }

    public function destroyAssessment(Request $request, Assessment $assessment): RedirectResponse
    {
        $course = $assessment->course;
        $this->ensureCourseIsAccessible($request->user(), $course);
        $assessment->delete();

        return to_route('grades.show', $course)->with('success', 'Evaluación y sus notas eliminadas.');
    }

    private function ensureCourseIsAccessible(User $user, Course $course): void
    {
        if (! $user->isAdmin()) {
            abort_unless($course->teacher_id === $user->id, 403);
        }
    }

    private function ensureCourseHasStudyPlan(Course $course): void
    {
        abort_unless($course->study_plan_id, 422, 'Asigna primero un plan de estudio al curso.');
    }
}
