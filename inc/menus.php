<?php
defined('ABSPATH') || exit;

/*
 * Imprime un menú de WordPress por su ubicación (specs/_shared.md, tabla "Menús").
 * Mientras la ubicación no tenga un menú asignado, imprime los enlaces del diseño ($enlaces),
 * para que el header y el footer se vean igual que en Figma hasta el paso de menús.
 *
 * $enlaces: [['texto' => 'Blog', 'url' => '/blog/'], ...] (url relativa al sitio, '#' = pendiente)
 * $args: clase (clases del <ul>), clase_enlace (clases de cada <a>), etiqueta (aria-label del <nav>, opcional)
 */
function angelitozzz_menu(string $ubicacion, array $enlaces, array $args = []): void
{
    $clase        = (string) ($args['clase'] ?? '');
    $clase_enlace = (string) ($args['clase_enlace'] ?? '');

    if (has_nav_menu($ubicacion)) {
        wp_nav_menu([
            'theme_location' => $ubicacion,
            'container'      => false,
            'menu_class'     => $clase,
            'depth'          => 1,
            'fallback_cb'    => false,
            'link_before'    => '',
            'link_after'     => '',
        ]);
        return;
    }

    echo '<ul class="' . esc_attr($clase) . '">';
    foreach ($enlaces as $enlace) {
        $texto = (string) ($enlace['texto'] ?? '');
        $url   = (string) ($enlace['url'] ?? '#');
        $link  = $url === '#' ? '#' : home_url($url);
        $attrs = angelitozzz_link_attrs($link, ['class' => $clase_enlace, 'current' => true]);

        echo '<li>';
        if ($attrs !== '') {
            echo '<a' . $attrs . '>' . esc_html($texto) . '</a>';
        } else {
            // Página que aún no existe: se muestra el texto sin enlace.
            echo '<span class="' . esc_attr($clase_enlace) . '">' . esc_html($texto) . '</span>';
        }
        echo '</li>';
    }
    echo '</ul>';
}
