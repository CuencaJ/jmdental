@extends('layouts.app')

@section('content')

<section class="login-wrapper" style="padding: 80px 0;">
    <div class="container d-flex justify-content-center">
        <div class="col-lg-5">

            <div class="card shadow-sm p-4 rounded-3 register-card">
                <h3 class="text-center mb-4 register-title">Crear cuenta</h3>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Nombre --}}
                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control register-input"
                            placeholder="Ingresa tu nombre completo"
                            required
                        >
                    </div>

                    {{-- Correo --}}
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control register-input"
                            placeholder="Ingresa tu correo"
                            required
                        >
                    </div>

                    {{-- Contraseña --}}
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control register-input"
                            placeholder="Ingresa tu contraseña"
                            required
                        >
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div class="mb-4">
                        <label class="form-label">Confirmar contraseña</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control register-input"
                            placeholder="Ingresa tu contraseña nuevamente"
                            required
                        >
                    </div>

                    {{-- Botón Registrar con mismo estilo que login --}}
                    <button type="submit" class="register-btn">
                        Registrar
                    </button>

                </form>

                <div class="text-center mt-4">
                    <p class="mb-1 text-muted">¿Ya tienes una cuenta?</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-4">
                        Iniciar sesión
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>

@endsection
