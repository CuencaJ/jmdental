@extends('layouts.admin')

@section('titulo', 'Editar Usuario - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-admin')

    <main class="flex-1 flex flex-col overflow-hidden">

        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.usuarios.index') }}" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-xl font-bold text-slate-900">Editar Usuario</h1>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- ROL --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Rol del usuario</label>
                            <select name="rol" id="selectRol"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500" required>
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->name }}"
                                        {{ old('rol', $usuario->roles->first()?->name) == $rol->name ? 'selected' : '' }}>
                                        {{ ucfirst($rol->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- NOMBRES Y APELLIDOS --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Primer nombre</label>
                                <input type="text" name="primer_nombre"
                                    value="{{ old('primer_nombre', $usuario->primer_nombre) }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Juan" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Segundo nombre <span class="text-slate-400 font-normal">(opcional)</span>
                                </label>
                                <input type="text" name="segundo_nombre"
                                    value="{{ old('segundo_nombre', $usuario->segundo_nombre) }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Carlos">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Primer apellido</label>
                                <input type="text" name="primer_apellido"
                                    value="{{ old('primer_apellido', $usuario->primer_apellido) }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Sánchez" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Segundo apellido <span class="text-slate-400 font-normal">(opcional)</span>
                                </label>
                                <input type="text" name="segundo_apellido"
                                    value="{{ old('segundo_apellido', $usuario->segundo_apellido) }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Torres">
                            </div>
                        </div>

                        {{-- CÉDULA --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Cédula <span class="text-slate-400 font-normal">(se usa para iniciar sesión)</span>
                            </label>
                            <input type="text" name="cedula"
                                value="{{ old('cedula', $usuario->cedula) }}"
                                maxlength="10" inputmode="numeric"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="1712345678" required>
                        </div>

                        {{-- TELÉFONO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="0991234567" required>
                        </div>

                        {{-- EMAIL --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Correo electrónico</label>
                            <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="correo@ejemplo.com" required>
                        </div>

                        {{-- CONTRASEÑA --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Nueva contraseña <span class="text-slate-400 font-normal">(dejar vacío para no cambiar)</span>
                            </label>
                            <input type="password" name="password"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Mínimo 8 caracteres">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Confirmar nueva contraseña</label>
                            <input type="password" name="password_confirmation"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Repite la nueva contraseña">
                        </div>

                        {{-- ══════ CAMPOS EXTRA PARA ODONTÓLOGO ══════ --}}
                        <div id="camposOdontologo" class="space-y-6 hidden">

                            <div class="border-t border-slate-200 pt-6">
                                <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">medical_services</span>
                                    Información Profesional
                                </h3>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Especialidad</label>
                                <input type="text" name="especialidad"
                                    value="{{ old('especialidad', $usuario->odontologo->especialidad ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Odontología General, Ortodoncia">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Número de licencia</label>
                                <input type="text" name="numero_licencia"
                                    value="{{ old('numero_licencia', $usuario->odontologo->numero_licencia ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: LIC-101">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Universidad</label>
                                <input type="text" name="universidad"
                                    value="{{ old('universidad', $usuario->odontologo->universidad ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Universidad Central del Ecuador">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Título</label>
                                    <input type="text" name="titulo"
                                        value="{{ old('titulo', $usuario->odontologo->titulo ?? '') }}"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                        placeholder="Odontólogo">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Años de experiencia</label>
                                    <input type="number" name="anios_experiencia"
                                        value="{{ old('anios_experiencia', $usuario->odontologo->anios_experiencia ?? '') }}"
                                        min="0" max="70"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                        placeholder="8">
                                </div>
                            </div>
                        </div>

                        {{-- ══════ CAMPOS EXTRA PARA PACIENTE ══════ --}}
                        <div id="camposPaciente" class="space-y-6 hidden">

                            <div class="border-t border-slate-200 pt-6">
                                <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">person</span>
                                    Información Personal del Paciente
                                </h3>
                            </div>

                            {{-- SEXO --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Sexo <span class="text-slate-400 font-normal">(opcional)</span>
                                </label>
                                <select name="genero"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                                    <option value="">Prefiere no indicarlo</option>
                                    @foreach(['Masculino', 'Femenino', 'Otro'] as $g)
                                        <option value="{{ $g }}"
                                            {{ old('genero', $usuario->paciente->genero ?? '') == $g ? 'selected' : '' }}>
                                            {{ $g }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- FECHA NACIMIENTO --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Fecha de nacimiento</label>
                                <input type="date" name="fecha_nacimiento"
                                    value="{{ old('fecha_nacimiento', $usuario->paciente?->fecha_nacimiento?->format('Y-m-d')) }}"
                                    max="{{ now()->format('Y-m-d') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                            </div>

                            {{-- DIRECCIÓN --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Dirección</label>
                                <input type="text" name="direccion"
                                    value="{{ old('direccion', $usuario->paciente->direccion ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Av. Ejemplo N23-45, Ciudad">
                            </div>

                            <div class="border-t border-slate-200 pt-6">
                                <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">medical_information</span>
                                    Información Médica
                                </h3>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tipo de sangre</label>
                                <select name="tipo_sangre"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                                    <option value="">Selecciona el tipo</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                                        <option value="{{ $tipo }}"
                                            {{ old('tipo_sangre', $usuario->paciente->tipo_sangre ?? '') == $tipo ? 'selected' : '' }}>
                                            {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Alergias</label>
                                <textarea name="alergias" rows="2"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Penicilina, Ibuprofeno">{{ old('alergias', $usuario->paciente->alergias ?? '') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Enfermedades crónicas</label>
                                <textarea name="enfermedades_cronicas" rows="2"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Hipertensión, Diabetes">{{ old('enfermedades_cronicas', $usuario->paciente->enfermedades_cronicas ?? '') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Medicamentos actuales</label>
                                <textarea name="medicamentos_actuales" rows="2"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Losartán 50mg">{{ old('medicamentos_actuales', $usuario->paciente->medicamentos_actuales ?? '') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Médico de cabecera</label>
                                <input type="text" name="medico_cabecera"
                                    value="{{ old('medico_cabecera', $usuario->paciente->medico_cabecera ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Dr. Ramírez">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Observaciones médicas</label>
                                <textarea name="observaciones" rows="3"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Observaciones adicionales...">{{ old('observaciones', $usuario->paciente->observaciones ?? '') }}</textarea>
                            </div>

                            <div class="border-t border-slate-200 pt-6">
                                <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">contact_emergency</span>
                                    Contacto de Emergencia
                                </h3>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre del contacto</label>
                                <input type="text" name="contacto_emergencia"
                                    value="{{ old('contacto_emergencia', $usuario->paciente->contacto_emergencia ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Carlos Pérez">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Teléfono del contacto</label>
                                <input type="text" name="telefono_emergencia"
                                    value="{{ old('telefono_emergencia', $usuario->paciente->telefono_emergencia ?? '') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="0987654321">
                            </div>

                        </div>

                        {{-- BOTONES --}}
                        <div class="flex gap-4 pt-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors">
                                Guardar Cambios
                            </button>
                            <a href="{{ route('admin.usuarios.index') }}"
                                class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-colors">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection

@section('scripts')
<script>
    const selectRol = document.getElementById('selectRol');
    const camposPaciente = document.getElementById('camposPaciente');
    const camposOdontologo = document.getElementById('camposOdontologo');

    function toggleCamposPorRol() {
        camposPaciente.classList.toggle('hidden', selectRol.value !== 'paciente');
        camposOdontologo.classList.toggle('hidden', selectRol.value !== 'odontologo');
    }

    toggleCamposPorRol();
    selectRol.addEventListener('change', toggleCamposPorRol);
</script>
@endsection