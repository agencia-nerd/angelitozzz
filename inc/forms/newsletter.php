<?php
defined('ABSPATH') || exit;

/*
 * Formulario "newsletter" del footer (specs/opciones.md, sección footer).
 * Un solo campo email; envía un correo y guarda cada suscripción en Envíos.
 */
add_filter('angelitozzz_forms', function ($forms) {
    $forms['newsletter'] = [
        'title'    => 'Newsletter',
        'subject'  => 'Nueva suscripción al blog desde la web',
        'to'       => angelitozzz_form_recipients('newsletter'),
        'reply_to' => 'email',
        'store'    => 'all',
        'redirect' => '',
        'success'  => '¡Gracias! Ya estás suscrita a nuestro blog.',

        'fields' => [
            'email' => [
                'type'         => 'email',
                'label'        => 'Correo electrónico',
                'required'     => true,
                'autocomplete' => 'email',
            ],
        ],
    ];

    return $forms;
});
