<?php

/**
 * Checkout · "Método de pago" y términos (specs/checkout.md, bloques 3 y 4), en la columna izquierda.
 * La caja dice qué tarjetas se aceptan; debajo va lo que dibuje la pasarela (Niubiz). Con una sola
 * pasarela no se ve el radio de elegir método.
 *
 * F4, solo maquetación. Diferencia con el original (templates/checkout/payment.php): "Pagar ahora" no
 * está aquí sino en la columna derecha de form-checkout.php (con `form` apuntando al checkout). En la F5,
 * wp-woocommerce deja aquí los hooks woocommerce_review_order_before_submit / after_submit y el nonce
 * (o los mueve junto al botón, conservando el orden) y revisa el fragmento `.woocommerce-checkout-payment`
 * que Woo reemplaza por AJAX.
 *
 * $checkout: WC_Checkout (lo pasa WooCommerce).
 */

defined('ABSPATH') || exit;

// woo: if (!wp_doing_ajax()) do_action('woocommerce_review_order_before_payment');

$id_titulo = wp_unique_id('checkout-pago-');
$logos_alt = __('Visa, Mastercard, American Express y Diners Club', 'angelitozzz');
?>
<div id="payment" class="woocommerce-checkout-payment max-lg:order-5">
    <section class="flex min-h-[124px] items-center justify-between gap-6 rounded-[12px] border border-[#bcb28a] pb-[31px] pl-[30px] pr-[35px] pt-[25px] max-md:flex-col max-md:items-start max-md:px-5 max-md:py-5" aria-labelledby="<?= esc_attr($id_titulo) ?>">
        <div class="flex flex-col gap-[14px]">
            <h2 id="<?= esc_attr($id_titulo) ?>" class="text-cuerpo-l text-tinta"><?= esc_html__('Método de pago', 'angelitozzz') ?></h2>
            <p class="text-cuerpo-s text-tinta"><?= esc_html__('Tarjetas de crédito / débito aceptadas', 'angelitozzz') ?></p>
        </div>
        <img class="h-[53px] w-[212px] max-w-full shrink-0" src="<?= esc_url(get_theme_file_uri('assets/img/compra/checkout-logos-tarjetas.svg')) ?>" alt="<?= esc_attr($logos_alt) ?>" width="212" height="53" loading="lazy">
    </section>

    <?php
    // woo: if (WC()->cart && WC()->cart->needs_payment()) : <ul class="wc_payment_methods payment_methods methods">
    //      foreach ($available_gateways as $gateway) wc_get_template('checkout/payment-method.php', ['gateway' => $gateway]);
    //      (lo que dibuje Niubiz va aquí, bajo la caja; sin pasarelas: woocommerce_no_available_payment_methods_message)
    // woo: <noscript> con el botón "Actualizar totales" del original.
    ?>
</div>

<div class="form-row place-order max-lg:order-6">
    <?php wc_get_template('checkout/terms.php'); ?>
    <?php
    // woo: do_action('woocommerce_review_order_before_submit'); (el botón está en form-checkout.php)
    // woo: do_action('woocommerce_review_order_after_submit');
    // woo: wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce');
    ?>
</div>
<?php
// woo: if (!wp_doing_ajax()) do_action('woocommerce_review_order_after_payment');
