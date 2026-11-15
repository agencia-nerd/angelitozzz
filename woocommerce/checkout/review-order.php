<?php

/**
 * Checkout · resumen de compra (specs/checkout.md, columna derecha; Figma 4156:9961): el componente
 * resumen-compra, el mismo del carrito. Descuento solo con cupón e IGV solo con impuesto configurado.
 *
 * F4, solo maquetación: los datos salen de la muestra angelitozzz_compra_muestra_carrito(). Hooks del
 * original (templates/checkout/review-order.php) como comentarios `// woo:`. La clase
 * woocommerce-checkout-review-order-table es el fragmento que Woo reemplaza por AJAX al actualizar.
 */

defined('ABSPATH') || exit;

$carrito = angelitozzz_compra_muestra_carrito(); // woo: WC()->cart

// woo: do_action('woocommerce_review_order_before_cart_contents');
// woo: foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) — con los filtros
//      woocommerce_cart_item_product, woocommerce_checkout_cart_item_visible, woocommerce_cart_item_name
//      y woocommerce_cart_item_subtotal — arma cada ítem [tipo, nombre, precio].
// woo: do_action('woocommerce_review_order_after_cart_contents');
$items = array_map(
    static fn(array $item): array => [
        'tipo'   => $item['tipo'],
        'nombre' => $item['nombre'],
        'precio' => $item['precio'],
    ],
    $carrito['items']
);

// woo: subtotal wc_cart_totals_subtotal_html(); cupones wc_cart_totals_coupon_html() (solo con cupón);
//      woo: do_action('woocommerce_review_order_before_shipping'); envío (no hay: cursos virtuales); do_action('woocommerce_review_order_after_shipping');
//      woo: tarifas e impuestos wc_cart_totals_taxes_total_html() (solo con impuestos, IGV);
//      woo: do_action('woocommerce_review_order_before_order_total'); total wc_cart_totals_order_total_html();
//      woo: do_action('woocommerce_review_order_after_order_total');
?>
<div class="shop_table woocommerce-checkout-review-order-table">
    <?php get_template_part('template-parts/components/resumen-compra', null, [
        'items'     => $items,
        'subtotal'  => $carrito['subtotal'],
        'descuento' => $carrito['descuento'],
        'impuesto'  => $carrito['impuesto'],
        'total'     => $carrito['total'],
    ]); ?>
</div>
