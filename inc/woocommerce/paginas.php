<?php
defined('ABSPATH') || exit;

/*
 * Carrito y Finalizar compra con el shortcode clásico (specs/_shared.md → WooCommerce: clásico; el tema dibuja
 * esas páginas con sus plantillas de woocommerce/). WooCommerce las crea con sus bloques: este cambio se hace
 * una sola vez, en el Local y en producción, y solo si la página todavía tiene el bloque de Woo. Nunca pisa un
 * contenido que alguien haya editado.
 */

const ANGELITOZZZ_WOO_PAGINAS_VERSION = '1';

function angelitozzz_woo_paginas_clasicas(): void
{
    if (get_option('angelitozzz_woo_paginas') === ANGELITOZZZ_WOO_PAGINAS_VERSION || !function_exists('wc_get_page_id')) {
        return;
    }

    $paginas = [
        'cart'     => ['bloque' => '<!-- wp:woocommerce/cart', 'shortcode' => '[woocommerce_cart]'],
        'checkout' => ['bloque' => '<!-- wp:woocommerce/checkout', 'shortcode' => '[woocommerce_checkout]'],
    ];

    foreach ($paginas as $clave => $pagina) {
        $id = wc_get_page_id($clave);
        if ($id <= 0) {
            continue;
        }
        $contenido = (string) get_post_field('post_content', $id);
        if (str_contains($contenido, $pagina['bloque'])) {
            wp_update_post(['ID' => $id, 'post_content' => $pagina['shortcode']]);
        }
    }

    update_option('angelitozzz_woo_paginas', ANGELITOZZZ_WOO_PAGINAS_VERSION, false);
}
add_action('init', 'angelitozzz_woo_paginas_clasicas', 30);
