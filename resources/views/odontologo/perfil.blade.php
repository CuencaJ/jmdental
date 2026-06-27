@extends('layouts.admin')
@section('titulo', 'Mi Perfil - JM Dental')
@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50">

    <aside class="w-64 flex flex-col bg-white border-r border-slate-200">
        <div class="p-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                <img src="{{ asset('assets/img/logo.png') }}" class="w-5 h-5 object-contain">
            </div>
            <h2 class="text-xl font-bold text-slate-900">DentalCare</h2>
        </div>
        <div class="flex items-center gap-3 p-3 mx-4 bg-slate-50 rounded-xl mb-4">
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex flex-col overflow-hidden">
                <h1 class="text-sm font-semibold truncate">{{ Auth::user()->name }}</h1>
                <p class="text-xs text-slate-500">Odontólogo</p>
            </div>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ request()->routeIs('odontologo.dashboard') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
                href="{{ route('odontologo.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span><span class="text-sm">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ request()->routeIs('odontologo.pacientes.*') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
                href="{{ route('odontologo.pacientes.index') }}">
                <span class="material-symbols-outlined">group</span><span class="text-sm">Pacientes</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ request()->routeIs('odontologo.agenda') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
                href="{{ route('odontologo.agenda') }}">
                <span class="material-symbols-outlined">calendar_today</span><span class="text-sm">Agenda</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ request()->routeIs('odontologo.historial*') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
                href="{{ route('odontologo.historial') }}">
                <span class="material-symbols-outlined">medical_information</span><span class="text-sm">Historial Clínico</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ request()->routeIs('odontologo.perfil') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
                href="{{ route('odontologo.perfil') }}">
                <span class="material-symbols-outlined">settings</span><span class="text-sm">Configuración</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-200 mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-blue-500 text-white py-3 rounded-lg font-bold text-sm hover:bg-blue-600 transition-all">
                    <span class="material-symbols-outlined">logout</span><span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none"
                    placeholder="Buscar paciente, cita o historial..." type="text"/>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-3xl mx-auto space-y-6">

                {{-- MENSAJES --}}
                @if(session('mensaje'))
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl">
                        {{ session('mensaje') }}
                    </div>
                @endif
                @if(session('mensaje_password'))
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl">
                        {{ session('mensaje_password') }}
                    </div>
                @endif

                {{-- CABECERA PERFIL --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 flex items-center gap-5">
                    <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-900">{{ $user->name }}</h1>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
                        <span class="inline-block mt-2 bg-blue-50 text-blue-500 text-xs font-bold px-3 py-1 rounded-full">Odontólogo</span>
                    </div>
                </div>

                {{-- INFORMACIÓN PERSONAL Y PROFESIONAL --}}
                @if($errors->hasAny(['name','email','telefono','cedula','especialidad','numero_licencia','descripcion']))
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->only(['name','email','telefono','cedula','especialidad','numero_licencia','descripcion']) as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('odontologo.perfil.actualizar') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h2 class="font-bold text-slate-900">Información personal</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Nombre completo</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Correo electrónico</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $odontologo->telefono ?? '') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Cédula</label>
                                <input type="text" name="cedula" value="{{ old('cedula', $odontologo->cedula ?? '') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h2 class="font-bold text-slate-900">Información profesional</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Especialidad</label>
                                <input type="text" name="especialidad" value="{{ old('especialidad', $odontologo->especialidad ?? '') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Número de licencia</label>
                                <input type="text" name="numero_licencia" value="{{ old('numero_licencia', $odontologo->numero_licencia ?? '') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Descripción profesional</label>
                            <textarea name="descripcion" rows="3"
                                placeholder="Describe tu experiencia y áreas de atención..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('descripcion', $odontologo->descripcion ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
                            Guardar cambios
                        </button>
                    </div>
                </form>

                {{-- CAMBIO DE CONTRASEÑA --}}
                @if($errors->hasAny(['password_actual','password']))
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->only(['password_actual','password']) as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('odontologo.perfil.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                        <h2 class="font-bold text-slate-900">Cambiar contraseña</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Contraseña actual</label>
                                <input type="password" name="password_actual" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Nueva contraseña</label>
                                <input type="password" name="password" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-slate-700 hover:bg-slate-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold">
                            Cambiar contraseña
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
</div>
@endsection