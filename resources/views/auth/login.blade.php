@extends('layouts.guest')

@section('titulo', 'Iniciar Sesión - JM Dental')

@section('estilos')
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .bg-login-overlay {
        background-image: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)),
        url("{{ asset('assets/img/hero-bg.jpg') }}");
        background-size: cover;
        background-position: center;
    }
</style>
@endsection

@section('contenido')
<div class="relative min-h-screen w-full flex items-center justify-center bg-login-overlay px-4 py-12">

    {{-- CARD LOGIN --}}
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden p-8 sm:p-10">

        {{-- BOTÓN REGRESAR --}}
        <div class="mb-4">
            <a href="{{ url()->previous() === url()->current() ? route('inicio') : url()->previous() }}"
                class="flex items-center gap-2 text-slate-400 hover:text-blue-500 transition-colors text-sm font-semibold">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
                <span>Regresar</span>
            </a>
        </div>

        {{-- LOGO --}}
        <div class="flex flex-col items-center mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-blue-50 rounded-xl">
                    <img src="{{ asset('assets/img/logo.png') }}" class="w-10 h-10 object-contain">
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-slate-900">JM Dental</span>
            </div>
            <div class="text-center">
                <h1 class="text-2xl font-black tracking-tight text-slate-900 mb-2">Bienvenido de nuevo</h1>
                <p class="text-slate-500 text-sm">Inicia sesión en tu cuenta</p>
            </div>
        </div>

        {{-- ERRORES --}}
        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORMULARIO --}}
        <form action="{{ route('login.iniciar') }}" method="POST" class="space-y-5">
            @csrf

            {{-- EMAIL --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Correo Electrónico
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-xl">mail</span>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300"
                        placeholder="nombre@ejemplo.com" required autofocus>
                </div>
            </div>

            {{-- CONTRASEÑA --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Contraseña
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-xl">lock</span>
                    </div>
                    <input type="password" name="password" id="password"
                        class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300"
                        placeholder="••••••••" required>
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-blue-500">
                        <span class="material-symbols-outlined text-xl" id="eyeIcon">visibility</span>
                    </button>
                </div>
                <div class="flex justify-end">
                    <a class="text-xs font-bold text-blue-500 hover:text-blue-600 transition-colors" href="#">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            {{-- MANTENERME CONECTADO --}}
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember"
                    class="h-4 w-4 rounded border-slate-200 text-blue-500 focus:ring-blue-500 cursor-pointer">
                <label class="ml-2 text-sm text-slate-500 cursor-pointer" for="remember">
                    Mantenerme conectado
                </label>
            </div>

            {{-- BOTÓN --}}
            <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-blue-500 to-blue-700 hover:brightness-110 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2 group">
                <span>Iniciar Sesión</span>
                <span class="material-symbols-outlined group-hover:translate-x-0.5 transition-transform">login</span>
            </button>

        </form>

        {{-- REGISTRO --}}
        <div class="mt-8 pt-6 border-t border-slate-200 text-center">
            <p class="text-sm text-slate-500">
                ¿Nuevo en JM Dental?
                <a class="font-bold text-blue-500 hover:text-blue-600 transition-colors ml-1"
                    href="{{ route('registro') }}">Crear una cuenta</a>
            </p>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endsection