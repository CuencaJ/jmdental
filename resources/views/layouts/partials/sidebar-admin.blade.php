<aside class="w-64 flex flex-col bg-white border-r border-slate-200" style="min-height: 100vh">
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
            <p class="text-xs text-slate-500">{{ ucfirst(Auth::user()->roles->first()->name ?? '') }}</p>
        </div>
    </div>
    <nav class="flex-1 px-4 space-y-1">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
            {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
            href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-sm">Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors
            {{ request()->routeIs('admin.usuarios.*') ? 'bg-blue-50 text-blue-500 font-semibold' : 'text-slate-600 hover:bg-slate-100' }}"
            href="{{ route('admin.usuarios.index') }}">
            <span class="material-symbols-outlined">group</span>
            <span class="text-sm">Usuarios</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" href="#">
            <span class="material-symbols-outlined">calendar_month</span>
            <span class="text-sm">Citas</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" href="#">
            <span class="material-symbols-outlined">payments</span>
            <span class="text-sm">Finanzas</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors" href="#">
            <span class="material-symbols-outlined">description</span>
            <span class="text-sm">Reportes</span>
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