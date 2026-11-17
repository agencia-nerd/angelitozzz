<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial de la página Soluciones de sueño (specs/soluciones-de-sueno.md, "valor actual" de cada campo).
 * Es una página de opciones: el archivo de soluciones no tiene pantalla de edición.
 * Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en el admin no se pisa.
 */
return [
    'option' => true,
    'fields' => [
        'soluciones_de_sueno_shop' => [
            'titulo' => 'Soluciones de sueño',
            'bajada' => 'Encuentra la solución perfecta para ti — según la edad de tu bebé y lo que necesitas hoy.',
        ],
        'soluciones_de_sueno_cta_final' => [
            'titulo'      => '¿No sabes por dónde empezar?',
            'descripcion' => 'Cuéntame la edad de tu bebé y te ayudo a encontrar la opción correcta.',
            'boton_texto' => 'Escríbeme por WhatsApp',
        ],
    ],
];
