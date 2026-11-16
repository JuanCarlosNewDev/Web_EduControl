

<?php $__env->startSection('title', $schedule->exists ? 'Editar horario' : 'Nueva sesión'); ?>
<?php $__env->startSection('breadcrumb', 'Horarios / '.($schedule->exists ? 'Editar' : 'Nueva sesión')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">ORGANIZACIÓN ACADÉMICA / HORARIOS</span><h1><?php echo e($schedule->exists ? 'Editar sesión' : 'Agregar sesión semanal'); ?></h1><p>Un curso puede tener varias sesiones en distintos días.</p></div><a class="button button-light" href="<?php echo e(route('schedules.index')); ?>">← Volver a horarios</a></div>
<section class="panel-card form-card"><form method="POST" action="<?php echo e($schedule->exists ? route('schedules.update', $schedule) : route('schedules.store')); ?>" class="entity-form">
    <?php echo csrf_field(); ?> <?php if($schedule->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <?php if($errors->any()): ?><div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div><?php endif; ?>
    <div class="form-section"><span class="overline">SESIÓN RECURRENTE</span><h2>Datos del horario</h2><p>No se permite programar dos cursos del mismo docente en horas que se crucen.</p></div>
    <div class="form-grid">
        <label>Curso *<select name="course_id" required><option value="">Selecciona un curso</option><?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($course->id); ?>" <?php if((string) old('course_id', $schedule->course_id) === (string) $course->id): echo 'selected'; endif; ?>><?php echo e($course->code); ?> · <?php echo e($course->name); ?> · <?php echo e($course->teacher?->name ?: 'Sin docente'); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Día *<select name="day_of_week" required><option value="">Selecciona un día</option><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($day); ?>" <?php if((string) old('day_of_week', $schedule->day_of_week) === (string) $day): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['day_of_week'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Hora de inicio *<input type="time" name="starts_at" value="<?php echo e(old('starts_at', $schedule->exists ? substr($schedule->starts_at, 0, 5) : '')); ?>" required><?php $__errorArgs = ['starts_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Hora de fin *<input type="time" name="ends_at" value="<?php echo e(old('ends_at', $schedule->exists ? substr($schedule->ends_at, 0, 5) : '')); ?>" required><?php $__errorArgs = ['ends_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Aula<input name="classroom" value="<?php echo e(old('classroom', $schedule->classroom)); ?>" maxlength="80" placeholder="Aula 101"><?php $__errorArgs = ['classroom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
    </div>
    <div class="form-actions"><a class="button button-light" href="<?php echo e(route('schedules.index')); ?>">Cancelar</a><button class="button button-primary" type="submit"><?php echo e($schedule->exists ? 'Guardar cambios' : 'Agregar sesión'); ?></button></div>
</form></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/schedules/form.blade.php ENDPATH**/ ?>