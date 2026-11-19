<?php
defined('ABSPATH') || exit;

/*
 * Lo que WooCommerce imprime por defecto en los ganchos del carrito, el checkout y gracias, y que las plantillas
 * del tema (woocommerce/) ya dibujan con el diseño (specs/carrito.md, checkout.md, gracias.md). Los ganchos se
 * conservan en las plantillas: solo se quita la salida por defecto de Woo, para que no salga dos veces.
 * Los avisos (woocommerce_output_all_notices) se quedan.
 */

add_action('init', static function (): void {
    // Checkout: el inicio de sesión y el cupón son formularios propios del diseño (form-checkout.php los imprime
    // en su lugar); el pago va en la columna izquierda (form-checkout.php llama a woocommerce_checkout_payment()),
    // así que se saca de woocommerce_checkout_order_review, que queda en la columna del resumen.
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
    remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
    // Términos: el enlace va a la página de términos (specs/checkout.md), no a su contenido plegado en el checkout.
    remove_action('woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30);

    // Carrito: el resumen es el componente resumen-compra (cart.php) y no hay ventas cruzadas.
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
    // Carrito vacío: el mensaje es el del diseño (cart-empty.php).
    remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);

    // Gracias: el diseño muestra la confirmación y los cursos; la tabla de detalle de Woo no va (specs/gracias.md).
    // Lo que agregue la pasarela en woocommerce_thankyou_<método> sí se muestra.
    remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);
}, 20);
