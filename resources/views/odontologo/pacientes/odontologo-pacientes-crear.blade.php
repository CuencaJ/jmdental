@extends('layouts.admin')

@section('titulo', 'Agregar Paciente - JM Dental')

@section('content')

<div class="flex min-h-screen bg-slate-50">

    @include('layouts.partials.sidebar-odontologo')

    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 flex items-center px-8 bg-white border-b border-slate-200 sticky top-0 z-10">
            <div class="relative w-96">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text"
                    class="w-full bg-slate-100 border-none rounded-lg pl-10 pr-4 py-2 text-sm outline-none"
                    placeholder="Buscar pacientes por nombre, correo o ID...">
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-2xl mx-auto">

                <div class="flex items-center gap-3 mb-6">
                    <a href="{{ route('odontologo.pacientes.index') }}"
                        class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <h1 class="text-xl font-bold text-slate-900">Agregar paciente</h1>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-6">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('odontologo.pacientes.store') }}" method="POST"
                    class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nombre completo</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                            placeholder="Ej. Juan Pérez"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Correo electrónico</label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                placeholder="correo@ejemplo.com"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}"
                                placeholder="0991234567"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Contraseña</label>
                            <input type="password" name="password" required
                                placeholder="Mínimo 8 caracteres"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" required
                                placeholder="Repite la contraseña"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-400 text-base">info</span>
                        <p class="text-xs text-blue-600">El rol de <strong>Paciente</strong> se asigna automáticamente.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('odontologo.pacientes.index') }}"
                            class="px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-100">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold">
                            Guardar paciente
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
</div>

@endsection