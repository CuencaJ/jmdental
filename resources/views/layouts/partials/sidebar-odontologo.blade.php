<aside class="w-64 flex flex-col bg-white border-r border-slate-200" style="min-height: 100vh">
    <a href="{{ route('odontologo.dashboard') }}" class="flex items-center gap-3 px-6 py-4 hover:opacity-80 transition-opacity">
        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
            <img src="{{ asset('assets/img/logo.png') }}" class="w-5 h-5 object-contain">
        </div>
        <h2 class="text-xl font-bold text-slate-900">DentalCare</h2>
    </a>

    {{-- PERFIL CLICKEABLE --}}
    <a href="{{ route('odontologo.perfil') }}"
        class="flex items-center gap-3 p-3 mx-4 rounded-xl mb-4 hover:bg-blue-50 transition-colors
        {{ request()->routeIs('odontologo.perfil') ? 'bg-blue-50 ring-2 ring-blue-200' : 'bg-slate-50' }}">
        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="flex flex-col overflow-hidden">
            <h1 class="text-sm font-semibold truncate">{{ Auth::user()->name }}</h1>
            <p class="text-xs text-slate-500">Odontólogo</p>
        </div>
    </a>

    <nav class="flex-1 px-4 space-y-1">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
            {{ request()->routeIs('odontologo.dashboard') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
            href="{{ route('odontologo.dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Inicio</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
            {{ request()->routeIs('odontologo.pacientes.*') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
            href="{{ route('odontologo.pacientes.index') }}">
            <span class="material-symbols-outlined">group</span>
            <span class="text-sm">Pacientes</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
            {{ request()->routeIs('odontologo.agenda') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
            href="{{ route('odontologo.agenda') }}">
            <span class="material-symbols-outlined">calendar_today</span>
            <span class="text-sm">Agenda</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
            {{ request()->routeIs('odontologo.historial*') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
            href="{{ route('odontologo.historial') }}">
            <span class="material-symbols-outlined">medical_information</span>
            <span class="text-sm">Historial Clínico</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
                {{ request()->routeIs('odontologo.perfil') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
                href="{{ route('odontologo.perfil') }}">
                <span class="material-symbols-outlined">account_circle</span>
                <span class="text-sm">Mi Perfil</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-200 mt-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-blue-500 text-white py-3 rounded-lg font-bold text-sm hover:bg-blue-600 transition-all">
                <span class="material-symbols-outlined">logout</span>
                <span>Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside>