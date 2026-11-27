<?php

/**
 * Checkout · "Método de pago" y términos (specs/checkout.md, bloques 3 y 4), en la columna izquierda,
 * conectado a WooCommerce. Lo imprime form-checkout.php con woocommerce_checkout_payment() dentro del
 * <form name="checkout">, y WooCommerce lo vuelve a pedir por AJAX (update_order_review) para reemplazar
 * el fragmento `.woocommerce-checkout-payment`: por eso todo (caja, pasarelas, términos, ganchos del botón
 * y nonce) va dentro de un solo #payment, como en el original (templates/checkout/payment.php).
 *
 * La caja dice qué tarjetas se aceptan; debajo va lo que dibuje la pasarela (Openpay) con
 * checkout/payment-method.php. Con una sola pasarela no se ve el radio de elegir método (checkout.js lo oculta
 * con count === 1; la clase de la lista lo oculta también antes de que cargue el JS).
 *
 * Diferencia con el original: el botón "Pagar ahora" (#place_order) no está aquí sino en la columna derecha
 * de form-checkout.php, con `form` apuntando al checkout. Los ganchos woocommerce_review_order_before_submit /
 * after_submit y el nonce siguen aquí, dentro del <form>, en su orden.
 *
 * $checkout, $available_gateways, $order_button_text: los pasa WooCommerce.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Angelitozzz
 * @version 10.9.0
 */

defined('ABSPATH') || exit;

if (!wp_doing_ajax()) {
    do_action('woocommerce_review_order_before_payment');
}

$id_titulo  = wp_unique_id('checkout-pago-');
$logos_alt  = __('Visa, Mastercard, American Express y Diners Club', 'angelitozzz');
$pasarelas  = !empty($available_gateways) ? (array) $available_gateways : [];
// Con una sola pasarela no se ve el radio de elegir método ni su <label> (título y logos de la pasarela):
// los logos ya están en la cabecera del paso.
$clase_lista = count($pasarelas) === 1 ? ' [&_input.input-radio]:hidden [&>li>label]:hidden' : '';
?>
<div id="payment" data-paso="pago" class="woocommerce-checkout-payment flex flex-col gap-7 rounded-[12px] border border-[#bcb28a] bg-blanco/60 p-8 max-lg:order-5 max-md:p-5" role="region" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between gap-6 max-md:flex-col max-md:items-start max-md:gap-4">
            <div class="flex flex-col gap-2">
                <?php get_template_part('template-parts/components/paso-titulo', null, ['id' => $id_titulo, 'numero' => 2, 'titulo' => __('Pago', 'angelitozzz')]); ?>
                <p class="text-cuerpo-s text-tinta-suave"><?= esc_html__('Tarjetas de crédito / débito aceptadas', 'angelitozzz') ?></p>
            </div>
            <img class="h-[40px] w-[160px] max-w-full shrink-0" src="<?= esc_url(get_theme_file_uri('assets/img/compra/checkout-logos-tarjetas.svg')) ?>" alt="<?= esc_attr($logos_alt) ?>" width="160" height="40" loading="lazy">
        </div>

        <?php if (WC()->cart && WC()->cart->needs_payment()) : ?>
            <ul class="wc_payment_methods payment_methods methods flex flex-col gap-4 text-cuerpo-s text-tinta<?= esc_attr($clase_lista) ?>" aria-label="<?= esc_attr__('Métodos de pago', 'angelitozzz') ?>">
                <?php
                if ($pasarelas) {
                    foreach ($pasarelas as $gateway) {
                        wc_get_template('checkout/payment-method.php', ['gateway' => $gateway]);
                    }
                } else {
                    echo '<li>';
                    wc_print_notice(
                        apply_filters(
                            'woocommerce_no_available_payment_methods_message',
                            WC()->customer->get_billing_country()
                                ? esc_html__('Lo sentimos, no hay métodos de pago disponibles. Escríbenos si necesitas ayuda o quieres coordinar otra forma de pago.', 'angelitozzz')
                                : esc_html__('Completa tus datos para ver los métodos de pago disponibles.', 'angelitozzz')
                        ),
                        'notice'
                    );
                    echo '</li>';
                }
                ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="form-row place-order border-t border-[#bcb28a]/60 pt-7">
        <noscript>
            <p class="text-cuerpo-s text-tinta">
                <?php
                /* translators: %1$s y %2$s: etiquetas de énfasis. */
                printf(esc_html__('Tu navegador no tiene JavaScript o está desactivado: haz clic en %1$sActualizar totales%2$s antes de pagar. Si no lo haces, el monto cobrado puede ser distinto del que ves arriba.', 'angelitozzz'), '<em>', '</em>');
                ?>
            </p>
            <button type="submit" class="mt-2 cursor-pointer rounded-full bg-neutro px-[18px] py-[2px] text-rotulo text-tinta" name="woocommerce_checkout_update_totals" value="<?= esc_attr__('Actualizar totales', 'angelitozzz') ?>"><?= esc_html__('Actualizar totales', 'angelitozzz') ?></button>
        </noscript>

        <?php wc_get_template('checkout/terms.php'); ?>

        <?php do_action('woocommerce_review_order_before_submit'); ?>

        <?php // El botón "Pagar ahora" (#place_order) está en form-checkout.php, columna derecha, con form="angelitozzz-checkout". ?>

        <?php do_action('woocommerce_review_order_after_submit'); ?>

        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
    </div>
</div>
<?php
if (!wp_doing_ajax()) {
    do_action('woocommerce_review_order_after_payment');
}
