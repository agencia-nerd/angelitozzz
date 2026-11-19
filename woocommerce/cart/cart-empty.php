<?php

/**
 * Carrito vacío (specs/carrito.md, sección carrito-de-compras; propuesta: el diseño no lo trae).
 *
 * Sobrescribe woocommerce/templates/cart/cart-empty.php. Conserva woocommerce_cart_is_empty (también imprime
 * los avisos, p. ej. "se quitó el curso"); su mensaje por defecto (wc_empty_cart_message) lo quita
 * inc/woocommerce/plantillas.php para que no salga dos veces. El botón "Volver a la tienda" del original lo
 * reemplaza el del diseño, con la misma URL filtrada: inc/tienda.php la manda a Soluciones de sueño.
 * La plantilla no imprime <main>: lo pone la página.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;
?>
<div class="mx-auto max-w-[1129px] px-4 py-[120px] max-md:py-14">
    <h1 class="text-h1 text-marca max-md:text-h3"><?= esc_html__('Carrito de compras', 'angelitozzz') ?></h1>

    <?php do_action('woocommerce_cart_is_empty'); ?>

    <div class="mt-9 flex flex-col items-start gap-6 max-md:items-stretch">
        <p class="text-cuerpo-l text-tinta"><?= esc_html__('Tu carrito está vacío', 'angelitozzz') ?></p>
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace'   => [
                'url'   => apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop')),
                'title' => __('Ver soluciones de sueño', 'angelitozzz'),
            ],
            'variante' => 'frambuesa',
            'clase'    => 'max-md:justify-center',
        ]); ?>
    </div>
</div>
