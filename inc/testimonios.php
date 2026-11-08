<?php
defined('ABSPATH') || exit;

/*
 * Ajustes del tipo de contenido `testimonio` (specs/testimonio.md y specs/testimonios.md).
 * El tipo lo registra ACF desde acf-json/ (público y con archivo en /testimonio/); aquí se deja como pide el spec:
 * - el archivo vive en /testimonios/ (la página Testimonios);
 * - cada testimonio no tiene página propia: su URL individual da 404 (es solo un mostrario).
 */

function angelitozzz_testimonio_args(array $args, string $post_type): array
{
    if ($post_type !== 'testimonio') {
        return $args;
    }

    $args['has_archive'] = 'testimonios';
    $args['rewrite']     = ['slug' => 'testimonios', 'with_front' => false];

    return $args;
}
add_filter('register_post_type_args', 'angelitozzz_testimonio_args', 10, 2);

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

// El cambio de URL del archivo necesita refrescar las reglas de reescritura una sola vez.
function angelitozzz_testimonio_refrescar_reglas(): void
{
    $version = '1';
    if (get_option('angelitozzz_testimonio_reglas') !== $version && post_type_exists('testimonio')) {
        flush_rewrite_rules(false);
        update_option('angelitozzz_testimonio_reglas', $version, false);
    }
}
add_action('init', 'angelitozzz_testimonio_refrescar_reglas', 99);
