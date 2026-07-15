<?php

namespace App\Services;

use App\Models\HistoriaClinica;
use App\Models\Tratamiento;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;

/**
 * FPDF/FPDI base solo trae Line() y Rect() como primitivas públicas de
 * dibujo - no trae círculos ni elipses. Para poder marcar los símbolos
 * oficiales de la leyenda "K. SIMBOLOGÍA DEL ODONTOGRAMA" (caries=círculo
 * rojo, obturado=círculo azul, corona=punto relleno, etc.) se necesita esa
 * capacidad. Esta es la receta estándar y ampliamente usada para agregar
 * Ellipse()/Circle() a FPDF (basada en 4 curvas Bézier), extendida sobre
 * Fpdi para no perder la funcionalidad de importar la plantilla PDF.
 */
class Fpdi033 extends Fpdi
{
    public function Ellipse(float $x, float $y, float $rx, float $ry, string $style = 'D'): void
    {
        if ($style === 'F') {
            $op = 'f';
        } elseif ($style === 'FD' || $style === 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;
        $k = $this->k;
        $h = $this->h;
        $this->_out(sprintf(
            '%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x + $rx) * $k, ($h - $y) * $k,
            ($x + $rx) * $k, ($h - ($y - $ly)) * $k,
            ($x + $lx) * $k, ($h - ($y - $ry)) * $k,
            $x * $k, ($h - ($y - $ry)) * $k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $lx) * $k, ($h - ($y - $ry)) * $k,
            ($x - $rx) * $k, ($h - ($y - $ly)) * $k,
            ($x - $rx) * $k, ($h - $y) * $k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $rx) * $k, ($h - ($y + $ly)) * $k,
            ($x - $lx) * $k, ($h - ($y + $ry)) * $k,
            $x * $k, ($h - ($y + $ry)) * $k
        ));
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x + $lx) * $k, ($h - ($y + $ry)) * $k,
            ($x + $rx) * $k, ($h - ($y + $ly)) * $k,
            ($x + $rx) * $k, ($h - $y) * $k,
            $op
        ));
    }

    public function Circle(float $x, float $y, float $r, string $style = 'D'): void
    {
        $this->Ellipse($x, $y, $r, $r, $style);
    }
}

class Formulario033Service
{
    protected float $scaleX;
    protected float $scaleY;

    // Dimensiones de la imagen de referencia en px (usada para medir TODAS
    // las coordenadas de este archivo comparando contra form033.pdf
    // renderizado a 150dpi). NO cambiar sin re-calibrar.
    const IMG_W = 1241;
    const IMG_H = 1754;
    // Dimensiones A4 en mm
    const A4_W = 210;
    const A4_H = 297;

    public function __construct()
    {
        $this->scaleX = self::A4_W / self::IMG_W;
        $this->scaleY = self::A4_H / self::IMG_H;
    }

    /**
     * Convierte coordenadas en px (medidas sobre la imagen de referencia) a mm para FPDF.
     * IMPORTANTE: todas las coordenadas de este archivo deben pasar por x()/y().
     * Nunca pasar valores mm "a mano" directamente a escribir() - eso fue lo que
     * causaba el desalineamiento del bloque de apellidos/nombre en la versión anterior.
     */
    private function x(float $px): float
    {
        return $px * $this->scaleX;
    }

    private function y(float $px): float
    {
        return $px * $this->scaleY;
    }

    public function generar($paciente, ?HistoriaClinica $historia, $tratamientos): string
    {
        $templatePath = storage_path('app/public/formularios/form033.pdf');

        $pdf = new Fpdi033();
        $pdf->SetAutoPageBreak(false);

        // ===== PÁGINA 1 =====
        $pdf->AddPage('P', 'A4');
        $pdf->setSourceFile($templatePath);
        $tpl1 = $pdf->importPage(1);
        $pdf->useTemplate($tpl1, 0, 0, 210, 297);

        $pac = $paciente->paciente ?? null;
        $od  = $tratamientos->first()?->cita?->odontologo ?? null;

        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetTextColor(0, 0, 0);

        // ── A. DATOS PACIENTE ──
        // Fila de datos (banda real en la plantilla: 134.7px - 160.9px).
        // Primer nombre/apellido vienen de users.name en formato "Nombre
        // Apellido" (mismo estándar que se exige al registrar pacientes).
        // Segundo nombre/apellido vienen de historias_clinicas, capturados
        // manualmente por el odontólogo en la primera consulta.
        $tokensPac = $this->tokensNombre($paciente->name ?? '');
        $nombrePac   = $tokensPac[0] ?? '';
        $apellidoPac = $tokensPac[1] ?? '';
        $segundoApellidoPac = $tokensPac[2] ?? '';

        $this->escribir($pdf, $this->x(60),  $this->y(140), $apellidoPac);             // primer apellido (col 49.5-241.2)
        $this->escribir($pdf, $this->x(250), $this->y(140), $historia?->segundo_apellido ?? $segundoApellidoPac); // segundo apellido (col 241.2-407.5)
        $this->escribir($pdf, $this->x(415), $this->y(140), $nombrePac);                // primer nombre (col 407.5-624.7)
        $this->escribir($pdf, $this->x(632), $this->y(140), $historia?->segundo_nombre ?? '');   // segundo nombre (col 624.7-791.0)
        $this->escribir($pdf, $this->x(800), $this->y(140), $pac?->genero ? mb_strtoupper(mb_substr($pac->genero, 0, 1)) : ''); // sexo (col 791.0-846.4) - "Femenino"->"F" / "Masculino"->"M"
        $this->escribir($pdf, $this->x(855), $this->y(140), (string) ($pac?->edad ?? ''));       // edad (col 846.4-901.8)

        // Cédula: no existe casilla propia en la sección A del 033, se usa
        // la celda de "NÚMERO DE HISTORIA CLÍNICA ÚNICA" (fila de datos,
        // banda 61.5-87.7px, columna 557.7-804.8px).
        $this->escribir($pdf, $this->x(575), $this->y(66), $pac?->cedula ?? '');

        // Condición edad H/D/M/A. Columnas: H=901.8-943.4 D=943.4-988.5
        // M=988.5-1053.3 A=1053.3-1177.0 (fila de datos y=134.7-160.9px).
        $colCondicionEdad = [
            'horas' => 922, 'dias' => 966, 'meses' => 1021, 'anios' => 1115,
        ];
        $condicion = $historia?->condicion_edad ?? 'anios';
        if (isset($colCondicionEdad[$condicion])) {
            $this->escribirCentrado($pdf, $this->x($colCondicionEdad[$condicion]), $this->y(148), 'X', 8, [0, 0, 0]);
        }

        // Embarazada SI/NO. Casillas: SI centro (959,186.5) NO centro (1115,186.5)
        if ($historia?->embarazada !== null) {
            $colX = $historia->embarazada ? 959 : 1115;
            $this->escribirCentrado($pdf, $this->x($colX), $this->y(186.5), 'X', 8, [0, 0, 0]);
        }

        // ── B. MOTIVO ── (banda 199.2-239.6px; subido a 204 para no cruzar la línea guía en 225.4px)
        $this->escribir($pdf, $this->x(60), $this->y(204), $historia?->motivo_consulta ?? '');

        // ── C. ENFERMEDAD ACTUAL ── (banda 239.6-409.1px, texto desde 268px)
        $this->escribirMultilinea($pdf, $this->x(60), $this->y(268), $this->x(1117), $historia?->enfermedad_actual ?? '');

        // ── D. ANTECEDENTES PERSONALES ── (banda 409.1-578.6px, texto desde 465px, debajo de la fila de checkboxes 1-10)
        $this->escribirMultilinea($pdf, $this->x(60), $this->y(465), $this->x(1117), $historia?->antecedentes_personales ?? '');

        // ── E. ANTECEDENTES FAMILIARES ── (banda 578.6-759.0px, texto desde 645px)
        $this->escribirMultilinea($pdf, $this->x(60), $this->y(645), $this->x(1117), $historia?->antecedentes_familiares ?? '');

        // ── F. CONSTANTES VITALES ── (celdas de valor, banda real 783.1-809.3px)
        $pdf->SetFont('Helvetica', '', 7);
        $this->escribir($pdf, $this->x(210), $this->y(790), (string) ($historia?->temperatura ?? '')); // celda 185.8-296.6
        $this->escribir($pdf, $this->x(430), $this->y(790), (string) ($historia?->pulso ?? ''));       // celda 407.5-571.5
        $pdf->SetFont('Helvetica', '', 6.5); // celda angosta (694.0-777.1)
        $this->escribir($pdf, $this->x(705), $this->y(790), (string) ($historia?->frecuencia_respiratoria ?? ''));
        $pdf->SetFont('Helvetica', '', 7);
        $this->escribir($pdf, $this->x(900), $this->y(790), (string) ($historia?->presion_arterial ?? '')); // celda 888.0-1177.0

        // ── G. EXAMEN ESTOMATOGNÁTICO ── (banda 823.5-1046.6px, texto desde 906px, debajo de las 2 filas de labels)
        $texto = trim(($historia?->examen_extraoral ?? '') . ' ' . ($historia?->examen_intraoral ?? ''));
        $this->escribirMultilinea($pdf, $this->x(60), $this->y(906), $this->x(1117), $texto);

        // ── H. ODONTOGRAMA — marcar piezas ──
        $this->marcarOdontograma($pdf, $tratamientos);

        // ===== PÁGINA 2 =====
        $pdf->AddPage('P', 'A4');
        $tpl2 = $pdf->importPage(2);
        $pdf->useTemplate($tpl2, 0, 0, 210, 297);

        $pdf->SetFont('Helvetica', '', 7);

        // ── N. DIAGNÓSTICO ── (fila "1." en y=351.3px, texto tras el número, x=75px)
        $this->escribir($pdf, $this->x(75), $this->y(351), $historia?->diagnostico_inicial ?? '');

        // ── O. DATOS PROFESIONAL ── (fila de datos y=500px, debajo de los labels en 472.7px)
        $this->escribir($pdf, $this->x(76),  $this->y(500), $historia?->fecha_apertura?->format('Y-m-d') ?? now()->format('Y-m-d'));

        // Mismo estándar "Nombre Apellido" que el paciente arriba.
        $tokensOd = $this->tokensNombre($od?->user?->name ?? '');
        $this->escribir($pdf, $this->x(429), $this->y(500), $tokensOd[0] ?? '');  // primer nombre (col x=429.6)
        $this->escribir($pdf, $this->x(744), $this->y(500), $tokensOd[1] ?? '');  // primer apellido (col x=744.8)
        $this->escribir($pdf, $this->x(1013), $this->y(500), $tokensOd[2] ?? ''); // segundo apellido (col x=1013.8)

        // Número de documento / licencia. La celda del label (banda
        // 526.4-559.9px) es angosta y de una sola línea - no cabe nada al
        // lado ni debajo sin chocar con el texto del label. Pero justo
        // abajo (banda 559.9-612.0px, misma columna) hay una franja en
        // blanco sin usar (es alto por espacio para firma/sello) - el
        // valor va ahí.
        $this->escribir($pdf, $this->x(81), $this->y(578), $od?->numero_licencia ?? '');

        // ── P. TRATAMIENTO — sesiones ──
        $this->escribirSesiones($pdf, $tratamientos);

        return $pdf->Output('S');
    }

    private function escribir(Fpdi $pdf, float $x, float $y, string $texto): void
    {
        $pdf->SetXY($x, $y);
        $pdf->Write(4, $this->limpiar($texto));
    }

    private function escribirMultilinea(Fpdi $pdf, float $x, float $y, float $ancho, string $texto): void
    {
        $pdf->SetXY($x, $y);
        $pdf->MultiCell($ancho, 4.4, $this->limpiar($texto), 0, 'L');
    }

    /**
     * Escribe texto centrado horizontalmente (y verticalmente aprox.) sobre
     * un punto (px, py). Útil para marcas dentro de casillas/celdas, para
     * que la "X" no quede pegada al borde sino dentro del cuadro.
     */
    private function escribirCentrado(Fpdi $pdf, float $xMm, float $yMm, string $texto, float $size, array $rgb): void
    {
        $pdf->SetFont('Helvetica', 'B', $size);
        [$r, $g, $b] = $rgb;
        $pdf->SetTextColor($r, $g, $b);
        $ancho = $pdf->GetStringWidth($texto);
        $pdf->SetXY($xMm - $ancho / 2, $yMm - ($size * 0.35 * 0.352778)); // 0.352778 = pt->mm
        $pdf->Write($size * 0.352778, $texto);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Helvetica', '', 7);
    }

    private function limpiar(string $texto): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $texto) ?: $texto;
    }

    /**
     * Limpia un nombre completo (quita títulos como Od., Dr., Dra., Lic.,
     * Ing., Mg.) y lo separa en tokens, asumiendo el formato estándar
     * "Nombre Apellido [SegundoApellido]". Nota: el paciente de prueba
     * actual ("Lopez Paciente") no sigue este formato porque es un dato
     * de prueba mal cargado; los pacientes que se registren de ahora en
     * adelante deben ingresar su nombre como "Nombre Apellido".
     */
    private function tokensNombre(?string $nombreCompleto): array
    {
        $limpio = trim((string) $nombreCompleto);
        $limpio = preg_replace('/^(od|dr|dra|lic|ing|mg)\.?\s+/iu', '', $limpio);

        return array_values(array_filter(explode(' ', $limpio), fn ($t) => $t !== ''));
    }

    private function marcarOdontograma(Fpdi033 $pdf, $tratamientos): void
    {
        // Coordenadas REALES (px sobre imagen de referencia 1241x1754),
        // medidas a partir de las posiciones de las etiquetas numéricas de
        // cada pieza en form033.pdf. La marca del centro del ícono se
        // estima desplazada respecto al número (los íconos están justo
        // debajo de los números en el arco superior y justo encima en el
        // inferior).
        $coordsPerm = [
            // Superior permanente (número impreso en y=1124.6; ícono debajo)
            18 => [167.1, 1155], 17 => [208.7, 1155], 16 => [250.3, 1155], 15 => [291.8, 1155],
            14 => [333.4, 1155], 13 => [375.0, 1155], 12 => [416.5, 1155], 11 => [458.1, 1155],
            21 => [619.9, 1155], 22 => [661.5, 1155], 23 => [703.0, 1155], 24 => [744.6, 1155],
            25 => [786.2, 1155], 26 => [827.7, 1155], 27 => [869.3, 1155], 28 => [910.9, 1155],
            // Inferior permanente (número impreso en y=1338.9; ícono encima)
            48 => [167.1, 1309], 47 => [208.7, 1309], 46 => [250.3, 1309], 45 => [291.8, 1309],
            44 => [333.4, 1309], 43 => [375.0, 1309], 42 => [416.5, 1309], 41 => [458.1, 1309],
            31 => [619.9, 1309], 32 => [661.5, 1309], 33 => [703.0, 1309], 34 => [744.6, 1309],
            35 => [786.2, 1309], 36 => [827.7, 1309], 37 => [869.3, 1309], 38 => [910.9, 1309],
        ];

        $coordsTemp = [
            // Superior temporal (número impreso en y=1188.2; ícono debajo)
            55 => [229.5, 1208], 54 => [271.1, 1208], 53 => [312.6, 1208], 52 => [354.2, 1208], 51 => [395.8, 1208],
            61 => [682.3, 1208], 62 => [723.8, 1208], 63 => [765.4, 1208], 64 => [807.0, 1208], 65 => [848.6, 1208],
            // Inferior temporal (número impreso en y=1273.1; ícono encima)
            85 => [229.5, 1253], 84 => [271.1, 1253], 83 => [312.6, 1253], 82 => [354.2, 1253], 81 => [395.8, 1253],
            71 => [682.3, 1253], 72 => [723.8, 1253], 73 => [765.4, 1253], 74 => [807.0, 1253], 75 => [848.6, 1253],
        ];

        // OJO: usar "+" (unión de arrays) y NO array_merge() aquí.
        // array_merge() con arrays de claves numéricas (18, 51, etc.) las
        // vuelve a numerar desde 0, perdiendo la relación pieza->coordenada
        // por completo. Con "+" sí se conservan las claves originales.
        $todas = $coordsPerm + $coordsTemp;

        $piezasConInfo = collect();
        foreach ($tratamientos as $t) {
            foreach ($t->piezas as $p) {
                $piezasConInfo->push($p);
            }
        }

        foreach ($piezasConInfo->groupBy('pieza_numero') as $num => $piezas) {
            if (!isset($todas[$num])) continue;

            [$px, $py] = $todas[$num];
            $xMm = $this->x($px);
            $yMm = $this->y($py);
            $ausente = $piezas->contains('ausente', true);
            // Si hay varias caras/procedimientos para la misma pieza, se usa
            // el primero con procedimiento no vacío como referencia para el símbolo.
            $procedimiento = $piezas->pluck('procedimiento')->filter()->first() ?? '';

            $this->dibujarSimboloOdontograma($pdf, $xMm, $yMm, $procedimiento, $ausente);

            // Movilidad/Recesión: solo piezas permanentes (11-48) tienen
            // estas casillas en el formulario 033 - las temporales (51-85)
            // no las traen.
            if ($num >= 11 && $num <= 48) {
                $movilidad = $piezas->pluck('movilidad')->filter()->first();
                $recesion = $piezas->pluck('recesion')->filter()->first();
                $this->marcarMovilidadRecesion($pdf, $px, $num, $movilidad, $recesion);
            }
        }
    }

    /**
     * Marca las casillas de MOVILIDAD/RECESIÓN de la sección H (arriba de
     * las piezas superiores 18-28, abajo de las piezas inferiores 48-38).
     * Coordenadas (px, sistema de referencia 1241x1754) medidas
     * directamente sobre form033.pdp:
     *   Superior: RECESIÓN y=1094.0 | MOVILIDAD y=1112.5
     *   Inferior: MOVILIDAD y=1360.6 | RECESIÓN y=1379.1
     * (el orden se invierte abajo: primero movilidad, luego recesión,
     * igual que está impreso en la plantilla).
     */
    private function marcarMovilidadRecesion(Fpdi033 $pdf, float $px, int $num, ?int $movilidad, ?int $recesion): void
    {
        $esSuperior = ($num >= 11 && $num <= 28);
        $yRecesion = $esSuperior ? 1094.0 : 1379.1;
        $yMovilidad = $esSuperior ? 1112.5 : 1360.6;

        if ($movilidad !== null) {
            $this->escribirCentrado($pdf, $this->x($px), $this->y($yMovilidad), (string) $movilidad, 6, [0, 0, 0]);
        }
        if ($recesion !== null) {
            $this->escribirCentrado($pdf, $this->x($px), $this->y($yRecesion), (string) $recesion, 6, [0, 0, 0]);
        }
    }

    /**
     * Traduce (procedimiento, ausente) al símbolo oficial de la leyenda
     * "K. SIMBOLOGÍA DEL ODONTOGRAMA" del formulario 033 y lo dibuja.
     *
     * Cobertura según los procedimientos disponibles en el <select> del
     * formulario de "Completar tratamiento" (Profiláctico, Restauración
     * con Resina, Sellador, Extracción, Endodoncia, Corona, Implante,
     * Ortodoncia, Blanqueamiento, Limpieza):
     *
     *   - Ausente (cualquier procedimiento)  -> letra "A" (AUSENTE)
     *   - Extracción (no ausente, pendiente) -> X roja (EXTRACCIÓN INDICADA)
     *   - Sellador                           -> punto relleno azul (SELLANTE REALIZADO)
     *   - Restauración con Resina            -> círculo azul (OBTURADO)
     *   - Endodoncia                         -> triángulo azul (ENDODONCIA REALIZADA)
     *   - Corona                             -> punto relleno negro (CORONA REALIZADA)
     *
     * Profiláctico, Implante, Ortodoncia, Blanqueamiento y Limpieza NO
     * tienen símbolo en la leyenda oficial del HCU-form.033 (esa leyenda
     * es específica de caries/obturaciones/endodoncia/corona/prótesis/
     * ausencias, no cubre todos los procedimientos odontológicos posibles).
     * Para esos casos se deja un asterisco azul genérico como respaldo,
     * marcado en el código para que se note que no es un símbolo oficial.
     */
    private function dibujarSimboloOdontograma(Fpdi033 $pdf, float $xMm, float $yMm, string $procedimiento, bool $ausente): void
    {
        $proc = mb_strtolower($procedimiento);
        $r = 1.4; // radio de los símbolos circulares/triángulos, en mm

        if ($ausente) {
            // AUSENTE: letra "A", símbolo oficial (no una "X" - la "X" del
            // formulario 033 significa otra cosa: extracción indicada o
            // pérdida por caries, no "pieza ausente" en general).
            $this->escribirCentrado($pdf, $xMm, $yMm, 'A', 8, [0, 0, 0]);
            return;
        }

        if (str_contains($proc, 'extrac')) {
            // Pieza presente pero con extracción registrada como pendiente:
            // X roja = EXTRACCIÓN INDICADA.
            $this->escribirCentrado($pdf, $xMm, $yMm, 'X', 8, [255, 0, 0]);
            return;
        }

        if (str_contains($proc, 'sellad')) {
            // SELLANTE REALIZADO: punto relleno azul.
            $pdf->SetFillColor(0, 0, 255);
            $pdf->Circle($xMm, $yMm, $r, 'F');
            return;
        }

        if (str_contains($proc, 'resina') || str_contains($proc, 'restaura')) {
            // OBTURADO: círculo azul (contorno, para diferenciarlo del
            // sellante que va relleno).
            $pdf->SetDrawColor(0, 0, 255);
            $pdf->SetLineWidth(0.25);
            $pdf->Circle($xMm, $yMm, $r, 'D');
            return;
        }

        if (str_contains($proc, 'endodon')) {
            // ENDODONCIA REALIZADA: triángulo azul (contorno). FPDF base no
            // trae Polygon(), así que se dibuja con 3 líneas.
            $pdf->SetDrawColor(0, 0, 255);
            $pdf->SetLineWidth(0.25);
            $p1 = [$xMm, $yMm - $r];
            $p2 = [$xMm - $r, $yMm + $r];
            $p3 = [$xMm + $r, $yMm + $r];
            $pdf->Line($p1[0], $p1[1], $p2[0], $p2[1]);
            $pdf->Line($p2[0], $p2[1], $p3[0], $p3[1]);
            $pdf->Line($p3[0], $p3[1], $p1[0], $p1[1]);
            return;
        }

        if (str_contains($proc, 'corona')) {
            // CORONA REALIZADA: punto relleno.
            $pdf->SetFillColor(0, 0, 0);
            $pdf->Circle($xMm, $yMm, $r, 'F');
            return;
        }

        // Profiláctico, Implante, Ortodoncia, Blanqueamiento, Limpieza u
        // otro procedimiento sin símbolo en la leyenda oficial del 033:
        // marca genérica (NO oficial) para no dejar la pieza sin indicar
        // que tuvo algún tratamiento.
        $this->escribirCentrado($pdf, $xMm, $yMm, '*', 10, [0, 0, 255]);
    }

    /**
     * Envuelve $texto (puede traer "\n" para forzar saltos) en las líneas
     * físicas que realmente entran en $anchoPx, haciendo el mismo cálculo
     * palabra-por-palabra que hace MultiCell internamente. Se necesita
     * hacerlo a mano (en vez de dejárselo a MultiCell) para poder ubicar
     * cada línea física en su propio hueco entre rayas después.
     */
    private function envolverTexto(Fpdi $pdf, string $texto, float $anchoPx): array
    {
        $anchoMm = $this->x($anchoPx) - 1; // -1mm de margen de seguridad
        $lineasFisicas = [];
        foreach (explode("\n", $texto) as $lineaLogica) {
            $actual = '';
            foreach (explode(' ', $lineaLogica) as $palabra) {
                $prueba = $actual === '' ? $palabra : $actual . ' ' . $palabra;
                if ($pdf->GetStringWidth($this->limpiar($prueba)) > $anchoMm && $actual !== '') {
                    $lineasFisicas[] = $actual;
                    $actual = $palabra;
                } else {
                    $actual = $prueba;
                }
            }
            $lineasFisicas[] = $actual;
        }

        return $lineasFisicas;
    }

    /**
     * Escribe cada línea física de $texto en el CENTRO de un hueco entre
     * rayas guía consecutivo (una línea por hueco), para que nunca cruce
     * ninguna raya y a la vez la plantilla se mantenga intacta.
     *
     * Medido directamente sobre un PDF real generado: las rayas guía de la
     * tabla P.TRATAMIENTO están espaciadas cada 11.34pt exactos = 23.64px
     * en el sistema de referencia de este archivo (1241x1754). El bloque
     * de una sesión (165.4px) son exactamente 7 huecos de 23.64px.
     */
    private function escribirEntreRayas(Fpdi $pdf, float $xPx, float $anchoPx, float $yPxBase, string $texto, float $fontSize = 6.5): void
    {
        $altoHueco = 23.64;
        $pdf->SetFont('Helvetica', '', $fontSize);
        $lineas = $this->envolverTexto($pdf, $texto, $anchoPx);

        foreach ($lineas as $idx => $linea) {
            if ($idx >= 7) break; // no salir del bloque de sesión (7 huecos disponibles)
            $yHueco = $yPxBase + ($idx * $altoHueco) - 8; // -8px: compensa un desfase de ~2.5pt medido entre la posición pedida y donde FPDF realmente dibuja el texto
            $this->escribir($pdf, $this->x($xPx), $this->y($yHueco), $linea);
        }
    }

    private function escribirSesiones(Fpdi $pdf, $tratamientos): void
    {
        // Medido directamente sobre la tabla P de la plantilla:
        // 1ra fila "No. SESIÓN" en y=703.5px, "FECHA" (misma fila) en y=774.4px
        // (+70.9px), y cada bloque de sesión mide 165.4px de alto.
        //
        // La columna "No. DE SESIÓN Y FECHA" (x=48.9-190.0px) es angosta y
        // ya trae impresas las etiquetas "No. SESIÓN" (empieza en x=93.6)
        // y "FECHA" (empieza en x=103.6) como guía de llenado a mano. Si se
        // escribe el valor en esa misma línea se monta encima del label, así
        // que: el número de sesión va a la IZQUIERDA del label "No. SESIÓN"
        // (hay ~40px libres antes de que empiece el texto impreso), y la
        // fecha va en la línea de abajo (no a la derecha de "FECHA", porque
        // una fecha completa no cabe en el espacio angosto que queda).
        $yBase = 703.5;
        $alturaFila = 165.4;

        // Columnas de la tabla (px): Diagnósticos 190-472 | Procedimientos 490-790 | Prescripciones 790-1037
        foreach ($tratamientos as $i => $t) {
            $yPxBase = $yBase + ($i * $alturaFila); // tope del bloque de esta sesión, en px
            if ($this->y($yPxBase) > 280) break; // no salir de la página

            // Número de sesión: hueco 0 (mismo renglón que "No. SESIÓN").
            // Fecha: hueco 4 (dos huecos después de "FECHA", que está en el
            // hueco 3 = offset 70.9px). Mismo margen de +2px verificado.
            $ySesionNum = $this->y($yPxBase - 8);
            $yFecha     = $this->y($yPxBase + (4 * 23.64) - 8);

            $pdf->SetFont('Helvetica', 'B', 6.5);
            $this->escribir($pdf, $this->x(55), $ySesionNum, (string) ($i + 1)); // a la izquierda de "No. SESIÓN"
            $pdf->SetFont('Helvetica', '', 6);
            $this->escribir($pdf, $this->x(55), $yFecha, Carbon::parse($t->fecha_tratamiento)->format('d/m/Y')); // debajo de "FECHA"

            // Solo se agrega el "—" cuando NO hay ni descripción ni piezas
            // (para no dejarlo como primera línea suelta encima de "Piezas: ...")
            $lineasDiag = [];
            if (!empty($t->descripcion)) {
                $lineasDiag[] = $t->descripcion;
            }
            if ($t->piezas->count() > 0) {
                $lineasDiag[] = 'Piezas: ' . $t->piezas->pluck('pieza_numero')->unique()->join(', ');
            }
            $diag = $lineasDiag ? implode("\n", $lineasDiag) : '—';
            $this->escribirEntreRayas($pdf, 200, 270, $yPxBase, $diag);

            $proc = $t->nombre;
            // Si el odontólogo puso el mismo procedimiento en "Nombre del
            // tratamiento" (ej. "Extraccion") y en el select de la pieza
            // (ej. "Extracción"), no lo repitas dos veces en el PDF.
            $normalizar = fn (string $s) => mb_strtolower(trim(str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $s)));
            $procsPiezas = $t->piezas->pluck('procedimiento')->filter()
                ->reject(fn ($p) => $normalizar($p) === $normalizar($t->nombre ?? ''))
                ->unique();
            if ($procsPiezas->count() > 0) {
                $proc .= "\n" . $procsPiezas->join(', ');
            }
            $this->escribirEntreRayas($pdf, 500, 290, $yPxBase, $proc);

            $this->escribirEntreRayas($pdf, 800, 235, $yPxBase, $t->observaciones ?? '—');
        }
    }
}

/*
 * ══════════════════════════════════════════════════════════════════════
 * REQUIERE (para que este archivo funcione tal cual):
 * ══════════════════════════════════════════════════════════════════════
 *
 * 1. Ejecutar la migración que agrega a `historias_clinicas`:
 *    segundo_nombre, segundo_apellido, embarazada, condicion_edad
 *    (ver 2026_07_11_222503_add_campos_formulario033_to_historias_clinicas_table.php)
 *
 * 2. Agregar esas 4 columnas al $fillable de App\Models\HistoriaClinica.
 *
 * 3. Agregar los campos al formulario de creación de historia clínica
 *    (resources/views/odontologo/historia-clinica/crear.blade.php) y a
 *    HistoriaClinicaController@store / @update para que se guarden.
 *    Ver snippets sugeridos aparte.
 *
 * 4. Ejecutar la migración que agrega a `tratamiento_piezas`: movilidad,
 *    recesion (ver 2026_07_14_000000_add_movilidad_recesion_to_tratamiento_piezas_table.php)
 *    y aplicar los cambios del formulario de "Completar tratamiento"
 *    descritos en INSTRUCCIONES_movilidad_recesion.md para poder
 *    capturarlos desde la web.
 *
 * PENDIENTE (sin resolver, no es parte de este archivo):
 * - Teléfono, email, dirección y contacto de emergencia del paciente NO
 *   se imprimen en el PDF: el formulario 033 oficial no tiene casilla para
 *   ellos en la sección A. Siguen disponibles en el sistema (perfil del
 *   paciente), solo no aparecen en este documento específico.
 */