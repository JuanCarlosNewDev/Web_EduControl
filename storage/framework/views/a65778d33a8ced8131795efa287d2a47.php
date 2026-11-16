

<?php $__env->startSection('title', 'Sílabos'); ?>
<?php $__env->startSection('breadcrumb', 'Sílabos'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">MATERIAL ACADÉMICO</span><h1>Sílabos por curso y plan</h1><p><?php echo e(auth()->user()->isAdmin() ? 'Revisa, publica y administra los sílabos PDF.' : 'Consulta los sílabos publicados de tus cursos y envía los tuyos para aprobación.'); ?></p></div><a class="button button-primary" href="<?php echo e(route('syllabi.create')); ?>"><span>＋</span> <?php echo e(auth()->user()->isAdmin() ? 'Cargar sílabo PDF' : 'Subir mi sílabo'); ?></a></div>
<?php $__empty_1 = true; $__currentLoopData = $studyPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studyPlan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <section class="panel-card syllabus-plan">
        <div class="panel-heading"><div><span class="overline"><?php echo e($studyPlan->code); ?></span><h2><?php echo e($studyPlan->name); ?></h2><p><?php echo e($studyPlan->syllabi->count()); ?> <?php echo e($studyPlan->syllabi->count() === 1 ? 'sílabo disponible' : 'sílabos disponibles'); ?></p></div></div>
        <div class="syllabus-list">
            <?php $__empty_2 = true; $__currentLoopData = $studyPlan->syllabi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $syllabus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                <article class="syllabus-row"><span class="syllabus-icon">PDF</span><div class="syllabus-copy"><strong><?php echo e($syllabus->title); ?></strong><small><?php echo e($syllabus->course ? $syllabus->course->name.' · ' : ''); ?><?php echo e($syllabus->original_filename); ?> · <?php echo e($syllabus->created_at->format('d/m/Y')); ?><?php if(auth()->user()->isAdmin() && $syllabus->uploadedBy): ?> · Subido por <?php echo e($syllabus->uploadedBy->name); ?><?php endif; ?></small></div>
                    <?php if(auth()->user()->isAdmin() || ! $syllabus->is_published): ?>
                        <span class="status <?php echo e($syllabus->is_published ? 'status-presente' : 'status-tardanza'); ?>"><?php echo e($syllabus->is_published ? 'Publicado' : 'Pendiente de aprobación'); ?></span>
                    <?php endif; ?>
                    <a class="button button-small button-light" href="<?php echo e(route('syllabi.download', $syllabus)); ?>">Descargar</a>
                    <?php if(auth()->user()->isAdmin()): ?>
                        <form method="POST" action="<?php echo e(route('syllabi.publication', $syllabus)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><input type="hidden" name="is_published" value="<?php echo e($syllabus->is_published ? 0 : 1); ?>"><button class="button button-small <?php echo e($syllabus->is_published ? 'button-light' : 'button-primary'); ?>" type="submit"><?php echo e($syllabus->is_published ? 'Retirar' : 'Publicar'); ?></button></form>
                        <form method="POST" action="<?php echo e(route('syllabi.destroy', $syllabus)); ?>" onsubmit="return confirm('¿Eliminar este sílabo PDF?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-small button-danger" type="submit">Eliminar</button></form>
                    <?php endif; ?>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                <div class="empty-state"><span>PDF</span><strong><?php echo e(auth()->user()->isAdmin() ? 'Este plan aún no tiene sílabos' : 'No hay sílabos para este plan'); ?></strong><p><?php echo e(auth()->user()->isAdmin() ? 'Carga el documento PDF para que los docentes puedan consultarlo.' : 'El administrador aún no ha publicado documentos y no has enviado un sílabo para aprobación.'); ?></p></div>
            <?php endif; ?>
        </div>
    </section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <section class="panel-card"><div class="empty-state"><span>PDF</span><strong><?php echo e(auth()->user()->isAdmin() ? 'No hay planes de estudio' : 'No tienes cursos asignados a un plan'); ?></strong><p><?php echo e(auth()->user()->isAdmin() ? 'Crea primero un plan de estudio para publicar sílabos.' : 'Solicita al administrador que asocie tus cursos con un plan.'); ?></p></div></section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/syllabi/index.blade.php ENDPATH**/ ?>