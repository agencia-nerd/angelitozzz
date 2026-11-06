<?php

/**
 * Inicio · testimonios (specs/home.md, grupo home_testimonios).
 * Repetidor propio de Inicio, independiente del tipo `testimonio` y de otras páginas.
 */

defined('ABSPATH') || exit;

$testimonios = get_field('home_testimonios') ?: [];

$items = [];
foreach ((array) ($testimonios['items'] ?? []) as $fila) {
    $items[] = [
        'texto'     => (string) ($fila['texto'] ?? ''),
        'autora'    => (string) ($fila['autora'] ?? ''),
        'detalle'   => (string) ($fila['detalle'] ?? ''),
        'estrellas' => (int) ($fila['estrellas'] ?? 0),
    ];
}

get_template_part('template-parts/components/testimonios-slider', null, [
    'titulo'      => (string) ($testimonios['titulo'] ?? ''),
    'testimonios' => $items,
    'boton'       => $testimonios['boton'] ?? null,
]);
