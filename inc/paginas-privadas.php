<?php

defined('ABSPATH') || exit;

/*
 * Páginas que no deben aparecer en buscadores (lo piden sus specs): acceso, cuenta y canje de regalo.
 * El carrito, el checkout y "Mi cuenta" de WooCommerce ya salen con noindex por WooCommerce.
 */

/**
 * Plantillas de página con noindex.
 */
function angelitozzz_plantillas_privadas(): array
{
    return [
        'pages/template-iniciar-sesion.php',
        'pages/template-crear-cuenta.php',
        'pages/template-recuperar-contrasena.php',
        'pages/template-canjear-regalo.php',
    ];
}

add_filter('wp_robots', 'angelitozzz_robots_paginas_privadas');

/**
 * Agrega noindex (y nofollow) a las páginas privadas.
 */
function angelitozzz_robots_paginas_privadas(array $robots): array
{
    foreach (angelitozzz_plantillas_privadas() as $plantilla) {
        if (is_page_template($plantilla)) {
            return wp_robots_no_robots($robots);
        }
    }

    return $robots;
}
