<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial del Blog (specs/blog.md, "valor actual" de cada campo).
 * Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en el admin no se pisa.
 * Las 4 categorías del diseño se crean si no existen y quedan como secciones, en este orden.
 */
return [
    'page'   => 'blog',
    'fields' => [
        'blog_hero' => [
            'antetitulo' => 'Recursos gratuitos',
            'bajada'     => 'Consejos de Bárbara para que tu bebé duerma mejor, en cada etapa.',
        ],
        'blog_categorias' => [
            'secciones' => [
                ['categoria' => ['_term' => 'especiales', 'taxonomy' => 'category', 'nombre' => 'Especiales']],
                ['categoria' => ['_term' => 'bebes-0-4-meses', 'taxonomy' => 'category', 'nombre' => 'Bebés 0 – 4 meses']],
                ['categoria' => ['_term' => 'bebes-4-24-meses', 'taxonomy' => 'category', 'nombre' => 'Bebés 4 – 24 meses']],
                ['categoria' => ['_term' => 'bebes-24-meses-4-anos', 'taxonomy' => 'category', 'nombre' => 'Bebés 24 meses – 4 años']],
            ],
        ],
    ],
];
