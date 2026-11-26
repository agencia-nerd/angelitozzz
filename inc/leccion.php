<?php
defined('ABSPATH') || exit;

/*
 * Lección (specs/leccion.md → temario y pestanas): qué ícono lleva cada material. Los materiales salen
 * del plugin (angelitozzz_cursos_materiales() y angelitozzz_cursos_temario()) con su clase (`tipo`) y su
 * extensión; Word, Excel y PowerPoint comparten la clase `office`, así que se distinguen por la extensión.
 */

/**
 * Ícono de un material: documento, hoja, presentacion, imagen o audio.
 *
 * - Tarjeta de Materiales: assets/img/leccion/archivo.svg (documento) o archivo-<ícono>.svg.
 * - Temario: assets/img/leccion/pdf.svg (documento) o tipo-<ícono>.svg.
 *
 * @param array<string, mixed> $material Un material del plugin.
 */
function angelitozzz_icono_material(array $material): string
{
    $extension = strtolower((string) ($material['extension'] ?? ''));
    if ($extension === 'xlsx') {
        return 'hoja';
    }
    if ($extension === 'pptx') {
        return 'presentacion';
    }

    // En el constructor la clase va en `clase` (ahí `tipo` dice si el ítem es una lección o un material).
    $clase = (string) ($material['clase'] ?? $material['tipo'] ?? '');
    if ($clase === 'imagen' || $clase === 'audio') {
        return $clase;
    }
    return 'documento';
}
