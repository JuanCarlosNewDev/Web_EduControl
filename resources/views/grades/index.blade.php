@extends('layout')

@section('title', 'Notas')
@section('breadcrumb', 'Notas')

@section('content')
<div class="page-heading"><div><span class="overline">SEGUIMIENTO ACADÉMICO</span><h1>{{ auth()->user()->isAdmin() ? 'Notas por curso' : 'Notas de mis cursos' }}</h1><p>Registra evaluaciones y consulta promedios en escala de 0 a 20.</p></div></div>
<section class="panel-card">
    <div class="list-toolbar"><div><h2>Cursos disponibles</h2><p>{{ $courses->count() }} {{ $courses->count() === 1 ? 'curso' : 'cursos' }}</p></div></div>
    <div class="table-wrap"><table>
        <thead><tr><th>CURSO</th><th>PLAN DE ESTUDIO</th><th>PERIODO</th><th>EVALUACIONES</th><th></th></tr></thead>
        <tbody>
        @forelse ($courses as $course)
            <tr><td><strong>{{ $course->name }}</strong><small class="table-sub">{{ $course->code }} · {{ $course->section }}</small></td><td>{{ $course->studyPlan?->name ?: 'Sin plan asignado' }}</td><td>{{ $course->term }}</td><td>{{ $course->assessments_count }}</td><td><a class="button button-small button-light" href="{{ route('grades.show', $course) }}">Abrir notas</a></td></tr>
        @empty
            <tr><td colspan="5"><div class="empty-state"><span>✓</span><strong>No hay cursos disponibles</strong><p>{{ auth()->user()->isAdmin() ? 'Crea un curso y asígnale un plan de estudio.' : 'Solicita al administrador que asigne cursos a tu cuenta.' }}</p></div></td></tr>
        @endforelse
        </tbody>
    </table></div>
</section>
@endsection
