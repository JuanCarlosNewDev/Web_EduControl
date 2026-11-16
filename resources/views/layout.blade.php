<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduControl') · EduControl</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @auth
        <div class="app-shell">
            <aside class="sidebar">
                <a class="brand" href="{{ route(auth()->user()->isAdmin() ? 'dashboard' : 'attendances.index') }}">
                    <span class="brand-mark">E</span>
                    <span><strong>EduControl</strong><small>CONTROL ACADÉMICO</small></span>
                </a>
                <nav class="side-nav" aria-label="Navegación principal">
                    @if (auth()->user()->isAdmin())
                        <div class="nav-caption">INICIO</div>
                        <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span>▦</span> Panel general</a>
                        <div class="nav-caption nav-caption-spaced">GESTIÓN ACADÉMICA</div>
                        <a class="{{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}"><span>♙</span> Estudiantes</a>
                        <a class="{{ request()->routeIs('study-plans.*') ? 'active' : '' }}" href="{{ route('study-plans.index') }}"><span>▤</span> Planes de estudio</a>
                        <a class="{{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}"><span>▤</span> Cursos y docentes</a>
                        <a class="{{ request()->routeIs('schedules.*') ? 'active' : '' }}" href="{{ route('schedules.index') }}"><span>◷</span> Horarios</a>
                        <a class="{{ request()->routeIs('syllabi.*') ? 'active' : '' }}" href="{{ route('syllabi.index') }}"><span>PDF</span> Sílabos</a>
                        <div class="nav-caption nav-caption-spaced">SEGUIMIENTO</div>
                        <a class="{{ request()->routeIs('grades.*') ? 'active' : '' }}" href="{{ route('grades.index') }}"><span>✓</span> Notas</a>
                        <a class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}" href="{{ route('attendances.index') }}"><span>✓</span> Asistencia institucional</a>
                    @else
                        <div class="nav-caption">MI ESPACIO DOCENTE</div>
                        <a class="{{ request()->routeIs('schedules.*') ? 'active' : '' }}" href="{{ route('schedules.index') }}"><span>◷</span> Mi horario</a>
                        <a class="{{ request()->routeIs('syllabi.*') ? 'active' : '' }}" href="{{ route('syllabi.index') }}"><span>PDF</span> Sílabos</a>
                        <a class="{{ request()->routeIs('grades.*') ? 'active' : '' }}" href="{{ route('grades.index') }}"><span>✓</span> Notas</a>
                        <a class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}" href="{{ route('attendances.index') }}"><span>✓</span> Asistencia de mis cursos</a>
                    @endif
                </nav>
                <div class="sidebar-bottom">
                    <div class="help-card">
                        <span>{{ auth()->user()->isAdmin() ? 'i' : '✓' }}</span>
                        <strong>{{ auth()->user()->isAdmin() ? 'Tu espacio administrativo' : 'Tu espacio docente' }}</strong>
                        <p>{{ auth()->user()->isAdmin() ? 'Organiza planes, cursos y horarios; supervisa el seguimiento académico.' : 'Registra asistencia y notas. Solo verás los cursos asignados a tu cuenta.' }}</p>
                    </div>
                    <div class="profile">
                        <div class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
                        <div class="profile-copy"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->isAdmin() ? 'Administración' : 'Docente' }}</small></div>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="icon-button" aria-label="Cerrar sesión" title="Cerrar sesión">↗</button></form>
                    </div>
                </div>
            </aside>
            <main class="main-area">
                <header class="topbar"><div><span class="topbar-label">EDUCONTROL</span><span class="topbar-divider">/</span><span>@yield('breadcrumb', 'Panel')</span></div><div class="topbar-date">{{ now()->locale('es')->translatedFormat('l, d \\d\\e F Y') }}</div></header>
                <div class="page-content">
                    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if (session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif
                    @yield('content')
                </div>
            </main>
        </div>
    @else
        @yield('content')
    @endauth
</body>
</html>
