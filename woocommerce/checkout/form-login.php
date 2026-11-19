<?php

/**
 * Checkout · "Iniciar sesión" (specs/checkout.md, bloque 1), conectado a WooCommerce. Solo sin sesión.
 * Siempre visible, no plegado tras "¿Ya eres cliente?": checkout.js no oculta form.login al iniciar (solo
 * lo pliega con a.showlogin, que aquí no hay). Es su propio <form>, fuera del checkout; lo procesa
 * WC_Form_Handler::process_login() (wp_signon, con el límite de intentos del plugin angelitozzz-cursos) y
 * vuelve al checkout por el campo `redirect`.
 * "Regístrate" no lleva a otra página: lleva al campo Correo de los datos de facturación, donde la cuenta
 * se crea al pagar.
 *
 * El original (templates/checkout/form-login.php) imprime woocommerce_login_form() (templates/global/form-login.php):
 * aquí va ese formulario con el diseño y sus ganchos woocommerce_login_form_start, woocommerce_login_form y
 * woocommerce_login_form_end, el nonce woocommerce-login y el botón `login`.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Angelitozzz
 * @version 10.0.0
 */

defined('ABSPATH') || exit;

if (is_user_logged_in()) {
    return;
}

$id_titulo = wp_unique_id('checkout-login-');
$campo     = 'flex items-end gap-2 border-b border-tinta pb-[2px]';
$etiqueta  = 'shrink-0 text-cuerpo-s text-tinta';
$entrada   = 'input-text min-w-0 flex-1 bg-transparent text-cuerpo-s text-tinta focus:outline-none';
?>
<section class="flex flex-col gap-[21px] p-6 max-md:px-0" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <h2 id="<?= esc_attr($id_titulo) ?>" class="text-cuerpo-l text-tinta"><?= esc_html__('Iniciar sesión', 'angelitozzz') ?></h2>

    <form class="woocommerce-form woocommerce-form-login login flex flex-col gap-[21px]" method="post">
        <?php do_action('woocommerce_login_form_start'); ?>

        <div class="flex items-center gap-[35px] max-md:flex-col max-md:items-stretch max-md:gap-6">
            <p class="<?= esc_attr($campo) ?> w-[420px] max-w-full focus-within:border-marca">
                <label class="<?= esc_attr($etiqueta) ?>" for="username"><?= esc_html__('Correo:', 'angelitozzz') ?></label>
                <input class="<?= esc_attr($entrada) ?>" type="email" name="username" id="username" autocomplete="username" required aria-required="true">
            </p>
            <p class="<?= esc_attr($campo) ?> w-[377px] max-w-full focus-within:border-marca">
                <label class="<?= esc_attr($etiqueta) ?>" for="password"><?= esc_html__('Contraseña:', 'angelitozzz') ?></label>
                <input class="<?= esc_attr($entrada) ?> woocommerce-Input" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true">
            </p>
        </div>

        <?php do_action('woocommerce_login_form'); ?>

        <div>
            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
            <input type="hidden" name="redirect" value="<?= esc_url(wc_get_checkout_url()) ?>">
            <button type="submit" class="woocommerce-form-login__submit cursor-pointer rounded-full bg-neutro px-[22px] py-[5px] text-rotulo text-tinta focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" name="login" value="<?= esc_attr__('Iniciar sesión', 'angelitozzz') ?>">
                <?= esc_html__('Iniciar sesión', 'angelitozzz') ?>
            </button>
        </div>

        <p class="flex flex-wrap items-center gap-x-[53px] gap-y-1 text-cuerpo-s text-tinta">
            <span><?= esc_html__('¿No tienes una cuenta?', 'angelitozzz') ?></span>
            <a class="underline underline-offset-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="#billing_email"><?= esc_html__('Regístrate', 'angelitozzz') ?></a>
        </p>

        <?php do_action('woocommerce_login_form_end'); ?>
    </form>
</section>
