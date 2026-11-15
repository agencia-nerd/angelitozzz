<?php

/**
 * Checkout (specs/checkout.md, sección hero; Figma 4156:9962).
 *
 * F4, solo maquetación: los hooks del original (woocommerce/templates/checkout/form-checkout.php)
 * quedan como comentarios `// woo:` en su lugar y orden, y las partes se imprimen directo con
 * wc_get_template(). wp-woocommerce (F5) los conecta y pone el @version del original.
 *
 * Estructura (para que funcione igual con Woo conectado):
 * - Columna izquierda: "Iniciar sesión" (form-login.php, su propio <form>, fuera del checkout) y el
 *   <form name="checkout"> con los datos de facturación, el método de pago (payment.php) y los términos.
 * - Columna derecha: el resumen (review-order.php), "Pagar ahora" (`form` apunta al checkout, así el
 *   botón vive fuera del <form> sin anidar formularios) y el cupón (form-coupon.php, otro <form>).
 * - Móvil: las dos columnas y el <form> del checkout pasan a `display: contents` y cada bloque toma su
 *   lugar con `order`: iniciar sesión, facturación, resumen, cupón, método de pago, términos y
 *   "Pagar ahora" al final.
 *
 * $checkout: WC_Checkout (lo pasa WooCommerce).
 */

defined('ABSPATH') || exit;

$checkout = $checkout ?? null;

// woo: do_action('woocommerce_before_checkout_form', $checkout);
//      (Woo cuelga aquí el aviso de "¿Ya eres cliente?" y el cupón plegado: en la F5 se quitan de ese
//      hook y se imprimen donde los pone el diseño: form-login.php y form-coupon.php.)

// woo: si el checkout exige sesión y no la hay: woocommerce_checkout_must_be_logged_in_message.

$id_form = 'angelitozzz-checkout';
?>
<div class="mx-auto grid max-w-[1507px] grid-cols-1 gap-7 px-4 py-30 max-md:py-14 lg:grid-cols-[minmax(0,969px)_346px] lg:items-start lg:justify-center lg:gap-x-[clamp(48px,10vw,192px)]">
    <h1 class="sr-only"><?= esc_html__('Finalizar compra', 'angelitozzz') ?></h1>

    <?php // Columna izquierda (en móvil, sus bloques se reparten con `order`). ?>
    <div class="flex flex-col gap-7 max-lg:contents">
        <?php if (!is_user_logged_in()) : ?>
            <div class="max-lg:order-1">
                <?php wc_get_template('checkout/form-login.php', ['checkout' => $checkout]); ?>
            </div>
        <?php endif; ?>

        <form id="<?= esc_attr($id_form) ?>" name="checkout" method="post" class="checkout woocommerce-checkout flex flex-col gap-[41px] max-lg:contents" action="<?= esc_url(wc_get_checkout_url()) ?>" enctype="multipart/form-data" aria-label="<?= esc_attr__('Finalizar compra', 'angelitozzz') ?>">
            <?php // woo: do_action('woocommerce_checkout_before_customer_details'); ?>

            <div id="customer_details" class="max-lg:order-2">
                <?php // woo: do_action('woocommerce_checkout_billing'); — hoy imprime form-billing.php directo. ?>
                <?php wc_get_template('checkout/form-billing.php', ['checkout' => $checkout]); ?>
                <?php // woo: do_action('woocommerce_checkout_shipping'); (sin envío: son cursos virtuales) ?>
            </div>

            <?php // woo: do_action('woocommerce_checkout_after_customer_details'); ?>

            <?php
            // woo: do_action('woocommerce_checkout_payment') vía woocommerce_checkout_order_review (prioridad 20).
            //      Hoy imprime payment.php directo: caja del método de pago y términos, en la columna izquierda.
            ?>
            <div class="flex flex-col gap-[41px] max-lg:contents">
                <?php wc_get_template('checkout/payment.php', ['checkout' => $checkout]); ?>
            </div>
        </form>
    </div>

    <?php // Columna derecha: resumen, "Pagar ahora" y cupón. ?>
    <div class="flex flex-col items-center gap-[34px] max-lg:contents">
        <?php // woo: do_action('woocommerce_checkout_before_order_review_heading'); ?>
        <?php // woo: do_action('woocommerce_checkout_before_order_review'); ?>

        <div id="order_review" class="woocommerce-checkout-review-order w-full max-lg:order-3">
            <?php // woo: do_action('woocommerce_checkout_order_review'); — hoy imprime review-order.php directo. ?>
            <?php wc_get_template('checkout/review-order.php', ['checkout' => $checkout]); ?>
        </div>

        <?php
        // woo: do_action('woocommerce_review_order_before_submit');
        // woo: apply_filters('woocommerce_order_button_html', …) — el botón conserva id, name y value de Woo.
        ?>
        <div class="flex w-full justify-center max-lg:order-7">
            <button type="submit" form="<?= esc_attr($id_form) ?>" id="place_order" name="woocommerce_checkout_place_order" value="<?= esc_attr__('Pagar ahora', 'angelitozzz') ?>" class="inline-flex cursor-pointer items-center gap-[10px] whitespace-nowrap rounded-full bg-acento py-[14px] pl-[28px] pr-[24px] text-rotulo text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca max-lg:w-full max-lg:justify-center">
                <span><?= esc_html__('Pagar ahora', 'angelitozzz') ?></span>
                <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
            </button>
        </div>
        <?php // woo: do_action('woocommerce_review_order_after_submit'); ?>

        <div class="w-full max-lg:order-4">
            <?php wc_get_template('checkout/form-coupon.php', ['checkout' => $checkout]); ?>
        </div>

        <?php // woo: do_action('woocommerce_checkout_after_order_review'); ?>
    </div>
</div>
<?php
// woo: do_action('woocommerce_after_checkout_form', $checkout);
