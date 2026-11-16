

<?php $__env->startSection('title', 'Iniciar sesión'); ?>

<?php $__env->startSection('content'); ?>
<main class="login-page">
    <section class="login-art">
        <div class="login-brand"><span class="brand-mark">E</span><strong>EduControl</strong></div>
        <div class="login-message">
            <span class="overline">GESTIÓN ACADÉMICA INTELIGENTE</span>
            <h1>La asistencia,<br>siempre bajo control.</h1>
            <p>Una experiencia sencilla para organizar tus cursos y acompañar el progreso de cada estudiante.</p>
        </div>
        <div class="login-art-foot">EDUCONTROL <span>·</span> 2026</div>
    </section>
    <section class="login-panel">
        <div class="login-form-wrap">
            <div class="mobile-login-brand"><span class="brand-mark">E</span><strong>EduControl</strong></div>
            <span class="overline">BIENVENIDO DE NUEVO</span>
            <h2>Iniciar sesión</h2>
            <p class="muted">Ingresa tus credenciales para continuar.</p>
            <?php if($errors->any()): ?><div class="alert alert-error"><?php echo e($errors->first()); ?></div><?php endif; ?>
            <form method="POST" action="<?php echo e(route('login.submit')); ?>" class="stack-form">
                <?php echo csrf_field(); ?>
                <label for="email">Correo institucional</label>
                <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" placeholder="nombre@institucion.edu" autocomplete="username" required autofocus>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="field-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <label class="check-label"><input type="checkbox" name="remember" value="1"> Mantener sesión iniciada</label>
                <button class="button button-primary button-wide" type="submit">Ingresar al sistema <span>→</span></button>
            </form>
            <div class="demo-access"><strong>Acceso de demostración</strong><span>Administrador: admin@educontrol.test</span><span>Docente: docente@educontrol.test</span><small>Contraseña para ambos: password</small></div>
        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Estudiante\Desktop\Mesa de Partes\resources\views/auth/login.blade.php ENDPATH**/ ?>