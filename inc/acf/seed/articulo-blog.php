<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial de la página de opciones "Artículo del blog" (specs/articulo-blog.md, "valor actual").
 * El CTA final, igual en todos los artículos. Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en
 * el admin no se pisa. Los destinos "#" están pendientes (la tienda de WooCommerce todavía no existe).
 */
return [
    'option' => true,
    'fields' => [
        'articulo_blog_cta_final' => [
            'titulo'      => '¿Quieres un plan para tu bebé?',
            'descripcion' => 'Encuentra la solución de sueño ideal según su edad.',
            'boton'       => ['title' => 'Encuentra tu solución', 'url' => '#'],
        ],
    ],
];
