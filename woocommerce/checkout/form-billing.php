<?php

/**
 * Checkout · "Datos de facturación" (specs/checkout.md, bloque 2 y "Campos del formulario"), conectado a
 * WooCommerce. Grilla de 2 columnas con campos subrayados: etiqueta y campo sobre la misma línea, sin
 * placeholder. Sin sesión suma Correo y Contraseña (la cuenta se crea al pagar) con las reglas de contraseña.
 *
 * Los campos los define el plugin angelitozzz-cursos (includes/checkout.php, filtro woocommerce_checkout_fields):
 * etiqueta, requerido, opciones y valor por defecto salen de $checkout->get_checkout_fields(). Las claves que
 * el diseño conoce se pintan con su marcado (prefijo dentro del celular, chevron del select); toda otra clave
 * que agregue un plugin se imprime con woocommerce_form_field(). Cada envoltorio lleva las clases que usa la
 * validación de checkout.js (form-row, validate-required, validate-email, validate-phone) y cada campo la
 * clase input-text. Conserva los do_action() del original (templates/checkout/form-billing.php).
 *
 * $checkout: WC_Checkout (lo pasa WooCommerce).
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Angelitozzz
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

$id_titulo = wp_unique_id('checkout-facturacion-');

$campo     = 'flex flex-wrap items-end gap-2 border-b border-tinta pb-[2px] focus-within:border-marca [&>.checkout-inline-error-message]:basis-full';
$etiqueta  = 'shrink-0 text-cuerpo-s text-tinta';
$entrada   = 'min-w-0 flex-1 bg-transparent text-cuerpo-s text-tinta focus:outline-none';
$asterisco = '<abbr class="no-underline" title="' . esc_attr__('obligatorio', 'angelitozzz') . '">*</abbr>';
$chevron   = '<svg class="pointer-events-none size-[13px] shrink-0" viewBox="0 0 13 13" fill="currentColor" aria-hidden="true" focusable="false"><path d="M6.21 8.63 1.89 4.1a.4.4 0 0 1 0-.56.38.38 0 0 1 .55 0L6.5 7.8l4.06-4.26a.38.38 0 0 1 .55 0 .4.4 0 0 1 0 .56L6.79 8.63a.4.4 0 0 1-.58 0Z" /></svg>';

$campos_facturacion = $checkout->get_checkout_fields('billing');
$campos_cuenta      = $checkout->get_checkout_fields('account');

/**
 * Valor de un campo: el de Woo (sesión, pedido anterior o lo enviado) y, si viene vacío, su default.
 */
$valor = static function (string $clave, array $args) use ($checkout): string {
    $actual = $checkout->get_value($clave);
    if ($actual === null || $actual === '') {
        $actual = $args['default'] ?? '';
    }
    return is_scalar($actual) ? (string) $actual : '';
};

/**
 * Clases del envoltorio: las del diseño, las del campo y las de la validación de Woo.
 */
$clases_envoltorio = static function (array $args, string $extra = '') use ($campo): string {
    $clases = array_merge(
        [$campo, 'form-row'],
        array_map('strval', (array) ($args['class'] ?? []))
    );
    if (!empty($args['required'])) {
        $clases[] = 'validate-required';
    }
    foreach ((array) ($args['validate'] ?? []) as $regla) {
        $clases[] = 'validate-' . sanitize_html_class((string) $regla);
    }
    if ($extra !== '') {
        $clases[] = $extra;
    }
    return implode(' ', array_unique(array_filter($clases)));
};

// Lo que pueden traer la etiqueta, las opciones de un select y el chevron (se imprimen con wp_kses()).
$html_permitido = [
    'label'  => ['class' => true, 'for' => true],
    'abbr'   => ['class' => true, 'title' => true],
    'option' => ['value' => true, 'selected' => true],
    'svg'    => ['class' => true, 'viewbox' => true, 'fill' => true, 'aria-hidden' => true, 'focusable' => true],
    'path'   => ['d' => true],
];

/**
 * Imprime los atributos comunes del campo, escapados: required, autocomplete, maxlength y custom_attributes.
 */
$atributos = static function (array $args, string $autocomplete_diseno = ''): void {
    if (!empty($args['required'])) {
        echo ' required aria-required="true"';
    }
    $autocomplete = (string) ($args['autocomplete'] ?? $autocomplete_diseno);
    if ($autocomplete !== '') {
        echo ' autocomplete="' . esc_attr($autocomplete) . '"';
    }
    if (!empty($args['maxlength'])) {
        echo ' maxlength="' . esc_attr((string) absint($args['maxlength'])) . '"';
    }
    foreach ((array) ($args['custom_attributes'] ?? []) as $nombre => $dato) {
        $nombre = (string) preg_replace('/[^a-z0-9_\-:]/i', '', (string) $nombre);
        if ($nombre !== '') {
            echo ' ' . esc_attr($nombre) . '="' . esc_attr((string) $dato) . '"';
        }
    }
};

/**
 * Clases del input: las del diseño, input-text (la validación de Woo la busca) y las del campo.
 */
$clases_input = static function (array $args, string $base) : string {
    return implode(' ', array_unique(array_filter(array_merge(
        [$base],
        array_map('strval', (array) ($args['input_class'] ?? []))
    ))));
};

/**
 * Etiqueta con el asterisco si el campo es requerido.
 */
$etiqueta_html = static function (string $clave, array $args) use ($etiqueta, $asterisco): string {
    $requerido = !empty($args['required']) ? ' ' . $asterisco : '';
    return '<label class="' . esc_attr($etiqueta) . '" for="' . esc_attr($clave) . '">'
        . esc_html((string) ($args['label'] ?? '')) . $requerido . '</label>';
};

/**
 * Un campo de texto del diseño (text, email, password).
 */
$campo_texto = static function (string $clave, array $args, string $tipo, string $autocomplete) use ($valor, $clases_envoltorio, $atributos, $clases_input, $etiqueta_html, $entrada, $html_permitido): void {
    $valor_actual = $tipo === 'password' ? '' : $valor($clave, $args);
    ?>
    <p class="<?= esc_attr($clases_envoltorio($args)) ?>" id="<?= esc_attr($clave . '_field') ?>">
        <?= wp_kses($etiqueta_html($clave, $args), $html_permitido) ?>
        <input class="<?= esc_attr($clases_input($args, $entrada . ' input-text')) ?>" type="<?= esc_attr($tipo) ?>" name="<?= esc_attr($clave) ?>" id="<?= esc_attr($clave) ?>" value="<?= esc_attr($valor_actual) ?>"<?php $atributos($args, $autocomplete); ?>>
    </p>
    <?php
};

/**
 * Opciones de un select con la marcada.
 */
$opciones_html = static function (array $args, string $actual): string {
    $html = '';
    foreach ((array) ($args['options'] ?? []) as $opcion => $texto) {
        $html .= '<option value="' . esc_attr((string) $opcion) . '"' . selected($actual, (string) $opcion, false) . '>' . esc_html((string) $texto) . '</option>';
    }
    return $html;
};

$tiene_prefijo = isset($campos_facturacion['billing_phone_prefijo'], $campos_facturacion['billing_phone']);
?>
<section class="woocommerce-billing-fields flex flex-col gap-[25px] p-6 max-md:px-0" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <h2 id="<?= esc_attr($id_titulo) ?>" class="text-cuerpo-l text-tinta"><?= esc_html__('Datos de facturación', 'angelitozzz') ?></h2>

    <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

    <?php // Una sola grilla: los campos de facturación y los de la cuenta (Correo y Contraseña en la misma fila). ?>
    <div class="grid grid-cols-2 gap-x-[39px] gap-y-[28px] max-md:grid-cols-1">
    <div class="woocommerce-billing-fields__field-wrapper contents">
        <?php
        foreach ($campos_facturacion as $clave => $args) {
            $clave = (string) $clave;
            $args  = (array) $args;

            switch ($clave) {
                case 'billing_first_name':
                    $campo_texto($clave, $args, 'text', 'given-name');
                    break;

                case 'billing_last_name':
                    $campo_texto($clave, $args, 'text', 'family-name');
                    break;

                case 'billing_email':
                    $campo_texto($clave, $args, 'email', 'email');
                    break;

                case 'billing_numero_documento':
                    // Teclado según el tipo elegido: numérico con DNI y RUC; con CE y Pasaporte, letras en mayúscula.
                    // Solo cambia el teclado: el formato lo valida el plugin. documento-teclado.js lo actualiza al cambiar el tipo.
                    $tipo_actual = $valor('billing_tipo_documento', (array) ($campos_facturacion['billing_tipo_documento'] ?? []));
                    $teclado     = [
                        'data-documento-numero' => '',
                        'inputmode'             => in_array($tipo_actual, ['dni', 'ruc'], true) ? 'numeric' : 'text',
                    ];
                    if (in_array($tipo_actual, ['ce', 'pasaporte'], true)) {
                        $teclado['autocapitalize'] = 'characters';
                    }
                    $args['custom_attributes'] = array_merge((array) ($args['custom_attributes'] ?? []), $teclado);
                    $campo_texto($clave, $args, 'text', 'off');
                    break;

                case 'billing_phone_prefijo':
                    // Va dentro del campo del celular; solo, si el celular no está.
                    if (!$tiene_prefijo) {
                        woocommerce_form_field($clave, $args, $checkout->get_value($clave));
                    }
                    break;

                case 'billing_phone':
                    $prefijo = $tiene_prefijo ? (array) $campos_facturacion['billing_phone_prefijo'] : [];
                    ?>
                    <p class="<?= esc_attr($clases_envoltorio($args)) ?>" id="billing_phone_field">
                        <?= wp_kses($etiqueta_html($clave, $args), $html_permitido) ?>
                        <?php if ($prefijo) : ?>
                            <span class="relative flex shrink-0 items-center">
                                <select class="<?= esc_attr($clases_input($prefijo, 'cursor-pointer appearance-none bg-transparent pr-3 text-cuerpo-s text-tinta focus:outline-none')) ?>" name="billing_phone_prefijo" id="billing_phone_prefijo" aria-label="<?= esc_attr((string) ($prefijo['label'] ?? __('Código de país', 'angelitozzz'))) ?>"<?php $atributos($prefijo); ?>>
                                    <?= wp_kses($opciones_html($prefijo, $valor('billing_phone_prefijo', $prefijo)), $html_permitido) ?>
                                </select>
                                <svg class="pointer-events-none absolute right-0 size-[7px]" viewBox="0 0 8 5" fill="currentColor" aria-hidden="true" focusable="false"><path d="M0 0h8L4 5z" /></svg>
                            </span>
                        <?php endif; ?>
                        <input class="<?= esc_attr($clases_input($args, $entrada . ' input-text')) ?>" type="tel" name="billing_phone" id="billing_phone" value="<?= esc_attr($valor($clave, $args)) ?>" inputmode="tel"<?php $atributos($args, 'tel-national'); ?>>
                    </p>
                    <?php
                    break;

                case 'billing_tipo_documento':
                    ?>
                    <p class="<?= esc_attr($clases_envoltorio($args, 'relative')) ?>" id="billing_tipo_documento_field">
                        <?= wp_kses($etiqueta_html($clave, $args), $html_permitido) ?>
                        <select class="<?= esc_attr($clases_input($args, 'min-w-0 flex-1 cursor-pointer appearance-none bg-transparent pr-6 text-cuerpo-s text-tinta focus:outline-none')) ?>" name="billing_tipo_documento" id="billing_tipo_documento" data-documento-tipo<?php $atributos($args); ?>>
                            <?= wp_kses($opciones_html($args, $valor($clave, $args)), $html_permitido) ?>
                        </select>
                        <span class="pointer-events-none absolute bottom-[8px] right-0 text-tinta"><?= wp_kses($chevron, $html_permitido) ?></span>
                    </p>
                    <?php
                    break;

                default:
                    woocommerce_form_field($clave, $args, $checkout->get_value($clave));
            }
        }
        ?>
    </div>

    <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>

    <?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
        <div class="woocommerce-account-fields contents">
            <?php if (!$checkout->is_registration_required()) : ?>
                <p class="form-row form-row-wide create-account col-span-full">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox inline-flex cursor-pointer items-center gap-2 text-cuerpo-s text-tinta">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked((true === $checkout->get_value('createaccount') || (true === apply_filters('woocommerce_create_account_default_checked', false))), true); ?> type="checkbox" name="createaccount" value="1">
                        <span><?= esc_html__('¿Crear una cuenta?', 'angelitozzz') ?></span>
                    </label>
                </p>
            <?php endif; ?>

            <?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>

            <?php if ($campos_cuenta) : ?>
                <div class="create-account contents">
                    <?php
                    foreach ($campos_cuenta as $clave => $args) {
                        $clave = (string) $clave;
                        $args  = (array) $args;

                        if ($clave === 'account_password') {
                            $campo_texto($clave, $args, 'password', 'new-password');
                            ?>
                            <div class="col-span-full">
                                <?php get_template_part('template-parts/components/reglas-contrasena', null, ['campo' => 'account_password']); ?>
                            </div>
                            <?php
                            continue;
                        }

                        woocommerce_form_field($clave, $args, $checkout->get_value($clave));
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
        </div>
    <?php endif; ?>
    </div>
</section>
