@extends('layout')

@section('title', $course->exists ? 'Editar curso' : 'Nuevo curso')
@section('breadcrumb', 'Cursos / '.($course->exists ? 'Editar' : 'Nuevo'))

@section('content')
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA / CURSOS</span><h1>{{ $course->exists ? 'Editar curso' : 'Crear curso' }}</h1><p>Define el periodo, el plan y el docente responsable; configura las sesiones en Horarios.</p></div><a class="button button-light" href="{{ route('courses.index') }}">← Volver al catálogo</a></div>
<section class="panel-card form-card"><form method="POST" action="{{ $course->exists ? route('courses.update', $course) : route('courses.store') }}" class="entity-form">
    @csrf @if ($course->exists) @method('PUT') @endif
    @if ($errors->any())<div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div>@endif
    <div class="form-section"><span class="overline">CONFIGURACIÓN ACADÉMICA</span><h2>Información del curso</h2><p>Los campos con * son obligatorios. Asigna un docente para habilitar la toma de asistencia en su cuenta.</p></div>
    <div class="form-grid">
        <label>Código *<input name="code" value="{{ old('code', $course->code) }}" maxlength="30" required placeholder="PRG-101">@error('code')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Nombre del curso *<input name="name" value="{{ old('name', $course->name) }}" maxlength="150" required>@error('name')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Sección *<input name="section" value="{{ old('section', $course->section ?: 'A') }}" maxlength="20" required>@error('section')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Periodo académico *<input name="term" value="{{ old('term', $course->term) }}" maxlength="40" required placeholder="2026-II">@error('term')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Plan de estudio *<select name="study_plan_id" required><option value="">Selecciona un plan</option>@foreach ($studyPlans as $studyPlan)<option value="{{ $studyPlan->id }}" @selected((string) old('study_plan_id', $course->study_plan_id) === (string) $studyPlan->id)>{{ $studyPlan->name }}</option>@endforeach</select>@error('study_plan_id')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Docente responsable<select name="teacher_id"><option value="">Sin asignar</option>@foreach ($teachers as $teacher)<option value="{{ $teacher->id }}" @selected((string) old('teacher_id', $course->teacher_id) === (string) $teacher->id)>{{ $teacher->name }} · {{ $teacher->email }}</option>@endforeach</select>@error('teacher_id')<span class="field-error">{{ $message }}</span>@enderror</label>
    </div>
    <div class="form-actions"><a class="button button-light" href="{{ route('courses.index') }}">Cancelar</a><button class="button button-primary" type="submit">{{ $course->exists ? 'Guardar cambios' : 'Crear curso' }}</button></div>
</form></section>
@endsection
