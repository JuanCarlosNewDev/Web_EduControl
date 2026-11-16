

<?php $__env->startSection('title', $course->exists ? 'Editar curso' : 'Nuevo curso'); ?>
<?php $__env->startSection('breadcrumb', 'Cursos / '.($course->exists ? 'Editar' : 'Nuevo')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA / CURSOS</span><h1><?php echo e($course->exists ? 'Editar curso' : 'Crear curso'); ?></h1><p>Define el periodo, el plan y el docente responsable; configura las sesiones en Horarios.</p></div><a class="button button-light" href="<?php echo e(route('courses.index')); ?>">← Volver al catálogo</a></div>
<section class="panel-card form-card"><form method="POST" action="<?php echo e($course->exists ? route('courses.update', $course) : route('courses.store')); ?>" class="entity-form">
    <?php echo csrf_field(); ?> <?php if($course->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <?php if($errors->any()): ?><div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div><?php endif; ?>
    <div class="form-section"><span class="overline">CONFIGURACIÓN ACADÉMICA</span><h2>Información del curso</h2><p>Los campos con * son obligatorios. Asigna un docente para habilitar la toma de asistencia en su cuenta.</p></div>
    <div class="form-grid">
        <label>Código *<input name="code" value="<?php echo e(old('code', $course->code)); ?>" maxlength="30" required placeholder="PRG-101"><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Nombre del curso *<input name="name" value="<?php echo e(old('name', $course->name)); ?>" maxlength="150" required><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Sección *<input name="section" value="<?php echo e(old('section', $course->section ?: 'A')); ?>" maxlength="20" required><?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Periodo académico *<input name="term" value="<?php echo e(old('term', $course->term)); ?>" maxlength="40" required placeholder="2026-II"><?php $__errorArgs = ['term'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Plan de estudio *<select name="study_plan_id" required><option value="">Selecciona un plan</option><?php $__currentLoopData = $studyPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studyPlan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($studyPlan->id); ?>" <?php if((string) old('study_plan_id', $course->study_plan_id) === (string) $studyPlan->id): echo 'selected'; endif; ?>><?php echo e($studyPlan->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['study_plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Docente responsable<select name="teacher_id"><option value="">Sin asignar</option><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($teacher->id); ?>" <?php if((string) old('teacher_id', $course->teacher_id) === (string) $teacher->id): echo 'selected'; endif; ?>><?php echo e($teacher->name); ?> · <?php echo e($teacher->email); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
    </div>
    <div class="form-actions"><a class="button button-light" href="<?php echo e(route('courses.index')); ?>">Cancelar</a><button class="button button-primary" type="submit"><?php echo e($course->exists ? 'Guardar cambios' : 'Crear curso'); ?></button></div>
</form></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/courses/form.blade.php ENDPATH**/ ?>