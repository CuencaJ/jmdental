@extends('layouts.admin')

@section('titulo', 'Crear Usuario - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-admin')

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.usuarios.index') }}" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-xl font-bold text-slate-900">Crear Nuevo Usuario</h1>
            </div>
        </header>

        {{-- FORMULARIO --}}
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

                    {{-- ERRORES --}}
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- ROL --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Rol del usuario
                            </label>
                            <select name="rol" id="selectRol"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500" required>
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->name }}"
                                        {{ (old('rol') ?? request('rol')) == $rol->name ? 'selected' : '' }}>
                                        {{ ucfirst($rol->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- NOMBRE --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Nombre completo
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Ingresa el nombre completo" required>
                        </div>

                        {{-- TELÉFONO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="0991234567" required>
                        </div>

                        {{-- GÉNERO --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Género
                            </label>
                            <select name="genero"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                                <option value="">Selecciona el género</option>
                                <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        {{-- EMAIL --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Correo electrónico
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="correo@ejemplo.com" required>
                        </div>

                        {{-- CONTRASEÑA --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Contraseña
                            </label>
                            <input type="password" name="password"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Mínimo 8 caracteres" required>
                        </div>

                        {{-- CONFIRMAR CONTRASEÑA --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Confirmar contraseña
                            </label>
                            <input type="password" name="password_confirmation"
                                class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                placeholder="Repite la contraseña" required>
                        </div>

                        {{-- CAMPOS EXTRA PARA PACIENTE --}}
                        <div id="camposPaciente" class="space-y-6 hidden">

                            <div class="border-t border-slate-200 pt-6">
                                <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">person</span>
                                    Información Personal del Paciente
                                </h3>
                            </div>

                            {{-- CÉDULA --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Cédula</label>
                                <input type="text" name="cedula" value="{{ old('cedula') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="1712345678" maxlength="10">
                            </div>

                            {{-- FECHA NACIMIENTO --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Fecha de nacimiento</label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                    value="{{ old('fecha_nacimiento') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    onchange="calcularEdad()">
                                <div id="infoEdad" class="mt-2 hidden flex items-center gap-2">
                                    <span class="text-sm text-slate-500">Edad:</span>
                                    <span id="edadTexto" class="text-sm font-bold text-slate-900"></span>
                                    <span class="text-slate-300 mx-1">|</span>
                                    <span class="text-sm text-slate-500">Dentición:</span>
                                    <span id="denticionTexto" class="text-xs font-bold px-2 py-1 rounded-full"></span>
                                </div>
                            </div>

                            {{-- DIRECCIÓN --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Dirección</label>
                                <input type="text" name="direccion" value="{{ old('direccion') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Av. Ejemplo N23-45, Ciudad">
                            </div>

                            <div class="border-t border-slate-200 pt-6">
                                <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">medical_information</span>
                                    Información Médica
                                </h3>
                            </div>

                            {{-- TIPO DE SANGRE --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tipo de sangre</label>
                                <select name="tipo_sangre"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500">
                                    <option value="">Selecciona el tipo</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo_sangre') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ALERGIAS --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Alergias</label>
                                <textarea name="alergias" rows="2"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Penicilina, Ibuprofeno">{{ old('alergias') }}</textarea>
                            </div>

                            {{-- ENFERMEDADES CRÓNICAS --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Enfermedades crónicas</label>
                                <textarea name="enfermedades_cronicas" rows="2"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Hipertensión, Diabetes">{{ old('enfermedades_cronicas') }}</textarea>
                            </div>

                            {{-- MEDICAMENTOS --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Medicamentos actuales</label>
                                <textarea name="medicamentos_actuales" rows="2"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Losartán 50mg">{{ old('medicamentos_actuales') }}</textarea>
                            </div>

                            {{-- MÉDICO DE CABECERA --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Médico de cabecera</label>
                                <input type="text" name="medico_cabecera" value="{{ old('medico_cabecera') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Dr. Ramírez">
                            </div>

                            {{-- OBSERVACIONES --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Observaciones médicas</label>
                                <textarea name="observaciones" rows="3"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
                            </div>

                            <div class="border-t border-slate-200 pt-6">
                                <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-blue-500">contact_emergency</span>
                                    Contacto de Emergencia
                                </h3>
                            </div>

                            {{-- CONTACTO EMERGENCIA --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre del contacto</label>
                                <input type="text" name="contacto_emergencia" value="{{ old('contacto_emergencia') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="Ej: Carlos Pérez">
                            </div>

                            {{-- TELÉFONO EMERGENCIA --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Teléfono del contacto</label>
                                <input type="text" name="telefono_emergencia" value="{{ old('telefono_emergencia') }}"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500"
                                    placeholder="0987654321">
                            </div>

                        </div>
                        {{-- FIN CAMPOS PACIENTE --}}

                        {{-- BOTONES --}}
                        <div class="flex gap-4 pt-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors">
                                Crear Usuario
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
    // Toggle campos paciente
    const selectRol = document.getElementById('selectRol');
    const camposPaciente = document.getElementById('camposPaciente');

    function toggleCamposPaciente() {
        if (selectRol.value === 'paciente') {
            camposPaciente.classList.remove('hidden');
        } else {
            camposPaciente.classList.add('hidden');
        }
    }

    toggleCamposPaciente();
    selectRol.addEventListener('change', toggleCamposPaciente);

    // Calcular edad y tipo de dentición
    function calcularEdad() {
        const fechaInput = document.getElementById('fecha_nacimiento');
        const infoEdad = document.getElementById('infoEdad');
        const edadTexto = document.getElementById('edadTexto');
        const denticionTexto = document.getElementById('denticionTexto');

        if (!fechaInput.value) {
            infoEdad.classList.add('hidden');
            return;
        }

        const hoy = new Date();
        const nacimiento = new Date(fechaInput.value);
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }

        edadTexto.textContent = edad + ' años';

        let tipo, color;
        if (edad < 6) {
            tipo = 'Temporal';
            color = 'bg-yellow-100 text-yellow-700';
        } else if (edad < 13) {
            tipo = 'Mixta';
            color = 'bg-orange-100 text-orange-700';
        } else {
            tipo = 'Permanente';
            color = 'bg-green-100 text-green-700';
        }

        denticionTexto.textContent = tipo;
        denticionTexto.className = 'text-xs font-bold px-2 py-1 rounded-full ' + color;
        infoEdad.classList.remove('hidden');
    }

    // Calcular al cargar si hay old value
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('fecha_nacimiento').value) {
            calcularEdad();
        }
    });
</script>
@endsection