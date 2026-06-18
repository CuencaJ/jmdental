@extends('layouts.admin')

@section('titulo', 'Gestión de Usuarios - JM Dental')

@section('content')

<div class="flex h-screen overflow-hidden bg-slate-50">

    @include('layouts.partials.sidebar-admin')
    
    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-1 flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
            <h1 class="text-xl font-bold text-slate-900">Gestión de Usuarios</h1>
            <a href="{{ route('admin.usuarios.create') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined">add</span>
                Nuevo Usuario
            </a>
        </header>

        {{-- CONTENIDO --}}
        <div class="flex-1 overflow-y-auto p-8">

            {{-- MENSAJE --}}
            @if(session('mensaje'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium">
                    {{ session('mensaje') }}
                </div>
            @endif

            {{-- TARJETAS RESUMEN --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
                    <p class="text-2xl font-bold text-blue-500">{{ $totalAdmins }}</p>
                    <p class="text-xs text-slate-500 mt-1">Administradores</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
                    <p class="text-2xl font-bold text-green-500">{{ $totalDoctores }}</p>
                    <p class="text-xs text-slate-500 mt-1">Odontólogos</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
                    <p class="text-2xl font-bold text-amber-500">{{ $totalRecepcionistas }}</p>
                    <p class="text-xs text-slate-500 mt-1">Recepcionistas</p>
                </div>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
                    <p class="text-2xl font-bold text-purple-500">{{ $totalPacientes }}</p>
                    <p class="text-xs text-slate-500 mt-1">Pacientes</p>
                </div>
            </div>

            {{-- PESTAÑAS Y TABLA --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 px-6 pt-4 flex gap-2 overflow-x-auto">
                    @php $rolActivo = request('rol', 'todos'); @endphp
                    @foreach(['todos' => 'Todos', 'administrador' => 'Administradores', 'odontologo' => 'Odontólogos', 'recepcionista' => 'Recepcionistas', 'paciente' => 'Pacientes'] as $key => $label)
                        <a href="{{ route('admin.usuarios.index', ['rol' => $key]) }}"
                           class="px-4 py-2 text-sm font-semibold rounded-t-lg whitespace-nowrap
                           {{ $rolActivo === $key ? 'bg-blue-50 text-blue-600 border-b-2 border-blue-500' : 'text-slate-500 hover:text-slate-700' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                {{-- TABLA --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">#</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Usuario</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Correo</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Teléfono</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Rol</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Estado</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php
                                $usuariosFiltrados = $rolActivo === 'todos'
                                    ? $usuarios
                                    : $usuarios->filter(fn($u) => $u->hasRole($rolActivo));
                            @endphp
                            @forelse($usuariosFiltrados as $usuario)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-semibold text-slate-900">{{ $usuario->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $usuario->email }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $usuario->telefono ?? '-' }}</td>
                                    <td class="px-6 py-4">
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
                                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $color }}">
                                                {{ ucfirst($rol->name) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.usuarios.toggle', $usuario->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1 rounded-full text-xs font-bold
                                                {{ $usuario->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.usuarios.show', $usuario->id) }}"
                                               class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500" title="Ver detalle">
                                                <span class="material-symbols-outlined text-lg">visibility</span>
                                            </a>
                                            <a href="{{ route('admin.usuarios.edit', $usuario->id) }}"
                                               class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-500" title="Editar">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 rounded-lg hover:bg-red-50 text-red-500" title="Eliminar">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-400 text-sm">
                                        No hay usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

@endsection