{{--
    Parámetros esperados:
        $odontologo_id   – ID del odontólogo
        $excluir_cita    – ID de cita a excluir [opcional]
--}}
<div class="space-y-3">

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

<script>
(function () {
    const ODONTOLOGO_ID  = {!! json_encode($odontologo_id  ?? null) !!};
    const EXCLUIR_CITA   = {!! json_encode($excluir_cita   ?? null) !!};
    const OLD_FECHA_HORA = {!! json_encode(old('fecha_hora')) !!};

    const inputFecha = document.getElementById('selector-fecha');
    const selectHora = document.getElementById('selector-hora');
    const msgEl      = document.getElementById('selector-msg');

    let oldFecha = null;
    let oldHora  = null;
    if (OLD_FECHA_HORA) {
        const parts = OLD_FECHA_HORA.split('T');
        if (parts.length === 2) {
            oldFecha = parts[0];
            oldHora  = parts[1].substring(0, 5);
            if (!inputFecha.value) inputFecha.value = oldFecha;
        }
    }

    function cargarSlots(fecha) {
        if (!fecha) return;

        const odontologoId = window.ODONTOLOGO_ID_ACTUAL || ODONTOLOGO_ID;

        selectHora.disabled = true;
        selectHora.innerHTML = '<option value="">Cargando horarios…</option>';
        msgEl.textContent = '';

        const url = new URL('/citas/horas-disponibles', location.origin);
        url.searchParams.set('fecha', fecha);
        if (odontologoId) url.searchParams.set('odontologo_id', odontologoId);
        if (EXCLUIR_CITA)  url.searchParams.set('excluir_cita', EXCLUIR_CITA);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
            .then(data => {
                let slots = Array.isArray(data.slots) ? data.slots : [];

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
                    const opt = document.createElement('option');
                    opt.value = `${fecha}T${slot}`;
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

    inputFecha.addEventListener('change', e => cargarSlots(e.target.value));

    if (inputFecha.value) cargarSlots(inputFecha.value);

})();
</script>