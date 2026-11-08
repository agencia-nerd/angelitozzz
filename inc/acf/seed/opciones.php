<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial de Ajustes del sitio (specs/opciones.md, "valor actual" de cada campo).
 * Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en el admin no se pisa.
 */
return [
    'option' => true,
    'fields' => [
        'opciones_cintillo_promociones' => [
            'texto' => '¿Tu bebé no duerme bien? Nuestro coach de sueño virtual te ayuda a resolver tus dudas, gratis 💛',
        ],
        'opciones_modal_coach' => [
            'titulo'         => '¡Hola! 💛 Vamos a conectarte con el coach de sueño virtual de Angelitozzz, para que te ayude a resolver cualquier duda sobre el sueño de tu bebé',
            'texto'          => 'Déjame estos datos y en un momento podrás escribirle, gratis.',
            'ayuda_whatsapp' => 'Lo necesitamos porque tu coach de sueño virtual te va a escribir y ayudarte por aquí 💛',
            'boton'          => 'Quiero que me ayuden con el sueño de mi bebé',
        ],
        'opciones_modal_coach_gracias' => [
            'titulo'  => '¡Listo, {nombre}! 💛',
            'texto'   => 'Ya puedes escribirle a tu coach de sueño virtual y empezar a resolver las dudas de sueño de tu bebé, gratis.',
            'mensaje' => 'Hola, soy {nombre} 💛 Quiero ayuda con el sueño de mi bebé.',
        ],
        // Las 3 redes del diseño, sin URL todavía: una red sin enlace no se muestra.
        'opciones_contacto' => [
            'destinatarios_contacto'   => [['email' => 'info@angelitozzz.com']],
            'destinatarios_coach'      => [['email' => 'info@angelitozzz.com']],
            'destinatarios_newsletter' => [['email' => 'info@angelitozzz.com']],
        ],
        'opciones_redes' => [
            'items'  => [
                ['red' => 'instagram', 'enlace' => ''],
                ['red' => 'tiktok', 'enlace' => ''],
                ['red' => 'facebook', 'enlace' => ''],
            ],
        ],
        'opciones_footer' => [
            'newsletter_texto'  => 'Te enviaremos consejos gratuitos para que tu Angelito aprenda a dormir',
        ],
    ],
];
