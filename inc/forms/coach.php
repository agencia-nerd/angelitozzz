<?php
defined('ABSPATH') || exit;

/*
 * Formulario "coach" del modal del cintillo (specs/opciones.md, sección modal-coach).
 * Envía un correo y guarda cada inscripción en Envíos (nombre, WhatsApp con su código de país,
 * edad del bebé, email y fecha). Al enviar, el modal pasa a la pantalla de gracias.
 */
add_filter('angelitozzz_forms', function ($forms) {
    $forms['coach'] = [
        'title'    => 'Coach de sueño virtual',
        'subject'  => 'Nueva inscripción al coach de sueño virtual',
        'to'       => ['info@angelitozzz.com'],
        'reply_to' => 'email',
        'store'    => 'all',
        'redirect' => '',
        'success'  => '¡Listo! Ya puedes escribirle a tu coach de sueño virtual.',

        'fields' => [
            'nombre' => [
                'type'         => 'name',
                'label'        => 'Nombre',
                'required'     => true,
                'autocomplete' => 'given-name',
            ],
            // Código de país del WhatsApp: se guarda junto al número (por defecto Perú +51).
            'whatsapp_pais' => [
                'type'     => 'select',
                'label'    => 'Código de país',
                'required' => true,
                'options'  => [
                    'pe' => '🇵🇪 +51',
                    'ar' => '🇦🇷 +54',
                    'bo' => '🇧🇴 +591',
                    'cl' => '🇨🇱 +56',
                    'co' => '🇨🇴 +57',
                    'cr' => '🇨🇷 +506',
                    'ec' => '🇪🇨 +593',
                    'sv' => '🇸🇻 +503',
                    'es' => '🇪🇸 +34',
                    'us' => '🇺🇸 +1',
                    'gt' => '🇬🇹 +502',
                    'hn' => '🇭🇳 +504',
                    'mx' => '🇲🇽 +52',
                    'ni' => '🇳🇮 +505',
                    'pa' => '🇵🇦 +507',
                    'py' => '🇵🇾 +595',
                    'pr' => '🇵🇷 +1',
                    'do' => '🇩🇴 +1',
                    'uy' => '🇺🇾 +598',
                    've' => '🇻🇪 +58',
                ],
            ],
            'whatsapp' => [
                'type'         => 'phone',
                'label'        => 'WhatsApp',
                'required'     => true,
                'min_digits'   => 6,
                'max_digits'   => 13,
                'autocomplete' => 'tel-national',
            ],
            'edad_bebe' => [
                'type'     => 'select',
                'label'    => 'Edad del bebé',
                'required' => true,
                'options'  => [
                    '0-4-meses'   => '0 a 4 meses',
                    '4-12-meses'  => '4 a 12 meses',
                    '12-24-meses' => '12 a 24 meses',
                    '2-4-anos'    => '2 a 4 años',
                ],
            ],
            'email' => [
                'type'         => 'email',
                'label'        => 'Email',
                'required'     => false,
                'autocomplete' => 'email',
            ],
            'consent' => [
                'type'  => 'consent',
                'label' => 'Acepta que Angelitozzz use sus datos para contactarla',
            ],
        ],
    ];

    return $forms;
});
