

<?php $__env->startSection('title', 'Asistencias'); ?>
<?php $__env->startSection('breadcrumb', 'Asistencias'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">CONTROL DE ASISTENCIA</span><h1>Asistencias</h1><p><?php echo e(auth()->user()->isAdmin() ? 'Supervisa y administra los registros de asistencia de toda la institución.' : 'Pasa asistencia y consulta los registros de tus cursos asignados.'); ?></p></div><a class="button button-primary" href="<?php echo e(route('attendances.create')); ?>"><span>＋</span><?php echo e(auth()->user()->isAdmin() ? 'Registrar o corregir' : 'Pasar asistencia'); ?></a></div>
<section class="role-explainer" aria-labelledby="attendance-purpose-heading">
    <span class="role-explainer-icon"><?php echo e(auth()->user()->isAdmin() ? '⌕' : '✓'); ?></span>
    <div>
        <h2 id="attendance-purpose-heading"><?php echo e(auth()->user()->isAdmin() ? '¿Para qué sirve Asistencias en Administración?' : 'Tu función docente en Asistencias'); ?></h2>
        <?php if(auth()->user()->isAdmin()): ?>
            <p>Este espacio sirve para supervisar la asistencia institucional: consulta registros de todos los cursos, usa los filtros y registra o corrige datos cuando sea necesario. La toma habitual de asistencia corresponde al docente durante su clase.</p>
            <div class="role-comparison"><span><strong>Administración</strong> Supervisa todos los cursos y gestiona correcciones.</span><span><strong>Docente</strong> Pasa lista en clase y trabaja solo con sus cursos asignados.</span></div>
        <?php else: ?>
            <p>Usa este espacio para pasar lista en cada clase: selecciona tu curso, fecha, estudiante y estado de asistencia. Puedes corregir los registros de tus propios cursos; Administración puede supervisar los registros de toda la institución.</p>
        <?php endif; ?>
    </div>
</section>
<section class="panel-card">
    <div class="list-toolbar"><div><h2><?php echo e(auth()->user()->isAdmin() ? 'Registros institucionales' : 'Asistencia de mis cursos'); ?></h2><p><?php echo e($attendances->total()); ?> <?php echo e($attendances->total() === 1 ? 'registro' : 'registros'); ?></p></div></div>
    <form class="filter-form" method="GET" action="<?php echo e(route('attendances.index')); ?>">
        <label class="filter-search"><span>⌕</span><input name="q" value="<?php echo e(request('q')); ?>" placeholder="Buscar estudiante..." aria-label="Buscar estudiante"></label>
        <select name="course_id" aria-label="Filtrar por curso"><option value="">Todos los cursos</option><?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($course->id); ?>" <?php if((string) request('course_id') === (string) $course->id): echo 'selected'; endif; ?>><?php echo e($course->code); ?> · <?php echo e($course->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <select name="status" aria-label="Filtrar por estado"><option value="">Todos los estados</option><?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>" <?php if(request('status') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
        <input type="date" name="date" value="<?php echo e(request('date')); ?>" aria-label="Filtrar por fecha">
        <button class="button button-light" type="submit">Filtrar</button>
        <?php if(request()->hasAny(['q', 'course_id', 'status', 'date'])): ?><a class="clear-filters" href="<?php echo e(route('attendances.index')); ?>">Limpiar</a><?php endif; ?>
    </form>
    <div class="table-wrap"><table>
        <thead><tr><th>ESTUDIANTE</th><th>CURSO</th><th>FECHA</th><th>ESTADO</th><th>OBSERVACIÓN</th><th></th></tr></thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><div class="student-cell"><span class="table-avatar"><?php echo e(mb_strtoupper(mb_substr($attendance->student->first_name, 0, 1))); ?></span><div><strong><?php echo e($attendance->student->first_name); ?> <?php echo e($attendance->student->last_name); ?></strong><small><?php echo e($attendance->student->student_code); ?></small></div></div></td><td><strong><?php echo e($attendance->course->name); ?></strong><small class="table-sub"><?php echo e($attendance->course->code); ?> · <?php echo e($attendance->course->section); ?></small></td><td><?php echo e($attendance->attendance_date->format('d/m/Y')); ?></td><td><span class="status status-<?php echo e($attendance->status); ?>"><?php echo e($statuses[$attendance->status]); ?></span></td><td class="notes-cell"><?php echo e($attendance->notes ?: '—'); ?></td><td><div class="row-actions"><a class="button button-small button-light" href="<?php echo e(route('attendances.edit', $attendance)); ?>">Editar</a><form method="POST" action="<?php echo e(route('attendances.destroy', $attendance)); ?>" onsubmit="return confirm('¿Eliminar este registro de asistencia?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-small button-danger" type="submit">Eliminar</button></form></div></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6"><div class="empty-state"><span>✓</span><strong>No encontramos asistencias</strong><p>Ajusta los filtros o registra una nueva asistencia.</p></div></td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
    <div class="pagination-wrap"><?php echo e($attendances->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/attendances/index.blade.php ENDPATH**/ ?>