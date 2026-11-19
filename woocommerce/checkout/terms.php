<?php

/**
 * Checkout · privacidad y términos (specs/checkout.md, bloque 4), conectado a WooCommerce: el texto de
 * privacidad con el enlace a la política y la casilla requerida de términos.
 *
 * Los textos son los de WooCommerce, no fijos del tema, para no duplicarlos y que se editen donde Woo los
 * guarda: el de privacidad lo imprime woocommerce_checkout_terms_and_conditions (wc_checkout_privacy_policy_text,
 * Ajustes → Cuentas y privacidad) y el de la casilla, wc_terms_and_conditions_checkbox_text() (Personalizar →
 * WooCommerce → Finalizar compra). En español, los textos por defecto de Woo son los del diseño. Los enlaces
 * van a las páginas configuradas en Woo (Ajustes → Avanzado → Página de términos; Ajustes → Privacidad);
 * sin página configurada, el texto sale sin enlace. El estilo de los enlaces va en las clases del envoltorio.
 *
 * Conserva la estructura y los do_action() del original (templates/checkout/terms.php).
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package Angelitozzz
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

if (apply_filters('woocommerce_checkout_show_terms', true) && function_exists('wc_terms_and_conditions_checkbox_enabled')) {
    do_action('woocommerce_checkout_before_terms_and_conditions');

    ?>
    <div class="woocommerce-terms-and-conditions-wrapper flex flex-col gap-[41px] text-cuerpo-s text-tinta [&_a]:underline [&_a]:underline-offset-2 [&_a:focus-visible]:outline-2 [&_a:focus-visible]:outline-offset-2 [&_a:focus-visible]:outline-marca">
        <?php
        /*
         * Texto de privacidad (wc_checkout_privacy_policy_text, prioridad 20) y contenido de la página de
         * términos plegado (wc_terms_and_conditions_page_content, prioridad 30).
         */
        do_action('woocommerce_checkout_terms_and_conditions');
        ?>

        <?php if (wc_terms_and_conditions_checkbox_enabled()) : ?>
            <p class="form-row validate-required">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox inline-flex cursor-pointer items-start gap-1">
                    <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mt-[5px] size-[14px] shrink-0 cursor-pointer appearance-none border border-tinta bg-transparent checked:bg-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" name="terms" <?php checked(apply_filters('woocommerce_terms_is_checked_default', isset($_POST['terms'])), true); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Solo se mira si vino marcada para volver a pintarla; WC_Checkout::process_checkout() verifica el nonce. ?> id="terms" required aria-required="true">
                    <span>
                        <span class="woocommerce-terms-and-conditions-checkbox-text"><?php wc_terms_and_conditions_checkbox_text(); ?></span>
                        <abbr class="required no-underline" title="<?= esc_attr__('obligatorio', 'angelitozzz') ?>">*</abbr>
                    </span>
                </label>
                <input type="hidden" name="terms-field" value="1">
            </p>
        <?php endif; ?>
    </div>
    <?php

    do_action('woocommerce_checkout_after_terms_and_conditions');
}
