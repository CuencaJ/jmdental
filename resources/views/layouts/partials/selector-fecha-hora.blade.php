{{--
    Partial reutilizable: selector de fecha + hora disponible
    Variables esperadas:
    - $odontologo_id (opcional): ID del odontólogo para filtrar slots
    - $fecha_actual (opcional): fecha preseleccionada
    - $hora_actual (opcional): hora preseleccionada
--}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fecha</label>
        <input type="date" id="campo-fecha" name="fecha_cita"
            value="{{ old('fecha_cita', $fecha_actual ?? '') }}"
            min="{{ date('Y-m-d') }}"
            required
            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Hora disponible</label>
        <select id="campo-hora" name="hora_cita" required
            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
            <option value="">Primero selecciona una fecha</option>
        </select>
        <input type="hidden" name="fecha_hora" id="campo-fecha-hora"
            value="{{ old('fecha_hora', isset($fecha_actual, $hora_actual) ? $fecha_actual.' '.$hora_actual.':00' : '') }}">
    </div>
</div>
<p id="msg-sin-slots" class="hidden text-xs text-red-500 mt-1">No hay horarios disponibles para esta fecha.</p>
<p id="msg-cargando" class="hidden text-xs text-slate-400 mt-1">Cargando horarios disponibles...</p>

<script>
(function() {
    const campoFecha  = document.getElementById('campo-fecha');
    const campoHora   = document.getElementById('campo-hora');
    const campoHidden = document.getElementById('campo-fecha-hora');
    const msgSinSlots = document.getElementById('msg-sin-slots');
    const msgCargando = document.getElementById('msg-cargando');
    const horaActual  = '{{ $hora_actual ?? "" }}';

    // Odontólogo inicial pasado desde el controlador
    window._odontologoId = '{{ $odontologo_id ?? "" }}';

    function getOdontologoId() {
        // Si hay un select de odontólogo en la página, usar su valor actual
        const selectOd = document.getElementById('select-odontologo');
        if (selectOd && selectOd.value) return selectOd.value;
        return window._odontologoId || '';
    }

    function cargarSlots(fecha) {
        if (!fecha) return;

        msgCargando.classList.remove('hidden');
        msgSinSlots.classList.add('hidden');
        campoHora.innerHTML = '<option value="">Cargando...</option>';
        campoHora.disabled = true;

        const odontologoId = getOdontologoId();
        let url = '/citas/horas-disponibles?fecha=' + fecha;
        if (odontologoId) url += '&odontologo_id=' + odontologoId;

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            msgCargando.classList.add('hidden');
            campoHora.disabled = false;
            campoHora.innerHTML = '';

            if (!data.slots || data.slots.length === 0) {
                campoHora.innerHTML = '<option value="">Sin horarios disponibles</option>';
                msgSinSlots.classList.remove('hidden');
                return;
            }

            campoHora.innerHTML = '<option value="">Selecciona una hora</option>';
            data.slots.forEach(function(slot) {
                const opt = document.createElement('option');
                opt.value = slot;
                opt.textContent = slot;
                if (slot === horaActual) opt.selected = true;
                campoHora.appendChild(opt);
            });

            actualizarHidden();
        })
        .catch(function() {
            msgCargando.classList.add('hidden');
            campoHora.disabled = false;
            campoHora.innerHTML = '<option value="">Error al cargar horarios</option>';
        });
    }

    function actualizarHidden() {
        const fecha = campoFecha.value;
        const hora  = campoHora.value;
        campoHidden.value = (fecha && hora) ? fecha + ' ' + hora + ':00' : '';
    }

    campoFecha.addEventListener('change', function() {
        cargarSlots(this.value);
    });

    campoHora.addEventListener('change', actualizarHidden);

    // Si ya hay fecha preseleccionada cargar slots
    if (campoFecha.value) {
        cargarSlots(campoFecha.value);
    }
})();
</script>