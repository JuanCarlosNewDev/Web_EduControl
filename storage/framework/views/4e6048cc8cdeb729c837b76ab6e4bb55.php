

<?php $__env->startSection('title', $studyPlan->exists ? 'Editar plan' : 'Nuevo plan'); ?>
<?php $__env->startSection('breadcrumb', 'Planes de estudio / '.($studyPlan->exists ? 'Editar' : 'Nuevo')); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">GESTIÓN ACADÉMICA / PLANES</span><h1><?php echo e($studyPlan->exists ? 'Editar plan de estudio' : 'Crear plan de estudio'); ?></h1><p>Los planes activos pueden asignarse a nuevos cursos y estudiantes.</p></div><a class="button button-light" href="<?php echo e(route('study-plans.index')); ?>">← Volver a planes</a></div>
<section class="panel-card form-card"><form method="POST" action="<?php echo e($studyPlan->exists ? route('study-plans.update', $studyPlan) : route('study-plans.store')); ?>" class="entity-form">
    <?php echo csrf_field(); ?> <?php if($studyPlan->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <?php if($errors->any()): ?><div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div><?php endif; ?>
    <div class="form-section"><span class="overline">PROGRAMA ACADÉMICO</span><h2>Datos del plan</h2></div>
    <div class="form-grid">
        <label>Código *<input name="code" value="<?php echo e(old('code', $studyPlan->code)); ?>" maxlength="40" required placeholder="DES-SIS-2026"><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Nombre *<input name="name" value="<?php echo e(old('name', $studyPlan->name)); ?>" maxlength="150" required placeholder="Desarrollo de Sistemas"><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label class="full-field">Descripción<textarea name="description" rows="4" maxlength="2000"><?php echo e(old('description', $studyPlan->description)); ?></textarea><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
        <label>Estado<select name="is_active" required><option value="1" <?php if((string) old('is_active', $studyPlan->exists ? (int) $studyPlan->is_active : 1) === '1'): echo 'selected'; endif; ?>>Activo</option><option value="0" <?php if((string) old('is_active', $studyPlan->exists ? (int) $studyPlan->is_active : 1) === '0'): echo 'selected'; endif; ?>>Inactivo</option></select><?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
    </div>
    <div class="form-actions"><a class="button button-light" href="<?php echo e(route('study-plans.index')); ?>">Cancelar</a><button class="button button-primary" type="submit"><?php echo e($studyPlan->exists ? 'Guardar cambios' : 'Crear plan'); ?></button></div>
</form></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/study-plans/form.blade.php ENDPATH**/ ?>