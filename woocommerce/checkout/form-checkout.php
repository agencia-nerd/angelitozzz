<?php

/**
 * Checkout (specs/checkout.md, sección hero; Figma 4156:9962), conectado a WooCommerce.
 *
 * Conserva todos los do_action() del original (templates/checkout/form-checkout.php) en su orden. El tema
 * quita de sus ganchos lo que el diseño dibuja en otro lugar (inc/woocommerce/plantillas.php): el login y
 * el cupón de woocommerce_before_checkout_form, y el pago de woocommerce_checkout_order_review.
 *
 * Estructura:
 * - Columna izquierda: "Iniciar sesión" (form-login.php, su propio <form>, fuera del checkout) y el
 *   <form name="checkout"> con los datos de facturación (woocommerce_checkout_billing) y el método de pago
 *   con los términos, el nonce y los ganchos del botón (woocommerce_checkout_payment() → payment.php).
 * - Columna derecha: el resumen (#order_review: woocommerce_checkout_order_review → review-order.php),
 *   "Pagar ahora" (#place_order, con `form` apuntando al checkout: vive fuera del <form> sin anidar
 *   formularios; el envío nativo y el de checkout.js disparan el submit de form.checkout) y el cupón
 *   (form-coupon.php, otro <form>).
 * - Móvil: las dos columnas y el <form> del checkout pasan a `display: contents` y cada bloque toma su
 *   lugar con `order`: iniciar sesión, facturación, resumen, cupón, método de pago y términos, y
 *   "Pagar ahora" al final.
 *
 * $checkout: WC_Checkout (lo pasa WooCommerce).
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Angelitozzz
 * @version 9.4.0
 */

defined('ABSPATH') || exit;

// Avisos de Woo (woocommerce_output_all_notices) y lo que cuelgue de este gancho, en el mismo ancho que el checkout.
?>
<div class="mx-auto w-full max-w-[1200px] px-6 max-md:px-4">
    <?php do_action('woocommerce_before_checkout_form', $checkout); ?>
</div>
<?php

// Si el registro en el checkout está desactivado y no hay sesión, no se puede comprar.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('Debes iniciar sesión para finalizar la compra.', 'angelitozzz')));
    return;
}

$id_form = 'angelitozzz-checkout';

$texto_boton = (string) apply_filters('woocommerce_order_button_text', __('Pagar ahora', 'angelitozzz'));
$boton_html  = '<button type="submit" form="' . esc_attr($id_form) . '" class="inline-flex cursor-pointer items-center gap-[10px] whitespace-nowrap rounded-full bg-acento py-[14px] pl-[28px] pr-[24px] text-rotulo text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca w-full justify-center" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($texto_boton) . '" data-value="' . esc_attr($texto_boton) . '">'
    . '<span>' . esc_html($texto_boton) . '</span>'
    . '<svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>'
    . '</button>';

// Lo que puede traer el botón (el del diseño o el que deje un filtro de woocommerce_order_button_html).
$boton_permitido = [
    'button' => [
        'type' => true, 'form' => true, 'class' => true, 'name' => true, 'id' => true, 'value' => true,
        'data-value' => true, 'disabled' => true, 'aria-label' => true, 'aria-disabled' => true,
        'aria-describedby' => true, 'style' => true,
    ],
    'span' => ['class' => true, 'aria-hidden' => true],
    'svg'  => [
        'class' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true,
        'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true, 'focusable' => true,
        'xmlns' => true, 'width' => true, 'height' => true,
    ],
    'path' => ['d' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'fill-rule' => true, 'clip-rule' => true],
];
?>
<div class="mx-auto grid w-full max-w-[1200px] grid-cols-1 gap-6 px-6 pb-30 pt-14 max-md:px-4 max-md:pb-14 max-md:pt-8 lg:grid-cols-[minmax(0,1fr)_345px] lg:items-start lg:gap-12">
    <h1 class="sr-only"><?= esc_html__('Finalizar compra', 'angelitozzz') ?></h1>

    <?php // Columna izquierda (en móvil, sus bloques se reparten con `order`). ?>
    <div class="flex flex-col gap-6 max-lg:contents">
        <?php if (!is_user_logged_in()) : ?>
            <div class="max-lg:order-1">
                <?php wc_get_template('checkout/form-login.php', ['checkout' => $checkout]); ?>
            </div>
        <?php endif; ?>

        <form id="<?= esc_attr($id_form) ?>" name="checkout" method="post" class="checkout woocommerce-checkout flex flex-col gap-6 max-lg:contents" action="<?= esc_url(wc_get_checkout_url()) ?>" enctype="multipart/form-data" aria-label="<?= esc_attr__('Finalizar compra', 'angelitozzz') ?>">
            <?php if ($checkout->get_checkout_fields()) : ?>

                <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                <div id="customer_details" class="max-lg:order-2">
                    <?php do_action('woocommerce_checkout_billing'); ?>
                    <?php do_action('woocommerce_checkout_shipping'); ?>
                </div>

                <?php do_action('woocommerce_checkout_after_customer_details'); ?>

            <?php endif; ?>

            <?php // Método de pago, términos, ganchos del botón y nonce (payment.php), dentro del <form>. ?>
            <div class="flex flex-col gap-6 max-lg:contents">
                <?php woocommerce_checkout_payment(); ?>
            </div>
        </form>
    </div>

    <?php // Columna derecha: resumen, "Pagar ahora" y cupón. En escritorio queda fija al bajar (bajo el header). ?>
    <div class="flex flex-col items-center gap-6 max-lg:contents lg:sticky lg:top-28">
        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
        <?php // Sin el <h3 id="order_review_heading"> del original: el título "Resumen de compra" es el h2 del componente. ?>
        <?php do_action('woocommerce_checkout_before_order_review'); ?>

        <div id="order_review" class="woocommerce-checkout-review-order w-full max-lg:order-3">
            <?php do_action('woocommerce_checkout_order_review'); ?>
        </div>

        <div class="flex w-full flex-col items-center gap-3 max-lg:order-7">
            <?= wp_kses(apply_filters('woocommerce_order_button_html', $boton_html), $boton_permitido) ?>

            <?php // Aviso de confianza junto al pago (la tarjeta la tokeniza Openpay; el sitio no la guarda). ?>
            <p class="flex items-center justify-center gap-1.5 text-center text-caption text-tinta/70">
                <svg class="size-[12px] shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="7" width="10" height="7" rx="1.5" /><path d="M5.5 7V5a2.5 2.5 0 0 1 5 0v2" /></svg>
                <span><?= esc_html__('Pago seguro con Openpay', 'angelitozzz') ?></span>
            </p>
        </div>

        <div class="w-full max-lg:order-4">
            <?php wc_get_template('checkout/form-coupon.php', ['checkout' => $checkout]); ?>
        </div>

        <?php do_action('woocommerce_checkout_after_order_review'); ?>
    </div>
</div>
<?php
do_action('woocommerce_after_checkout_form', $checkout);
