@extends('layout')

@section('title', 'Iniciar sesión')

@section('content')
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
            @if ($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif
            <form method="POST" action="{{ route('login.submit') }}" class="stack-form">
                @csrf
                <label for="email">Correo institucional</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nombre@institucion.edu" autocomplete="username" required autofocus>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
                <label class="check-label"><input type="checkbox" name="remember" value="1"> Mantener sesión iniciada</label>
                <button class="button button-primary button-wide" type="submit">Ingresar al sistema <span>→</span></button>
            </form>
            <div class="demo-access"><strong>Acceso de demostración</strong><span>Administrador: admin@educontrol.test</span><span>Docente: docente@educontrol.test</span><small>Contraseña para ambos: password</small></div>
        </div>
    </section>
</main>
@endsection
