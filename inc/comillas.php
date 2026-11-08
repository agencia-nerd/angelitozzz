<?php
defined('ABSPATH') || exit;

/*
 * Comillas del diseño en todo el sitio. WordPress convierte las comillas rectas ("…") en tipográficas según el
 * idioma, y en español usa las angulares (« »). El diseño usa las curvas (“ ”): este filtro devuelve las curvas a
 * esas dos traducciones. El resto de lo que convierte WordPress (guiones, apóstrofos) no cambia.
 */

function angelitozzz_comillas_curvas(string $traduccion, string $texto, string $contexto, string $dominio): string
{
    if ($dominio !== 'default') {
        return $traduccion;
    }

    if ($contexto === 'opening curly double quote') {
        return '&#8220;';
    }
    if ($contexto === 'closing curly double quote') {
        return '&#8221;';
    }

    return $traduccion;
}
add_filter('gettext_with_context', 'angelitozzz_comillas_curvas', 10, 4);
