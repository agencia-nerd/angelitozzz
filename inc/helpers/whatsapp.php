<?php
defined('ABSPATH') || exit;

/**
 * Enlace de WhatsApp del sitio con el número y el mensaje de Ajustes del sitio (opciones_contacto),
 * listo para el helper de enlaces o el componente boton. Sin número o sin texto: null (no se muestra).
 * Es el único lugar donde se arma el enlace de WhatsApp en PHP.
 */
function angelitozzz_whatsapp(string $texto): ?array
{
    $contacto = get_field('opciones_contacto', 'option') ?: [];
    $numero   = preg_replace('/\D+/', '', (string) ($contacto['whatsapp'] ?? ''));
    $mensaje  = trim((string) ($contacto['whatsapp_mensaje'] ?? ''));
    $texto    = trim($texto);

    if ($numero === '' || $texto === '') {
        return null;
    }

    return [
        'url'    => 'https://wa.me/' . $numero . ($mensaje !== '' ? '?text=' . rawurlencode($mensaje) : ''),
        'title'  => $texto,
        'target' => '_blank',
    ];
}
