<?php

/**
 * Checkout · "Código de descuento" (specs/checkout.md, columna derecha bajo "Pagar ahora"), conectado a
 * WooCommerce. Es un <form> aparte, fuera del formulario del checkout: aplicar un cupón no borra lo escrito
 * en los datos de facturación. checkout.js lo envía por AJAX (?wc-ajax=apply_coupon) y actualiza el resumen.
 *
 * Siempre visible (no plegado tras "¿Tienes un cupón?"): checkout.js hace $('form.checkout_coupon').hide()
 * al iniciar y slideUp() al aplicar o quitar un cupón; la clase `!flex` (display del diseño con !important)
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
<form class="checkout_coupon woocommerce-form-coupon !flex w-full items-end gap-4" method="post" id="woocommerce-checkout-form-coupon" aria-label="<?= esc_attr__('Código de descuento', 'angelitozzz') ?>">
    <p class="flex min-w-0 flex-1 flex-wrap items-end gap-2 border-b border-tinta pb-[2px] focus-within:border-marca [&>.coupon-error-notice]:basis-full">
        <label class="shrink-0 text-cuerpo-s text-tinta" for="coupon_code"><?= esc_html__('Código de descuento', 'angelitozzz') ?></label>
        <input class="input-text min-w-0 flex-1 bg-transparent text-cuerpo-s text-tinta focus:outline-none" type="text" name="coupon_code" id="coupon_code" value="" autocomplete="off">
    </p>
    <button type="submit" class="shrink-0 cursor-pointer rounded-full bg-neutro px-[18px] py-[2px] text-rotulo text-tinta focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" name="apply_coupon" value="<?= esc_attr__('Aplicar', 'angelitozzz') ?>">
        <?= esc_html__('Aplicar', 'angelitozzz') ?>
    </button>
</form>
