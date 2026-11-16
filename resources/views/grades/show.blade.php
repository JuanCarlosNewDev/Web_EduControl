@extends('layout')

@section('title', 'Notas · '.$course->name)
@section('breadcrumb', 'Notas / '.$course->code)

@section('content')
<div class="page-heading"><div><span class="overline">SEGUIMIENTO ACADÉMICO / {{ $course->code }}</span><h1>{{ $course->name }}</h1><p>{{ $course->studyPlan?->name ?: 'Sin plan de estudio' }} · {{ $course->term }} · notas de 0 a 20</p></div><a class="button button-light" href="{{ route('grades.index') }}">← Volver a cursos</a></div>
@if (! $course->study_plan_id)
    <div class="alert alert-error">Este curso aún no tiene plan asignado. Administración debe asignarlo antes de registrar notas.</div>
@else
    <section class="panel-card form-card grade-assessment-form">
        <form method="POST" action="{{ route('grades.assessments.store', $course) }}" class="entity-form">
            @csrf
            <div class="form-section"><span class="overline">EVALUACIONES</span><h2>Agregar evaluación</h2><p>El promedio se calcula con las notas registradas; una nota en blanco no cuenta.</p></div>
            <div class="form-grid">
                <label>Nombre de evaluación *<input name="title" value="{{ old('title') }}" maxlength="120" required placeholder="Práctica calificada 1">@error('title')<span class="field-error">{{ $message }}</span>@enderror</label>
                <label>Fecha *<input type="date" name="assessment_date" value="{{ old('assessment_date', today()->toDateString()) }}" required>@error('assessment_date')<span class="field-error">{{ $message }}</span>@enderror</label>
            </div>
            <div class="form-actions"><button class="button button-primary" type="submit">Crear evaluación</button></div>
        </form>
    </section>

    @if ($assessments->isNotEmpty())
        <section class="panel-card grade-assessments">
            <div class="panel-heading"><div><span class="overline">LISTA DE EVALUACIONES</span><h2>{{ $assessments->count() }} {{ $assessments->count() === 1 ? 'evaluación' : 'evaluaciones' }}</h2></div></div>
            <div class="assessment-list">
                @foreach ($assessments as $assessment)
                    <div class="assessment-chip"><span><strong>{{ $assessment->title }}</strong><small>{{ $assessment->assessment_date->format('d/m/Y') }}</small></span><form method="POST" action="{{ route('grades.assessments.destroy', $assessment) }}" onsubmit="return confirm('¿Eliminar esta evaluación y todas sus notas?')">@csrf @method('DELETE')<button class="button button-small button-danger" type="submit">Eliminar</button></form></div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="panel-card gradebook-card">
        <div class="panel-heading"><div><span class="overline">REGISTRO DE CALIFICACIONES</span><h2>Estudiantes del plan</h2><p>{{ $students->count() }} estudiantes activos · promedio simple</p></div></div>
        @if ($assessments->isEmpty())
            <div class="empty-state"><span>✓</span><strong>Crea una evaluación para empezar</strong><p>Después podrás ingresar una nota de 0 a 20 por estudiante.</p></div>
        @elseif ($students->isEmpty())
            <div class="empty-state"><span>♙</span><strong>No hay estudiantes activos en este plan</strong><p>Asigna estudiantes activos a {{ $course->studyPlan?->name }} para ingresar calificaciones.</p></div>
        @else
            <form method="POST" action="{{ route('grades.save', $course) }}">
                @csrf @method('PUT')
                @if ($errors->any())<div class="alert alert-error grade-alert">Revisa las notas. Cada calificación debe estar entre 0 y 20.</div>@endif
                <div class="table-wrap"><table class="grade-table">
                    <thead><tr><th>ESTUDIANTE</th>@foreach ($assessments as $assessment)<th>{{ $assessment->title }}<small>{{ $assessment->assessment_date->format('d/m') }}</small></th>@endforeach<th>PROMEDIO</th></tr></thead>
                    <tbody>
                    @foreach ($students as $student)
                        <tr><td><strong>{{ $student->last_name }}, {{ $student->first_name }}</strong><small class="table-sub">{{ $student->student_code }}</small></td>
                            @foreach ($assessments as $assessment)
                                @php($grade = $assessment->grades->firstWhere('student_id', $student->id))
                                <td><input class="grade-input" type="number" name="scores[{{ $assessment->id }}][{{ $student->id }}]" min="0" max="20" step="0.01" value="{{ $grade?->score }}" aria-label="Nota de {{ $student->first_name }} en {{ $assessment->title }}"></td>
                            @endforeach
                            <td><strong class="grade-average">{{ isset($averages[$student->id]) ? number_format((float) $averages[$student->id], 2) : '—' }}</strong></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
                <div class="grade-actions"><button class="button button-primary" type="submit">Guardar notas</button></div>
            </form>
        @endif
    </section>
@endif
@endsection
