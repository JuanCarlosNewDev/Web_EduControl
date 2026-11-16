@extends('layout')

@section('title', 'Cargar sílabo')
@section('breadcrumb', 'Sílabos / Cargar')

@section('content')
<div class="page-heading"><div><span class="overline">MATERIAL ACADÉMICO / SÍLABOS</span><h1>{{ auth()->user()->isAdmin() ? 'Cargar sílabo PDF' : 'Subir mi sílabo' }}</h1><p>El documento se guardará de forma privada; {{ auth()->user()->isAdmin() ? 'solo administración y docentes autorizados podrán descargarlo.' : 'el administrador deberá aprobarlo antes de publicarlo.' }}</p></div><a class="button button-light" href="{{ route('syllabi.index') }}">← Volver a sílabos</a></div>
<section class="panel-card form-card"><form method="POST" action="{{ route('syllabi.store') }}" class="entity-form" enctype="multipart/form-data">
    @csrf
    @if ($errors->any())<div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div>@endif
    @if (! auth()->user()->isAdmin() && $courses->isEmpty())
        <div class="empty-state"><span>PDF</span><strong>No tienes cursos vinculados a un plan</strong><p>Solicita al administrador que asigne un plan de estudio a uno de tus cursos antes de subir el sílabo.</p></div>
    @else
        <div class="form-section"><span class="overline">{{ auth()->user()->isAdmin() ? 'PUBLICACIÓN POR PLAN' : 'SÍLABO DEL CURSO' }}</span><h2>Documento académico</h2><p>Solo se aceptan archivos PDF de hasta 2 MB.</p></div>
        <div class="form-grid">
            @if (auth()->user()->isAdmin())
                <label>Plan de estudio *<select name="study_plan_id" required><option value="">Selecciona un plan</option>@foreach ($studyPlans as $studyPlan)<option value="{{ $studyPlan->id }}" @selected((string) old('study_plan_id') === (string) $studyPlan->id)>{{ $studyPlan->name }}</option>@endforeach</select>@error('study_plan_id')<span class="field-error">{{ $message }}</span>@enderror</label>
            @else
                <label>Curso asignado *<select name="course_id" required><option value="">Selecciona un curso</option>@foreach ($courses as $course)<option value="{{ $course->id }}" @selected((string) old('course_id') === (string) $course->id)>{{ $course->name }} — {{ $course->studyPlan->name }}</option>@endforeach</select>@error('course_id')<span class="field-error">{{ $message }}</span>@enderror</label>
            @endif
            <label>Título del sílabo *<input name="title" value="{{ old('title') }}" maxlength="150" required placeholder="Sílabo 2026-II">@error('title')<span class="field-error">{{ $message }}</span>@enderror</label>
            <label class="full-field">Archivo PDF *<input type="file" name="file" accept="application/pdf,.pdf" required>@error('file')<span class="field-error">{{ $message }}</span>@enderror</label>
            @if (auth()->user()->isAdmin())
                <label>Publicación<select name="is_published"><option value="0" @selected(old('is_published', '1') === '0')>Guardar como borrador</option><option value="1" @selected(old('is_published', '1') === '1')>Publicar ahora</option></select>@error('is_published')<span class="field-error">{{ $message }}</span>@enderror</label>
            @else
                <p class="full-field">El sílabo quedará pendiente de aprobación. Podrás descargar tu archivo mientras lo revisa el administrador.</p>
            @endif
        </div>
        <div class="form-actions"><a class="button button-light" href="{{ route('syllabi.index') }}">Cancelar</a><button class="button button-primary" type="submit">Cargar sílabo</button></div>
    @endif
</form></section>
@endsection
