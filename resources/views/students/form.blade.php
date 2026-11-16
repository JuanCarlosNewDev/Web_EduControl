@extends('layout')

@section('title', $student->exists ? 'Editar estudiante' : 'Nuevo estudiante')
@section('breadcrumb', 'Estudiantes / '.($student->exists ? 'Editar' : 'Nuevo'))

@section('content')
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA / ESTUDIANTES</span><h1>{{ $student->exists ? 'Editar estudiante' : 'Registrar estudiante' }}</h1><p>Completa los datos de identificación y asigna un plan de estudio.</p></div><a class="button button-light" href="{{ route('students.index') }}">← Volver al directorio</a></div>
<section class="panel-card form-card">
    <form method="POST" action="{{ $student->exists ? route('students.update', $student) : route('students.store') }}" class="entity-form">
        @csrf @if ($student->exists) @method('PUT') @endif
        @if ($errors->any())<div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div>@endif
        <div class="form-section"><span class="overline">PASO 1 · IDENTIFICACIÓN</span><h2>Datos del estudiante</h2><p>Usa el código institucional y el documento de identidad del estudiante.</p></div>
        <div class="form-grid">
            <label>Código de estudiante<input name="student_code" value="{{ old('student_code', $student->student_code) }}" maxlength="30" required placeholder="EC-2026-001">@error('student_code')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label>DNI / documento<input name="document_number" value="{{ old('document_number', $student->document_number) }}" maxlength="20" required>@error('document_number')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label>Nombres<input name="first_name" value="{{ old('first_name', $student->first_name) }}" maxlength="100" required>@error('first_name')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label>Apellidos<input name="last_name" value="{{ old('last_name', $student->last_name) }}" maxlength="100" required>@error('last_name')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label>Correo electrónico<input name="email" type="email" value="{{ old('email', $student->email) }}" maxlength="255" placeholder="estudiante@institucion.edu">@error('email')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label>Plan de estudio *<select name="study_plan_id" required><option value="">Selecciona un plan</option>@foreach ($studyPlans as $studyPlan)<option value="{{ $studyPlan->id }}" @selected((string) old('study_plan_id', $student->study_plan_id) === (string) $studyPlan->id)>{{ $studyPlan->name }}{{ $studyPlan->is_active ? '' : ' · Inactivo' }}</option>@endforeach</select>@error('study_plan_id')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label>Estado<select name="is_active" required><option value="1" @selected((string) old('is_active', $student->exists ? (int) $student->is_active : 1) === '1')>Activo</option><option value="0" @selected((string) old('is_active', $student->exists ? (int) $student->is_active : 1) === '0')>Inactivo</option></select>@error('is_active')<span class="field-error">{{ $message }}</span>@enderror</label>
        </div>
        <div class="form-actions"><a class="button button-light" href="{{ route('students.index') }}">Cancelar</a><button class="button button-primary" type="submit">{{ $student->exists ? 'Guardar cambios' : 'Registrar estudiante' }}</button></div>
    </form>
</section>
@endsection
