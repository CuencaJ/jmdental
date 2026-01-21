@extends('layouts.app')

@section('content')

<section class="login-wrapper" style="padding: 80px 0;">
    <div class="container d-flex justify-content-center">
        <div class="col-lg-5">

            <div class="card shadow-sm p-4 rounded-3">
                <h3 class="text-center mb-4">Iniciar Sesión</h3>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" required class="form-control">
                    </div>

                    <button class="btn btn-primary w-100">Ingresar</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
