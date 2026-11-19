<?php

/**
 * Checkout · resumen de compra (specs/checkout.md, columna derecha; Figma 4156:9961), conectado a
 * WooCommerce: el componente resumen-compra, el mismo del carrito, con los datos de WC()->cart.
 * Descuento (cupones y cargos negativos, como el descuento por etapas) solo si hay; impuesto solo si los
 * impuestos están activos y hay monto.
 *
 * Lo imprime woocommerce_checkout_order_review (woocommerce_order_review) dentro de #order_review, y
 * WooCommerce lo vuelve a pedir por AJAX para reemplazar el fragmento `.woocommerce-checkout-review-order-table`:
 * por eso todo va dentro de un solo elemento raíz con esa clase. Conserva los filtros y los do_action() del
 * original (templates/checkout/review-order.php) en su orden; su salida va dentro de la raíz, antes del resumen.
 * Sin el bloque de envío (cursos virtuales): sus ganchos se llaman con la misma condición del original.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Angelitozzz
 * @version 11.2.2
 */

defined('ABSPATH') || exit;

$carrito = WC()->cart;
?>
<div class="shop_table woocommerce-checkout-review-order-table">
    <?php
    do_action('woocommerce_review_order_before_cart_contents');

    $items = [];
    foreach ($carrito->get_cart() as $cart_item_key => $cart_item) {
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

        /** Este filtro está documentado en woocommerce/templates/checkout/review-order.php. */
        $visible = apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key);

        if (!($_product instanceof WC_Product && $_product->exists() && $cart_item['quantity'] > 0 && $visible)) {
            continue;
        }

        $cart_item_name = $carrito->get_item_product_name($cart_item, $_product);
        $product_name   = apply_filters('woocommerce_cart_item_name', $cart_item_name, $cart_item, $cart_item_key);
        $regalo         = function_exists('angelitozzz_cursos_linea_carrito_es_regalo') && angelitozzz_cursos_linea_carrito_es_regalo($cart_item);

        $items[] = [
            'tipo'   => $regalo ? __('Curso de regalo', 'angelitozzz') : __('Curso', 'angelitozzz'),
            // El componente escapa el nombre: sin el HTML que pueda traer el filtro (enlaces).
            'nombre' => wp_strip_all_tags((string) $product_name),
            'precio' => (string) apply_filters('woocommerce_cart_item_subtotal', $carrito->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key),
        ];
    }

    do_action('woocommerce_review_order_after_cart_contents');

    // Subtotal (wc_cart_totals_subtotal_html() lo imprime: se captura).
    ob_start();
    wc_cart_totals_subtotal_html();
    $subtotal = (string) ob_get_clean();

    // Descuento: los cupones (como wc_cart_totals_coupon_html()) y los cargos negativos (como wc_cart_totals_fee_html()).
    $descuento_monto = 0.0;
    foreach ($carrito->get_coupons() as $code => $coupon) {
        $descuento_monto += (float) $carrito->get_coupon_discount_amount((string) $code, !$carrito->display_prices_including_tax());
    }
    foreach ($carrito->get_fees() as $fee) {
        $monto_cargo = $carrito->display_prices_including_tax() ? (float) $fee->total + (float) $fee->tax : (float) $fee->total;
        if ($monto_cargo < 0) {
            $descuento_monto += abs($monto_cargo);
        }
    }
    $descuento = $descuento_monto > 0 ? '-' . wc_price($descuento_monto) : '';

    if ($carrito->needs_shipping() && $carrito->show_shipping()) {
        do_action('woocommerce_review_order_before_shipping');
        // Sin envío en el diseño (cursos virtuales): no se llama a wc_cart_totals_shipping_html().
        do_action('woocommerce_review_order_after_shipping');
    }

    // Impuesto: solo con impuestos activos, precios sin impuesto incluido y monto (como el original). Con una
    // sola tasa, su nombre en Woo (p. ej. "IGV"); con varias, el total con la etiqueta genérica de Woo.
    $impuesto = [];
    if (wc_tax_enabled() && !$carrito->display_prices_including_tax() && (float) $carrito->get_taxes_total() > 0) {
        $tasas = $carrito->get_tax_totals();
        if (count($tasas) === 1) {
            $tasa     = reset($tasas);
            $impuesto = [
                'etiqueta' => (string) $tasa->label,
                'monto'    => (string) $tasa->formatted_amount,
            ];
        } else {
            ob_start();
            wc_cart_totals_taxes_total_html();
            $impuesto = [
                'etiqueta' => WC()->countries->tax_or_vat(),
                'monto'    => (string) ob_get_clean(),
            ];
        }
    }

    do_action('woocommerce_review_order_before_order_total');

    get_template_part('template-parts/components/resumen-compra', null, [
        'items'     => $items,
        'subtotal'  => $subtotal,
        'descuento' => $descuento,
        'impuesto'  => $impuesto,
        'total'     => (string) $carrito->get_total(),
    ]);

    do_action('woocommerce_review_order_after_order_total');
    ?>
</div>
