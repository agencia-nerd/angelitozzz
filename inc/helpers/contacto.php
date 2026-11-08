<?php
defined('ABSPATH') || exit;

/*
 * Destinatarios de los formularios (Ajustes del sitio > Contacto, campos `opciones_contacto_destinatarios_*`).
 * Cada formulario tiene su propia lista de correos (uno o más): ningún correo va escrito en el código.
 * Si la lista está vacía, usa el correo del administrador de WordPress.
 *
 * $form: 'contacto' | 'coach' | 'newsletter'
 */
function angelitozzz_form_recipients(string $form): array
{
    $contacto = function_exists('get_field') ? get_field('opciones_contacto', 'option') : [];
    $filas    = is_array($contacto) ? (array) ($contacto['destinatarios_' . $form] ?? []) : [];

    $correos = [];
    foreach ($filas as $fila) {
        $email = sanitize_email((string) (is_array($fila) ? ($fila['email'] ?? '') : ''));
        if ($email !== '') {
            $correos[] = $email;
        }
    }
    $correos = array_values(array_unique($correos));

    return $correos ?: array_filter([sanitize_email((string) get_option('admin_email'))]);
}
