<?php
defined('ABSPATH') || exit;

/*
 * Buscador general de WordPress (/?s=palabra): el tema no lo usa (el blog busca con `q` en su página,
 * specs/blog.md), así que se redirige con 301 a la página Blog con ?q=, un solo buscador con su diseño.
 *
 * Se mira $_GET['s'] y no is_search(): el motor de wp-query pone `s` en la consulta de /blog/?q=
 * (inc/query/motor.php) y con is_search() el blog se redirigiría a sí mismo.
 */
function angelitozzz_busqueda_al_blog(): void
{
    if (!isset($_GET['s']) || is_admin() || is_feed() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }

    $blog_id = (int) get_option('page_for_posts');
    $blog    = $blog_id ? get_permalink($blog_id) : '';
    if (!$blog) {
        return;
    }

    $texto   = sanitize_text_field(wp_unslash((string) $_GET['s']));
    $destino = $texto !== '' ? add_query_arg('q', rawurlencode($texto), $blog) : $blog;

    wp_safe_redirect($destino, 301);
    exit;
}
add_action('template_redirect', 'angelitozzz_busqueda_al_blog');
