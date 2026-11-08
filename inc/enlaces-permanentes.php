<?php
defined('ABSPATH') || exit;

/**
 * Enlaces permanentes del blog:
 * - entradas:    /blog/<entrada>/
 * - categorías:  /blog/categoria/<slug>/
 *   (con una base propia WordPress no le antepone el /blog/ de las entradas: va en la base)
 *
 * Se fija una sola vez y solo si los ajustes siguen en el valor por defecto
 * (/%postname%/ y base de categoría vacía o "categoria"): no pisa lo que se elija desde
 * Ajustes → Enlaces permanentes.
 */
const ANGELITOZZZ_ENLACES_VERSION = '6';

function angelitozzz_enlaces_permanentes()
{
    global $wp_rewrite;

    if (get_option('angelitozzz_enlaces_version') === ANGELITOZZZ_ENLACES_VERSION) {
        return;
    }

    $cambio = false;

    if (in_array((string) get_option('permalink_structure'), ['', '/%postname%/'], true)) {
        // set_*() guardan la opción y reinician $wp_rewrite con el valor nuevo.
        $wp_rewrite->set_permalink_structure('/blog/%postname%/');
        $cambio = true;
    }

    // El admin guarda la base con barra inicial ("/categoria"): se compara sin ella.
    if (in_array(trim((string) get_option('category_base'), '/'), ['', 'categoria', 'blog/categoria'], true)) {
        $wp_rewrite->set_category_base('blog/categoria');
        $cambio = true;
    }

    if ($cambio) {
        // La taxonomía category ya se registró con la base anterior: se vuelve a registrar
        // para que las reglas nuevas usen la base actual.
        create_initial_taxonomies();
        flush_rewrite_rules(false);
    }

    update_option('angelitozzz_enlaces_version', ANGELITOZZZ_ENLACES_VERSION);
    delete_option('angelitozzz_categoria_base_ok');
}
add_action('init', 'angelitozzz_enlaces_permanentes', 20);

/**
 * El /blog/ del frente es solo para el blog: la categoría de testimonios no lo lleva.
 */
function angelitozzz_enlaces_taxonomias(array $args, string $taxonomia): array
{
    if ($taxonomia === 'categoria_testimonio' && is_array($args['rewrite'] ?? null)) {
        $args['rewrite']['with_front'] = false;
    }

    return $args;
}
add_filter('register_taxonomy_args', 'angelitozzz_enlaces_taxonomias', 10, 2);

/**
 * Los enlaces viejos redirigen (301) a la URL actual:
 * - categorías: /category/<slug>/ y /categoria/<slug>/ (con su /page/N/);
 * - entradas:   /<entrada>/ → /blog/<entrada>/.
 */
function angelitozzz_enlaces_redireccion()
{
    if (!is_404()) {
        return;
    }

    $ruta = trim((string) wp_parse_url(add_query_arg([]), PHP_URL_PATH), '/');
    $raiz = trim((string) wp_parse_url(home_url('/'), PHP_URL_PATH), '/');
    if ($raiz !== '' && str_starts_with($ruta, $raiz . '/')) {
        $ruta = substr($ruta, strlen($raiz) + 1);
    }

    if (preg_match('#^[a-z0-9-]+$#', $ruta)) {
        $entrada = get_page_by_path($ruta, OBJECT, 'post');
        if ($entrada && $entrada->post_status === 'publish') {
            wp_safe_redirect(get_permalink($entrada), 301);
            exit;
        }
        return;
    }

    if (!preg_match('#^(?:category|categoria)/([^/]+)(?:/page/(\d+))?/?$#', $ruta, $partes)) {
        return;
    }

    $termino = get_term_by('slug', sanitize_title($partes[1]), 'category');
    if (!$termino) {
        return;
    }

    $destino = get_category_link($termino);
    if (!empty($partes[2]) && (int) $partes[2] > 1) {
        $destino = trailingslashit($destino) . 'page/' . (int) $partes[2] . '/';
    }

    $query = (string) wp_parse_url(add_query_arg([]), PHP_URL_QUERY);
    if ($query !== '') {
        $destino .= '?' . $query;
    }

    if (untrailingslashit($destino) === untrailingslashit(home_url('/' . $ruta))) {
        return;
    }

    wp_safe_redirect($destino, 301);
    exit;
}
add_action('template_redirect', 'angelitozzz_enlaces_redireccion', 1);
