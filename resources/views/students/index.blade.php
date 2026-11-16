@extends('layout')

@section('title', 'Estudiantes')
@section('breadcrumb', 'Estudiantes')

@section('content')
<div class="page-heading">
    <div><span class="overline">GESTIÓN ACADÉMICA</span><h1>Estudiantes</h1><p>Consulta y actualiza el padrón de estudiantes de la institución.</p></div>
    <a class="button button-primary" href="{{ route('students.create') }}"><span>＋</span> Nuevo estudiante</a>
</div>
<div class="section-guide"><span class="section-guide-icon">i</span><p><strong>¿Qué puedes hacer aquí?</strong> Registrar estudiantes y mantener actualizados sus datos, documentos y programa. Los estudiantes activos estarán disponibles para tomar asistencia.</p></div>
<section class="panel-card">
    <div class="list-toolbar">
        <div><h2>Directorio de estudiantes</h2><p>{{ $students->total() }} {{ $students->total() === 1 ? 'estudiante' : 'estudiantes' }}</p></div>
        <form class="search-form" method="GET" action="{{ route('students.index') }}"><span>⌕</span><input name="q" value="{{ $search }}" placeholder="Buscar por nombre, código o programa..." aria-label="Buscar estudiantes"><button class="button button-light" type="submit">Buscar</button></form>
    </div>
    <div class="table-wrap"><table>
        <thead><tr><th>ESTUDIANTE</th><th>CÓDIGO / DOCUMENTO</th><th>PROGRAMA</th><th>ESTADO</th><th></th></tr></thead>
        <tbody>
        @forelse ($students as $student)
            <tr>
                <td><div class="student-cell"><span class="table-avatar">{{ mb_strtoupper(mb_substr($student->first_name, 0, 1)) }}</span><div><strong>{{ $student->first_name }} {{ $student->last_name }}</strong><small>{{ $student->email ?: 'Sin correo registrado' }}</small></div></div></td>
                <td><strong>{{ $student->student_code }}</strong><small class="table-sub">DNI {{ $student->document_number }}</small></td>
                <td>{{ $student->studyPlan?->name ?? $student->program }}</td>
                <td><span class="status {{ $student->is_active ? 'status-presente' : 'status-ausente' }}">{{ $student->is_active ? 'Activo' : 'Inactivo' }}</span></td>
                <td><div class="row-actions"><a class="button button-small button-light" href="{{ route('students.edit', $student) }}">Editar</a><form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('¿Eliminar este estudiante y sus registros de asistencia?')">@csrf @method('DELETE')<button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td>
            </tr>
        @empty
            <tr><td colspan="5"><div class="empty-state"><span>♙</span><strong>{{ $search !== '' ? 'No hay resultados para esta búsqueda' : 'Aún no hay estudiantes registrados' }}</strong><p>{{ $search !== '' ? 'Revisa el nombre, código o programa e inténtalo nuevamente.' : 'Registra el primer estudiante para comenzar a organizar el padrón académico.' }}</p>@if ($search === '')<a class="button button-primary button-small" href="{{ route('students.create') }}">＋ Registrar estudiante</a>@endif</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    <div class="pagination-wrap">{{ $students->links() }}</div>
</section>
@endsection
