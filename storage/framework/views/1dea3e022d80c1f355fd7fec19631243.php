

<?php $__env->startSection('title', $student->exists ? 'Editar estudiante' : 'Nuevo estudiante'); ?>
<?php $__env->startSection('breadcrumb', 'Estudiantes / '.($student->exists ? 'Editar' : 'Nuevo')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA / ESTUDIANTES</span><h1><?php echo e($student->exists ? 'Editar estudiante' : 'Registrar estudiante'); ?></h1><p>Completa los datos de identificación y el programa académico.</p></div><a class="button button-light" href="<?php echo e(route('students.index')); ?>">← Volver al directorio</a></div>
<section class="panel-card form-card">
    <form method="POST" action="<?php echo e($student->exists ? route('students.update', $student) : route('students.store')); ?>" class="entity-form">
        <?php echo csrf_field(); ?> <?php if($student->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <?php if($errors->any()): ?><div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div><?php endif; ?>
        <div class="form-section"><span class="overline">PASO 1 · IDENTIFICACIÓN</span><h2>Datos del estudiante</h2><p>Usa el código institucional y el documento de identidad del estudiante.</p></div>
        <div class="form-grid">
            <label>Código de estudiante<input name="student_code" value="<?php echo e(old('student_code', $student->student_code)); ?>" maxlength="30" required placeholder="EC-2026-001"><?php $__errorArgs = ['student_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label>DNI / documento<input name="document_number" value="<?php echo e(old('document_number', $student->document_number)); ?>" maxlength="20" required><?php $__errorArgs = ['document_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label>Nombres<input name="first_name" value="<?php echo e(old('first_name', $student->first_name)); ?>" maxlength="100" required><?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label>Apellidos<input name="last_name" value="<?php echo e(old('last_name', $student->last_name)); ?>" maxlength="100" required><?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label>Correo electrónico<input name="email" type="email" value="<?php echo e(old('email', $student->email)); ?>" maxlength="255" placeholder="estudiante@institucion.edu"><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label>Programa académico<input name="program" value="<?php echo e(old('program', $student->program)); ?>" maxlength="120" required placeholder="Desarrollo de Sistemas"><?php $__errorArgs = ['program'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label>Estado<select name="is_active" required><option value="1" <?php if((string) old('is_active', $student->exists ? (int) $student->is_active : 1) === '1'): echo 'selected'; endif; ?>>Activo</option><option value="0" <?php if((string) old('is_active', $student->exists ? (int) $student->is_active : 1) === '0'): echo 'selected'; endif; ?>>Inactivo</option></select><?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        </div>
        <div class="form-actions"><a class="button button-light" href="<?php echo e(route('students.index')); ?>">Cancelar</a><button class="button button-primary" type="submit"><?php echo e($student->exists ? 'Guardar cambios' : 'Registrar estudiante'); ?></button></div>
    </form>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\Mesa de Partes\resources\views/students/form.blade.php ENDPATH**/ ?>