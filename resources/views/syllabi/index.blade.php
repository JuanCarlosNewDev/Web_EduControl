@extends('layout')

@section('title', 'Sílabos')
@section('breadcrumb', 'Sílabos')

@section('content')
<div class="page-heading"><div><span class="overline">MATERIAL ACADÉMICO</span><h1>Sílabos por curso y plan</h1><p>{{ auth()->user()->isAdmin() ? 'Revisa, publica y administra los sílabos PDF.' : 'Consulta los sílabos publicados de tus cursos y envía los tuyos para aprobación.' }}</p></div><a class="button button-primary" href="{{ route('syllabi.create') }}"><span>＋</span> {{ auth()->user()->isAdmin() ? 'Cargar sílabo PDF' : 'Subir mi sílabo' }}</a></div>
@forelse ($studyPlans as $studyPlan)
    <section class="panel-card syllabus-plan">
        <div class="panel-heading"><div><span class="overline">{{ $studyPlan->code }}</span><h2>{{ $studyPlan->name }}</h2><p>{{ $studyPlan->syllabi->count() }} {{ $studyPlan->syllabi->count() === 1 ? 'sílabo disponible' : 'sílabos disponibles' }}</p></div></div>
        <div class="syllabus-list">
            @forelse ($studyPlan->syllabi as $syllabus)
                <article class="syllabus-row"><span class="syllabus-icon">PDF</span><div class="syllabus-copy"><strong>{{ $syllabus->title }}</strong><small>{{ $syllabus->course ? $syllabus->course->name.' · ' : '' }}{{ $syllabus->original_filename }} · {{ $syllabus->created_at->format('d/m/Y') }}@if (auth()->user()->isAdmin() && $syllabus->uploadedBy) · Subido por {{ $syllabus->uploadedBy->name }}@endif</small></div>
                    @if (auth()->user()->isAdmin() || ! $syllabus->is_published)
                        <span class="status {{ $syllabus->is_published ? 'status-presente' : 'status-tardanza' }}">{{ $syllabus->is_published ? 'Publicado' : 'Pendiente de aprobación' }}</span>
                    @endif
                    <a class="button button-small button-light" href="{{ route('syllabi.download', $syllabus) }}">Descargar</a>
                    @if (auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('syllabi.publication', $syllabus) }}">@csrf @method('PATCH')<input type="hidden" name="is_published" value="{{ $syllabus->is_published ? 0 : 1 }}"><button class="button button-small {{ $syllabus->is_published ? 'button-light' : 'button-primary' }}" type="submit">{{ $syllabus->is_published ? 'Retirar' : 'Publicar' }}</button></form>
                        <form method="POST" action="{{ route('syllabi.destroy', $syllabus) }}" onsubmit="return confirm('¿Eliminar este sílabo PDF?')">@csrf @method('DELETE')<button class="button button-small button-danger" type="submit">Eliminar</button></form>
                    @endif
                </article>
            @empty
                <div class="empty-state"><span>PDF</span><strong>{{ auth()->user()->isAdmin() ? 'Este plan aún no tiene sílabos' : 'No hay sílabos para este plan' }}</strong><p>{{ auth()->user()->isAdmin() ? 'Carga el documento PDF para que los docentes puedan consultarlo.' : 'El administrador aún no ha publicado documentos y no has enviado un sílabo para aprobación.' }}</p></div>
            @endforelse
        </div>
    </section>
@empty
    <section class="panel-card"><div class="empty-state"><span>PDF</span><strong>{{ auth()->user()->isAdmin() ? 'No hay planes de estudio' : 'No tienes cursos asignados a un plan' }}</strong><p>{{ auth()->user()->isAdmin() ? 'Crea primero un plan de estudio para publicar sílabos.' : 'Solicita al administrador que asocie tus cursos con un plan.' }}</p></div></section>
@endforelse
@endsection
