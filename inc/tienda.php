<?php

/**
 * La tienda de WooCommerce no se muestra (specs/_shared.md, Contenido dinámico → producto):
 * el producto es solo para cobrar, "no tiene listado ni ficha". Se vende desde las landings de
 * `solucion`, y su listado es /soluciones-de-sueno/.
 */

defined('ABSPATH') || exit;

/**
 * URL del listado de soluciones. Si el tipo `solucion` aún no está registrado, va la ruta fija.
 */
function angelitozzz_tienda_destino(): string
{
    $destino = get_post_type_archive_link('solucion');

    return $destino ?: home_url('/soluciones-de-sueno/');
}

/**
 * /tienda/ (y ?post_type=product), las categorías y etiquetas de producto y la ficha de un producto
 * redirigen (301) al listado de soluciones.
 */
function angelitozzz_tienda_redireccion(): void
{
    if (!function_exists('is_shop')) {
        return;
    }

    if (is_shop() || is_product_taxonomy() || is_singular('product')) {
        wp_safe_redirect(angelitozzz_tienda_destino(), 301);
        exit;
    }
}
add_action('template_redirect', 'angelitozzz_tienda_redireccion');

/**
 * Los enlaces de WooCommerce a la tienda ("Volver a la tienda", "Seguir comprando") van
 * directo al listado de soluciones, sin pasar por la redirección.
 */
add_filter('woocommerce_get_shop_page_permalink', 'angelitozzz_tienda_destino');
add_filter('woocommerce_return_to_shop_redirect', 'angelitozzz_tienda_destino');
add_filter('woocommerce_continue_shopping_redirect', 'angelitozzz_tienda_destino');
