@extends('layouts.admin')

@section('titulo', 'Detalle de Usuario - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    {{-- SIDEBAR --}}
    <aside class="w-64 flex flex-col bg-white border-r border-slate-200" style="min-height: 100vh">
        <div class="p-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined text-sm">dentistry</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900">DentalCare</h2>
        </div>
        <nav class="flex-1 px-4 space-y-1 mt-2">
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 text-blue-600 font-semibold" href="{{ route('admin.usuarios.index') }}">
                <span class="material-symbols-outlined">group</span>
                <span>Usuarios</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors" href="#">
                <span class="material-symbols-outlined">calendar_month</span>
                <span>Citas</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors" href="#">
                <span class="material-symbols-outlined">payments</span>
                <span>Finanzas</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 transition-colors" href="#">
                <span class="material-symbols-outlined">description</span>
                <span>Reportes</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-200">
            <div class="flex items-center gap-3 p-2 bg-slate-50 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">Administrador</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-slate-400">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.usuarios.index') }}" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-xl font-bold text-slate-900">Detalle de Usuario</h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.usuarios.edit', $usuario->id) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    Editar
                </a>
                <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST"
                    onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-50 hover:bg-red-100 text-red-500 px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">delete</span>
                        Eliminar
                    </button>
                </form>
            </div>
        </header>

        {{-- CONTENIDO --}}
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-2xl mx-auto space-y-6">

                {{-- TARJETA PERFIL --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
                    <div class="flex items-center gap-6 mb-8">
                        <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-3xl">
                            {{ strtoupper(substr($usuario->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">{{ $usuario->name }}</h2>
                            <div class="mt-2 flex gap-2">
                                @foreach($usuario->roles as $rol)
                                    @php
                                        $colores = [
                                            'administrador' => 'bg-blue-100 text-blue-700',
                                            'odontologo' => 'bg-green-100 text-green-700',
                                            'recepcionista' => 'bg-amber-100 text-amber-700',
                                            'paciente' => 'bg-purple-100 text-purple-700',
                                        ];
                                        $color = $colores[$rol->name] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
                                        {{ ucfirst($rol->name) }}
                                    </span>
                                @endforeach
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $usuario->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- INFORMACIÓN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <p class="text-xs font-bold uppercase text-slate-400">Correo electrónico</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $usuario->email }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-bold uppercase text-slate-400">Teléfono</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $usuario->telefono ?? 'No registrado' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-bold uppercase text-slate-400">Fecha de registro</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $usuario->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-bold uppercase text-slate-400">Última actualización</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $usuario->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

@endsection