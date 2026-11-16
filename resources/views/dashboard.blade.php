@extends('layout')

@section('title', 'Panel general')
@section('breadcrumb', 'Panel general')

@section('content')
<div class="page-heading">
    <div><span class="overline">PANEL DE ADMINISTRACIÓN</span><h1>Resumen académico</h1><p>Consulta la actividad y los indicadores de tu institución.</p></div>
    <a class="button button-primary" href="{{ route('attendances.index') }}"><span>▤</span> Revisar asistencias</a>
</div>

<section class="stat-grid" aria-label="Resumen">
    <a class="stat-card stat-card-link" href="{{ route('students.index') }}"><div class="stat-icon icon-violet">♙</div><div class="stat-label">Estudiantes</div><strong>{{ number_format($studentCount) }}</strong><small>Ver directorio académico <span>→</span></small><span class="stat-accent accent-violet"></span></a>
    <a class="stat-card stat-card-link" href="{{ route('courses.index') }}"><div class="stat-icon icon-blue">▤</div><div class="stat-label">Cursos</div><strong>{{ number_format($courseCount) }}</strong><small>Organizar cursos y docentes <span>→</span></small><span class="stat-accent accent-blue"></span></a>
    <a class="stat-card stat-card-link" href="{{ route('attendances.index', ['date' => today()->toDateString()]) }}"><div class="stat-icon icon-orange">◷</div><div class="stat-label">Asistencias de hoy</div><strong>{{ number_format($todayCount) }}</strong><small>Consultar registros de hoy <span>→</span></small><span class="stat-accent accent-orange"></span></a>
    <article class="stat-card"><div class="stat-icon icon-green">↗</div><div class="stat-label">Asistencia del mes</div><strong>{{ $attendanceRate }}<span class="stat-percent">%</span></strong><small>Presentes y tardanzas del mes</small><div class="progress-track"><span style="width: {{ $attendanceRate }}%"></span></div></article>
</section>

<section class="workflow-card" aria-labelledby="workflow-title">
    <div class="workflow-heading"><div><span class="overline">FLUJO DE TRABAJO</span><h2 id="workflow-title">Organiza la gestión académica</h2><p>Configura los planes, horarios y recursos; registra asistencia y notas.</p></div></div>
    <div class="workflow-steps">
        <a class="workflow-step" href="{{ route('students.index') }}"><span class="workflow-number">01</span><span><strong>Registra estudiantes</strong><small>Crea y actualiza el padrón académico.</small></span><span class="workflow-arrow">→</span></a>
        <a class="workflow-step" href="{{ route('study-plans.index') }}"><span class="workflow-number">02</span><span><strong>Define planes de estudio</strong><small>Agrupa estudiantes y cursos por programa.</small></span><span class="workflow-arrow">→</span></a>
        <a class="workflow-step" href="{{ route('courses.index') }}"><span class="workflow-number">03</span><span><strong>Organiza cursos</strong><small>Asigna plan, periodo y docente.</small></span><span class="workflow-arrow">→</span></a>
        <a class="workflow-step" href="{{ route('schedules.index') }}"><span class="workflow-number">04</span><span><strong>Programa horarios</strong><small>Registra días, horas y aulas de clase.</small></span><span class="workflow-arrow">→</span></a>
        <a class="workflow-step" href="{{ route('syllabi.index') }}"><span class="workflow-number">05</span><span><strong>Publica sílabos</strong><small>Comparte PDFs con los docentes del plan.</small></span><span class="workflow-arrow">→</span></a>
        <a class="workflow-step" href="{{ route('grades.index') }}"><span class="workflow-number">06</span><span><strong>Registra notas</strong><small>Gestiona evaluaciones y promedios.</small></span><span class="workflow-arrow">→</span></a>
        <a class="workflow-step" href="{{ route('attendances.index') }}"><span class="workflow-number">07</span><span><strong>Supervisa asistencia</strong><small>Consulta registros y su actividad.</small></span><span class="workflow-arrow">→</span></a>
    </div>
</section>

<section class="panel-card">
    <div class="panel-heading"><div><span class="overline">SEGUIMIENTO</span><h2>Actividad reciente</h2><p>Últimas asistencias registradas en la institución.</p></div><a class="text-link" href="{{ route('attendances.index') }}">Ver todos los registros <span>→</span></a></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>ESTUDIANTE</th><th>CURSO</th><th>FECHA</th><th>ESTADO</th></tr></thead>
            <tbody>
                @forelse ($recentAttendances as $attendance)
                    <tr><td><div class="student-cell"><span class="table-avatar">{{ mb_strtoupper(mb_substr($attendance->student->first_name, 0, 1)) }}</span><div><strong>{{ $attendance->student->first_name }} {{ $attendance->student->last_name }}</strong><small>{{ $attendance->student->student_code }}</small></div></div></td><td><strong>{{ $attendance->course->name }}</strong><small class="table-sub">{{ $attendance->course->code }}</small></td><td>{{ $attendance->attendance_date->format('d/m/Y') }}</td><td><span class="status status-{{ $attendance->status }}">{{ \App\Models\Attendance::STATUSES[$attendance->status] }}</span></td></tr>
                @empty
                    <tr><td colspan="4"><div class="empty-state"><span>◷</span><strong>Aún no hay asistencias</strong><p>Registra la primera asistencia para ver actividad aquí.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
