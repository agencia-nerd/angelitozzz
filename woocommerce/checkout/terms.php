<?php

/**
 * Checkout · privacidad y términos (specs/checkout.md, bloque 4): el texto de privacidad con el enlace
 * a la política y la casilla requerida de términos. Los enlaces van a las páginas configuradas en Woo
 * (Ajustes → Avanzado → Página de términos; Ajustes → Privacidad), no a la URL del diseño.
 *
 * F4, solo maquetación. Hooks del original (templates/checkout/terms.php) como comentarios `// woo:`.
 */

defined('ABSPATH') || exit;

// woo: if (apply_filters('woocommerce_checkout_show_terms', true) && function_exists('wc_terms_and_conditions_checkbox_enabled')) :
// woo: do_action('woocommerce_checkout_before_terms_and_conditions');

$privacidad = (string) get_privacy_policy_url(); // woo: wc_checkout_privacy_policy_text() arma este texto con [privacy_policy].
$terminos   = function_exists('wc_get_page_permalink') ? (string) wc_get_page_permalink('terms') : ''; // woo: wc_terms_and_conditions_checkbox_text() con [terms].
// Sin página de términos configurada, wc_get_page_permalink() devuelve la portada: no se enlaza.
if ($terminos === home_url('/') || $terminos === trailingslashit(home_url())) {
    $terminos = '';
}

$enlace = static function (string $url, string $texto): string {
    if ($url === '') {
        return esc_html($texto);
    }

    return '<a class="underline underline-offset-2 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="' . esc_url($url) . '">' . esc_html($texto) . '</a>';
};
?>
<div class="woocommerce-terms-and-conditions-wrapper flex flex-col gap-[41px] text-cuerpo-s text-tinta">
    <?php // woo: do_action('woocommerce_checkout_terms_and_conditions'); (imprime el texto de privacidad) ?>
    <p class="woocommerce-privacy-policy-text">
        <?= esc_html__('Tus datos personales se utilizarán para procesar tu pedido, mejorar tu experiencia en esta web y otros propósitos descritos en nuestra', 'angelitozzz') ?>
        <?= $enlace($privacidad, __('política de privacidad', 'angelitozzz')) // Escapado en $enlace. ?>.
    </p>

    <?php // woo: if (wc_terms_and_conditions_checkbox_enabled()) : ?>
    <p class="validate-required">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox inline-flex cursor-pointer items-start gap-1">
            <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mt-[5px] size-[14px] shrink-0 cursor-pointer appearance-none border border-tinta bg-transparent checked:bg-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" name="terms" id="terms" required>
            <span>
                <?= esc_html__('He leído y estoy de acuerdo con los', 'angelitozzz') ?>
                <?= $enlace($terminos, __('términos y condiciones', 'angelitozzz')) ?>
                <?= esc_html__('de la web', 'angelitozzz') ?>
                <abbr class="no-underline" title="<?= esc_attr__('obligatorio', 'angelitozzz') ?>">*</abbr>
            </span>
        </label>
        <input type="hidden" name="terms-field" value="1">
    </p>
    <?php // woo: endif; ?>
</div>
<?php
// woo: do_action('woocommerce_checkout_after_terms_and_conditions');
// woo: endif;
