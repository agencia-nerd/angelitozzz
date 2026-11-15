<?php

/**
 * TEMPORAL (F4): layout `pasos` de solucion_secciones_bloques (solo A Dormir Por Fin). Se borra en la F5.
 */

defined('ABSPATH') || exit;

$img      = static fn(string $archivo): string => get_theme_file_uri('assets/img/solucion/a-dormir-por-fin/' . $archivo);
$contacto = function_exists('get_field') ? (get_field('opciones_contacto', 'option') ?: []) : [];
$numero   = preg_replace('/\D+/', '', (string) ($contacto['whatsapp'] ?? ''));
$mensaje  = trim((string) ($contacto['whatsapp_mensaje'] ?? ''));

return [
    'a-dormir-por-fin' => [
        'fondo'      => 'salvia',
        'antetitulo' => 'Cómo funciona la asesoría',
        'titulo'     => 'Un proceso de tres pasos — simple, claro y completamente guiado.',
        'bajada'     => 'Cada semana te guiaremos con estrategias claras y prácticas adaptadas al crecimiento y desarrollo de tu bebé.',
        'items'      => [
            [
                'imagen'   => $img('pasos-1.webp'),
                'titulo'   => 'Videollamada inicial',
                'texto'    => 'Una sesión de 1 hora para conocernos y crear el plan de sueño ideal para ti y tu bebé, de acuerdo a tu estilo.',
                'etiqueta' => '1 hora · por videollamada',
            ],
            [
                'imagen'   => $img('pasos-2.webp'),
                'titulo'   => 'Tu plan de sueño',
                'texto'    => 'Recibes tu plan integral personalizado por escrito: métodos, horarios, rutinas y todo lo que necesitas para empezar con claridad.',
                'etiqueta' => 'Plan escrito y personalizado',
            ],
            [
                'imagen'   => $img('pasos-3.webp'),
                'titulo'   => '3 semanas de WhatsApp conmigo',
                'texto'    => 'Me escribes cuando lo necesites: cada duda y cada noche difícil la respondo yo, personalmente.',
                'etiqueta' => 'Acompañamiento directo',
            ],
        ],
        'boton'      => $numero === '' ? null : [
            'url'    => 'https://wa.me/' . $numero . ($mensaje !== '' ? '?text=' . rawurlencode($mensaje) : ''),
            'title'  => 'Escríbeme por WhatsApp para empezar',
            'target' => '_blank',
        ],
    ],
];
