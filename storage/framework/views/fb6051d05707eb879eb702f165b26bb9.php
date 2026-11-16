

<?php $__env->startSection('title', $attendance->exists ? 'Editar asistencia' : 'Registrar asistencia'); ?>
<?php $__env->startSection('breadcrumb', 'Asistencias / '.($attendance->exists ? 'Editar' : 'Nuevo registro')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">CONTROL DE ASISTENCIA</span><h1><?php echo e($attendance->exists ? 'Editar asistencia' : (auth()->user()->isAdmin() ? 'Registrar o corregir asistencia' : 'Pasar asistencia')); ?></h1><p><?php echo e(auth()->user()->isAdmin() ? 'Registra una asistencia o corrige un dato reportado para cualquier curso.' : 'Registra la asistencia de tus estudiantes durante la clase.'); ?></p></div><a class="button button-light" href="<?php echo e(route('attendances.index')); ?>">← Volver a asistencias</a></div>
<div class="form-role-note"><strong><?php echo e(auth()->user()->isAdmin() ? 'Acceso administrativo' : 'Acceso docente'); ?></strong><span><?php echo e(auth()->user()->isAdmin() ? 'Puedes gestionar registros de todos los cursos. La asistencia diaria debe ser tomada por el docente responsable.' : 'Solo puedes registrar o editar la asistencia de los cursos que tienes asignados.'); ?></span></div>
<section class="panel-card form-card"><form method="POST" action="<?php echo e($attendance->exists ? route('attendances.update', $attendance) : route('attendances.store')); ?>" class="entity-form">
    <?php echo csrf_field(); ?> <?php if($attendance->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <?php if($errors->any()): ?><div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div><?php endif; ?>
    <?php if($courses->isEmpty()): ?><div class="alert alert-error">No tienes cursos asignados. Solicita al administrador que asigne un curso a tu cuenta.</div><?php endif; ?>
    <div class="form-section"><h2>Detalle de la asistencia</h2><p>Solo puede existir un registro por estudiante, curso y fecha.</p></div>
    <div class="form-grid">
        <label>Curso *<select name="course_id" required><option value="">Selecciona un curso</option><?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($course->id); ?>" <?php if((string) old('course_id', $attendance->course_id) === (string) $course->id): echo 'selected'; endif; ?>><?php echo e($course->code); ?> · <?php echo e($course->name); ?> (<?php echo e($course->section); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Estudiante *<select name="student_id" required><option value="">Selecciona un estudiante</option><?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($student->id); ?>" <?php if((string) old('student_id', $attendance->student_id) === (string) $student->id): echo 'selected'; endif; ?>><?php echo e($student->student_code); ?> · <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Fecha de clase *<input type="date" name="attendance_date" value="<?php echo e(old('attendance_date', $attendance->attendance_date?->format('Y-m-d') ?? today()->toDateString())); ?>" max="<?php echo e(today()->toDateString()); ?>" required><?php $__errorArgs = ['attendance_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Estado *<select name="status" required><option value="">Selecciona un estado</option><?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($key); ?>" <?php if(old('status', $attendance->status ?: 'presente') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label class="full-field">Observación<textarea name="notes" rows="4" maxlength="1000" placeholder="Agrega una observación si es necesario..."><?php echo e(old('notes', $attendance->notes)); ?></textarea><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
    </div>
    <div class="form-actions"><a class="button button-light" href="<?php echo e(route('attendances.index')); ?>">Cancelar</a><button class="button button-primary" type="submit" <?php if($courses->isEmpty()): echo 'disabled'; endif; ?>><?php echo e($attendance->exists ? (auth()->user()->isAdmin() ? 'Guardar corrección' : 'Actualizar asistencia') : (auth()->user()->isAdmin() ? 'Guardar registro' : 'Guardar asistencia')); ?></button></div>
</form></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\Mesa de Partes\resources\views/attendances/form.blade.php ENDPATH**/ ?>