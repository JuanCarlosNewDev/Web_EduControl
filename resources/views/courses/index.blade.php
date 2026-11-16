@extends('layout')

@section('title', 'Cursos')
@section('breadcrumb', 'Cursos')

@section('content')
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA</span><h1>Cursos y docentes</h1><p>Organiza los cursos, periodos académicos y responsables de cada clase.</p></div><a class="button button-primary" href="{{ route('courses.create') }}"><span>＋</span> Nuevo curso</a></div>
<div class="section-guide"><span class="section-guide-icon">i</span><p><strong>¿Qué puedes hacer aquí?</strong> Crear cursos, asignarles un plan de estudio y docente. Configura sus sesiones semanales desde Horarios. Los docentes verán únicamente los cursos que tienen asignados.</p></div>
<section class="panel-card">
    <div class="list-toolbar"><div><h2>Catálogo de cursos</h2><p>{{ $courses->total() }} {{ $courses->total() === 1 ? 'curso registrado' : 'cursos registrados' }}</p></div><form class="search-form" method="GET" action="{{ route('courses.index') }}"><span>⌕</span><input name="q" value="{{ $search }}" placeholder="Buscar por curso, código o periodo..." aria-label="Buscar cursos"><button class="button button-light" type="submit">Buscar</button></form></div>
    <div class="table-wrap"><table>
        <thead><tr><th>CURSO</th><th>PLAN</th><th>SECCIÓN</th><th>PERIODO</th><th>DOCENTE</th><th>HORARIO</th><th></th></tr></thead>
        <tbody>
        @forelse ($courses as $course)
            <tr><td><div class="course-cell"><span class="course-mark">▤</span><div><strong>{{ $course->name }}</strong><small>{{ $course->code }}</small></div></div></td><td>{{ $course->studyPlan?->name ?: 'Sin asignar' }}</td><td>{{ $course->section }}</td><td><span class="term-tag">{{ $course->term }}</span></td><td>{{ $course->teacher?->name ?: 'Sin asignar' }}</td><td>@forelse ($course->schedules as $schedule)<span class="schedule-summary">{{ \App\Models\CourseSchedule::DAYS[$schedule->day_of_week] }} {{ substr($schedule->starts_at, 0, 5) }}–{{ substr($schedule->ends_at, 0, 5) }}</span>@empty{{ $course->schedule ?: 'Sin horario' }}@endforelse</td><td><div class="row-actions"><a class="button button-small button-light" href="{{ route('courses.edit', $course) }}">Editar</a><form method="POST" action="{{ route('courses.destroy', $course) }}" onsubmit="return confirm('¿Eliminar este curso y sus registros de asistencia?')">@csrf @method('DELETE')<button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td></tr>
        @empty
            <tr><td colspan="7"><div class="empty-state"><span>▤</span><strong>{{ $search !== '' ? 'No hay resultados para esta búsqueda' : 'Aún no hay cursos registrados' }}</strong><p>{{ $search !== '' ? 'Prueba con otro nombre, código o periodo.' : 'Crea un curso y asígnale un plan y un docente para habilitar el flujo académico.' }}</p>@if ($search === '')<a class="button button-primary button-small" href="{{ route('courses.create') }}">＋ Crear primer curso</a>@endif</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    <div class="pagination-wrap">{{ $courses->links() }}</div>
</section>
@endsection
