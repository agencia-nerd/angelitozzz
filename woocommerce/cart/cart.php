<?php

/**
 * Carrito (specs/carrito.md, sección carrito-de-compras; Figma 4151:10148).
 *
 * Sobrescribe woocommerce/templates/cart/cart.php con el diseño y el carrito real de WooCommerce
 * (WC()->cart). Conserva todos los do_action() del original, en su orden, y los filtros de cada línea.
 *
 * Sin cantidad ni "Actualizar carrito" (cada curso se vende una vez) y sin el campo de cupón (va en la
 * landing y en el checkout; el do_action('woocommerce_cart_coupon') se queda). La papelera es el enlace de
 * Woo (wc_get_cart_remove_url, con nonce) y recarga la página: no lleva .product-remove porque el quitar por
 * AJAX de cart.js de Woo solo actualiza .cart_totals, que el diseño no usa.
 * El resumen es el componente resumen-compra; woocommerce_cart_totals y las ventas cruzadas se quitan de
 * woocommerce_cart_collaterals en inc/woocommerce/plantillas.php. La plantilla no imprime <main>: lo pone la página.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 11.3.0
 */

defined('ABSPATH') || exit;

$regalo_icono = get_theme_file_uri('assets/img/compra/carrito-icono-regalo.svg');
$sin_imagen   = get_theme_file_uri('assets/img/ayudarte-imagen-not-found.webp');
$resumen      = [];
?>
<div class="mx-auto max-w-[1129px] px-4 py-[120px] max-md:py-14">
    <h1 class="text-h1 text-marca max-md:text-h3"><?= esc_html__('Carrito de compras', 'angelitozzz') ?></h1>

    <?php do_action('woocommerce_before_cart'); ?>

    <div class="mt-9 flex items-start justify-between gap-[145px] max-lg:flex-col max-lg:items-stretch max-lg:gap-12">
        <form class="woocommerce-cart-form w-full max-w-[607px] max-lg:max-w-none" action="<?= esc_url(wc_get_cart_url()) ?>" method="post">
            <?php do_action('woocommerce_before_cart_table'); ?>

            <ul class="woocommerce-cart-form__contents flex flex-col gap-7">
                <?php do_action('woocommerce_before_cart_contents'); ?>

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                    $visible    = apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key);

                    if (!($_product instanceof WC_Product && $_product->exists() && $cart_item['quantity'] > 0 && $visible)) {
                        continue;
                    }

                    $cart_item_name = WC()->cart->get_item_product_name($cart_item, $_product);
                    $product_name   = apply_filters('woocommerce_cart_item_name', $cart_item_name, $cart_item, $cart_item_key);
                    // El producto no tiene ficha (inc/tienda.php la redirige): sin enlace salvo que un plugin lo dé.
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', '', $cart_item, $cart_item_key);

                    $datos = function_exists('angelitozzz_cursos_datos_producto')
                        ? angelitozzz_cursos_datos_producto((int) $_product->get_id())
                        : ['curso_id' => 0, 'meses' => 0, 'acceso_texto' => ''];
                    $es_regalo = function_exists('angelitozzz_cursos_linea_carrito_es_regalo')
                        && angelitozzz_cursos_linea_carrito_es_regalo($cart_item);
                    $imagen_id = $datos['curso_id'] ? (int) get_post_thumbnail_id($datos['curso_id']) : 0;

                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', angelitozzz_image($imagen_id ?: (int) $_product->get_image_id(), [
                        'size'     => 'thumbnail',
                        'class'    => 'size-full object-cover',
                        'alt'      => '',
                        'fallback' => $sin_imagen,
                    ]), $cart_item, $cart_item_key);

                    $precio = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                    $nombre_texto = wp_strip_all_tags((string) $product_name);

                    $resumen[] = [
                        'tipo'   => $es_regalo ? __('Curso de regalo', 'angelitozzz') : __('Curso', 'angelitozzz'),
                        'nombre' => $nombre_texto,
                        'precio' => (string) $precio,
                    ];
                    ?>
                    <li class="woocommerce-cart-form__cart-item <?= esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)) ?> flex items-start gap-[17px] border-b border-[#bcb28a] pb-7">
                        <div class="mt-1 size-[68px] shrink-0 overflow-hidden rounded-[12px]">
                            <?= wp_kses_post($thumbnail) ?>
                        </div>

                        <div class="min-w-0 flex-1 text-cuerpo-s">
                            <p class="flex items-center gap-[6px] text-marca">
                                <img class="h-[19px] w-[18px] shrink-0" src="<?= esc_url($regalo_icono) ?>" alt="" aria-hidden="true" width="18" height="19">
                                <span><?= esc_html(trim(__('Curso', 'angelitozzz') . ' · ' . (string) $datos['acceso_texto'], ' ·')) ?></span>
                            </p>
                            <p class="text-tinta">
                                <?php if ($product_permalink) : ?>
                                    <?= wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $cart_item_name), $cart_item, $cart_item_key)) ?>
                                <?php else : ?>
                                    <?= wp_kses_post($product_name) ?>
                                <?php endif; ?>
                            </p>
                            <?php
                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                            // Datos de la línea (p. ej. "Compra: Regalo", del plugin angelitozzz-cursos).
                            echo wp_kses_post(wc_get_formatted_cart_item_data($cart_item, false, $cart_item_name));

                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id, $cart_item, $cart_item_key));
                            }
                            ?>
                        </div>

                        <p class="mt-[7px] shrink-0 text-cuerpo-s text-tinta"><?= wp_kses_post($precio) ?></p>

                        <?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- cada parte se escapa en el sprintf, como en el original.
                        echo apply_filters(
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a role="button" href="%s" class="remove grid size-8 shrink-0 place-items-center rounded-full text-tinta-suave hover:bg-neutro/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-product_id="%s" data-product_sku="%s"><span class="sr-only">%s</span>%s</a>',
                                esc_url(wc_get_cart_remove_url($cart_item_key)),
                                esc_attr($product_id),
                                esc_attr($_product->get_sku()),
                                /* translators: %s: nombre del curso. */
                                esc_html(sprintf(__('Quitar %s', 'angelitozzz'), $nombre_texto)),
                                '<svg class="size-[18px]" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M2.25 4.5h13.5M14.25 4.5V15a1.5 1.5 0 0 1-1.5 1.5h-7.5a1.5 1.5 0 0 1-1.5-1.5V4.5M6 4.5V3a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 12 3v1.5" /></svg>'
                            ),
                            $cart_item_key
                        );
                        ?>
                    </li>
                <?php endforeach; ?>

                <?php do_action('woocommerce_cart_contents'); ?>
            </ul>

            <?php
            // Sin el campo de cupón del original: el gancho se queda para los plugins.
            if (wc_coupons_enabled()) {
                do_action('woocommerce_cart_coupon');
            }

            do_action('woocommerce_cart_actions');

            wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce');

            do_action('woocommerce_after_cart_contents');
            ?>

            <?php do_action('woocommerce_after_cart_table'); ?>
        </form>

        <?php do_action('woocommerce_before_cart_collaterals'); ?>

        <div class="cart-collaterals flex w-full max-w-[345px] shrink-0 flex-col items-center gap-[22px] max-lg:max-w-none">
            <?php
            do_action('woocommerce_cart_collaterals');

            // Descuento: cupones y cargos negativos (p. ej. el descuento por etapas de inc/woocommerce/descuentos.php).
            $descuento = (float) WC()->cart->get_discount_total();
            foreach (WC()->cart->get_fees() as $cargo) {
                if ((float) $cargo->total < 0) {
                    $descuento += abs((float) $cargo->total);
                }
            }

            $impuesto = [];
            if (wc_tax_enabled() && (float) WC()->cart->get_taxes_total() > 0) {
                $impuesto = [
                    'etiqueta' => WC()->countries->tax_or_vat(),
                    'monto'    => wc_price((float) WC()->cart->get_taxes_total()),
                ];
            }

            get_template_part('template-parts/components/resumen-compra', null, [
                'items'     => $resumen,
                'subtotal'  => WC()->cart->get_cart_subtotal(),
                'descuento' => $descuento > 0 ? '-' . wc_price($descuento) : '',
                'impuesto'  => $impuesto,
                'total'     => WC()->cart->get_total(),
            ]);

            get_template_part('template-parts/components/boton', null, [
                'enlace'   => ['url' => wc_get_checkout_url(), 'title' => __('Ir a pagar', 'angelitozzz')],
                'variante' => 'frambuesa',
                'clase'    => 'max-lg:w-full max-lg:justify-center',
            ]);
            ?>
        </div>
    </div>

    <?php do_action('woocommerce_after_cart'); ?>
</div>
