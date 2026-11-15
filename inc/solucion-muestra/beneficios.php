<?php

/**
 * TEMPORAL (F4): layout `beneficios` de cada solución, con los textos de Figma (specs/solucion.md).
 * Se borra en la F5.
 */

defined('ABSPATH') || exit;

return [
    'angelita-pro' => [
        'fondo'  => 'crema',
        'titulo' => 'Cómo funciona',
        'bajada' => 'Angelita no trabaja sola. Es un sistema completo de tres partes.',
        'items'  => [
            ['icono' => 'libro', 'texto' => 'El plan: videos temáticos y Tu Plan de Sueño, disponibles desde tu compra.'],
            ['icono' => 'diamante', 'texto' => 'La activación: cuando estés lista, le dices a Angelita “esta noche empiezo”.'],
            ['icono' => 'video', 'texto' => 'Angelita: te acompaña 24/7 por WhatsApp durante 2 semanas.'],
        ],
    ],
    'a-dormir-por-fin' => [
        'fondo'  => 'crema',
        'titulo' => 'Además, incluye',
        'bajada' => 'Todo por $399 USD · Cupos limitados — trabajo con un número reducido de familias a la vez.',
        'items'  => [
            ['icono' => 'libro', 'texto' => 'E-book con guías de horarios — 4 meses a 3 años. Para navegar cada cambio con confianza.'],
            ['icono' => 'diamante', 'texto' => '1 mes en Mundo Angelitozzz — 4 sesiones en vivo con Bárbara y videoteca completa.'],
        ],
    ],
    'horarios-ideales' => [
        'fondo'  => 'rosa',
        'titulo' => 'Lo que incluye además del curso.',
        'bajada' => '',
        'items'  => [
            ['icono' => 'libro', 'texto' => 'Guías de horarios descargables — una guía por cada etapa, para tener siempre a mano el horario ideal.'],
            ['icono' => 'diamante', 'texto' => '1 mes en Mundo Angelitozzz — 4 sesiones grupales en vivo con Bárbara, todos los miércoles a las 10am hora Perú/Colombia, y videoteca completa.'],
            ['icono' => 'video', 'texto' => 'Grabaciones de talleres de transiciones de siestas — según la etapa en la que estás.'],
        ],
    ],
];
