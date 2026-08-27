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
        // Los 4 componentes del nombre se leen DIRECTO de las columnas de
        // `users` (primer_nombre, segundo_nombre, primer_apellido,
        // segundo_apellido). Ya no se parsea `users.name`: ese parseo era
        // ambiguo y dejaba las casillas de apellidos vacías.
        $this->escribir($pdf, $this->x(60),  $this->y(140), $paciente->primer_apellido ?? '');   // primer apellido (col 49.5-241.2)
        $this->escribir($pdf, $this->x(250), $this->y(140), $paciente->segundo_apellido ?? '');  // segundo apellido (col 241.2-407.5)
        $this->escribir($pdf, $this->x(415), $this->y(140), $paciente->primer_nombre ?? '');     // primer nombre (col 407.5-624.7)
        $this->escribir($pdf, $this->x(632), $this->y(140), $paciente->segundo_nombre ?? '');    // segundo nombre (col 624.7-791.0)
        $this->escribir($pdf, $this->x(800), $this->y(140), $pac?->genero ? mb_strtoupper(mb_substr($pac->genero, 0, 1)) : ''); // sexo (col 791.0-846.4) - "Femenino"->"F" / "Masculino"->"M"
        $this->escribir($pdf, $this->x(855), $this->y(140), (string) ($pac?->edad ?? ''));       // edad (col 846.4-901.8)

        // Cédula: no existe casilla propia en la sección A del 033, se usa
        // la celda de "NÚMERO DE HISTORIA CLÍNICA ÚNICA" (fila de datos,
        // banda 61.5-87.7px, columna 557.7-804.8px).
        $this->escribir($pdf, $this->x(575), $this->y(66), $pac?->cedula ?? $paciente->cedula ?? '');

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

        // ── I. INDICADORES DE SALUD BUCAL (HOS) ── (PÁGINA 1, bloque inferior
        // izquierdo, justo debajo del odontograma — NO en la página 2).
        // Calibrado rasterizando form033.pdf a 150dpi (pdftoppm -r 150) y
        // midiendo los bordes reales de celda con detección de píxeles
        // oscuros (no a ojo). Bordes de fila reales (px):
        // 1499 / 1526 / 1552 / 1578 / 1606 / 1639 / 1681 → se usa el centro
        // de cada fila para que el valor no choque con las líneas guía.
        $hosFilas = [
            16 => 1512, 11 => 1539, 26 => 1565,
            36 => 1592, 31 => 1622, 46 => 1660,
        ];
        // Columnas (centro de celda, px): PLACA=268 CÁLCULO=323 GINGIVITIS=378
        // (bordes reales: 241 / 296 / 351 / 406)
        $hosPlacaData      = $historia?->hos_placa ?? [];
        $hosCalculoData    = $historia?->hos_calculo ?? [];
        $hosGingivitisData = $historia?->hos_gingivitis ?? [];

        foreach ($hosFilas as $pieza => $yPx) {
            $yMm = $this->y($yPx);
            $vPlaca      = $hosPlacaData[(string)$pieza] ?? $hosPlacaData[$pieza] ?? '';
            $vCalculo    = $hosCalculoData[(string)$pieza] ?? $hosCalculoData[$pieza] ?? '';
            $vGingivitis = $hosGingivitisData[(string)$pieza] ?? $hosGingivitisData[$pieza] ?? '';
            if ($vPlaca !== '')      $this->escribirCentrado($pdf, $this->x(268), $yMm, (string)$vPlaca, 7, [0, 0, 0]);
            if ($vCalculo !== '')    $this->escribirCentrado($pdf, $this->x(323), $yMm, (string)$vCalculo, 7, [0, 0, 0]);
            if ($vGingivitis !== '') $this->escribirCentrado($pdf, $this->x(378), $yMm, (string)$vGingivitis, 7, [0, 0, 0]);
        }

        // Casillas de "pieza examinada" (16/17/55, 11/21/51, 26/27/65,
        // 36/37/75, 31/41/71, 46/47/85): cada fila trae 3 piezas
        // alternativas y una casilla en blanco junto a cada una (celda
        // vacía sin imprimir, a la derecha del número). Se marca con "X"
        // la(s) pieza(s) presentes en hos_examinada. Columnas de casilla
        // (centro, px): 97 / 171 / 226 (bordes reales 77-118, 157-185,
        // 213-240). Mismas filas Y que $hosFilas.
        $filasPiezas = [
            1512 => [16, 17, 55], 1539 => [11, 21, 51], 1565 => [26, 27, 65],
            1592 => [36, 37, 75], 1622 => [31, 41, 71], 1660 => [46, 47, 85],
        ];
        $checkboxX = [97, 171, 226];
        $hosExaminadaData = $historia?->hos_examinada ?? [];
        foreach ($filasPiezas as $yPx => $piezasFila) {
            foreach ($piezasFila as $idx => $pieza) {
                $marcado = $hosExaminadaData[(string)$pieza] ?? $hosExaminadaData[$pieza] ?? null;
                if ($marcado) {
                    $this->escribirCentrado($pdf, $this->x($checkboxX[$idx]), $this->y($yPx), 'X', 7, [0, 0, 0]);
                }
            }
        }

        // Tipo de oclusión (ANGLE I/II/III) y nivel de fluorosis (LEVE/
        // MODERADA/SEVERA): casillas a la derecha de cada etiqueta, en las
        // mismas 3 filas (bordes reales 1460/1486/1512/1538 → centros
        // 1473/1499/1525). Columnas de casilla (centro, px): oclusión=665,
        // fluorosis=775 (bordes reales 652-679 y 762-789).
        $oclusion = $historia?->tipo_oclusion ?? '';
        $oclusionCoordsY = ['Angle I' => 1473, 'Angle II' => 1499, 'Angle III' => 1525];
        if (isset($oclusionCoordsY[$oclusion])) {
            $this->escribirCentrado($pdf, $this->x(665), $this->y($oclusionCoordsY[$oclusion]), 'X', 7, [0, 0, 0]);
        }
        $fluorosis = $historia?->nivel_fluorosis ?? '';
        $fluorosisCoordsY = ['Leve' => 1473, 'Moderada' => 1499, 'Severa' => 1525];
        if (isset($fluorosisCoordsY[$fluorosis])) {
            $this->escribirCentrado($pdf, $this->x(775), $this->y($fluorosisCoordsY[$fluorosis]), 'X', 7, [0, 0, 0]);
        }

        // ── J. ÍNDICES CPO-ceo ── (PÁGINA 1, misma banda que I, mitad
        // derecha del bloque inferior). Filas (centro, px): D=1473 d=1524
        // (bordes reales 1460/1486 y 1512/1538). Columnas (centro, px):
        // C=852 P=894 O=951 TOTAL=1080 (bordes reales 831/873/915/987/1174).
        $cpoC = $historia?->cpo_c ?? 0;
        $cpoP = $historia?->cpo_p ?? 0;
        $cpoO = $historia?->cpo_o ?? 0;
        $ceoC = $historia?->ceo_c ?? 0;
        $ceoE = $historia?->ceo_e ?? 0;
        $ceoO = $historia?->ceo_o ?? 0;

        $this->escribirCentrado($pdf, $this->x(852),  $this->y(1473), (string)$cpoC, 7, [0, 0, 0]);
        $this->escribirCentrado($pdf, $this->x(894),  $this->y(1473), (string)$cpoP, 7, [0, 0, 0]);
        $this->escribirCentrado($pdf, $this->x(951),  $this->y(1473), (string)$cpoO, 7, [0, 0, 0]);
        // La celda TOTAL (naranja) YA trae un "0" impreso en la plantilla
        // form033.pdf (no es un campo en blanco). Si se escribe el total
        // encima, el "0" de la plantilla y el valor calculado se solapan
        // (ej. "1" real se ve pegado a un "0" fantasma). Se tapa esa celda
        // con un rectángulo del mismo naranja del template (RGB muestreado
        // directo del PDF) antes de escribir el valor real.
        $this->taparCeldaNaranja($pdf, 989, 1462, 1172, 1484);
        $this->escribirCentrado($pdf, $this->x(1080), $this->y(1473), (string)($cpoC + $cpoP + $cpoO), 7, [0, 0, 0]);

        $this->escribirCentrado($pdf, $this->x(852),  $this->y(1524), (string)$ceoC, 7, [0, 0, 0]);
        $this->escribirCentrado($pdf, $this->x(894),  $this->y(1524), (string)$ceoE, 7, [0, 0, 0]);
        $this->escribirCentrado($pdf, $this->x(951),  $this->y(1524), (string)$ceoO, 7, [0, 0, 0]);
        $this->taparCeldaNaranja($pdf, 989, 1514, 1172, 1536);
        $this->escribirCentrado($pdf, $this->x(1080), $this->y(1524), (string)($ceoC + $ceoE + $ceoO), 7, [0, 0, 0]);

        // ===== PÁGINA 2 =====
        $pdf->AddPage('P', 'A4');
        $tpl2 = $pdf->importPage(2);
        $pdf->useTemplate($tpl2, 0, 0, 210, 297);

        $pdf->SetFont('Helvetica', '', 7);

        // ── L. PEDIDO DE EXÁMENES COMPLEMENTARIOS ──
        // Caja de texto libre (3 líneas guía), banda real y=0-105px en el
        // sistema de referencia de la página 2. Texto arranca debajo del
        // título (y=26px) con el mismo margen usado en las demás cajas de
        // texto libre del formulario (C/D/E/G en la página 1).
        $this->escribirMultilinea($pdf, $this->x(60), $this->y(32), $this->x(1117), $historia?->examenes_pedido ?? '');

        // ── M. INFORME DE EXÁMENES ──
        // Fila de checkboxes (banda real y=142-166px, centro=154):
        // BIOMETRIA=171 | QUÍMICA SANGUÍNEA=313 | RAYOS-X=454 | OTROS(check)=595
        // seguido de un espacio libre (x=620-1170) para el nombre del otro
        // examen. Debajo, caja de texto libre (banda 166-306px) para el
        // informe/resultado.
        $pdf->SetFont('Helvetica', '', 7);
        if ($historia?->examenes_biometria) {
            $this->escribirCentrado($pdf, $this->x(171), $this->y(154), 'X', 8, [0, 0, 0]);
        }
        if ($historia?->examenes_quimica) {
            $this->escribirCentrado($pdf, $this->x(313), $this->y(154), 'X', 8, [0, 0, 0]);
        }
        if ($historia?->examenes_rayos_x) {
            $this->escribirCentrado($pdf, $this->x(454), $this->y(154), 'X', 8, [0, 0, 0]);
        }
        $examenesOtros = $historia?->examenes_otros ?? '';
        if ($examenesOtros !== '') {
            $this->escribirCentrado($pdf, $this->x(595), $this->y(154), 'X', 8, [0, 0, 0]);
            $this->escribir($pdf, $this->x(625), $this->y(150), $examenesOtros);
        }
        $this->escribirMultilinea($pdf, $this->x(60), $this->y(170), $this->x(1117), $historia?->examenes_informe ?? '');

        // ── N. DIAGNÓSTICO (6 filas con CIE, PRE, DEF) ──
        // Coordenadas Y de cada fila en la página 2 (px)
        $diagFilasY = [351, 393, 435];  // filas 1,2,3 lado izquierdo
        $diagFilasYder = [351, 393, 435]; // filas 4,5,6 lado derecho
        $diagnosticos = $historia?->diagnosticos ?? [];

        $pdf->SetFont('Helvetica', '', 6.5);
        for ($i = 0; $i < 3; $i++) {
            $diag = $diagnosticos[$i] ?? [];
            $desc = $diag['descripcion'] ?? '';
            $cie  = $diag['cie'] ?? '';
            $tipo = $diag['tipo'] ?? '';
            if ($desc) $this->escribir($pdf, $this->x(75),  $this->y($diagFilasY[$i]), $this->limpiar($desc));
            if ($cie)  $this->escribir($pdf, $this->x(490), $this->y($diagFilasY[$i]), $this->limpiar($cie));
            if ($tipo === 'pre') $this->escribirCentrado($pdf, $this->x(560), $this->y($diagFilasY[$i] + 5), 'X', 7, [0,0,0]);
            if ($tipo === 'def') $this->escribirCentrado($pdf, $this->x(610), $this->y($diagFilasY[$i] + 5), 'X', 7, [0,0,0]);
        }
        for ($i = 3; $i < 6; $i++) {
            $diag = $diagnosticos[$i] ?? [];
            $desc = $diag['descripcion'] ?? '';
            $cie  = $diag['cie'] ?? '';
            $tipo = $diag['tipo'] ?? '';
            if ($desc) $this->escribir($pdf, $this->x(640), $this->y($diagFilasYder[$i-3]), $this->limpiar($desc));
            if ($cie)  $this->escribir($pdf, $this->x(1055), $this->y($diagFilasYder[$i-3]), $this->limpiar($cie));
            if ($tipo === 'pre') $this->escribirCentrado($pdf, $this->x(1125), $this->y($diagFilasYder[$i-3] + 5), 'X', 7, [0,0,0]);
            if ($tipo === 'def') $this->escribirCentrado($pdf, $this->x(1175), $this->y($diagFilasYder[$i-3] + 5), 'X', 7, [0,0,0]);
        }

        // ── O. DATOS PROFESIONAL ── (fila de datos y=500px, debajo de los labels en 472.7px)
        $this->escribir($pdf, $this->x(76),  $this->y(500), $historia?->fecha_apertura?->format('Y-m-d') ?? now()->format('Y-m-d'));

        // Igual que el paciente: columnas directas de `users`, sin parseo.
        $this->escribir($pdf, $this->x(429),  $this->y(500), $od?->user?->primer_nombre ?? '');    // primer nombre (col x=429.6)
        $this->escribir($pdf, $this->x(744),  $this->y(500), $od?->user?->primer_apellido ?? '');  // primer apellido (col x=744.8)
        $this->escribir($pdf, $this->x(1013), $this->y(500), $od?->user?->segundo_apellido ?? ''); // segundo apellido (col x=1013.8)

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

    /**
     * Tapa una celda de la plantilla (coords en px del sistema de
     * referencia 1241x1754) con el mismo naranja que usa form033.pdf en
     * los recuadros TOTAL de la sección J (RGB 249,206,153, muestreado
     * directo del PDF renderizado). Se usa para poder "reescribir" encima
     * de un valor que la plantilla ya trae impreso, sin dejar el dígito
     * viejo asomando debajo del nuevo.
     */
    private function taparCeldaNaranja(Fpdi $pdf, float $x1Px, float $y1Px, float $x2Px, float $y2Px): void
    {
        $pdf->SetFillColor(249, 206, 153);
        $pdf->Rect($this->x($x1Px), $this->y($y1Px), $this->x($x2Px) - $this->x($x1Px), $this->y($y2Px) - $this->y($y1Px), 'F');
    }

    private function limpiar(string $texto): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $texto) ?: $texto;
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
     * directamente sobre form033.pdf:
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