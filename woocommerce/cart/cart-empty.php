<?php

/**
 * Carrito vacío (specs/carrito.md, sección carrito-de-compras; propuesta: el diseño no lo trae).
 *
 * Sobrescribe woocommerce/templates/cart/cart-empty.php. F4, solo maquetación: el hook del original
 * queda como `// woo:`. En la F5, wp-woocommerce conserva woocommerce_cart_is_empty y quita de él el
 * aviso por defecto (wc_empty_cart_message) para que el mensaje no salga dos veces; el botón del
 * original ("Volver a la tienda") lo reemplaza el de Soluciones de sueño.
 * La plantilla no imprime <main>: lo pone la página.
 */

defined('ABSPATH') || exit;
?>
<div class="mx-auto max-w-[1129px] px-4 py-[120px] max-md:py-14">
    <h1 class="text-h1 text-marca max-md:text-h3"><?= esc_html__('Carrito de compras', 'angelitozzz') ?></h1>

    <?php // woo: do_action('woocommerce_cart_is_empty') ?>

    <div class="mt-9 flex flex-col items-start gap-6 max-md:items-stretch">
        <p class="text-cuerpo-l text-tinta"><?= esc_html__('Tu carrito está vacío', 'angelitozzz') ?></p>
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace'   => ['url' => home_url('/soluciones-de-sueno/'), 'title' => __('Ver soluciones de sueño', 'angelitozzz')],
            'variante' => 'frambuesa',
            'clase'    => 'max-md:justify-center',
        ]); ?>
    </div>
</div>
