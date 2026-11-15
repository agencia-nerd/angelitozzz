<?php

/**
 * Checkout · "Datos de facturación" (specs/checkout.md, bloque 2 y "Campos del formulario").
 * Grilla de 2 columnas con campos subrayados: etiqueta y campo sobre la misma línea, sin placeholder.
 * Sin sesión suma Correo y Contraseña (la cuenta se crea al pagar) con las reglas de contraseña.
 *
 * F4, solo maquetación: los campos se escriben a mano con los name/id de Woo del spec para que
 * wp-woocommerce (F5) los cambie por woocommerce_form_field() sin tocar el diseño. Los campos propios
 * (tipo y número de documento, prefijo del celular) los define el plugin angelitozzz-cursos en la F5
 * con el filtro woocommerce_checkout_fields. Hooks del original como comentarios `// woo:`.
 *
 * $checkout: WC_Checkout (lo pasa WooCommerce).
 */

defined('ABSPATH') || exit;

$checkout   = $checkout ?? null;
$con_sesion = is_user_logged_in();
$id_titulo  = wp_unique_id('checkout-facturacion-');

$campo    = 'flex items-end gap-2 border-b border-tinta pb-[2px] focus-within:border-marca';
$etiqueta = 'shrink-0 text-cuerpo-s text-tinta';
$entrada  = 'min-w-0 flex-1 bg-transparent text-cuerpo-s text-tinta focus:outline-none';
$asterisco = '<abbr class="no-underline" title="' . esc_attr__('obligatorio', 'angelitozzz') . '">*</abbr>';

// Prefijos del celular (propuesta: +51 por defecto y los países de la clienta). woo: lo define el plugin en la F5.
$prefijos = [
    '+51'  => __('Perú (+51)', 'angelitozzz'),
    '+57'  => __('Colombia (+57)', 'angelitozzz'),
    '+52'  => __('México (+52)', 'angelitozzz'),
    '+56'  => __('Chile (+56)', 'angelitozzz'),
    '+54'  => __('Argentina (+54)', 'angelitozzz'),
    '+593' => __('Ecuador (+593)', 'angelitozzz'),
    '+1'   => __('Estados Unidos (+1)', 'angelitozzz'),
    '+34'  => __('España (+34)', 'angelitozzz'),
];
$documentos = [
    'dni'       => __('DNI', 'angelitozzz'),
    'ce'        => __('CE', 'angelitozzz'),
    'pasaporte' => __('Pasaporte', 'angelitozzz'),
    'ruc'       => __('RUC', 'angelitozzz'),
];
$chevron = '<svg class="pointer-events-none size-[13px] shrink-0" viewBox="0 0 13 13" fill="currentColor" aria-hidden="true" focusable="false"><path d="M6.21 8.63 1.89 4.1a.4.4 0 0 1 0-.56.38.38 0 0 1 .55 0L6.5 7.8l4.06-4.26a.38.38 0 0 1 .55 0 .4.4 0 0 1 0 .56L6.79 8.63a.4.4 0 0 1-.58 0Z" /></svg>';
?>
<section class="woocommerce-billing-fields flex flex-col gap-[25px] p-6 max-md:px-0" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <h2 id="<?= esc_attr($id_titulo) ?>" class="text-cuerpo-l text-tinta"><?= esc_html__('Datos de facturación', 'angelitozzz') ?></h2>

    <?php // woo: do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

    <?php // Una sola grilla: los campos de facturación y los de la cuenta (Correo y Contraseña en la misma fila). ?>
    <div class="grid grid-cols-2 gap-x-[39px] gap-y-[28px] max-md:grid-cols-1">
    <div class="woocommerce-billing-fields__field-wrapper contents">
        <?php // woo: foreach ($checkout->get_checkout_fields('billing') as $key => $field) woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>

        <p class="<?= esc_attr($campo) ?>" id="billing_first_name_field">
            <label class="<?= esc_attr($etiqueta) ?>" for="billing_first_name"><?= esc_html__('Nombres y Apellidos:', 'angelitozzz') ?> <?= $asterisco // HTML fijo, escapado arriba. ?></label>
            <input class="<?= esc_attr($entrada) ?>" type="text" name="billing_first_name" id="billing_first_name" autocomplete="name" required>
        </p>

        <p class="<?= esc_attr($campo) ?>" id="billing_phone_field">
            <label class="<?= esc_attr($etiqueta) ?>" for="billing_phone"><?= esc_html__('Celular (con código de país):', 'angelitozzz') ?> <?= $asterisco ?></label>
            <span class="relative flex shrink-0 items-center">
                <select class="cursor-pointer appearance-none bg-transparent pr-3 text-cuerpo-s text-tinta focus:outline-none" name="billing_phone_prefijo" id="billing_phone_prefijo" aria-label="<?= esc_attr__('Código de país', 'angelitozzz') ?>">
                    <?php foreach ($prefijos as $prefijo => $pais) : ?>
                        <option value="<?= esc_attr($prefijo) ?>" <?php selected($prefijo, '+51'); ?>><?= esc_html($prefijo) ?></option>
                    <?php endforeach; ?>
                </select>
                <svg class="pointer-events-none absolute right-0 size-[7px]" viewBox="0 0 8 5" fill="currentColor" aria-hidden="true" focusable="false"><path d="M0 0h8L4 5z" /></svg>
            </span>
            <input class="<?= esc_attr($entrada) ?>" type="tel" name="billing_phone" id="billing_phone" autocomplete="tel-national" inputmode="tel" required>
        </p>

        <p class="<?= esc_attr($campo) ?> relative" id="billing_tipo_documento_field">
            <label class="<?= esc_attr($etiqueta) ?>" for="billing_tipo_documento"><?= esc_html__('Tipo de documento', 'angelitozzz') ?> <?= $asterisco ?></label>
            <select class="min-w-0 flex-1 cursor-pointer appearance-none bg-transparent pr-6 text-cuerpo-s text-tinta focus:outline-none" name="billing_tipo_documento" id="billing_tipo_documento" required>
                <option value=""></option>
                <?php foreach ($documentos as $valor => $texto) : ?>
                    <option value="<?= esc_attr($valor) ?>"><?= esc_html($texto) ?></option>
                <?php endforeach; ?>
            </select>
            <span class="pointer-events-none absolute bottom-[8px] right-0 text-tinta"><?= $chevron // SVG fijo del tema. ?></span>
        </p>

        <p class="<?= esc_attr($campo) ?>" id="billing_numero_documento_field">
            <label class="<?= esc_attr($etiqueta) ?>" for="billing_numero_documento"><?= esc_html__('Número de documento:', 'angelitozzz') ?> <?= $asterisco ?></label>
            <input class="<?= esc_attr($entrada) ?>" type="text" name="billing_numero_documento" id="billing_numero_documento" autocomplete="off" required>
        </p>

        <?php if (!$con_sesion) : ?>
            <p class="<?= esc_attr($campo) ?>" id="billing_email_field">
                <label class="<?= esc_attr($etiqueta) ?>" for="billing_email"><?= esc_html__('Correo:', 'angelitozzz') ?> <?= $asterisco ?></label>
                <input class="<?= esc_attr($entrada) ?>" type="email" name="billing_email" id="billing_email" autocomplete="email" required>
            </p>
        <?php endif; ?>
    </div>

    <?php // woo: do_action('woocommerce_after_checkout_billing_form', $checkout); ?>

    <?php if (!$con_sesion) : ?>
        <?php
        // woo: la cuenta se crea siempre al pagar (sin compra como invitada): no hay casilla "¿Crear una cuenta?".
        // woo: do_action('woocommerce_before_checkout_registration_form', $checkout);
        ?>
        <div class="woocommerce-account-fields create-account contents">
            <?php // woo: foreach ($checkout->get_checkout_fields('account') as $key => $field) woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
            <p class="<?= esc_attr($campo) ?>" id="account_password_field">
                <label class="<?= esc_attr($etiqueta) ?>" for="account_password"><?= esc_html__('Contraseña:', 'angelitozzz') ?> <?= $asterisco ?></label>
                <input class="<?= esc_attr($entrada) ?>" type="password" name="account_password" id="account_password" autocomplete="new-password" required>
            </p>
            <div class="col-span-full">
                <?php get_template_part('template-parts/components/reglas-contrasena', null, ['campo' => 'account_password']); ?>
            </div>
        </div>
        <?php // woo: do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
    <?php endif; ?>
    </div>
</section>
