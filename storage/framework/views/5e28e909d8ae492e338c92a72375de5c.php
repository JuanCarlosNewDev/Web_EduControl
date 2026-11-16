

<?php $__env->startSection('title', 'Cargar sílabo'); ?>
<?php $__env->startSection('breadcrumb', 'Sílabos / Cargar'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">MATERIAL ACADÉMICO / SÍLABOS</span><h1><?php echo e(auth()->user()->isAdmin() ? 'Cargar sílabo PDF' : 'Subir mi sílabo'); ?></h1><p>El documento se guardará de forma privada; <?php echo e(auth()->user()->isAdmin() ? 'solo administración y docentes autorizados podrán descargarlo.' : 'el administrador deberá aprobarlo antes de publicarlo.'); ?></p></div><a class="button button-light" href="<?php echo e(route('syllabi.index')); ?>">← Volver a sílabos</a></div>
<section class="panel-card form-card"><form method="POST" action="<?php echo e(route('syllabi.store')); ?>" class="entity-form" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if($errors->any()): ?><div class="alert alert-error form-alert">Revisa los campos marcados e inténtalo nuevamente.</div><?php endif; ?>
    <?php if(! auth()->user()->isAdmin() && $courses->isEmpty()): ?>
        <div class="empty-state"><span>PDF</span><strong>No tienes cursos vinculados a un plan</strong><p>Solicita al administrador que asigne un plan de estudio a uno de tus cursos antes de subir el sílabo.</p></div>
    <?php else: ?>
        <div class="form-section"><span class="overline"><?php echo e(auth()->user()->isAdmin() ? 'PUBLICACIÓN POR PLAN' : 'SÍLABO DEL CURSO'); ?></span><h2>Documento académico</h2><p>Solo se aceptan archivos PDF de hasta 2 MB.</p></div>
        <div class="form-grid">
            <?php if(auth()->user()->isAdmin()): ?>
                <label>Plan de estudio *<select name="study_plan_id" required><option value="">Selecciona un plan</option><?php $__currentLoopData = $studyPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studyPlan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($studyPlan->id); ?>" <?php if((string) old('study_plan_id') === (string) $studyPlan->id): echo 'selected'; endif; ?>><?php echo e($studyPlan->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['study_plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <?php else: ?>
                <label>Curso asignado *<select name="course_id" required><option value="">Selecciona un curso</option><?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($course->id); ?>" <?php if((string) old('course_id') === (string) $course->id): echo 'selected'; endif; ?>><?php echo e($course->name); ?> — <?php echo e($course->studyPlan->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select><?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <?php endif; ?>
            <label>Título del sílabo *<input name="title" value="<?php echo e(old('title')); ?>" maxlength="150" required placeholder="Sílabo 2026-II"><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <label class="full-field">Archivo PDF *<input type="file" name="file" accept="application/pdf,.pdf" required><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <?php if(auth()->user()->isAdmin()): ?>
                <label>Publicación<select name="is_published"><option value="0" <?php if(old('is_published', '1') === '0'): echo 'selected'; endif; ?>>Guardar como borrador</option><option value="1" <?php if(old('is_published', '1') === '1'): echo 'selected'; endif; ?>>Publicar ahora</option></select><?php $__errorArgs = ['is_published'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            <?php else: ?>
                <p class="full-field">El sílabo quedará pendiente de aprobación. Podrás descargar tu archivo mientras lo revisa el administrador.</p>
            <?php endif; ?>
        </div>
        <div class="form-actions"><a class="button button-light" href="<?php echo e(route('syllabi.index')); ?>">Cancelar</a><button class="button button-primary" type="submit">Cargar sílabo</button></div>
    <?php endif; ?>
</form></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/syllabi/form.blade.php ENDPATH**/ ?>