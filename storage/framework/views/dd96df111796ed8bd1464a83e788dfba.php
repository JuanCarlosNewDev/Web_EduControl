

<?php $__env->startSection('title', 'Notas · '.$course->name); ?>
<?php $__env->startSection('breadcrumb', 'Notas / '.$course->code); ?>

<?php $__env->startSection('content'); ?>
<div class="page-heading"><div><span class="overline">SEGUIMIENTO ACADÉMICO / <?php echo e($course->code); ?></span><h1><?php echo e($course->name); ?></h1><p><?php echo e($course->studyPlan?->name ?: 'Sin plan de estudio'); ?> · <?php echo e($course->term); ?> · notas de 0 a 20</p></div><a class="button button-light" href="<?php echo e(route('grades.index')); ?>">← Volver a cursos</a></div>
<?php if(! $course->study_plan_id): ?>
    <div class="alert alert-error">Este curso aún no tiene plan asignado. Administración debe asignarlo antes de registrar notas.</div>
<?php else: ?>
    <section class="panel-card form-card grade-assessment-form">
        <form method="POST" action="<?php echo e(route('grades.assessments.store', $course)); ?>" class="entity-form">
            <?php echo csrf_field(); ?>
            <div class="form-section"><span class="overline">EVALUACIONES</span><h2>Agregar evaluación</h2><p>El promedio se calcula con las notas registradas; una nota en blanco no cuenta.</p></div>
            <div class="form-grid">
                <label>Nombre de evaluación *<input name="title" value="<?php echo e(old('title')); ?>" maxlength="120" required placeholder="Práctica calificada 1"><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
                <label>Fecha *<input type="date" name="assessment_date" value="<?php echo e(old('assessment_date', today()->toDateString())); ?>" required><?php $__errorArgs = ['assessment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></label>
            </div>
            <div class="form-actions"><button class="button button-primary" type="submit">Crear evaluación</button></div>
        </form>
    </section>

    <?php if($assessments->isNotEmpty()): ?>
        <section class="panel-card grade-assessments">
            <div class="panel-heading"><div><span class="overline">LISTA DE EVALUACIONES</span><h2><?php echo e($assessments->count()); ?> <?php echo e($assessments->count() === 1 ? 'evaluación' : 'evaluaciones'); ?></h2></div></div>
            <div class="assessment-list">
                <?php $__currentLoopData = $assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="assessment-chip"><span><strong><?php echo e($assessment->title); ?></strong><small><?php echo e($assessment->assessment_date->format('d/m/Y')); ?></small></span><form method="POST" action="<?php echo e(route('grades.assessments.destroy', $assessment)); ?>" onsubmit="return confirm('¿Eliminar esta evaluación y todas sus notas?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="button button-small button-danger" type="submit">Eliminar</button></form></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="panel-card gradebook-card">
        <div class="panel-heading"><div><span class="overline">REGISTRO DE CALIFICACIONES</span><h2>Estudiantes del plan</h2><p><?php echo e($students->count()); ?> estudiantes activos · promedio simple</p></div></div>
        <?php if($assessments->isEmpty()): ?>
            <div class="empty-state"><span>✓</span><strong>Crea una evaluación para empezar</strong><p>Después podrás ingresar una nota de 0 a 20 por estudiante.</p></div>
        <?php elseif($students->isEmpty()): ?>
            <div class="empty-state"><span>♙</span><strong>No hay estudiantes activos en este plan</strong><p>Asigna estudiantes activos a <?php echo e($course->studyPlan?->name); ?> para ingresar calificaciones.</p></div>
        <?php else: ?>
            <form method="POST" action="<?php echo e(route('grades.save', $course)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <?php if($errors->any()): ?><div class="alert alert-error grade-alert">Revisa las notas. Cada calificación debe estar entre 0 y 20.</div><?php endif; ?>
                <div class="table-wrap"><table class="grade-table">
                    <thead><tr><th>ESTUDIANTE</th><?php $__currentLoopData = $assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th><?php echo e($assessment->title); ?><small><?php echo e($assessment->assessment_date->format('d/m')); ?></small></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><th>PROMEDIO</th></tr></thead>
                    <tbody>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr><td><strong><?php echo e($student->last_name); ?>, <?php echo e($student->first_name); ?></strong><small class="table-sub"><?php echo e($student->student_code); ?></small></td>
                            <?php $__currentLoopData = $assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php ($grade = $assessment->grades->firstWhere('student_id', $student->id)); ?>
                                <td><input class="grade-input" type="number" name="scores[<?php echo e($assessment->id); ?>][<?php echo e($student->id); ?>]" min="0" max="20" step="0.01" value="<?php echo e($grade?->score); ?>" aria-label="Nota de <?php echo e($student->first_name); ?> en <?php echo e($assessment->title); ?>"></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <td><strong class="grade-average"><?php echo e(isset($averages[$student->id]) ? number_format((float) $averages[$student->id], 2) : '—'); ?></strong></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table></div>
                <div class="grade-actions"><button class="button button-primary" type="submit">Guardar notas</button></div>
            </form>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\EduControl\resources\views/grades/show.blade.php ENDPATH**/ ?>