@extends('layouts.admin')
@section('titulo', 'Configuración de Horario - JM Dental')
@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50">
    @include('layouts.partials.sidebar-admin')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <h1 class="text-lg font-bold text-slate-900">Configuración de Horario</h1>
        </header>
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-xl mx-auto">

                @if(session('mensaje'))
                    <div class="bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl mb-6">
                        {{ session('mensaje') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-6">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.horario.update') }}" method="POST"
                    class="bg-white border border-slate-200 rounded-2xl p-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- HORARIO --}}
                    <div>
                        <h3 class="font-bold text-slate-900 mb-4">Horario de atención</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Hora de apertura</label>
                                <input type="time" name="hora_inicio"
                                    value="{{ \Carbon\Carbon::parse($config->hora_inicio)->format('H:i') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Hora de cierre</label>
                                <input type="time" name="hora_fin"
                                    value="{{ \Carbon\Carbon::parse($config->hora_fin)->format('H:i') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                    </div>

                    {{-- DURACIÓN SLOT --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Duración por cita</label>
                        <select name="duracion_slot"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            @foreach([15 => '15 minutos', 30 => '30 minutos', 45 => '45 minutos', 60 => '1 hora', 90 => '1 hora 30 min', 120 => '2 horas'] as $val => $label)
                                <option value="{{ $val }}" {{ $config->duracion_slot == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DÍAS LABORABLES --}}
                    <div>
                        <h3 class="font-bold text-slate-900 mb-3">Días laborables</h3>
                        <div class="grid grid-cols-4 gap-2">
                            @php
                                $dias = ['1'=>'Lunes','2'=>'Martes','3'=>'Miércoles','4'=>'Jueves','5'=>'Viernes','6'=>'Sábado','7'=>'Domingo'];
                            @endphp
                            @foreach($dias as $num => $nombre)
                                <label class="flex items-center gap-2 p-2 bg-slate-50 border border-slate-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                                    <input type="checkbox" name="dias_laborables[]" value="{{ $num }}"
                                        {{ in_array($num, $config->dias_laborables) ? 'checked' : '' }}
                                        class="rounded">
                                    <span class="text-xs font-medium text-slate-700">{{ $nombre }}</span>
                                </label>
                            @endforeach
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