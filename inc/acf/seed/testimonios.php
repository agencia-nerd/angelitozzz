<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial de la página Testimonios (specs/testimonios.md, "valor actual" de cada campo).
 * Es una página de opciones: el archivo de testimonios no tiene pantalla de edición.
 * Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en el admin no se pisa.
 * El botón del cierre queda con '#' hasta que exista la tienda de cursos.
 */
return [
    'option' => true,
    'fields' => [
        'testimonios_seccion_testimonios' => [
            'antetitulo' => 'Historias reales',
            'titulo'     => 'Testimonios',
            'bajada'     => 'Mamás que volvieron a dormir y a disfrutar su maternidad.',
        ],
        'testimonios_cta_final' => [
            'titulo'      => "Tu historia puede ser la próxima.",
            'descripcion' => 'Encuentra la solución de sueño ideal para tu bebé.',
            'boton'       => ['title' => 'Encuentra tu solución', 'url' => '#'],
        ],
    ],
];
