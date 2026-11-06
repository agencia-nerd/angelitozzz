<?php

/**
 * Inicio · cierre (specs/home.md, grupo home_cta_final). Sin descripción en Inicio.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/components/cta-final', null, [
    'titulo'      => 'La maternidad ya tiene suficientes sacrificios. El sueño no tiene que ser uno de ellos.',
    'descripcion' => '',
    // Lleva a la página shop/tienda/cursos, que aún no tiene nombre ni existe (pendiente).
    'boton'       => ['url' => '#', 'title' => 'Encuentra tu solución', 'target' => ''],
]);
