

<?php $__env->startSection('title', 'Notas'); ?>
<?php $__env->startSection('breadcrumb', 'Notas'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">SEGUIMIENTO ACADÉMICO</span><h1><?php echo e(auth()->user()->isAdmin() ? 'Notas por curso' : 'Notas de mis cursos'); ?></h1><p>Registra evaluaciones y consulta promedios en escala de 0 a 20.</p></div></div>
<section class="panel-card">
    <div class="list-toolbar"><div><h2>Cursos disponibles</h2><p><?php echo e($courses->count()); ?> <?php echo e($courses->count() === 1 ? 'curso' : 'cursos'); ?></p></div></div>
    <div class="table-wrap"><table>
        <thead><tr><th>CURSO</th><th>PLAN DE ESTUDIO</th><th>PERIODO</th><th>EVALUACIONES</th><th></th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><strong><?php echo e($course->name); ?></strong><small class="table-sub"><?php echo e($course->code); ?> · <?php echo e($course->section); ?></small></td><td><?php echo e($course->studyPlan?->name ?: 'Sin plan asignado'); ?></td><td><?php echo e($course->term); ?></td><td><?php echo e($course->assessments_count); ?></td><td><a class="button button-small button-light" href="<?php echo e(route('grades.show', $course)); ?>">Abrir notas</a></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5"><div class="empty-state"><span>✓</span><strong>No hay cursos disponibles</strong><p><?php echo e(auth()->user()->isAdmin() ? 'Crea un curso y asígnale un plan de estudio.' : 'Solicita al administrador que asigne cursos a tu cuenta.'); ?></p></div></td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/grades/index.blade.php ENDPATH**/ ?>