@extends('layout')

@section('title', $schedule->exists ? 'Editar horario' : 'Nueva sesión')
@section('breadcrumb', 'Horarios / '.($schedule->exists ? 'Editar' : 'Nueva sesión'))

@section('content')
<div class="page-heading"><div><span class="overline">ORGANIZACIÓN ACADÉMICA / HORARIOS</span><h1>{{ $schedule->exists ? 'Editar sesión' : 'Agregar sesión semanal' }}</h1><p>Un curso puede tener varias sesiones en distintos días.</p></div><a class="button button-light" href="{{ route('schedules.index') }}">← Volver a horarios</a></div>
<section class="panel-card form-card"><form method="POST" action="{{ $schedule->exists ? route('schedules.update', $schedule) : route('schedules.store') }}" class="entity-form">
    @csrf @if ($schedule->exists) @method('PUT') @endif
    @if ($errors->any())<div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div>@endif
    <div class="form-section"><span class="overline">SESIÓN RECURRENTE</span><h2>Datos del horario</h2><p>No se permite programar dos cursos del mismo docente en horas que se crucen.</p></div>
    <div class="form-grid">
        <label>Curso *<select name="course_id" required><option value="">Selecciona un curso</option>@foreach ($courses as $course)<option value="{{ $course->id }}" @selected((string) old('course_id', $schedule->course_id) === (string) $course->id)>{{ $course->code }} · {{ $course->name }} · {{ $course->teacher?->name ?: 'Sin docente' }}</option>@endforeach</select>@error('course_id')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Día *<select name="day_of_week" required><option value="">Selecciona un día</option>@foreach ($days as $day => $label)<option value="{{ $day }}" @selected((string) old('day_of_week', $schedule->day_of_week) === (string) $day)>{{ $label }}</option>@endforeach</select>@error('day_of_week')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Hora de inicio *<input type="time" name="starts_at" value="{{ old('starts_at', $schedule->exists ? substr($schedule->starts_at, 0, 5) : '') }}" required>@error('starts_at')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Hora de fin *<input type="time" name="ends_at" value="{{ old('ends_at', $schedule->exists ? substr($schedule->ends_at, 0, 5) : '') }}" required>@error('ends_at')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Aula<input name="classroom" value="{{ old('classroom', $schedule->classroom) }}" maxlength="80" placeholder="Aula 101">@error('classroom')<span class="field-error">{{ $message }}</span>@enderror</label>
    </div>
    <div class="form-actions"><a class="button button-light" href="{{ route('schedules.index') }}">Cancelar</a><button class="button button-primary" type="submit">{{ $schedule->exists ? 'Guardar cambios' : 'Agregar sesión' }}</button></div>
</form></section>
@endsection
