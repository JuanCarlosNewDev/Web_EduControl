@extends('layout')

@section('title', 'Asistencias')
@section('breadcrumb', 'Asistencias')

@section('content')
<div class="page-heading"><div><span class="overline">CONTROL DE ASISTENCIA</span><h1>Asistencias</h1><p>{{ auth()->user()->isAdmin() ? 'Supervisa y administra los registros de asistencia de toda la institución.' : 'Pasa asistencia y consulta los registros de tus cursos asignados.' }}</p></div><a class="button button-primary" href="{{ route('attendances.create') }}"><span>＋</span>{{ auth()->user()->isAdmin() ? 'Registrar o corregir' : 'Pasar asistencia' }}</a></div>
<section class="role-explainer" aria-labelledby="attendance-purpose-heading">
    <span class="role-explainer-icon">{{ auth()->user()->isAdmin() ? '⌕' : '✓' }}</span>
    <div>
        <h2 id="attendance-purpose-heading">{{ auth()->user()->isAdmin() ? '¿Para qué sirve Asistencias en Administración?' : 'Tu función docente en Asistencias' }}</h2>
        @if (auth()->user()->isAdmin())
            <p>Este espacio sirve para supervisar la asistencia institucional: consulta registros de todos los cursos, usa los filtros y registra o corrige datos cuando sea necesario. La toma habitual de asistencia corresponde al docente durante su clase.</p>
            <div class="role-comparison"><span><strong>Administración</strong> Supervisa todos los cursos y gestiona correcciones.</span><span><strong>Docente</strong> Pasa lista en clase y trabaja solo con sus cursos asignados.</span></div>
        @else
            <p>Usa este espacio para pasar lista en cada clase: selecciona tu curso, fecha, estudiante y estado de asistencia. Puedes corregir los registros de tus propios cursos; Administración puede supervisar los registros de toda la institución.</p>
        @endif
    </div>
</section>
<section class="panel-card">
    <div class="list-toolbar"><div><h2>{{ auth()->user()->isAdmin() ? 'Registros institucionales' : 'Asistencia de mis cursos' }}</h2><p>{{ $attendances->total() }} {{ $attendances->total() === 1 ? 'registro' : 'registros' }}</p></div></div>
    <form class="filter-form" method="GET" action="{{ route('attendances.index') }}">
        <label class="filter-search"><span>⌕</span><input name="q" value="{{ request('q') }}" placeholder="Buscar estudiante..." aria-label="Buscar estudiante"></label>
        <select name="course_id" aria-label="Filtrar por curso"><option value="">Todos los cursos</option>@foreach ($courses as $course)<option value="{{ $course->id }}" @selected((string) request('course_id') === (string) $course->id)>{{ $course->code }} · {{ $course->name }}</option>@endforeach</select>
        <select name="status" aria-label="Filtrar por estado"><option value="">Todos los estados</option>@foreach ($statuses as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach</select>
        <input type="date" name="date" value="{{ request('date') }}" aria-label="Filtrar por fecha">
        <button class="button button-light" type="submit">Filtrar</button>
        @if (request()->hasAny(['q', 'course_id', 'status', 'date']))<a class="clear-filters" href="{{ route('attendances.index') }}">Limpiar</a>@endif
    </form>
    <div class="table-wrap"><table>
        <thead><tr><th>ESTUDIANTE</th><th>CURSO</th><th>FECHA</th><th>ESTADO</th><th>OBSERVACIÓN</th><th></th></tr></thead>
        <tbody>
        @forelse ($attendances as $attendance)
            <tr><td><div class="student-cell"><span class="table-avatar">{{ mb_strtoupper(mb_substr($attendance->student->first_name, 0, 1)) }}</span><div><strong>{{ $attendance->student->first_name }} {{ $attendance->student->last_name }}</strong><small>{{ $attendance->student->student_code }}</small></div></div></td><td><strong>{{ $attendance->course->name }}</strong><small class="table-sub">{{ $attendance->course->code }} · {{ $attendance->course->section }}</small></td><td>{{ $attendance->attendance_date->format('d/m/Y') }}</td><td><span class="status status-{{ $attendance->status }}">{{ $statuses[$attendance->status] }}</span></td><td class="notes-cell">{{ $attendance->notes ?: '—' }}</td><td><div class="row-actions"><a class="button button-small button-light" href="{{ route('attendances.edit', $attendance) }}">Editar</a><form method="POST" action="{{ route('attendances.destroy', $attendance) }}" onsubmit="return confirm('¿Eliminar este registro de asistencia?')">@csrf @method('DELETE')<button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td></tr>
        @empty
            <tr><td colspan="6"><div class="empty-state"><span>✓</span><strong>No encontramos asistencias</strong><p>Ajusta los filtros o registra una nueva asistencia.</p></div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    <div class="pagination-wrap">{{ $attendances->links() }}</div>
</section>
@endsection
