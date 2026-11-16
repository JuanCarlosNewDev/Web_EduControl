

<?php $__env->startSection('title', 'Cursos'); ?>
<?php $__env->startSection('breadcrumb', 'Cursos'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA</span><h1>Cursos y docentes</h1><p>Organiza los cursos, periodos académicos y responsables de cada clase.</p></div><a class="button button-primary" href="<?php echo e(route('courses.create')); ?>"><span>＋</span> Nuevo curso</a></div>
<div class="section-guide"><span class="section-guide-icon">i</span><p><strong>¿Qué puedes hacer aquí?</strong> Crear cursos, definir su sección, periodo y horario, y asignar un docente. Cada docente verá únicamente las asistencias de los cursos asignados a su cuenta.</p></div>
<section class="panel-card">
    <div class="list-toolbar"><div><h2>Catálogo de cursos</h2><p><?php echo e($courses->total()); ?> <?php echo e($courses->total() === 1 ? 'curso registrado' : 'cursos registrados'); ?></p></div><form class="search-form" method="GET" action="<?php echo e(route('courses.index')); ?>"><span>⌕</span><input name="q" value="<?php echo e($search); ?>" placeholder="Buscar por curso, código o periodo..." aria-label="Buscar cursos"><button class="button button-light" type="submit">Buscar</button></form></div>
    <div class="table-wrap"><table>
        <thead><tr><th>CURSO</th><th>SECCIÓN</th><th>PERIODO</th><th>DOCENTE</th><th>HORARIO</th><th></th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><div class="course-cell"><span class="course-mark">▤</span><div><strong><?php echo e($course->name); ?></strong><small><?php echo e($course->code); ?></small></div></div></td><td><?php echo e($course->section); ?></td><td><span class="term-tag"><?php echo e($course->term); ?></span></td><td><?php echo e($course->teacher?->name ?: 'Sin asignar'); ?></td><td><?php echo e($course->schedule ?: 'Sin horario'); ?></td><td><div class="row-actions"><a class="button button-small button-light" href="<?php echo e(route('courses.edit', $course)); ?>">Editar</a><form method="POST" action="<?php echo e(route('courses.destroy', $course)); ?>" onsubmit="return confirm('¿Eliminar este curso y sus registros de asistencia?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6"><div class="empty-state"><span>▤</span><strong><?php echo e($search !== '' ? 'No hay resultados para esta búsqueda' : 'Aún no hay cursos registrados'); ?></strong><p><?php echo e($search !== '' ? 'Prueba con otro nombre, código o periodo.' : 'Crea un curso y asígnale un docente para habilitar la toma de asistencia.'); ?></p><?php if($search === ''): ?><a class="button button-primary button-small" href="<?php echo e(route('courses.create')); ?>">＋ Crear primer curso</a><?php endif; ?></div></td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
    <div class="pagination-wrap"><?php echo e($courses->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\Mesa de Partes\resources\views/courses/index.blade.php ENDPATH**/ ?>