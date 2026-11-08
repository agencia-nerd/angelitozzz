<?php
defined('ABSPATH') || exit;

/*
 * Formulario "contacto" de la página Contacto (specs/contacto.md, sección seccion-de-contacto).
 * Envía un correo a los destinatarios de Ajustes del sitio y guarda cada mensaje en Envíos
 * (nombre, email, celular con su código de país, curso, mensaje y fecha).
 */
add_filter('angelitozzz_forms', function ($forms) {
    $forms['contacto'] = [
        'title'    => 'Contacto',
        'subject'  => 'Nuevo mensaje desde la página Contacto',
        'to'       => angelitozzz_form_recipients('contacto'),
        'reply_to' => 'email',
        'store'    => 'all',
        'redirect' => '',
        'success'  => '¡Gracias! Te responderemos pronto.',

        'fields' => [
            'nombre' => [
                'type'         => 'name',
                'label'        => 'Nombres y apellidos',
                'required'     => true,
                'autocomplete' => 'name',
            ],
            'email' => [
                'type'         => 'email',
                'label'        => 'Email',
                'required'     => true,
                'autocomplete' => 'email',
            ],
            // Código de país del celular: se guarda junto al número (por defecto Perú +51).
            'celular_pais' => [
                'type'     => 'select',
                'label'    => 'Código de país',
                'required' => true,
                'options'  => angelitozzz_phone_countries(),
            ],
            'celular' => [
                'type'         => 'phone',
                'label'        => 'Celular',
                'required'     => true,
                'min_digits'   => 6,
                'max_digits'   => 13,
                'autocomplete' => 'tel-national',
            ],
            // Cursos del diseño. Pendiente: cuando WooCommerce esté conectado, las opciones salen de los productos publicados.
            'curso' => [
                'type'     => 'select',
                'label'    => 'Curso',
                'required' => true,
                'options'  => [
                    'angelita-pro'      => 'Angelita Pro',
                    'a-dormir-por-fin'  => 'A Dormir Por Fin',
                    'recien-nacidos-go' => 'Recién Nacidos Go',
                    'horarios-ideales'  => 'Horarios Ideales',
                ],
                'messages' => ['required' => 'Selecciona un curso.'],
            ],
            'mensaje' => [
                'type'     => 'textarea',
                'label'    => 'Mensaje',
                'required' => true,
            ],
            'privacidad' => [
                'type'  => 'consent',
                'label' => 'Acepta las políticas de privacidad',
            ],
        ],
    ];

    return $forms;
});
