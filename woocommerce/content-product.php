<?php

/**
 * Tarjeta de producto de la tienda (specs/soluciones-de-sueno.md, sección shop).
 *
 * Por ahora solo diseño: recibe los datos por $args (los mismos de template-parts/components/producto-card)
 * y no llama a WooCommerce. Al conectar Woo, los datos salen del producto del bucle.
 */

defined('ABSPATH') || exit;
?>
<li class="flex">
    <?php
    // woo: woocommerce_before_shop_loop_item

    get_template_part('template-parts/components/producto-card', null, $args ?? []);

    // woo: woocommerce_after_shop_loop_item
    ?>
</li>
