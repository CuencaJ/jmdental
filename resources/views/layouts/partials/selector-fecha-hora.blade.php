{{--
    Partial reutilizable: selector de fecha + hora disponible
    Variables esperadas:
    - $odontologo_id (opcional): ID del odontólogo para filtrar slots
    - $fecha_actual (opcional): fecha preseleccionada
    - $hora_actual (opcional): hora preseleccionada
--}}
<div class="space-y-3">

    {{-- FECHA --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fecha</label>
        <input
            type="date"
            id="selector-fecha"
            name="_fecha_visual"
            min="{{ now()->format('Y-m-d') }}"
            value="{{ old('_fecha_visual', isset($fechaActual) ? $fechaActual : '') }}"
            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400 cursor-pointer"
        >
    </div>

    {{-- HORA --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Hora disponible</label>
        <select
            id="selector-hora"
            name="fecha_hora"
            disabled
            required
            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400 cursor-pointer disabled:opacity-50"
        >
            <option value="">Primero selecciona una fecha</option>
        </select>
        <p id="selector-msg" class="text-xs text-slate-400 mt-1"></p>
    </div>

</div>

@once
@push('scripts')
<script>
(function () {
    const ODONTOLOGO_ID  = {{ json_encode($odontologo_id  ?? null) }};
    const EXCLUIR_CITA   = {{ json_encode($excluir_cita   ?? null) }};
    const OLD_FECHA_HORA = {{ json_encode(old('fecha_hora')) }};

    const inputFecha  = document.getElementById('selector-fecha');
    const selectHora  = document.getElementById('selector-hora');
    const msgEl       = document.getElementById('selector-msg');

    // ── Restaurar valor old() ──────────────────────────────────────────────
    let oldFecha = null;
    let oldHora  = null;
    if (OLD_FECHA_HORA) {
        const parts = OLD_FECHA_HORA.split('T');
        if (parts.length === 2) {
            oldFecha = parts[0];
            oldHora  = parts[1].substring(0, 5);   // "HH:MM"
            if (!inputFecha.value) inputFecha.value = oldFecha;
        }
    }

    // ── Función principal: pedir slots y renderizar ────────────────────────
    function cargarSlots(fecha) {
        if (!fecha) return;

        selectHora.disabled = true;
        selectHora.innerHTML = '<option value="">Cargando horarios…</option>';
        msgEl.textContent = '';

        const url = new URL('/horario/slots-disponibles', location.origin);
        url.searchParams.set('fecha', fecha);
        if (ODONTOLOGO_ID) url.searchParams.set('odontologo_id', ODONTOLOGO_ID);
        if (EXCLUIR_CITA)  url.searchParams.set('excluir_cita',  EXCLUIR_CITA);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
            .then(data => {
                let slots = Array.isArray(data.slots) ? data.slots : [];

                // ── Filtrar horas pasadas si la fecha es hoy ──────────────
                const ahora = new Date();
                const esHoy = fecha === ahora.toISOString().slice(0, 10);
                if (esHoy) {
                    slots = slots.filter(slot => {
                        const [h, m] = slot.split(':').map(Number);
                        const slotTime = new Date();
                        slotTime.setHours(h, m, 0, 0);
                        return slotTime > ahora;
                    });
                }

                selectHora.innerHTML = '';

                if (slots.length === 0) {
                    selectHora.innerHTML = '<option value="">Sin horarios disponibles</option>';
                    msgEl.textContent = 'No hay slots libres para este día.';
                    return;
                }

                selectHora.innerHTML = '<option value="">— Elige una hora —</option>';
                slots.forEach(slot => {
                    const opt       = document.createElement('option');
                    const fechaHora = `${fecha}T${slot}`;
                    opt.value       = fechaHora;
                    opt.textContent = slot;
                    if (oldFecha === fecha && oldHora === slot) opt.selected = true;
                    selectHora.appendChild(opt);
                });

                selectHora.disabled = false;
            })
            .catch(() => {
                selectHora.innerHTML = '<option value="">Error al cargar horarios</option>';
                msgEl.textContent = 'No se pudo conectar con el servidor.';
            });
    }

    // ── Eventos ───────────────────────────────────────────────────────────
    inputFecha.addEventListener('change', e => cargarSlots(e.target.value));

    // Cargar automáticamente si ya hay fecha (old() o valor inicial)
    if (inputFecha.value) cargarSlots(inputFecha.value);

})();
</script>
@endpush
@endonce