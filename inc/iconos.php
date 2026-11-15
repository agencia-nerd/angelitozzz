<?php

defined('ABSPATH') || exit;

/**
 * SVG del tema en línea (assets/img/<ruta>.svg), decorativo: con su clase, aria-hidden y
 * focusable="false". Lo usan los íconos que cambian de color con su estado (currentColor), como los
 * del menú y las pestañas de la cuenta. Solo lee archivos del propio tema; si no existe, no imprime nada.
 *
 * @param string $ruta  Ruta dentro de assets/img/, sin la extensión (p. ej. 'cuenta/menu-perfil').
 * @param string $clase Clases del <svg>.
 */
function angelitozzz_svg(string $ruta, string $clase = ''): string
{
    static $cache = [];

    $ruta = trim(preg_replace('#[^a-z0-9/_-]#', '', strtolower($ruta)) ?? '', '/');
    if ($ruta === '' || str_contains($ruta, '..')) {
        return '';
    }

    if (!array_key_exists($ruta, $cache)) {
        $archivo        = get_theme_file_path('assets/img/' . $ruta . '.svg');
        $cache[$ruta] = is_readable($archivo) ? (string) file_get_contents($archivo) : '';
    }
    $svg = $cache[$ruta];
    if ($svg === '' || !str_starts_with(ltrim($svg), '<svg')) {
        return '';
    }

    $atributos = ' aria-hidden="true" focusable="false"' . ($clase !== '' ? ' class="' . esc_attr($clase) . '"' : '');

    return (string) preg_replace('/<svg\b/', '<svg' . $atributos, $svg, 1);
}
