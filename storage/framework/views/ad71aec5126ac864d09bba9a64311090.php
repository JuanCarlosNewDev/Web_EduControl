

<?php $__env->startSection('title', 'Estudiantes'); ?>
<?php $__env->startSection('breadcrumb', 'Estudiantes'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading">
    <div><span class="overline">GESTIÓN ACADÉMICA</span><h1>Estudiantes</h1><p>Consulta y actualiza el padrón de estudiantes de la institución.</p></div>
    <a class="button button-primary" href="<?php echo e(route('students.create')); ?>"><span>＋</span> Nuevo estudiante</a>
</div>
<div class="section-guide"><span class="section-guide-icon">i</span><p><strong>¿Qué puedes hacer aquí?</strong> Registrar estudiantes y mantener actualizados sus datos, documentos y programa. Los estudiantes activos estarán disponibles para tomar asistencia.</p></div>
<section class="panel-card">
    <div class="list-toolbar">
        <div><h2>Directorio de estudiantes</h2><p><?php echo e($students->total()); ?> <?php echo e($students->total() === 1 ? 'estudiante' : 'estudiantes'); ?></p></div>
        <form class="search-form" method="GET" action="<?php echo e(route('students.index')); ?>"><span>⌕</span><input name="q" value="<?php echo e($search); ?>" placeholder="Buscar por nombre, código o programa..." aria-label="Buscar estudiantes"><button class="button button-light" type="submit">Buscar</button></form>
    </div>
    <div class="table-wrap"><table>
        <thead><tr><th>ESTUDIANTE</th><th>CÓDIGO / DOCUMENTO</th><th>PROGRAMA</th><th>ESTADO</th><th></th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><div class="student-cell"><span class="table-avatar"><?php echo e(mb_strtoupper(mb_substr($student->first_name, 0, 1))); ?></span><div><strong><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></strong><small><?php echo e($student->email ?: 'Sin correo registrado'); ?></small></div></div></td>
                <td><strong><?php echo e($student->student_code); ?></strong><small class="table-sub">DNI <?php echo e($student->document_number); ?></small></td>
                <td><?php echo e($student->studyPlan?->name ?? $student->program); ?></td>
                <td><span class="status <?php echo e($student->is_active ? 'status-presente' : 'status-ausente'); ?>"><?php echo e($student->is_active ? 'Activo' : 'Inactivo'); ?></span></td>
                <td><div class="row-actions"><a class="button button-small button-light" href="<?php echo e(route('students.edit', $student)); ?>">Editar</a><form method="POST" action="<?php echo e(route('students.destroy', $student)); ?>" onsubmit="return confirm('¿Eliminar este estudiante y sus registros de asistencia?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5"><div class="empty-state"><span>♙</span><strong><?php echo e($search !== '' ? 'No hay resultados para esta búsqueda' : 'Aún no hay estudiantes registrados'); ?></strong><p><?php echo e($search !== '' ? 'Revisa el nombre, código o programa e inténtalo nuevamente.' : 'Registra el primer estudiante para comenzar a organizar el padrón académico.'); ?></p><?php if($search === ''): ?><a class="button button-primary button-small" href="<?php echo e(route('students.create')); ?>">＋ Registrar estudiante</a><?php endif; ?></div></td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
    <div class="pagination-wrap"><?php echo e($students->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/students/index.blade.php ENDPATH**/ ?>