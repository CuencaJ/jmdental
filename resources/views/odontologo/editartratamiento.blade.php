@extends('layouts.admin')
@section('titulo', 'Completar Tratamiento - JM Dental')
@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50">
    @include('layouts.partials.sidebar-odontologo')

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8">
            <div class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input class="w-full bg-slate-100 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none" placeholder="Buscar paciente, cita o historial..." type="text"/>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-4xl mx-auto">

                <div class="flex items-center gap-3 mb-6">
                    <a href="{{ route('odontologo.historial') }}" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <h1 class="text-xl font-bold text-slate-900">
                        {{ $tratamiento->estado === 'completado' ? 'Editar tratamiento' : 'Completar tratamiento' }}
                    </h1>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-blue-500">person</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $tratamiento->cita->paciente->user->name ?? 'Paciente' }}</p>
                        <p class="text-xs text-slate-500">Cita del {{ $tratamiento->cita->fecha_hora->format('d/m/Y') }} — {{ $tratamiento->cita->motivo }}</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-6">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('odontologo.historial.actualizar', $tratamiento->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- DATOS BÁSICOS --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5">
                        <h3 class="font-bold text-slate-900">Datos del tratamiento</h3>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nombre del tratamiento</label>
                            <input type="text" name="nombre" required value="{{ old('nombre', $tratamiento->nombre) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Descripción</label>
                            <textarea name="descripcion" rows="3" placeholder="Describe el procedimiento realizado..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('descripcion', $tratamiento->descripcion) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Costo <span class="text-slate-400 font-normal">(referencia interna)</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                                <input type="number" name="costo" step="0.01" min="0" value="{{ old('costo', $tratamiento->costo) }}" placeholder="0.00"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-7 pr-3 py-2.5 text-sm outline-none focus:border-blue-400">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Observaciones</label>
                            <textarea name="observaciones" rows="3" placeholder="Indicaciones al paciente, notas adicionales..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-sm outline-none focus:border-blue-400">{{ old('observaciones', $tratamiento->observaciones) }}</textarea>
                        </div>
                    </div>

                    {{-- ODONTOGRAMA --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-slate-900" id="tit-odo">ODONTOGRAMA ADULTO</h3>
                            <button type="button" onclick="togOdo()" id="btn-tog"
                                class="text-sm text-blue-500 font-semibold hover:underline">
                                Odontograma Infantil
                            </button>
                        </div>
                        <p class="text-xs text-slate-400 mb-4">
                            Haz clic en <strong class="text-slate-600">una zona</strong> para marcar una cara ·
                            Haz clic en el <strong class="text-blue-500">número</strong> para marcar la pieza completa ·
                            Haz clic en las <strong class="text-slate-600">casillas de arriba/abajo</strong> para marcar Movilidad/Recesión (1-4, solo piezas permanentes)
                        </p>

                        <svg id="sva" xmlns="http://www.w3.org/2000/svg" style="width:100%;overflow:visible;"></svg>
                        <svg id="svi" xmlns="http://www.w3.org/2000/svg" style="width:100%;overflow:visible;display:none;"></svg>

                        {{-- PANEL PIEZA --}}
                        <div id="panel-cara" class="hidden mt-4 bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                                <span class="text-sm font-semibold text-slate-900">Pieza <span id="dp" class="text-blue-500"></span></span>
                                <span id="badge-modo" class="text-xs bg-blue-50 text-blue-600 rounded-md px-2 py-0.5 font-medium">cara individual</span>
                                <span id="dc" class="text-xs text-slate-500"></span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <p class="text-xs font-medium text-slate-500 mb-1">Procedimiento</p>
                                    <select id="spr" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none">
                                        <option value="">Selecciona un procedimiento</option>
                                        <option>Profiláctico</option>
                                        <option>Restauración con Resina</option>
                                        <option>Sellador</option>
                                        <option>Extracción</option>
                                        <option>Endodoncia</option>
                                        <option>Corona</option>
                                        <option>Implante</option>
                                        <option>Ortodoncia</option>
                                        <option>Blanqueamiento</option>
                                        <option>Limpieza</option>
                                    </select>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-slate-500 mb-1">Diagnóstico (CIE-10)</p>
                                    <input type="text" id="idx" placeholder="CIE 10 (nombre o código)"
                                        class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm outline-none">
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                    <input type="checkbox" id="caus"> Ausente / Pieza completa
                                </label>
                                <button type="button" onclick="agregar()"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg">
                                    Agregar
                                </button>
                            </div>
                            {{-- AVISO AUSENTE --}}
                            <div id="aviso-ausente" class="hidden mt-2 bg-amber-50 border border-amber-200 text-amber-700 text-xs px-3 py-2 rounded-lg">
                                ⚠️ Estás marcando esta pieza como <strong>Ausente</strong>. Verifica que el procedimiento sea una extracción o pieza perdida.
                            </div>
                        </div>

                        <div id="lista-piezas" class="mt-3 flex flex-wrap gap-1"></div>
                        <div id="hidden-piezas"></div>
                    </div>

                    {{-- ARCHIVOS EXISTENTES --}}
                    @if($tratamiento->archivos->count() > 0)
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <h3 class="font-bold text-slate-900 mb-4">Archivos existentes</h3>
                        <div class="space-y-2">
                            @foreach($tratamiento->archivos as $archivo)
                                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0
                                        {{ str_contains($archivo->tipo_archivo, 'pdf') ? 'bg-red-100 text-red-500' : 'bg-blue-100 text-blue-500' }}">
                                        <span class="material-symbols-outlined text-lg">
                                            {{ str_contains($archivo->tipo_archivo, 'pdf') ? 'picture_as_pdf' : 'image' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-900 flex-1 truncate">{{ $archivo->nombre_archivo }}</p>
                                    <a href="{{ Storage::url($archivo->ruta_archivo) }}" target="_blank" class="text-blue-500 hover:text-blue-600">
                                        <span class="material-symbols-outlined text-lg">open_in_new</span>
                                    </a>
                                    <a href="{{ route('odontologo.historial.archivo.eliminar', $archivo->id) }}"
                                        onclick="return confirm('¿Eliminar este archivo?')" class="text-red-400 hover:text-red-600">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- SUBIR ARCHIVOS --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <h3 class="font-bold text-slate-900 mb-4">Subir archivos <span class="text-slate-400 font-normal text-sm">(PDF, JPG, PNG — máx. 10MB c/u)</span></h3>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-blue-400 transition-colors cursor-pointer"
                            onclick="document.getElementById('inp-arch').click()">
                            <span class="material-symbols-outlined text-4xl text-slate-300 mb-2 block">upload_file</span>
                            <p class="text-sm text-slate-500">Haz clic para subir radiografías, fotos o documentos</p>
                            <p class="text-xs text-slate-400 mt-1">PDF, JPG, PNG hasta 10MB</p>
                        </div>
                        <input type="file" id="inp-arch" name="archivos[]" multiple accept=".pdf,.jpg,.jpeg,.png"
                            class="hidden" onchange="prevArch(this)">
                        <div id="prev-arch" class="mt-3 space-y-2"></div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('odontologo.historial') }}" class="px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-500 hover:bg-slate-100">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold">Guardar tratamiento</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
const SA=[18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28];
const IA=[48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38];
const SI=[55,54,53,52,51,61,62,63,64,65];
const II=[85,84,83,82,81,71,72,73,74,75];
const CARAS=['vestibular','palatina','mesial','distal','oclusal'];
const CL={vestibular:'Vestibular',palatina:'Palatina/Lingual',mesial:'Mesial',distal:'Distal',oclusal:'Oclusal'};
const CC={vestibular:'#dbeafe',palatina:'#fef3c7',mesial:'#ede9fe',distal:'#fce7f3',oclusal:'#dcfce7'};
const CS={vestibular:'#3b82f6',palatina:'#f59e0b',mesial:'#7c3aed',distal:'#db2777',oclusal:'#16a34a'};
const CAUS='#ef4444';
let st={},stMov={},cur={},modo='adulto';
const S=14;

function piezasIniciales(){
    return [
        @foreach($tratamiento->piezas as $p)
        {pieza:{{$p->pieza_numero}},cara:'{{$p->cara}}',tipo:'{{$p->tipo_denticion}}',proc:'{{addslashes($p->procedimiento ?? '')}}',dx:'{{addslashes($p->diagnostico ?? '')}}',ausente:{{$p->ausente?'true':'false'}},movilidad:'{{$p->movilidad ?? ''}}',recesion:'{{$p->recesion ?? ''}}'},
        @endforeach
    ];
}
piezasIniciales().forEach(p=>{
    if(p.cara !== '_movrec'){
        st[`${p.pieza}-${p.cara}`]={pieza:p.pieza,cara:p.cara,tipo:p.tipo,proc:p.proc,dx:p.dx,estado:p.ausente?'ausente':'seleccionado'};
    }
    if(p.movilidad || p.recesion){
        stMov[p.pieza] = {movilidad: p.movilidad || '', recesion: p.recesion || ''};
    }
});

function esPiezaCompleta(num){ return CARAS.every(c=>st[`${num}-${c}`]); }
function todoAusente(num){ return CARAS.every(c=>st[`${num}-${c}`]?.estado==='ausente'); }

function gf(p,c){
    const k=`${p}-${c}`,v=st[k];
    if(!v)return CC[c];
    return v.estado==='ausente'?CAUS:CS[c];
}

function numColor(num){
    if(todoAusente(num))return '#ef4444';
    if(esPiezaCompleta(num))return '#3b82f6';
    return '#94a3b8';
}

function buildDiente(svg,num,cx,cy,tipo){
    const g=document.createElementNS('http://www.w3.org/2000/svg','g');
    const ri=S*0.38;
    const bg=document.createElementNS('http://www.w3.org/2000/svg','rect');
    bg.setAttribute('x',cx-S);bg.setAttribute('y',cy-S);
    bg.setAttribute('width',S*2);bg.setAttribute('height',S*2);
    bg.setAttribute('fill','#fff');bg.setAttribute('stroke','#cbd5e1');bg.setAttribute('stroke-width','0.8');
    g.appendChild(bg);
    const mkTri=(pts,cara)=>{
        const p=document.createElementNS('http://www.w3.org/2000/svg','polygon');
        p.setAttribute('points',pts);p.setAttribute('fill',gf(num,cara));
        p.setAttribute('stroke','#cbd5e1');p.setAttribute('stroke-width','0.5');
        p.style.cursor='pointer';
        p.addEventListener('click',()=>clkCara(num,cara,tipo));
        g.appendChild(p);
    };
    mkTri(`${cx-S},${cy-S} ${cx+S},${cy-S} ${cx+ri},${cy-ri} ${cx-ri},${cy-ri}`,'vestibular');
    mkTri(`${cx-S},${cy+S} ${cx+S},${cy+S} ${cx+ri},${cy+ri} ${cx-ri},${cy+ri}`,'palatina');
    mkTri(`${cx-S},${cy-S} ${cx-S},${cy+S} ${cx-ri},${cy+ri} ${cx-ri},${cy-ri}`,'mesial');
    mkTri(`${cx+S},${cy-S} ${cx+S},${cy+S} ${cx+ri},${cy+ri} ${cx+ri},${cy-ri}`,'distal');
    const oc=document.createElementNS('http://www.w3.org/2000/svg','rect');
    oc.setAttribute('x',cx-ri);oc.setAttribute('y',cy-ri);
    oc.setAttribute('width',ri*2);oc.setAttribute('height',ri*2);
    oc.setAttribute('fill',gf(num,'oclusal'));
    oc.setAttribute('stroke','#cbd5e1');oc.setAttribute('stroke-width','0.5');
    oc.style.cursor='pointer';
    oc.addEventListener('click',()=>clkCara(num,'oclusal',tipo));
    g.appendChild(oc);
    const brd=document.createElementNS('http://www.w3.org/2000/svg','rect');
    brd.setAttribute('x',cx-S);brd.setAttribute('y',cy-S);
    brd.setAttribute('width',S*2);brd.setAttribute('height',S*2);
    brd.setAttribute('fill','none');brd.setAttribute('stroke','#94a3b8');brd.setAttribute('stroke-width','0.9');
    brd.style.pointerEvents='none';
    g.appendChild(brd);
    svg.appendChild(g);
}

function buildNum(svg,num,cx,cy,tipo){
    const t=document.createElementNS('http://www.w3.org/2000/svg','text');
    t.setAttribute('x',cx);t.setAttribute('y',cy);
    t.setAttribute('text-anchor','middle');t.setAttribute('dominant-baseline','middle');
    t.setAttribute('font-size','9');t.setAttribute('fill',numColor(num));
    t.setAttribute('font-weight',esPiezaCompleta(num)?'bold':'normal');
    t.setAttribute('font-family','sans-serif');
    t.style.cursor='pointer';t.style.pointerEvents='all';
    t.addEventListener('click',()=>clkPieza(num,tipo));
    svg.appendChild(t);
    t.textContent=num;
}

// Movilidad/Recesión: casillas visuales tipo checkbox, igual que en el
// formulario 033 en papel. Solo piezas permanentes (11-48) las tienen -
// el formulario no trae esa fila para las temporales (51-85). Clic
// rota el valor 0(vacío)->1->2->3->4->0.
function toggleMovRec(pieza,campo){
    if(!stMov[pieza]) stMov[pieza]={movilidad:'',recesion:''};
    const actual=stMov[pieza][campo];
    stMov[pieza][campo]= actual===''?'1':(actual==='4'?'':String(Number(actual)+1));
    if(stMov[pieza].movilidad==='' && stMov[pieza].recesion==='') delete stMov[pieza];
    rebuild();renderTags();
}

function buildCasillaMovRec(svg,pieza,campo,cx,cy){
    const g=document.createElementNS('http://www.w3.org/2000/svg','g');
    const s=9;
    const rect=document.createElementNS('http://www.w3.org/2000/svg','rect');
    rect.setAttribute('x',cx-s/2);rect.setAttribute('y',cy-s/2);
    rect.setAttribute('width',s);rect.setAttribute('height',s);
    rect.setAttribute('fill','#fff');rect.setAttribute('stroke','#94a3b8');rect.setAttribute('stroke-width','0.7');
    rect.style.cursor='pointer';
    rect.addEventListener('click',()=>toggleMovRec(pieza,campo));
    g.appendChild(rect);
    const val=(stMov[pieza]&&stMov[pieza][campo])||'';
    if(val){
        const t=document.createElementNS('http://www.w3.org/2000/svg','text');
        t.setAttribute('x',cx);t.setAttribute('y',cy);
        t.setAttribute('text-anchor','middle');t.setAttribute('dominant-baseline','middle');
        t.setAttribute('font-size','7');t.setAttribute('fill','#1e3a8a');t.setAttribute('font-weight','bold');
        t.style.pointerEvents='none';
        t.textContent=val;
        g.appendChild(t);
    }
    svg.appendChild(g);
}

function buildOdo(id,sup,inf,tipo,W,conMovRec){
    const svg=document.getElementById(id);
    svg.innerHTML='';
    const yOff=conMovRec?30:0; // espacio extra arriba para RECESIÓN/MOVILIDAD
    const H=conMovRec?200:140;
    svg.setAttribute('viewBox',`0 0 ${W} ${H}`);
    const n=sup.length,half=n/2,GAP=(W-20)/n,MID=W/2;
    if(conMovRec){
        buildEtiquetaFila(svg,'R',6,8);
        buildEtiquetaFila(svg,'M',6,20);
        buildEtiquetaFila(svg,'M',6,127+yOff+15);
        buildEtiquetaFila(svg,'R',6,127+yOff+27);
    }
    sup.forEach((num,i)=>{
        const x=i<half?MID-(half-i-0.5)*GAP:MID+(i-half+0.5)*GAP;
        if(conMovRec){
            buildCasillaMovRec(svg,num,'recesion',x,8);
            buildCasillaMovRec(svg,num,'movilidad',x,20);
        }
        buildNum(svg,num,x,13+yOff,tipo);buildDiente(svg,num,x,35+yOff,tipo);
    });
    inf.forEach((num,i)=>{
        const x=i<half?MID-(half-i-0.5)*GAP:MID+(i-half+0.5)*GAP;
        buildDiente(svg,num,x,105+yOff,tipo);buildNum(svg,num,x,127+yOff,tipo);
        if(conMovRec){
            buildCasillaMovRec(svg,num,'movilidad',x,127+yOff+15);
            buildCasillaMovRec(svg,num,'recesion',x,127+yOff+27);
        }
    });
    const lv=document.createElementNS('http://www.w3.org/2000/svg','line');
    lv.setAttribute('x1',MID);lv.setAttribute('y1',5+yOff);lv.setAttribute('x2',MID);lv.setAttribute('y2',135+yOff);
    lv.setAttribute('stroke','#e2e8f0');lv.setAttribute('stroke-width','1');svg.appendChild(lv);
    const lh=document.createElementNS('http://www.w3.org/2000/svg','line');
    lh.setAttribute('x1',10);lh.setAttribute('y1',70+yOff);lh.setAttribute('x2',W-10);lh.setAttribute('y2',70+yOff);
    lh.setAttribute('stroke','#e2e8f0');lh.setAttribute('stroke-width','1');svg.appendChild(lh);
}

function buildEtiquetaFila(svg,letra,cx,cy){
    const t=document.createElementNS('http://www.w3.org/2000/svg','text');
    t.setAttribute('x',cx);t.setAttribute('y',cy);
    t.setAttribute('text-anchor','middle');t.setAttribute('dominant-baseline','middle');
    t.setAttribute('font-size','6');t.setAttribute('fill','#64748b');t.setAttribute('font-weight','bold');
    t.setAttribute('font-family','sans-serif');
    t.textContent=letra;
    svg.appendChild(t);
}

function rebuild(){
    // OJO: las casillas de Movilidad/Recesión para las piezas TEMPORALES
    // (odontograma infantil) son solo visuales en esta pantalla - el PDF
    // del formulario 033 oficial NO trae esa fila de casillas para piezas
    // temporales (51-85), así que aunque se marquen aquí, no van a
    // aparecer impresas. Se agregan igual por consistencia visual con el
    // odontograma adulto.
    buildOdo('sva',SA,IA,'permanente',620,true);
    buildOdo('svi',SI,II,'temporal',420,true);
}

function clkCara(pieza,cara,tipo){
    cur={pieza,cara,tipo,completa:false};
    document.getElementById('dp').textContent=pieza;
    document.getElementById('dc').textContent='— '+CL[cara];
    document.getElementById('badge-modo').textContent='cara individual';
    document.getElementById('badge-modo').className='text-xs bg-blue-50 text-blue-600 rounded-md px-2 py-0.5 font-medium';
    document.getElementById('spr').value='';
    document.getElementById('idx').value='';
    document.getElementById('caus').checked=false; // siempre false al abrir
    document.getElementById('aviso-ausente').classList.add('hidden');
    const k=`${pieza}-${cara}`;
    if(st[k]){
        document.getElementById('spr').value=st[k].proc||'';
        document.getElementById('idx').value=st[k].dx||'';
        document.getElementById('caus').checked=st[k].estado==='ausente';
        if(st[k].estado==='ausente') document.getElementById('aviso-ausente').classList.remove('hidden');
    }
    document.getElementById('panel-cara').classList.remove('hidden');
}

function clkPieza(pieza,tipo){
    cur={pieza,cara:null,tipo,completa:true};
    document.getElementById('dp').textContent=pieza;
    document.getElementById('dc').textContent='— Todas las caras';
    document.getElementById('badge-modo').textContent='pieza completa';
    document.getElementById('badge-modo').className='text-xs bg-pink-50 text-pink-700 rounded-md px-2 py-0.5 font-medium';
    document.getElementById('spr').value='';
    document.getElementById('idx').value='';
    document.getElementById('caus').checked=false; // siempre false al abrir
    document.getElementById('aviso-ausente').classList.add('hidden');
    const k0=`${pieza}-oclusal`;
    if(st[k0]){
        document.getElementById('spr').value=st[k0].proc||'';
        document.getElementById('idx').value=st[k0].dx||'';
        document.getElementById('caus').checked=todoAusente(pieza);
        if(todoAusente(pieza)) document.getElementById('aviso-ausente').classList.remove('hidden');
    }
    document.getElementById('panel-cara').classList.remove('hidden');
}

// Mostrar aviso cuando se marca ausente
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('caus').addEventListener('change', function() {
        const aviso = document.getElementById('aviso-ausente');
        if(this.checked) {
            aviso.classList.remove('hidden');
        } else {
            aviso.classList.add('hidden');
        }
    });
});

// Autocompletar CIE-10 según el procedimiento elegido. OJO: el CIE-10
// codifica DIAGNÓSTICOS, no procedimientos - esto es el diagnóstico más
// típico que suele justificar cada procedimiento, como valor sugerido
// editable, no una regla clínica fija. El odontólogo puede corregirlo si
// el caso real es distinto. "Blanqueamiento" no tiene código porque es
// estético, no una enfermedad.
const CIE10_POR_PROCEDIMIENTO = {
    'Restauración con Resina': 'K02.1 - Caries de la dentina',
    'Sellador': 'K02.0 - Caries limitada al esmalte',
    'Extracción': 'K02.9 - Caries dental, no especificada',
    'Endodoncia': 'K04.0 - Pulpitis',
    'Corona': 'K02.9 - Caries dental, no especificada',
    'Implante': 'K08.1 - Pérdida de dientes por extracción o enfermedad periodontal',
    'Ortodoncia': 'K07.4 - Maloclusión, no especificada',
    'Profiláctico': 'K03.6 - Depósitos [acreciones] en los dientes',
    'Limpieza': 'K03.6 - Depósitos [acreciones] en los dientes',
};
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('spr').addEventListener('change', function() {
        const idxInput = document.getElementById('idx');
        // Solo autocompleta si el campo está vacío, para no pisar algo
        // que el odontólogo ya haya escrito a mano.
        if(idxInput.value.trim() === '' && CIE10_POR_PROCEDIMIENTO[this.value]){
            idxInput.value = CIE10_POR_PROCEDIMIENTO[this.value];
        }
    });
});

function agregar(){
    if(!cur.pieza)return;
    const proc=document.getElementById('spr').value;
    const dx=document.getElementById('idx').value;
    const aus=document.getElementById('caus').checked;
    const estado=aus?'ausente':'seleccionado';
    if(cur.completa){
        CARAS.forEach(cara=>{
            st[`${cur.pieza}-${cara}`]={pieza:cur.pieza,cara,tipo:cur.tipo,proc,dx,estado};
        });
    } else {
        st[`${cur.pieza}-${cur.cara}`]={pieza:cur.pieza,cara:cur.cara,tipo:cur.tipo,proc,dx,estado};
    }
    rebuild();renderTags();
    document.getElementById('panel-cara').classList.add('hidden');
    document.getElementById('aviso-ausente').classList.add('hidden');
}

function renderTags(){
    const div=document.getElementById('lista-piezas');
    const hid=document.getElementById('hidden-piezas');
    div.innerHTML='';hid.innerHTML='';
    const piezasVistas=new Set();
    const piezasConEntrada=new Set();
    let idx=0;
    Object.values(st).forEach(v=>{
        const todas=esPiezaCompleta(v.pieza);
        if(todas){
            if(!piezasVistas.has(v.pieza)){
                piezasVistas.add(v.pieza);
                piezasConEntrada.add(v.pieza);
                const aus=todoAusente(v.pieza);
                const sp=document.createElement('span');
                sp.style.cssText=`display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:12px;margin:3px;background:${aus?'#fee2e2':'#fce7f3'};color:${aus?'#991b1b':'#9d174d'};border:0.5px solid ${aus?'#fca5a5':'#fbcfe8'};`;
                sp.innerHTML=`Pieza ${v.pieza} · Completa${v.proc?' · '+v.proc:''} <button type="button" onclick="quitarPieza(${v.pieza})" style="background:none;border:none;cursor:pointer;color:inherit;font-size:13px;padding:0 0 0 2px;">✕</button>`;
                div.appendChild(sp);
                CARAS.forEach(cara=>{
                    const vv=st[`${v.pieza}-${cara}`];
                    hid.innerHTML+=`<input type="hidden" name="piezas[${idx}][pieza_numero]" value="${v.pieza}">
                        <input type="hidden" name="piezas[${idx}][tipo_denticion]" value="${vv.tipo}">
                        <input type="hidden" name="piezas[${idx}][cara]" value="${cara}">
                        <input type="hidden" name="piezas[${idx}][procedimiento]" value="${vv.proc||''}">
                        <input type="hidden" name="piezas[${idx}][diagnostico]" value="${vv.dx||''}">
                        <input type="hidden" name="piezas[${idx}][ausente]" value="${vv.estado==='ausente'?'1':'0'}">
                        <input type="hidden" name="piezas[${idx}][movilidad]" value="${(stMov[v.pieza]&&stMov[v.pieza].movilidad)||''}">
                        <input type="hidden" name="piezas[${idx}][recesion]" value="${(stMov[v.pieza]&&stMov[v.pieza].recesion)||''}">`;
                    idx++;
                });
            }
        } else {
            piezasConEntrada.add(v.pieza);
            const aus=v.estado==='ausente';
            const sp=document.createElement('span');
            sp.style.cssText=`display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:12px;margin:3px;background:${aus?'#fee2e2':'#eff6ff'};color:${aus?'#991b1b':'#1d4ed8'};border:0.5px solid ${aus?'#fca5a5':'#bfdbfe'};`;
            sp.innerHTML=`Pieza ${v.pieza} · ${CL[v.cara]}${v.proc?' · '+v.proc:''} <button type="button" onclick="quitar('${v.pieza}-${v.cara}')" style="background:none;border:none;cursor:pointer;color:inherit;font-size:13px;padding:0 0 0 2px;">✕</button>`;
            div.appendChild(sp);
            hid.innerHTML+=`<input type="hidden" name="piezas[${idx}][pieza_numero]" value="${v.pieza}">
                <input type="hidden" name="piezas[${idx}][tipo_denticion]" value="${v.tipo}">
                <input type="hidden" name="piezas[${idx}][cara]" value="${v.cara}">
                <input type="hidden" name="piezas[${idx}][procedimiento]" value="${v.proc||''}">
                <input type="hidden" name="piezas[${idx}][diagnostico]" value="${v.dx||''}">
                <input type="hidden" name="piezas[${idx}][ausente]" value="${v.estado==='ausente'?'1':'0'}">
                <input type="hidden" name="piezas[${idx}][movilidad]" value="${(stMov[v.pieza]&&stMov[v.pieza].movilidad)||''}">
                <input type="hidden" name="piezas[${idx}][recesion]" value="${(stMov[v.pieza]&&stMov[v.pieza].recesion)||''}">`;
            idx++;
        }
    });
    // Piezas que tienen SOLO Movilidad/Recesión marcada, sin ningún
    // procedimiento/cara (por eso no aparecen en `st`) - si no se agrega
    // esto aparte, esos valores nunca se mandarían al servidor. Se usa
    // cara="_movrec" (no es una cara real de CARAS) para que al recargar
    // piezasIniciales() NO se interprete como un tratamiento de una cara
    // individual y no aparezca como tag fantasma "Pieza X · Oclusal".
    Object.keys(stMov).forEach(key=>{
        const pieza=Number(key);
        if(piezasConEntrada.has(pieza)) return;
        const mv=stMov[pieza];
        const tipo=pieza>=51?'temporal':'permanente';
        hid.innerHTML+=`<input type="hidden" name="piezas[${idx}][pieza_numero]" value="${pieza}">
            <input type="hidden" name="piezas[${idx}][tipo_denticion]" value="${tipo}">
            <input type="hidden" name="piezas[${idx}][cara]" value="_movrec">
            <input type="hidden" name="piezas[${idx}][procedimiento]" value="">
            <input type="hidden" name="piezas[${idx}][diagnostico]" value="">
            <input type="hidden" name="piezas[${idx}][ausente]" value="0">
            <input type="hidden" name="piezas[${idx}][movilidad]" value="${mv.movilidad||''}">
            <input type="hidden" name="piezas[${idx}][recesion]" value="${mv.recesion||''}">`;
        idx++;
    });
}

function quitar(k){delete st[k];rebuild();renderTags();}
function quitarPieza(num){CARAS.forEach(c=>delete st[`${num}-${c}`]);rebuild();renderTags();}

function togOdo(){
    const sa=document.getElementById('sva'),si=document.getElementById('svi');
    const btn=document.getElementById('btn-tog'),tit=document.getElementById('tit-odo');
    if(modo==='adulto'){sa.style.display='none';si.style.display='';btn.textContent='Odontograma Adulto';tit.textContent='ODONTOGRAMA INFANTIL';modo='infantil';}
    else{si.style.display='none';sa.style.display='';btn.textContent='Odontograma Infantil';tit.textContent='ODONTOGRAMA ADULTO';modo='adulto';}
}

function prevArch(input){
    const div=document.getElementById('prev-arch');div.innerHTML='';
    Array.from(input.files).forEach(f=>{
        div.innerHTML+=`<div style="display:flex;align-items:center;gap:8px;padding:8px 10px;background:#f8fafc;border-radius:8px;font-size:13px;">
            <span class="material-symbols-outlined" style="color:#3b82f6;font-size:18px;">insert_drive_file</span>
            <span style="color:#0f172a;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f.name}</span>
            <span style="color:#94a3b8;font-size:11px;">${(f.size/1024).toFixed(0)} KB</span>
        </div>`;
    });
}

document.addEventListener('DOMContentLoaded',()=>{rebuild();renderTags();});
</script>
@endsection