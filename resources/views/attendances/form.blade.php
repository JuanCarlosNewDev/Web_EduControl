@extends('layout')

@section('title', $attendance->exists ? 'Editar asistencia' : 'Registrar asistencia')
@section('breadcrumb', 'Asistencias / '.($attendance->exists ? 'Editar' : 'Nuevo registro'))

@section('content')
<div class="page-heading"><div><span class="overline">CONTROL DE ASISTENCIA</span><h1>{{ $attendance->exists ? 'Editar asistencia' : (auth()->user()->isAdmin() ? 'Registrar o corregir asistencia' : 'Pasar asistencia') }}</h1><p>{{ auth()->user()->isAdmin() ? 'Registra una asistencia o corrige un dato reportado para cualquier curso.' : 'Registra la asistencia de tus estudiantes durante la clase.' }}</p></div><a class="button button-light" href="{{ route('attendances.index') }}">← Volver a asistencias</a></div>
<div class="form-role-note"><strong>{{ auth()->user()->isAdmin() ? 'Acceso administrativo' : 'Acceso docente' }}</strong><span>{{ auth()->user()->isAdmin() ? 'Puedes gestionar registros de todos los cursos. La asistencia diaria debe ser tomada por el docente responsable.' : 'Solo puedes registrar o editar la asistencia de los cursos que tienes asignados.' }}</span></div>
<section class="panel-card form-card"><form method="POST" action="{{ $attendance->exists ? route('attendances.update', $attendance) : route('attendances.store') }}" class="entity-form">
    @csrf @if ($attendance->exists) @method('PUT') @endif
    @if ($errors->any())<div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div>@endif
    @if ($courses->isEmpty())<div class="alert alert-error">No tienes cursos asignados. Solicita al administrador que asigne un curso a tu cuenta.</div>@endif
    <div class="form-section"><h2>Detalle de la asistencia</h2><p>Solo puede existir un registro por estudiante, curso y fecha.</p></div>
    <div class="form-grid">
        <label>Curso *<select name="course_id" required><option value="">Selecciona un curso</option>@foreach ($courses as $course)<option value="{{ $course->id }}" @selected((string) old('course_id', $attendance->course_id) === (string) $course->id)>{{ $course->code }} · {{ $course->name }} ({{ $course->section }})</option>@endforeach</select>@error('course_id')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Estudiante *<select name="student_id" required><option value="">Selecciona un estudiante</option>@foreach ($students as $student)<option value="{{ $student->id }}" @selected((string) old('student_id', $attendance->student_id) === (string) $student->id)>{{ $student->student_code }} · {{ $student->first_name }} {{ $student->last_name }}</option>@endforeach</select>@error('student_id')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Fecha de clase *<input type="date" name="attendance_date" value="{{ old('attendance_date', $attendance->attendance_date?->format('Y-m-d') ?? today()->toDateString()) }}" max="{{ today()->toDateString() }}" required>@error('attendance_date')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Estado *<select name="status" required><option value="">Selecciona un estado</option>@foreach ($statuses as $key => $label)<option value="{{ $key }}" @selected(old('status', $attendance->status ?: 'presente') === $key)>{{ $label }}</option>@endforeach</select>@error('status')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label class="full-field">Observación<textarea name="notes" rows="4" maxlength="1000" placeholder="Agrega una observación si es necesario...">{{ old('notes', $attendance->notes) }}</textarea>@error('notes')<span class="field-error">{{ $message }}</span>@enderror</label>
    </div>
    <div class="form-actions"><a class="button button-light" href="{{ route('attendances.index') }}">Cancelar</a><button class="button button-primary" type="submit" @disabled($courses->isEmpty())>{{ $attendance->exists ? (auth()->user()->isAdmin() ? 'Guardar corrección' : 'Actualizar asistencia') : (auth()->user()->isAdmin() ? 'Guardar registro' : 'Guardar asistencia') }}</button></div>
</form></section>
@endsection
