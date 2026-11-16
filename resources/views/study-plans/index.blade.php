@extends('layout')

@section('title', 'Planes de estudio')
@section('breadcrumb', 'Planes de estudio')

@section('content')
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA</span><h1>Planes de estudio</h1><p>Organiza los programas, cursos y sílabos por plan.</p></div><a class="button button-primary" href="{{ route('study-plans.create') }}"><span>＋</span> Nuevo plan</a></div>
<section class="panel-card">
    <div class="list-toolbar"><div><h2>Planes registrados</h2><p>{{ $studyPlans->total() }} {{ $studyPlans->total() === 1 ? 'plan' : 'planes' }}</p></div></div>
    <div class="table-wrap"><table>
        <thead><tr><th>PLAN</th><th>CÓDIGO</th><th>ESTUDIANTES</th><th>CURSOS</th><th>SÍLABOS</th><th>ESTADO</th><th></th></tr></thead>
        <tbody>
        @forelse ($studyPlans as $studyPlan)
            <tr>
                <td><strong>{{ $studyPlan->name }}</strong>@if ($studyPlan->description)<small class="table-sub">{{ $studyPlan->description }}</small>@endif</td>
                <td>{{ $studyPlan->code }}</td><td>{{ $studyPlan->students_count }}</td><td>{{ $studyPlan->courses_count }}</td><td>{{ $studyPlan->syllabi_count }}</td>
                <td><span class="status {{ $studyPlan->is_active ? 'status-presente' : 'status-ausente' }}">{{ $studyPlan->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                <td><div class="row-actions"><a class="button button-small button-light" href="{{ route('study-plans.edit', $studyPlan) }}">Editar</a><form method="POST" action="{{ route('study-plans.destroy', $studyPlan) }}" onsubmit="return confirm('¿Eliminar este plan? Solo se permite si no tiene datos asociados.')">@csrf @method('DELETE')<button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td>
            </tr>
        @empty
            <tr><td colspan="7"><div class="empty-state"><span>▤</span><strong>Aún no hay planes de estudio</strong><p>Crea un plan para asignar estudiantes, cursos y sílabos.</p><a class="button button-primary button-small" href="{{ route('study-plans.create') }}">＋ Crear plan</a></div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    <div class="pagination-wrap">{{ $studyPlans->links() }}</div>
</section>
@endsection
