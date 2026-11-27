<?php

/**
 * Checkout · "Iniciar sesión" (specs/checkout.md, bloque 1), conectado a WooCommerce. Solo sin sesión.
 * Tarjeta fija sobre "1 · Tus datos" (rediseño del 2026-11-27; sin plegar, decisión del usuario), sin número
 * de paso: es para quien ya tiene cuenta.
 * checkout.js no oculta form.login al iniciar (solo lo pliega con a.showlogin, que aquí no hay). Es su propio
 * <form>, fuera del checkout; lo procesa WC_Form_Handler::process_login() (wp_signon, con el límite de intentos
 * del plugin angelitozzz-cursos) y vuelve al checkout por el campo `redirect`.
 * "Regístrate" no lleva a otra página: lleva al inicio de "1 · Tus datos" (#tus-datos): la cuenta se crea con esos
 * datos, el correo y la contraseña al pagar.
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
$campo     = 'flex flex-col gap-2'; // Campos en caja (src/css/checkout-campos.css), como "Tus datos".
$etiqueta  = 'text-cuerpo-s text-tinta';
$entrada   = 'input-text campo-caja__control';
?>
<section data-paso="cuenta" class="flex flex-col gap-5 rounded-[12px] border border-[#bcb28a] bg-blanco/60 p-8 max-md:p-5" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="flex flex-col gap-1">
        <h2 id="<?= esc_attr($id_titulo) ?>" class="text-h5 text-marca"><?= esc_html__('¿Ya tienes cuenta?', 'angelitozzz') ?></h2>
        <p class="text-cuerpo-s text-tinta-suave"><?= esc_html__('Inicia sesión y tus datos se completan solos.', 'angelitozzz') ?></p>
    </div>

    <form class="woocommerce-form woocommerce-form-login login flex flex-col gap-[21px]" method="post">
        <?php do_action('woocommerce_login_form_start'); ?>

        <div class="flex items-start gap-6 max-md:flex-col max-md:items-stretch max-md:gap-5">
            <p class="<?= esc_attr($campo) ?> min-w-0 flex-1">
                <label class="<?= esc_attr($etiqueta) ?>" for="username"><?= esc_html__('Correo', 'angelitozzz') ?></label>
                <span class="campo-caja"><input class="<?= esc_attr($entrada) ?>" type="email" name="username" id="username" autocomplete="username" required aria-required="true"></span>
            </p>
            <p class="<?= esc_attr($campo) ?> min-w-0 flex-1">
                <label class="<?= esc_attr($etiqueta) ?>" for="password"><?= esc_html__('Contraseña', 'angelitozzz') ?></label>
                <span class="campo-caja"><input class="<?= esc_attr($entrada) ?> woocommerce-Input" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true"></span>
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
            <a class="underline underline-offset-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="#tus-datos"><?= esc_html__('Regístrate', 'angelitozzz') ?></a>
        </p>

        <?php do_action('woocommerce_login_form_end'); ?>
    </form>
</section>
