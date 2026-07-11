<?php

namespace App\Services;

use App\Models\HistoriaClinica;
use App\Models\Tratamiento;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;

class Formulario033Service
{
    protected Fpdi $pdf;
    protected float $scaleX;
    protected float $scaleY;

    // Dimensiones de la imagen de referencia en px
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
     * Convierte coordenadas en px (medidas sobre la imagen) a mm para FPDF
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

        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);

        // ===== PÁGINA 1 =====
        $pdf->AddPage('P', 'A4');
        $pdf->setSourceFile($templatePath);
        $tpl1 = $pdf->importPage(1);
        $pdf->useTemplate($tpl1, 0, 0, 210, 297);

        $pac = $paciente->paciente ?? null;
        $od  = $tratamientos->first()?->cita?->odontologo ?? null;

        // Font
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetTextColor(0, 0, 0);

        // ── A. DATOS PACIENTE ──
        $nombre = $paciente->user->name ?? '';
        $partes = explode(' ', $nombre);

        $this->escribir($pdf, 70,  82, $partes[0] ?? ''); // primer nombre
        $this->escribir($pdf, 140, 82, $partes[1] ?? ''); // primer apellido
        $this->escribir($pdf, 5,   82, $partes[2] ?? ''); // segundo apellido (ajustar según layout)

        // Sexo / Edad
        $this->escribir($pdf, $this->x(810), $this->y(86), $pac?->edad ?? '');

        // Cédula
        $this->escribir($pdf, $this->x(17),  $this->y(105), $pac?->cedula ?? '');
        // Fecha nacimiento
        $this->escribir($pdf, $this->x(205), $this->y(105), $pac?->fecha_nacimiento?->format('d/m/Y') ?? '');
        // Teléfono
        $this->escribir($pdf, $this->x(415), $this->y(105), $paciente->user->telefono ?? '');
        // Email
        $this->escribir($pdf, $this->x(620), $this->y(105), $paciente->user->email ?? '');
        // Dirección
        $this->escribir($pdf, $this->x(17),  $this->y(122), $pac?->direccion ?? '');
        // Contacto emergencia
        $this->escribir($pdf, $this->x(450), $this->y(122), $pac?->contacto_emergencia ?? '');

        // ── B. MOTIVO ──
        $this->escribir($pdf, $this->x(17), $this->y(163), $historia?->motivo_consulta ?? '');

        // ── C. ENFERMEDAD ACTUAL ──
        $this->escribirMultilinea($pdf, $this->x(17), $this->y(198), 180, $historia?->enfermedad_actual ?? '');

        // ── D. ANTECEDENTES PERSONALES ──
        $this->escribirMultilinea($pdf, $this->x(17), $this->y(340), 180, $historia?->antecedentes_personales ?? '');

        // ── E. ANTECEDENTES FAMILIARES ──
        $this->escribirMultilinea($pdf, $this->x(17), $this->y(430), 180, $historia?->antecedentes_familiares ?? '');

        // ── F. CONSTANTES VITALES ──
        $this->escribir($pdf, $this->x(75),  $this->y(463), $historia?->temperatura ?? '');
        $this->escribir($pdf, $this->x(285), $this->y(463), $historia?->pulso ?? '');
        $this->escribir($pdf, $this->x(530), $this->y(463), $historia?->frecuencia_respiratoria ?? '');
        $this->escribir($pdf, $this->x(770), $this->y(463), $historia?->presion_arterial ?? '');

        // ── G. EXAMEN ESTOMATOGNÁTICO ──
        $this->escribirMultilinea($pdf, $this->x(17), $this->y(575), 180, ($historia?->examen_extraoral ?? '') . ' ' . ($historia?->examen_intraoral ?? ''));

        // ── H. ODONTOGRAMA — marcar piezas ──
        $this->marcarOdontograma($pdf, $tratamientos);

        // ===== PÁGINA 2 =====
        $pdf->AddPage('P', 'A4');
        $tpl2 = $pdf->importPage(2);
        $pdf->useTemplate($tpl2, 0, 0, 210, 297);

        $pdf->SetFont('Helvetica', '', 7);

        // ── N. DIAGNÓSTICO ──
        $this->escribir($pdf, $this->x(17), $this->y(226), $historia?->diagnostico_inicial ?? '');

        // ── O. DATOS PROFESIONAL ──
        $this->escribir($pdf, $this->x(17),  $this->y(298), $historia?->fecha_apertura?->format('Y-m-d') ?? now()->format('Y-m-d'));
        $this->escribir($pdf, $this->x(230), $this->y(298), $od?->user?->name ?? '');
        $this->escribir($pdf, $this->x(17),  $this->y(320), $od?->numero_licencia ?? '');

        // ── P. TRATAMIENTO — sesiones ──
        $this->escribirSesiones($pdf, $tratamientos);

        // Guardar en memoria y retornar
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
        $pdf->MultiCell($ancho, 4, $this->limpiar($texto), 0, 'L');
    }

    private function limpiar(string $texto): string
    {
        return iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $texto) ?: $texto;
    }

    private function marcarOdontograma(Fpdi $pdf, $tratamientos): void
    {
        // Coordenadas aproximadas de cada pieza en el odontograma (px sobre imagen)
        // Fila superior permanente: piezas 18-11, 21-28
        $coordsPerm = [
            // Superior
            18 => [95, 660],  17 => [130, 660], 16 => [165, 660], 15 => [200, 660],
            14 => [235, 660], 13 => [270, 660], 12 => [305, 660], 11 => [340, 660],
            21 => [390, 660], 22 => [425, 660], 23 => [460, 660], 24 => [495, 660],
            25 => [530, 660], 26 => [565, 660], 27 => [600, 660], 28 => [635, 660],
            // Inferior
            48 => [95, 760],  47 => [130, 760], 46 => [165, 760], 45 => [200, 760],
            44 => [235, 760], 43 => [270, 760], 42 => [305, 760], 41 => [340, 760],
            31 => [390, 760], 32 => [425, 760], 33 => [460, 760], 34 => [495, 760],
            35 => [530, 760], 36 => [565, 760], 37 => [600, 760], 38 => [635, 760],
        ];

        $coordsTemp = [
            // Superior temporal
            55 => [200, 820], 54 => [240, 820], 53 => [280, 820], 52 => [320, 820], 51 => [360, 820],
            61 => [400, 820], 62 => [440, 820], 63 => [480, 820], 64 => [520, 820], 65 => [560, 820],
            // Inferior temporal
            85 => [200, 880], 84 => [240, 880], 83 => [280, 880], 82 => [320, 880], 81 => [360, 880],
            71 => [400, 880], 72 => [440, 880], 73 => [480, 880], 74 => [520, 880], 75 => [560, 880],
        ];

        $todas = array_merge($coordsPerm, $coordsTemp);

        // Obtener piezas trabajadas
        $piezasConInfo = collect();
        foreach($tratamientos as $t) {
            foreach($t->piezas as $p) {
                $piezasConInfo->push($p);
            }
        }

        $pdf->SetFont('Helvetica', 'B', 6);

        foreach($piezasConInfo->groupBy('pieza_numero') as $num => $piezas) {
            if (!isset($todas[$num])) continue;

            [$px, $py] = $todas[$num];
            $x = $this->x($px);
            $y = $this->y($py);
            $ausente = $piezas->contains('ausente', true);

            if ($ausente) {
                // Marcar con X roja para ausente
                $pdf->SetTextColor(255, 0, 0);
                $pdf->SetXY($x - 2, $y - 2);
                $pdf->Write(4, 'X');
                $pdf->SetTextColor(0, 0, 0);
            } else {
                // Marcar con punto azul para tratado
                $pdf->SetTextColor(0, 0, 255);
                $pdf->SetXY($x - 1, $y - 2);
                $pdf->Write(4, '*');
                $pdf->SetTextColor(0, 0, 0);
            }
        }
    }

    private function escribirSesiones(Fpdi $pdf, $tratamientos): void
    {
        // Posición inicial de la tabla de tratamiento (px sobre imagen p2)
        $yBase = 400; // ajustar según medición real
        $alturaFila = 55; // altura de cada sesión en px

        $pdf->SetFont('Helvetica', '', 6.5);

        foreach($tratamientos as $i => $t) {
            $yPx   = $yBase + ($i * $alturaFila);
            $yMm   = $this->y($yPx);

            if ($yMm > 270) break; // no salir de la página

            // No. sesión y fecha
            $pdf->SetFont('Helvetica', 'B', 6.5);
            $this->escribir($pdf, $this->x(25),  $yMm, 'No. ' . ($i + 1));
            $pdf->SetFont('Helvetica', '', 6.5);
            $this->escribir($pdf, $this->x(25),  $yMm + 5, Carbon::parse($t->fecha_tratamiento)->format('d/m/Y'));

            // Diagnóstico y complicaciones
            $diag = $t->descripcion ?? '—';
            if ($t->piezas->count() > 0) {
                $diag .= "\nPiezas: " . $t->piezas->pluck('pieza_numero')->unique()->join(', ');
            }
            $pdf->SetXY($this->x(185), $yMm);
            $pdf->MultiCell($this->x(330), 3.5, $this->limpiar($diag), 0, 'L');

            // Procedimientos
            $proc = $t->nombre;
            if ($t->piezas->pluck('procedimiento')->filter()->count() > 0) {
                $proc .= "\n" . $t->piezas->pluck('procedimiento')->filter()->unique()->join(', ');
            }
            $pdf->SetXY($this->x(520), $yMm);
            $pdf->MultiCell($this->x(330), 3.5, $this->limpiar($proc), 0, 'L');

            // Prescripciones
            $pdf->SetXY($this->x(855), $yMm);
            $pdf->MultiCell($this->x(230), 3.5, $this->limpiar($t->observaciones ?? '—'), 0, 'L');
        }
    }
}