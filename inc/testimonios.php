<?php
defined('ABSPATH') || exit;

/*
 * Ajustes del tipo de contenido `testimonio` (specs/testimonio.md y specs/testimonios.md).
 * El tipo lo registra ACF desde acf-json/, con el archivo en /testimonios/ (URL del spec testimonios.md).
 * Aquí: cada testimonio no tiene página propia, así que su URL individual da 404 (es solo un mostrario).
 */

function angelitozzz_testimonio_sin_ficha(): void
{
    if (is_singular('testimonio')) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
    }
}
add_action('template_redirect', 'angelitozzz_testimonio_sin_ficha');

// La URL del archivo necesita refrescar las reglas de reescritura una sola vez (también al publicar el tema).
function angelitozzz_testimonio_refrescar_reglas(): void
{
    $version = '2';
    if (get_option('angelitozzz_testimonio_reglas') !== $version && post_type_exists('testimonio')) {
        flush_rewrite_rules(false);
        update_option('angelitozzz_testimonio_reglas', $version, false);
    }
}
add_action('init', 'angelitozzz_testimonio_refrescar_reglas', 99);
