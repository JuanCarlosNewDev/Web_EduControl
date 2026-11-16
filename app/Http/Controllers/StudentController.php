<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudyPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $search = trim((string) ($filters['q'] ?? ''));
        $students = Student::with('studyPlan')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('student_code', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('program', 'like', "%{$search}%");
            }))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', compact('students', 'search'));
    }

    public function create(): View
    {
        return view('students.form', [
            'student' => new Student,
            'studyPlans' => StudyPlan::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Student::create($this->validatedData($request));

        return to_route('students.index')->with('success', 'Estudiante registrado correctamente.');
    }

    public function edit(Student $student): View
    {
        return view('students.form', [
            'student' => $student,
            'studyPlans' => StudyPlan::query()
                ->where('is_active', true)
                ->orWhereKey($student->study_plan_id)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $student->update($this->validatedData($request, $student));

        return to_route('students.index')->with('success', 'Los datos del estudiante fueron actualizados.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return to_route('students.index')->with('success', 'Estudiante eliminado.');
    }

    private function validatedData(Request $request, ?Student $student = null): array
    {
        $data = $request->validate([
            'student_code' => ['required', 'string', 'max:30', Rule::unique('students')->ignore($student)],
            'document_number' => ['required', 'string', 'max:20', Rule::unique('students')->ignore($student)],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'study_plan_id' => ['required', 'integer', 'exists:study_plans,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        $data['program'] = StudyPlan::findOrFail($data['study_plan_id'])->name;

        return $data;
    }
}
