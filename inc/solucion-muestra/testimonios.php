<?php

/**
 * TEMPORAL (F4): layout `testimonios` de muestra (componente Figma "Testimonio · Destacado").
 * Cada landing trae un solo testimonio propio en el diseño (y dibuja 4 puntos): para ver el slider
 * en la maquetación, después del propio van los de las otras landings como relleno. En la F5 la
 * clienta carga los testimonios propios de cada solución. Se borra en la F5.
 */

defined('ABSPATH') || exit;

$propios = [
    'angelita-pro'      => ['texto' => 'Mi bebé duerme 11 horas de corrido. No lo puedo creer.', 'autora' => 'María L., Colombia'],
    'a-dormir-por-fin'  => ['texto' => 'Hay un antes y un después de la asesoría. La consistencia y la seguridad que nos dabas permitió que Lara aprenda a dormir sola.', 'autora' => 'Bernardita, mamá de Lara (7 meses)'],
    'horarios-ideales'  => ['texto' => 'Por fin entendí las ventanas de vigilia de mi bebé. En una semana sus siestas pasaron de 30 minutos a más de una hora.', 'autora' => 'Andrea, mamá de Lucas (7 meses)'],
    'recien-nacidos-go' => ['texto' => 'Mi bebé tiene 12 días y ya la puedo poner en el moisés. Antes solo dormía en brazos.', 'autora' => 'Valeria, mamá de Joaquín (12 días)'],
];

$titulos = [
    'angelita-pro'      => '¡Historias reales de mamás felices!',
    'a-dormir-por-fin'  => 'Lo que dicen las mamás que trabajaron con Bárbara.',
    'horarios-ideales'  => 'Lo que dicen las mamás que ya lo tomaron.',
    'recien-nacidos-go' => 'Lo que dicen las mamás que ya lo tomaron.',
];

$filas = [];
foreach ($propios as $slug => $propio) {
    $relleno = array_values(array_diff_key($propios, [$slug => true]));
    $filas[$slug] = [
        'fondo'  => 'beige',
        'titulo' => $titulos[$slug],
        'items'  => array_merge([$propio], $relleno),
    ];
}

return $filas;
