

<?php $__env->startSection('title', 'Planes de estudio'); ?>
<?php $__env->startSection('breadcrumb', 'Planes de estudio'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA</span><h1>Planes de estudio</h1><p>Organiza los programas, cursos y sílabos por plan.</p></div><a class="button button-primary" href="<?php echo e(route('study-plans.create')); ?>"><span>＋</span> Nuevo plan</a></div>
<section class="panel-card">
    <div class="list-toolbar"><div><h2>Planes registrados</h2><p><?php echo e($studyPlans->total()); ?> <?php echo e($studyPlans->total() === 1 ? 'plan' : 'planes'); ?></p></div></div>
    <div class="table-wrap"><table>
        <thead><tr><th>PLAN</th><th>CÓDIGO</th><th>ESTUDIANTES</th><th>CURSOS</th><th>SÍLABOS</th><th>ESTADO</th><th></th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $studyPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studyPlan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($studyPlan->name); ?></strong><?php if($studyPlan->description): ?><small class="table-sub"><?php echo e($studyPlan->description); ?></small><?php endif; ?></td>
                <td><?php echo e($studyPlan->code); ?></td><td><?php echo e($studyPlan->students_count); ?></td><td><?php echo e($studyPlan->courses_count); ?></td><td><?php echo e($studyPlan->syllabi_count); ?></td>
                <td><span class="status <?php echo e($studyPlan->is_active ? 'status-presente' : 'status-ausente'); ?>"><?php echo e($studyPlan->is_active ? 'Activo' : 'Inactivo'); ?></span></td>
                <td><div class="row-actions"><a class="button button-small button-light" href="<?php echo e(route('study-plans.edit', $studyPlan)); ?>">Editar</a><form method="POST" action="<?php echo e(route('study-plans.destroy', $studyPlan)); ?>" onsubmit="return confirm('¿Eliminar este plan? Solo se permite si no tiene datos asociados.')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7"><div class="empty-state"><span>▤</span><strong>Aún no hay planes de estudio</strong><p>Crea un plan para asignar estudiantes, cursos y sílabos.</p><a class="button button-primary button-small" href="<?php echo e(route('study-plans.create')); ?>">＋ Crear plan</a></div></td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
    <div class="pagination-wrap"><?php echo e($studyPlans->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/study-plans/index.blade.php ENDPATH**/ ?>