<?php
defined('ABSPATH') || exit;

/*
 * Códigos de país para los campos de teléfono de los formularios (bandera y código).
 * Lo comparten los formularios con selector de país (coach, contacto). La primera clave, 'pe', es el valor por defecto.
 */
function angelitozzz_phone_countries(): array
{
    return [
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
    ];
}
