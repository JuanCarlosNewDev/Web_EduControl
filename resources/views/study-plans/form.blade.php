@extends('layout')

@section('title', $studyPlan->exists ? 'Editar plan' : 'Nuevo plan')
@section('breadcrumb', 'Planes de estudio / '.($studyPlan->exists ? 'Editar' : 'Nuevo'))

@section('content')
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA / PLANES</span><h1>{{ $studyPlan->exists ? 'Editar plan de estudio' : 'Crear plan de estudio' }}</h1><p>Los planes activos pueden asignarse a nuevos cursos y estudiantes.</p></div><a class="button button-light" href="{{ route('study-plans.index') }}">← Volver a planes</a></div>
<section class="panel-card form-card"><form method="POST" action="{{ $studyPlan->exists ? route('study-plans.update', $studyPlan) : route('study-plans.store') }}" class="entity-form">
    @csrf @if ($studyPlan->exists) @method('PUT') @endif
    @if ($errors->any())<div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div>@endif
    <div class="form-section"><span class="overline">PROGRAMA ACADÉMICO</span><h2>Datos del plan</h2></div>
    <div class="form-grid">
        <label>Código *<input name="code" value="{{ old('code', $studyPlan->code) }}" maxlength="40" required placeholder="DES-SIS-2026">@error('code')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Nombre *<input name="name" value="{{ old('name', $studyPlan->name) }}" maxlength="150" required placeholder="Desarrollo de Sistemas">@error('name')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label class="full-field">Descripción<textarea name="description" rows="4" maxlength="2000">{{ old('description', $studyPlan->description) }}</textarea>@error('description')<span class="field-error">{{ $message }}</span>@enderror</label>
        <label>Estado<select name="is_active" required><option value="1" @selected((string) old('is_active', $studyPlan->exists ? (int) $studyPlan->is_active : 1) === '1')>Activo</option><option value="0" @selected((string) old('is_active', $studyPlan->exists ? (int) $studyPlan->is_active : 1) === '0')>Inactivo</option></select>@error('is_active')<span class="field-error">{{ $message }}</span>@enderror</label>
    </div>
    <div class="form-actions"><a class="button button-light" href="{{ route('study-plans.index') }}">Cancelar</a><button class="button button-primary" type="submit">{{ $studyPlan->exists ? 'Guardar cambios' : 'Crear plan' }}</button></div>
</form></section>
@endsection
