<?php

/**
 * Carrito (specs/carrito.md, sección carrito-de-compras; Figma 4151:10148).
 *
 * Sobrescribe woocommerce/templates/cart/cart.php. F4, solo maquetación: los datos salen de
 * inc/compra-muestra.php y cada hook del original queda como `// woo:` en su lugar y orden.
 * wp-woocommerce (F5) cambia los marcadores por las funciones de Woo, conserva todos los
 * do_action() y pone el @version del original.
 *
 * Sin cantidad ni "Actualizar carrito" (cada curso se vende una vez) y sin cupón (va en la landing
 * y en el checkout). La plantilla no imprime <main>: lo pone la página.
 */

defined('ABSPATH') || exit;

$carrito = angelitozzz_compra_muestra_carrito(); // woo: WC()->cart
$items   = (array) ($carrito['items'] ?? []);
$pagar   = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/finalizar-compra/');
$accion  = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/carrito/');
$regalo  = get_theme_file_uri('assets/img/compra/carrito-icono-regalo.svg');
?>
<div class="mx-auto max-w-[1129px] px-4 py-[120px] max-md:py-14">
    <h1 class="text-h1 text-marca max-md:text-h3"><?= esc_html__('Carrito de compras', 'angelitozzz') ?></h1>

    <?php // woo: do_action('woocommerce_before_cart') — imprime los avisos (woocommerce_output_all_notices). ?>

    <div class="mt-9 flex items-start justify-between gap-[145px] max-lg:flex-col max-lg:items-stretch max-lg:gap-12">
        <form class="woocommerce-cart-form w-full max-w-[607px] max-lg:max-w-none" action="<?= esc_url($accion) ?>" method="post">
            <?php // woo: do_action('woocommerce_before_cart_table') ?>

            <ul class="woocommerce-cart-form__contents flex flex-col gap-7">
                <?php // woo: do_action('woocommerce_before_cart_contents') ?>

                <?php
                // woo: foreach WC()->cart->get_cart() con los filtros del original (woocommerce_cart_item_product,
                // _product_id, _visible, _name, _permalink, _class, _thumbnail, _remove_link, _subtotal).
                foreach ($items as $item) :
                    $nombre = (string) ($item['nombre'] ?? '');
                    ?>
                    <li class="woocommerce-cart-form__cart-item cart_item flex items-start gap-[17px] border-b border-[#bcb28a] pb-7">
                        <div class="mt-1 size-[68px] shrink-0 overflow-hidden rounded-[12px]">
                            <?= angelitozzz_image($item['imagen'] ?? '', [ // woo: woocommerce_cart_item_thumbnail
                                'class'    => 'size-full object-cover',
                                'alt'      => '',
                                'fallback' => get_theme_file_uri('assets/img/ayudarte-imagen-not-found.webp'),
                            ]) ?>
                        </div>

                        <div class="min-w-0 flex-1 text-cuerpo-s">
                            <p class="flex items-center gap-[6px] text-marca">
                                <img class="h-[19px] w-[18px] shrink-0" src="<?= esc_url($regalo) ?>" alt="" aria-hidden="true" width="18" height="19">
                                <span><?= esc_html(trim((string) ($item['tipo'] ?? __('Curso', 'angelitozzz')) . ' · ' . (string) ($item['acceso'] ?? ''), ' ·')) ?></span>
                            </p>
                            <p class="text-tinta"><?= esc_html($nombre) ?></p>
                            <?php // woo: do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key) ?>
                            <?php // woo: wc_get_formatted_cart_item_data($cart_item) (datos del ítem, p. ej. "Regalo") ?>
                        </div>

                        <p class="mt-[7px] shrink-0 text-cuerpo-s text-tinta"><?= wp_kses_post((string) ($item['precio'] ?? '')) ?></p>

                        <?php // woo: woocommerce_cart_item_remove_link (wc_get_cart_remove_url($cart_item_key), data-product_id, data-product_sku) ?>
                        <a class="remove grid size-8 shrink-0 place-items-center rounded-full text-tinta-suave hover:bg-neutro/40 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url((string) ($item['quitar'] ?? '#')) ?>">
                            <span class="sr-only"><?= esc_html(sprintf(__('Quitar %s', 'angelitozzz'), $nombre)) ?></span>
                            <svg class="size-[18px]" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M2.25 4.5h13.5M14.25 4.5V15a1.5 1.5 0 0 1-1.5 1.5h-7.5a1.5 1.5 0 0 1-1.5-1.5V4.5M6 4.5V3a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 12 3v1.5" /></svg>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php // woo: do_action('woocommerce_cart_contents') ?>
            </ul>

            <?php
            // woo: do_action('woocommerce_cart_coupon') — sin el formulario de cupón del original (el cupón va en la landing y el checkout).
            // woo: do_action('woocommerce_cart_actions')
            // woo: wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce')
            // woo: do_action('woocommerce_after_cart_contents')
            ?>

            <?php // woo: do_action('woocommerce_after_cart_table') ?>
        </form>

        <?php // woo: do_action('woocommerce_before_cart_collaterals') ?>

        <div class="cart-collaterals flex w-full max-w-[345px] shrink-0 flex-col items-center gap-[22px] max-lg:max-w-none">
            <?php
            // woo: do_action('woocommerce_cart_collaterals') — el resumen reemplaza a woocommerce_cart_totals
            // (wp-woocommerce lo quita del hook y conserva el do_action para los demás plugins).
            get_template_part('template-parts/components/resumen-compra', null, [
                'items'     => array_map(static fn(array $item): array => [
                    'tipo'   => (string) ($item['tipo'] ?? __('Curso', 'angelitozzz')),
                    'nombre' => (string) ($item['nombre'] ?? ''),
                    'precio' => (string) ($item['precio'] ?? ''),
                ], $items),
                'subtotal'  => (string) ($carrito['subtotal'] ?? ''),
                'descuento' => (string) ($carrito['descuento'] ?? ''), // woo: solo con cupón aplicado
                'impuesto'  => (array) ($carrito['impuesto'] ?? []),   // woo: solo con impuestos (IGV)
                'total'     => (string) ($carrito['total'] ?? ''),
            ]);

            get_template_part('template-parts/components/boton', null, [
                'enlace'   => ['url' => $pagar, 'title' => __('Ir a pagar', 'angelitozzz')],
                'variante' => 'frambuesa',
                'clase'    => 'max-lg:w-full max-lg:justify-center',
            ]);
            ?>
        </div>
    </div>

    <?php // woo: do_action('woocommerce_after_cart') ?>
</div>
