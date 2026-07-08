@extends('layouts.admin')

@section('titulo', 'Mi Perfil - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-paciente')

    <main class="flex-1 flex flex-col overflow-hidden">

        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <h1 class="text-lg font-bold text-slate-900">Mi Perfil</h1>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-3xl mx-auto space-y-6">

                @if(session('mensaje'))
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl">
                        {{ session('mensaje') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- CABECERA --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-5">
                    <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                        {{ strtoupper(substr($usuario->name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-slate-900">{{ $usuario->name }}</h1>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $usuario->email }}</p>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span class="inline-block bg-purple-50 text-purple-500 text-xs font-bold px-3 py-1 rounded-full">Paciente</span>
                            @if($usuario->paciente?->fecha_nacimiento)
                                <span class="inline-block bg-blue-50 text-blue-500 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $usuario->paciente->edad }} años
                                </span>
                                <span class="inline-block text-xs font-bold px-3 py-1 rounded-full {{ $usuario->paciente->color_denticion }}">
                                    Dentición {{ $usuario->paciente->tipo_denticion }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- FORMULARIO --}}
                <form action="{{ route('paciente.perfil.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- DATOS PERSONALES --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h2 class="font-bold text-slate-900">Datos personales</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Nombre completo</label>
                                <input type="text" name="name" required value="{{ old('name', $usuario->name) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Correo electrónico</label>
                                <input type="email" name="email" required value="{{ old('email', $usuario->email) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Teléfono</label>
                                <input type="text" name="telefono" required value="{{ old('telefono', $usuario->telefono) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Cédula</label>
                                <input type="text" name="cedula" value="{{ old('cedula', $usuario->paciente?->cedula) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">
                                    Fecha de nacimiento
                                    @if($usuario->paciente?->fecha_nacimiento)
                                        <span class="ml-1 text-blue-500 font-bold">
                                            ({{ $usuario->paciente->edad }} años — {{ $usuario->paciente->tipo_denticion }})
                                        </span>
                                    @endif
                                </label>
                                <input type="date" name="fecha_nacimiento" id="campo-fecha-nac"
                                    value="{{ old('fecha_nacimiento', $usuario->paciente?->fecha_nacimiento?->format('Y-m-d')) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                <p id="preview-edad" class="text-xs text-blue-500 font-medium mt-1 hidden"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Dirección</label>
                                <input type="text" name="direccion" value="{{ old('direccion', $usuario->paciente?->direccion) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>

                    {{-- DATOS MÉDICOS --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h2 class="font-bold text-slate-900">Datos médicos</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Tipo de sangre</label>
                                <select name="tipo_sangre"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                                    <option value="">Selecciona</option>
                                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo_sangre', $usuario->paciente?->tipo_sangre) == $tipo ? 'selected' : '' }}>
                                            {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Alergias conocidas</label>
                                <input type="text" name="alergias" value="{{ old('alergias', $usuario->paciente?->alergias) }}"
                                    placeholder="Ej. Penicilina, látex..."
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Contacto de emergencia</label>
                                <input type="text" name="contacto_emergencia" value="{{ old('contacto_emergencia', $usuario->paciente?->contacto_emergencia) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Teléfono de emergencia</label>
                                <input type="text" name="telefono_emergencia" value="{{ old('telefono_emergencia', $usuario->paciente?->telefono_emergencia) }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>

                    {{-- CAMBIAR CONTRASEÑA --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h2 class="font-bold text-slate-900">Cambiar contraseña <span class="text-slate-400 font-normal text-sm">(opcional)</span></h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Contraseña actual</label>
                                <input type="password" name="password_actual"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Nueva contraseña</label>
                                <input type="password" name="password"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
                            Guardar cambios
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('campo-fecha-nac').addEventListener('change', function() {
    if (!this.value) return;
    const fecha = new Date(this.value);
    const hoy = new Date();
    let edad = hoy.getFullYear() - fecha.getFullYear();
    const mes = hoy.getMonth() - fecha.getMonth();
    if (mes < 0 || (mes === 0 && hoy.getDate() < fecha.getDate())) edad--;

    let denticion = '';
    if (edad < 6) denticion = 'Temporal';
    else if (edad < 13) denticion = 'Mixta';
    else denticion = 'Permanente';

    const preview = document.getElementById('preview-edad');
    preview.textContent = edad + ' años — Dentición ' + denticion;
    preview.classList.remove('hidden');
});
</script>
@endsection