<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'EduControl'); ?> · EduControl</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>
    <?php if(auth()->guard()->check()): ?>
        <div class="app-shell">
            <aside class="sidebar">
                <a class="brand" href="<?php echo e(route(auth()->user()->isAdmin() ? 'dashboard' : 'attendances.index')); ?>">
                    <span class="brand-mark">E</span>
                    <span><strong>EduControl</strong><small>CONTROL ACADÉMICO</small></span>
                </a>
                <nav class="side-nav" aria-label="Navegación principal">
                    <?php if(auth()->user()->isAdmin()): ?>
                        <div class="nav-caption">INICIO</div>
                        <a class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>"><span>▦</span> Panel general</a>
                        <div class="nav-caption nav-caption-spaced">GESTIÓN ACADÉMICA</div>
                        <a class="<?php echo e(request()->routeIs('students.*') ? 'active' : ''); ?>" href="<?php echo e(route('students.index')); ?>"><span>♙</span> Estudiantes</a>
                        <a class="<?php echo e(request()->routeIs('study-plans.*') ? 'active' : ''); ?>" href="<?php echo e(route('study-plans.index')); ?>"><span>▤</span> Planes de estudio</a>
                        <a class="<?php echo e(request()->routeIs('courses.*') ? 'active' : ''); ?>" href="<?php echo e(route('courses.index')); ?>"><span>▤</span> Cursos y docentes</a>
                        <a class="<?php echo e(request()->routeIs('schedules.*') ? 'active' : ''); ?>" href="<?php echo e(route('schedules.index')); ?>"><span>◷</span> Horarios</a>
                        <a class="<?php echo e(request()->routeIs('syllabi.*') ? 'active' : ''); ?>" href="<?php echo e(route('syllabi.index')); ?>"><span>PDF</span> Sílabos</a>
                        <div class="nav-caption nav-caption-spaced">SEGUIMIENTO</div>
                        <a class="<?php echo e(request()->routeIs('grades.*') ? 'active' : ''); ?>" href="<?php echo e(route('grades.index')); ?>"><span>✓</span> Notas</a>
                        <a class="<?php echo e(request()->routeIs('attendances.*') ? 'active' : ''); ?>" href="<?php echo e(route('attendances.index')); ?>"><span>✓</span> Asistencia institucional</a>
                    <?php else: ?>
                        <div class="nav-caption">MI ESPACIO DOCENTE</div>
                        <a class="<?php echo e(request()->routeIs('schedules.*') ? 'active' : ''); ?>" href="<?php echo e(route('schedules.index')); ?>"><span>◷</span> Mi horario</a>
                        <a class="<?php echo e(request()->routeIs('syllabi.*') ? 'active' : ''); ?>" href="<?php echo e(route('syllabi.index')); ?>"><span>PDF</span> Sílabos</a>
                        <a class="<?php echo e(request()->routeIs('grades.*') ? 'active' : ''); ?>" href="<?php echo e(route('grades.index')); ?>"><span>✓</span> Notas</a>
                        <a class="<?php echo e(request()->routeIs('attendances.*') ? 'active' : ''); ?>" href="<?php echo e(route('attendances.index')); ?>"><span>✓</span> Asistencia de mis cursos</a>
                    <?php endif; ?>
                </nav>
                <div class="sidebar-bottom">
                    <div class="help-card">
                        <span><?php echo e(auth()->user()->isAdmin() ? 'i' : '✓'); ?></span>
                        <strong><?php echo e(auth()->user()->isAdmin() ? 'Tu espacio administrativo' : 'Tu espacio docente'); ?></strong>
                        <p><?php echo e(auth()->user()->isAdmin() ? 'Organiza planes, cursos y horarios; supervisa el seguimiento académico.' : 'Registra asistencia y notas. Solo verás los cursos asignados a tu cuenta.'); ?></p>
                    </div>
                    <div class="profile">
                        <div class="avatar"><?php echo e(mb_strtoupper(mb_substr(auth()->user()->name, 0, 1))); ?></div>
                        <div class="profile-copy"><strong><?php echo e(auth()->user()->name); ?></strong><small><?php echo e(auth()->user()->isAdmin() ? 'Administración' : 'Docente'); ?></small></div>
                        <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="icon-button" aria-label="Cerrar sesión" title="Cerrar sesión">↗</button></form>
                    </div>
                </div>
            </aside>
            <main class="main-area">
                <header class="topbar"><div><span class="topbar-label">EDUCONTROL</span><span class="topbar-divider">/</span><span><?php echo $__env->yieldContent('breadcrumb', 'Panel'); ?></span></div><div class="topbar-date"><?php echo e(now()->locale('es')->translatedFormat('l, d \\d\\e F Y')); ?></div></header>
                <div class="page-content">
                    <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
                    <?php if(session('error')): ?><div class="alert alert-error"><?php echo e(session('error')); ?></div><?php endif; ?>
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    <?php else: ?>
        <?php echo $__env->yieldContent('content'); ?>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/layout.blade.php ENDPATH**/ ?>