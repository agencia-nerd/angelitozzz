<?php

/**
 * Landing de una solución (specs/solucion.md): datos que comparten sus plantillas.
 * Solo presentación: no consulta ni registra nada.
 */

defined('ABSPATH') || exit;

/**
 * Layouts del contenido flexible `solucion_secciones_bloques`, cada uno con su vista en
 * template-parts/pages/solucion/<layout>.php. Un layout que no está aquí no se imprime.
 */
function angelitozzz_solucion_layouts(): array
{
    return ['comparacion', 'iconos', 'lista', 'beneficios', 'experiencia', 'pasos', 'galeria', 'etapas', 'testimonios', 'preguntas'];
}

/**
 * Clase de fondo de un bloque según su campo `fondo`; un valor desconocido cae en crema.
 */
function angelitozzz_solucion_fondo(string $fondo): string
{
    $fondos = [
        'crema'  => 'bg-fondo',
        'rosa'   => 'bg-fondo-rosa',
        'beige'  => 'bg-neutro',
        'salvia' => 'bg-etiqueta',
        'blanco' => 'bg-blanco',
    ];

    return $fondos[$fondo] ?? $fondos['crema'];
}

/**
 * Modal que abre el botón de compra de la landing: con etapas, el selector de edad;
 * si no, el drawer del carrito (en la F5, después de agregar el curso al carrito).
 */
function angelitozzz_solucion_modal_compra(string $tipo): string
{
    return $tipo === 'etapas' ? 'selecciona-edad' : 'carrito';
}
