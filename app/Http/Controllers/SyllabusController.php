<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudyPlan;
use App\Models\Syllabus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class SyllabusController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin();
        $studyPlans = StudyPlan::query()
            ->when(! $isAdmin, fn ($query) => $query->whereHas(
                'courses',
                fn ($query) => $query->where('teacher_id', $user->id)
            ))
            ->with(['syllabi' => fn ($query) => $query
                ->when(! $isAdmin, fn ($query) => $query->where(function ($query) use ($user) {
                    $query->where(function ($query) {
                        $query->whereNull('course_id')->where('is_published', true);
                    })->orWhere(function ($query) use ($user) {
                        $query->whereIn('course_id', $user->courses()->select('id'))
                            ->where('is_published', true);
                    })->orWhere(function ($query) use ($user) {
                        $query->where('uploaded_by_id', $user->id)
                            ->where('is_published', false);
                    });
                }))
                ->with(['course', 'uploadedBy'])
                ->latest()])
            ->orderBy('name')
            ->get();

        return view('syllabi.index', compact('studyPlans'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();

        return view('syllabi.form', [
            'studyPlans' => $user->isAdmin()
                ? StudyPlan::where('is_active', true)->orderBy('name')->get()
                : collect(),
            'courses' => $user->isAdmin()
                ? collect()
                : Course::with('studyPlan')
                    ->where('teacher_id', $user->id)
                    ->whereNotNull('study_plan_id')
                    ->orderBy('name')
                    ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $rules = [
            'title' => ['required', 'string', 'max:150'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ];

        if ($user->isAdmin()) {
            $rules['study_plan_id'] = ['required', 'integer', 'exists:study_plans,id'];
            $rules['is_published'] = ['nullable', 'boolean'];
        } else {
            $rules['course_id'] = ['required', 'integer', 'exists:courses,id'];
        }

        $data = $request->validate($rules);
        $course = null;
        if (! $user->isAdmin()) {
            $course = Course::whereKey($data['course_id'])
                ->where('teacher_id', $user->id)
                ->whereNotNull('study_plan_id')
                ->first();
            abort_unless($course, 403);
        }

        $file = $request->file('file');
        $path = $file->store('syllabi', 'local');
        if (! $path) {
            throw new RuntimeException('No se pudo guardar el archivo del sílabo.');
        }

        $isPublished = $user->isAdmin() && (bool) ($data['is_published'] ?? false);
        Syllabus::create([
            'study_plan_id' => $course?->study_plan_id ?? $data['study_plan_id'],
            'course_id' => $course?->id,
            'uploaded_by_id' => $user->isAdmin() ? null : $user->id,
            'title' => $data['title'],
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
            'is_published' => $isPublished,
            'published_at' => $isPublished ? now() : null,
        ]);

        return to_route('syllabi.index')->with(
            'success',
            $user->isAdmin()
                ? 'Sílabo PDF cargado correctamente.'
                : 'Sílabo cargado y enviado al administrador para su aprobación.'
        );
    }

    public function updatePublication(Request $request, Syllabus $syllabus): RedirectResponse
    {
        $data = $request->validate([
            'is_published' => ['required', 'boolean'],
        ]);

        $isPublished = (bool) $data['is_published'];
        $syllabus->update([
            'is_published' => $isPublished,
            'published_at' => $isPublished ? ($syllabus->published_at ?? now()) : null,
        ]);

        return to_route('syllabi.index')->with(
            'success',
            $isPublished ? 'Sílabo publicado para los docentes del plan.' : 'Publicación del sílabo retirada.'
        );
    }

    public function download(Request $request, Syllabus $syllabus)
    {
        $user = $request->user();
        if (! $user->isAdmin()) {
            $canDownloadPublished = $syllabus->is_published && (
                $syllabus->course_id
                    ? $syllabus->course()->where('teacher_id', $user->id)->exists()
                    : $syllabus->studyPlan()->whereHas('courses', fn ($query) => $query->where('teacher_id', $user->id))->exists()
            );
            $canDownloadOwnDraft = ! $syllabus->is_published && $syllabus->uploaded_by_id === $user->id;

            abort_unless($canDownloadPublished || $canDownloadOwnDraft, 403);
        }

        abort_unless(Storage::disk('local')->exists($syllabus->path), 404);

        return Storage::disk('local')->download($syllabus->path, $syllabus->original_filename);
    }

    public function destroy(Syllabus $syllabus): RedirectResponse
    {
        if (Storage::disk('local')->exists($syllabus->path)
            && ! Storage::disk('local')->delete($syllabus->path)) {
            throw new RuntimeException('No se pudo eliminar el archivo del sílabo.');
        }

        $syllabus->delete();

        return to_route('syllabi.index')->with('success', 'Sílabo eliminado.');
    }
}
