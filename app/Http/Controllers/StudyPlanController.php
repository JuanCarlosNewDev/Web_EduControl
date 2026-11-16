<?php

namespace App\Http\Controllers;

use App\Models\StudyPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudyPlanController extends Controller
{
    public function index(): View
    {
        return view('study-plans.index', [
            'studyPlans' => StudyPlan::withCount(['students', 'courses', 'syllabi'])
                ->orderBy('name')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('study-plans.form', ['studyPlan' => new StudyPlan]);
    }

    public function store(Request $request): RedirectResponse
    {
        StudyPlan::create($this->validatedData($request));

        return to_route('study-plans.index')->with('success', 'Plan de estudio creado correctamente.');
    }

    public function edit(StudyPlan $studyPlan): View
    {
        return view('study-plans.form', compact('studyPlan'));
    }

    public function update(Request $request, StudyPlan $studyPlan): RedirectResponse
    {
        $studyPlan->update($this->validatedData($request, $studyPlan));

        return to_route('study-plans.index')->with('success', 'Plan de estudio actualizado.');
    }

    public function destroy(StudyPlan $studyPlan): RedirectResponse
    {
        if ($studyPlan->students()->exists() || $studyPlan->courses()->exists() || $studyPlan->syllabi()->exists()) {
            return to_route('study-plans.index')
                ->with('error', 'No se puede eliminar un plan que tiene estudiantes, cursos o sílabos asociados.');
        }

        $studyPlan->delete();

        return to_route('study-plans.index')->with('success', 'Plan de estudio eliminado.');
    }

    private function validatedData(Request $request, ?StudyPlan $studyPlan = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:40', Rule::unique('study_plans')->ignore($studyPlan)],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
