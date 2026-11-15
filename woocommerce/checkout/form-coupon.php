<?php

/**
 * Checkout · "Código de descuento" (specs/checkout.md, columna derecha bajo "Pagar ahora"). Es un
 * <form> aparte, fuera del formulario del checkout: aplicar un cupón no borra lo escrito en los datos
 * de facturación. Siempre visible (no plegado tras "¿Tienes un cupón?").
 *
 * F4, solo maquetación. El original (templates/checkout/form-coupon.php) no tiene do_action(); usa el
 * filtro woocommerce_checkout_coupon_message para el aviso plegado, que aquí no se usa. En la F5,
 * wp-woocommerce lo conecta (Woo aplica el cupón por AJAX desde form.checkout_coupon).
 */

defined('ABSPATH') || exit;

// woo: if (!wc_coupons_enabled()) return;

$id_campo = 'coupon_code';
?>
<form class="checkout_coupon woocommerce-form-coupon flex w-full items-end gap-4" method="post" id="woocommerce-checkout-form-coupon" aria-label="<?= esc_attr__('Código de descuento', 'angelitozzz') ?>">
    <p class="flex min-w-0 flex-1 items-end gap-2 border-b border-tinta pb-[2px] focus-within:border-marca">
        <label class="shrink-0 text-cuerpo-s text-tinta" for="<?= esc_attr($id_campo) ?>"><?= esc_html__('Código de descuento', 'angelitozzz') ?></label>
        <input class="input-text min-w-0 flex-1 bg-transparent text-cuerpo-s text-tinta focus:outline-none" type="text" name="coupon_code" id="<?= esc_attr($id_campo) ?>" autocomplete="off">
    </p>
    <button type="submit" class="shrink-0 cursor-pointer rounded-full bg-neutro px-[18px] py-[2px] text-rotulo text-tinta focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" name="apply_coupon" value="<?= esc_attr__('Aplicar', 'angelitozzz') ?>">
        <?= esc_html__('Aplicar', 'angelitozzz') ?>
    </button>
</form>
