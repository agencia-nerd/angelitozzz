<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial de Contacto (specs/contacto.md, "valor actual" de cada campo).
 * Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en el admin no se pisa.
 * Los destinatarios del formulario no van aquí: son de Ajustes del sitio (opciones_contacto_destinatarios_contacto).
 */
return [
    'page'   => 'contacto',
    'fields' => [
        'contacto_seccion_de_contacto' => [
            'titulo'    => 'Contacto',
            'subtitulo' => '¡Introduce tu información aquí y pronto me pondré en contacto contigo!',
        ],
        'contacto_seccion_de_cta' => [
            'titulo' => 'Escríbenos',
            'texto'  => 'Da el primer paso hacia un mundo de tranquilidad junto a tu bebé.',
            'email'  => 'info@angelitozzz.com',
        ],
    ],
];
