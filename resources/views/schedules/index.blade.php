@extends('layout')

@section('title', 'Horarios')
@section('breadcrumb', 'Horarios')

@section('content')
<div class="page-heading"><div><span class="overline">ORGANIZACIÓN ACADÉMICA</span><h1>{{ auth()->user()->isAdmin() ? 'Horarios semanales' : 'Mi horario' }}</h1><p>{{ auth()->user()->isAdmin() ? 'Organiza las sesiones de los cursos y evita cruces de docente.' : 'Consulta las sesiones de tus cursos asignados.' }}</p></div>@if (auth()->user()->isAdmin())<a class="button button-primary" href="{{ route('schedules.create') }}"><span>＋</span> Agregar sesión</a>@endif</div>
<section class="panel-card">
    <div class="list-toolbar"><div><h2>{{ auth()->user()->isAdmin() ? 'Sesiones programadas' : 'Sesiones de mis cursos' }}</h2><p>{{ $schedules->count() }} {{ $schedules->count() === 1 ? 'sesión semanal' : 'sesiones semanales' }}</p></div></div>
    <div class="table-wrap"><table>
        <thead><tr><th>DÍA</th><th>HORA</th><th>CURSO</th><th>PLAN</th><th>DOCENTE</th><th>AULA</th>@if (auth()->user()->isAdmin())<th></th>@endif</tr></thead>
        <tbody>
        @forelse ($schedules as $schedule)
            <tr><td><strong>{{ $days[$schedule->day_of_week] }}</strong></td><td>{{ substr($schedule->starts_at, 0, 5) }}–{{ substr($schedule->ends_at, 0, 5) }}</td><td><strong>{{ $schedule->course->name }}</strong><small class="table-sub">{{ $schedule->course->code }} · {{ $schedule->course->section }}</small></td><td>{{ $schedule->course->studyPlan?->name ?: 'Sin plan' }}</td><td>{{ $schedule->course->teacher?->name ?: 'Sin asignar' }}</td><td>{{ $schedule->classroom ?: '—' }}</td>@if (auth()->user()->isAdmin())<td><div class="row-actions"><a class="button button-small button-light" href="{{ route('schedules.edit', $schedule) }}">Editar</a><form method="POST" action="{{ route('schedules.destroy', $schedule) }}" onsubmit="return confirm('¿Eliminar esta sesión del horario?')">@csrf @method('DELETE')<button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td>@endif</tr>
        @empty
            <tr><td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}"><div class="empty-state"><span>◷</span><strong>No hay sesiones semanales registradas</strong><p>{{ auth()->user()->isAdmin() ? 'Agrega las sesiones de cada curso para completar el horario.' : 'El administrador aún no registra horarios para tus cursos.' }}</p>@if (auth()->user()->isAdmin())<a class="button button-primary button-small" href="{{ route('schedules.create') }}">＋ Agregar sesión</a>@endif</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
</section>
@endsection
