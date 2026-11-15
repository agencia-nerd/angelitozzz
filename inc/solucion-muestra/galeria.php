<?php

/**
 * TEMPORAL (F4): layout `galeria` de muestra (specs/solucion.md → secciones). Se borra en la F5.
 * Angelita Pro: "Conoce Angelita Pro por dentro" (4013:14037). Recién Nacidos Go: "Así se ve el curso por dentro" (4013:18035).
 */

defined('ABSPATH') || exit;

$img = static fn(string $archivo): string => get_theme_file_uri('assets/img/solucion/' . $archivo);

return [
    'angelita-pro' => [
        'fondo'        => 'rosa',
        'titulo'       => 'Conoce Angelita Pro por dentro',
        'bajada'       => 'En este video Bárbara te explica cómo usar Angelita y cómo es tu plan de sueño.',
        'diapositivas' => [
            ['imagen' => $img('angelita-pro/galeria-video-madre-bebe.webp'), 'video' => ''],
        ],
        'texto'        => 'Tienes 4 meses de acceso a los videos y activas a Angelita cuando tú decidas.',
        'boton'        => 'Quiero a Angelita',
        'boton_precio' => false,
    ],
    'recien-nacidos-go' => [
        'fondo'        => 'rosa',
        'titulo'       => 'Así se ve el curso por dentro.',
        'bajada'       => 'Cada video es corto, claro y lleno de información práctica — para que puedas verlo cuando tu bebé te lo permita y aplicarlo ese mismo día.',
        'diapositivas' => [
            ['imagen' => $img('recien-nacidos-go/galeria-foto-curso.webp'), 'video' => ''],
        ],
        'texto'        => 'Desde tu compra tienes 4 meses para acceder y ver el curso las veces que quieras.',
        'boton'        => 'Comprar ahora',
        'boton_precio' => true,
    ],
];
