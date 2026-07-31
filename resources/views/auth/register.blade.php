@extends('layouts.guest')

@section('titulo', 'Crear Cuenta - JM Dental')

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

    {{-- CARD REGISTRO --}}
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
                    <span class="material-symbols-outlined text-blue-500 text-4xl">dentistry</span>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-slate-900">JM Dental</span>
            </div>
            <div class="text-center">
                <h1 class="text-2xl font-black tracking-tight text-slate-900 mb-2">Crear una cuenta</h1>
                <p class="text-slate-500 text-sm">Regístrate para agendar tus citas</p>
            </div>
        </div>

        {{-- ERRORES --}}
        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORMULARIO --}}
        <form id="formRegistro" action="{{ route('registro.guardar') }}" method="POST" class="space-y-4">
            @csrf

            {{-- NOMBRE COMPLETO --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Nombres y Apellidos Completos
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-xl">person</span>
                    </div>
                    <input type="text" id="nombreCompleto" name="nombre_completo"
                        value="{{ old('primer_nombre') ? trim(old('primer_nombre').' '.old('segundo_nombre').' '.old('primer_apellido').' '.old('segundo_apellido')) : old('nombre_completo') }}"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300"
                        placeholder="Nombre Nombre Apellido Apellido" required autofocus autocomplete="name">
                </div>
                <p class="text-[11px] text-slate-400 leading-snug">
                    Escribe tus nombres y apellidos, en ese orden (ej. Juan Carlos Sánchez Torres).
                </p>

                {{-- Campos reales que se envían --}}
                <input type="hidden" name="primer_nombre"    id="primerNombre"    value="{{ old('primer_nombre') }}">
                <input type="hidden" name="segundo_nombre"   id="segundoNombre"   value="{{ old('segundo_nombre') }}">
                <input type="hidden" name="primer_apellido"  id="primerApellido"  value="{{ old('primer_apellido') }}">
                <input type="hidden" name="segundo_apellido" id="segundoApellido" value="{{ old('segundo_apellido') }}">

                {{-- Vista previa del desglose --}}
                <div id="previewNombre" class="hidden mt-1 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                        Así se registrará
                    </p>
                    <div class="grid grid-cols-2 gap-x-3 gap-y-1 text-xs">
                        <div><span class="text-slate-400">1er nombre:</span> <span id="pvN1" class="font-semibold text-slate-700">—</span></div>
                        <div><span class="text-slate-400">2do nombre:</span> <span id="pvN2" class="font-semibold text-slate-700">—</span></div>
                        <div><span class="text-slate-400">1er apellido:</span> <span id="pvA1" class="font-semibold text-slate-700">—</span></div>
                        <div><span class="text-slate-400">2do apellido:</span> <span id="pvA2" class="font-semibold text-slate-700">—</span></div>
                    </div>
                </div>
            </div>

            {{-- CÉDULA --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Cédula
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-xl">badge</span>
                    </div>
                    <input type="text" name="cedula" value="{{ old('cedula') }}" maxlength="10" inputmode="numeric"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300"
                        placeholder="1234567890" required>
                </div>
                <p class="text-[11px] text-slate-400">Con esta cédula iniciarás sesión.</p>
            </div>

            {{-- FECHA DE NACIMIENTO --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Fecha de Nacimiento
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-xl">cake</span>
                    </div>
                    <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                        max="{{ now()->format('Y-m-d') }}"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        required>
                </div>
            </div>

            {{-- SEXO (OPCIONAL) --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Sexo <span class="normal-case font-medium text-slate-400">(opcional)</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-xl">wc</span>
                    </div>
                    <select name="genero"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white">
                        <option value="">Prefiero no indicarlo</option>
                        <option value="Masculino" @selected(old('genero') === 'Masculino')>Masculino</option>
                        <option value="Femenino" @selected(old('genero') === 'Femenino')>Femenino</option>
                        <option value="Otro" @selected(old('genero') === 'Otro')>Otro</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-xl">expand_more</span>
                    </div>
                </div>
            </div>

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
                        placeholder="nombre@ejemplo.com" required>
                </div>
            </div>

            {{-- TELÉFONO --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Teléfono
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-xl">phone</span>
                    </div>
                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300"
                        placeholder="0991234567" required>
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
                    <input type="password" name="password" id="password" autocomplete="new-password"
                        class="w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300"
                        placeholder="Mínimo 8 caracteres" required>
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-blue-500">
                        <span class="material-symbols-outlined text-xl" id="eyeIcon">visibility</span>
                    </button>
                </div>
            </div>

            {{-- CONFIRMAR CONTRASEÑA --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Confirmar Contraseña
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-xl">lock_reset</span>
                    </div>
                    <input type="password" name="password_confirmation" autocomplete="new-password"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-300"
                        placeholder="Repite tu contraseña" required>
                </div>
            </div>

            {{-- BOTÓN --}}
            <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-blue-500 to-blue-700 hover:brightness-110 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2 group">
                <span>Crear Cuenta</span>
                <span class="material-symbols-outlined group-hover:translate-x-0.5 transition-transform">person_add</span>
            </button>

        </form>

        {{-- LOGIN --}}
        <div class="mt-8 pt-6 border-t border-slate-200 text-center">
            <p class="text-sm text-slate-500">
                ¿Ya tienes una cuenta?
                <a class="font-bold text-blue-500 hover:text-blue-600 transition-colors ml-1"
                    href="{{ route('login') }}">Iniciar Sesión</a>
            </p>
        </div>

    </div>

</div>

{{-- JS INLINE: no depende de que el layout tenga @yield('scripts') --}}
<script>
(function () {
    var input   = document.getElementById('nombreCompleto');
    var form    = document.getElementById('formRegistro');
    var preview = document.getElementById('previewNombre');

    if (!input || !form) { return; }

    // Divide el nombre completo en 2 nombres + 2 apellidos.
    //   4+ palabras -> Nombre [Nombres...] Apellido Apellido
    //   3 palabras  -> Nombre Apellido Apellido
    //   2 palabras  -> Nombre Apellido
    function repartirNombre() {
        var t = input.value.trim().split(/\s+/).filter(function (p) { return p.length > 0; });
        var n1 = '', n2 = '', a1 = '', a2 = '';

        if (t.length === 1) {
            n1 = t[0];
        } else if (t.length === 2) {
            n1 = t[0]; a1 = t[1];
        } else if (t.length === 3) {
            n1 = t[0]; a1 = t[1]; a2 = t[2];
        } else if (t.length >= 4) {
            n1 = t[0];
            a2 = t[t.length - 1];
            a1 = t[t.length - 2];
            n2 = t.slice(1, t.length - 2).join(' ');
        }

        document.getElementById('primerNombre').value    = n1;
        document.getElementById('segundoNombre').value   = n2;
        document.getElementById('primerApellido').value  = a1;
        document.getElementById('segundoApellido').value = a2;

        if (preview) {
            if (t.length === 0) {
                preview.classList.add('hidden');
            } else {
                preview.classList.remove('hidden');
                document.getElementById('pvN1').textContent = n1 || '—';
                document.getElementById('pvN2').textContent = n2 || '—';
                document.getElementById('pvA1').textContent = a1 || '—';
                document.getElementById('pvA2').textContent = a2 || '—';
            }
        }
    }

    input.addEventListener('input', repartirNombre);
    // Red de seguridad: rellena los ocultos justo antes de enviar.
    form.addEventListener('submit', repartirNombre);
    repartirNombre();
})();

function togglePassword() {
    var input = document.getElementById('password');
    var icon  = document.getElementById('eyeIcon');
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