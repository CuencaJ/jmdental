<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Valida una cédula de identidad ecuatoriana según el algoritmo oficial
 * del Registro Civil (módulo 10 con dígito verificador).
 *
 * Reglas:
 *  - Exactamente 10 dígitos numéricos.
 *  - Los 2 primeros dígitos son el código de provincia: 01-24, o 30
 *    (ciudadanos registrados en el exterior).
 *  - El 3er dígito debe ser menor a 6 (cédulas de persona natural).
 *  - El 10mo dígito es el verificador, calculado con los coeficientes
 *    2,1,2,1,2,1,2,1,2 sobre los 9 primeros dígitos.
 */
class CedulaEcuatoriana implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cedula = trim((string) $value);

        if (!preg_match('/^\d{10}$/', $cedula)) {
            $fail('La cédula debe contener exactamente 10 dígitos numéricos.');
            return;
        }

        $provincia = (int) substr($cedula, 0, 2);
        if (($provincia < 1 || $provincia > 24) && $provincia !== 30) {
            $fail('Los dos primeros dígitos de la cédula no corresponden a una provincia válida.');
            return;
        }

        if ((int) $cedula[2] >= 6) {
            $fail('El tercer dígito de la cédula no es válido para una persona natural.');
            return;
        }

        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $producto = ((int) $cedula[$i]) * $coeficientes[$i];
            if ($producto > 9) {
                $producto -= 9;
            }
            $suma += $producto;
        }

        $verificador = (10 - ($suma % 10)) % 10;

        if ($verificador !== (int) $cedula[9]) {
            $fail('La cédula ingresada no es válida. Verifica que esté bien escrita.');
        }
    }
}