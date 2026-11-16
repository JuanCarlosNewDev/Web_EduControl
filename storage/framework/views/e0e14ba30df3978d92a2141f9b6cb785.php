

<?php $__env->startSection('title', 'Horarios'); ?>
<?php $__env->startSection('breadcrumb', 'Horarios'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">ORGANIZACIÓN ACADÉMICA</span><h1><?php echo e(auth()->user()->isAdmin() ? 'Horarios semanales' : 'Mi horario'); ?></h1><p><?php echo e(auth()->user()->isAdmin() ? 'Organiza las sesiones de los cursos y evita cruces de docente.' : 'Consulta las sesiones de tus cursos asignados.'); ?></p></div><?php if(auth()->user()->isAdmin()): ?><a class="button button-primary" href="<?php echo e(route('schedules.create')); ?>"><span>＋</span> Agregar sesión</a><?php endif; ?></div>
<section class="panel-card">
    <div class="list-toolbar"><div><h2><?php echo e(auth()->user()->isAdmin() ? 'Sesiones programadas' : 'Sesiones de mis cursos'); ?></h2><p><?php echo e($schedules->count()); ?> <?php echo e($schedules->count() === 1 ? 'sesión semanal' : 'sesiones semanales'); ?></p></div></div>
    <div class="table-wrap"><table>
        <thead><tr><th>DÍA</th><th>HORA</th><th>CURSO</th><th>PLAN</th><th>DOCENTE</th><th>AULA</th><?php if(auth()->user()->isAdmin()): ?><th></th><?php endif; ?></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><strong><?php echo e($days[$schedule->day_of_week]); ?></strong></td><td><?php echo e(substr($schedule->starts_at, 0, 5)); ?>–<?php echo e(substr($schedule->ends_at, 0, 5)); ?></td><td><strong><?php echo e($schedule->course->name); ?></strong><small class="table-sub"><?php echo e($schedule->course->code); ?> · <?php echo e($schedule->course->section); ?></small></td><td><?php echo e($schedule->course->studyPlan?->name ?: 'Sin plan'); ?></td><td><?php echo e($schedule->course->teacher?->name ?: 'Sin asignar'); ?></td><td><?php echo e($schedule->classroom ?: '—'); ?></td><?php if(auth()->user()->isAdmin()): ?><td><div class="row-actions"><a class="button button-small button-light" href="<?php echo e(route('schedules.edit', $schedule)); ?>">Editar</a><form method="POST" action="<?php echo e(route('schedules.destroy', $schedule)); ?>" onsubmit="return confirm('¿Eliminar esta sesión del horario?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td><?php endif; ?></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="<?php echo e(auth()->user()->isAdmin() ? 7 : 6); ?>"><div class="empty-state"><span>◷</span><strong>No hay sesiones semanales registradas</strong><p><?php echo e(auth()->user()->isAdmin() ? 'Agrega las sesiones de cada curso para completar el horario.' : 'El administrador aún no registra horarios para tus cursos.'); ?></p><?php if(auth()->user()->isAdmin()): ?><a class="button button-primary button-small" href="<?php echo e(route('schedules.create')); ?>">＋ Agregar sesión</a><?php endif; ?></div></td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/schedules/index.blade.php ENDPATH**/ ?>