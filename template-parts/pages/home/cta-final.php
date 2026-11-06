<?php

/**
 * Inicio · cierre (specs/home.md, grupo home_cta_final). Sin descripción en Inicio.
 */

defined('ABSPATH') || exit;

$cta_final = get_field('home_cta_final') ?: [];

get_template_part('template-parts/components/cta-final', null, [
    'titulo'      => (string) ($cta_final['titulo'] ?? ''),
    'descripcion' => '',
    'boton'       => $cta_final['boton'] ?? null,
]);
