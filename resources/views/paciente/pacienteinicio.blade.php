@extends('layouts.admin')

@section('titulo', 'Dashboard Paciente - JM Dental')

@section('content')

<div class="relative flex min-h-screen w-full flex-col bg-slate-50">
    <div class="flex h-full grow flex-col">

        {{-- HEADER --}}
        <header class="flex items-center justify-between border-b border-slate-200 bg-white px-10 py-3">
            <div class="flex items-center gap-4 text-blue-500">
                <span class="material-symbols-outlined text-3xl">dentistry</span>
                <h2 class="text-slate-900 text-lg font-bold">BrightSmile Dental</h2>
            </div>
            <div class="flex flex-1 justify-end gap-8">
                <div class="hidden md:flex items-center gap-9">
                    <a class="text-slate-900 text-sm font-medium hover:text-blue-500 transition-colors" href="#">Dashboard</a>
                    <a class="text-slate-600 text-sm font-medium hover:text-blue-500 transition-colors" href="#">Citas</a>
                    <a class="text-slate-600 text-sm font-medium hover:text-blue-500 transition-colors" href="#">Tratamientos</a>
                    <a class="text-slate-600 text-sm font-medium hover:text-blue-500 transition-colors" href="#">Facturación</a>
                </div>
                <div class="flex items-center gap-3">
                    <button class="flex items-center justify-center rounded-lg h-10 bg-slate-100 px-2.5">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                    </button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center justify-center rounded-lg h-10 bg-slate-100 px-2.5 text-slate-700 text-sm font-semibold">
                            <span class="material-symbols-outlined text-xl">logout</span>
                        </button>
                    </form>
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENIDO --}}
        <main class="flex-1 px-4 md:px-10 lg:px-40 py-8">
            <div class="max-w-6xl mx-auto">

                {{-- BIENVENIDA --}}
                <div class="flex flex-col gap-2 mb-8">
                    <h1 class="text-slate-900 text-4xl font-black leading-tight">
                        ¡Bienvenido, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-slate-500 text-lg">
                        Es hora de tu chequeo regular. ¡Mantén esa sonrisa saludable!
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- COLUMNA PRINCIPAL --}}
                    <div class="lg:col-span-2 flex flex-col gap-8">

                        {{-- TARJETA SIN CITAS --}}
                        <div class="bg-white rounded-xl p-8 shadow-sm border border-slate-100 text-center flex flex-col items-center">
                            <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                <span class="material-symbols-outlined text-5xl text-blue-300">calendar_today</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 mb-2">No tienes citas agendadas</h2>
                            <p class="text-slate-500 max-w-md mb-8">
                                Mantener visitas dentales regulares es clave para una vida de sonrisas saludables. Parece que aún no tienes tu próxima visita reservada.
                            </p>
                            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all flex items-center gap-3">
                                <span class="material-symbols-outlined">add_circle</span>
                                Reservar tu próxima cita
                            </button>
                        </div>

                        {{-- TRATAMIENTOS RECIENTES --}}
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-bold text-slate-900">Tratamientos Recientes</h2>
                                <a class="text-blue-500 text-sm font-semibold hover:underline" href="#">Ver historial</a>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-slate-100 shadow-sm">
                                    <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                                        <span class="material-symbols-outlined">health_and_safety</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">Sin tratamientos</p>
                                        <p class="text-slate-500 text-xs">No hay tratamientos registrados</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CONSEJOS --}}
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 mb-4">Consejos para una Sonrisa Saludable</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="group cursor-pointer">
                                    <div class="aspect-video w-full rounded-xl bg-slate-200 overflow-hidden mb-3 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl text-slate-400">dentistry</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 group-hover:text-blue-500 transition-colors">La manera correcta de usar el hilo dental</h3>
                                    <p class="text-slate-500 text-sm mt-1">Descubre la técnica que los dentistas recomiendan para la salud de las encías.</p>
                                </div>
                                <div class="group cursor-pointer">
                                    <div class="aspect-video w-full rounded-xl bg-slate-200 overflow-hidden mb-3 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl text-slate-400">nutrition</span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 group-hover:text-blue-500 transition-colors">5 Alimentos para un Esmalte más Fuerte</h3>
                                    <p class="text-slate-500 text-sm mt-1">Lo que comes importa. Aprende qué bocadillos ayudan a proteger tus dientes.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- COLUMNA DERECHA --}}
                    <div class="flex flex-col gap-8">

                        {{-- SALDO PENDIENTE --}}
                        <div class="bg-blue-500 rounded-xl p-6 text-white shadow-lg">
                            <div class="flex justify-between items-center mb-6">
                                <span class="material-symbols-outlined text-3xl">payments</span>
                                <span class="text-xs font-bold uppercase tracking-widest opacity-80">Acción Pendiente</span>
                            </div>
                            <h3 class="text-sm font-medium opacity-90 mb-1">Saldo Pendiente</h3>
                            <p class="text-3xl font-black mb-6">$0.00</p>
                            <button class="w-full bg-white text-blue-500 font-bold py-3 rounded-lg hover:bg-slate-50 transition-colors flex items-center justify-center gap-2">
                                Pagar Ahora
                                <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </button>
                        </div>

                        {{-- DOCUMENTOS --}}
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
                            <h3 class="text-slate-900 font-bold mb-4">Documentos Rápidos</h3>
                            <div class="space-y-3">
                                <p class="text-slate-400 text-sm text-center">No hay documentos disponibles.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@endsection