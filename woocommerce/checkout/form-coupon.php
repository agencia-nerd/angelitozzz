<?php

/**
 * Checkout · "Código de descuento" (specs/checkout.md, columna derecha bajo "Pagar ahora"), conectado a
 * WooCommerce. Es un <form> aparte, fuera del formulario del checkout: aplicar un cupón no borra lo escrito
 * en los datos de facturación. checkout.js lo envía por AJAX (?wc-ajax=apply_coupon) y actualiza el resumen.
 *
 * Siempre visible (no plegado tras "¿Tienes un cupón?"): checkout.js hace $('form.checkout_coupon').hide()
 * al iniciar y slideUp() al aplicar o quitar un cupón; la clase `!grid` (display del diseño con !important)
 * gana al display:none en línea que deja jQuery. Por eso no van el aviso plegado ni el filtro
 * woocommerce_checkout_coupon_message del original (templates/checkout/form-coupon.php), que no tiene do_action().
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Angelitozzz
 * @version 9.8.0
 */

defined('ABSPATH') || exit;

if (!wc_coupons_enabled()) {
    return;
}
?>
<form class="checkout_coupon woocommerce-form-coupon !grid w-full grid-cols-[minmax(0,1fr)_auto] items-center gap-x-3 gap-y-2" method="post" id="woocommerce-checkout-form-coupon" aria-label="<?= esc_attr__('Código de descuento', 'angelitozzz') ?>">
    <p class="contents">
        <label class="col-span-2 text-cuerpo-s text-tinta" for="coupon_code"><?= esc_html__('Código de descuento', 'angelitozzz') ?></label>
        <input class="input-text campo-caja col-start-1 row-start-2 !h-11 min-w-0" type="text" name="coupon_code" id="coupon_code" value="" autocomplete="off">
    </p>
    <button type="submit" class="col-start-2 row-start-2 h-11 shrink-0 cursor-pointer rounded-full bg-neutro px-5 text-rotulo text-tinta focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" name="apply_coupon" value="<?= esc_attr__('Aplicar', 'angelitozzz') ?>">
        <?= esc_html__('Aplicar', 'angelitozzz') ?>
    </button>
</form>
