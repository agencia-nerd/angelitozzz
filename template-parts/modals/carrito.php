<?php

/**
 * Drawer del carrito "Resumen de compra" (specs/solucion.md → drawer-carrito; Figma 3752:5054).
 * Panel lateral derecho de 480 px. Se imprime una vez en footer.php (todo el sitio): lo abren el ícono
 * del carrito del header y los botones de compra de las landings. Lista con scroll y pie fijo.
 *
 * F4, solo maquetación: los datos salen de inc/solucion-muestra/carrito.php
 * (woo: mini carrito de WooCommerce con wp-woocommerce en la F5).
 */

defined('ABSPATH') || exit;

$carrito = function_exists('angelitozzz_solucion_muestra_parte') ? angelitozzz_solucion_muestra_parte('carrito') : [];
// woo: WC()->cart

$items    = array_values(array_filter((array) ($carrito['items'] ?? []), 'is_array'));
$sugerida = (array) ($carrito['sugerida'] ?? []);
$subtotal = (string) ($carrito['subtotal'] ?? '');
$checkout = home_url('/finalizar-compra/'); // woo: wc_get_checkout_url()

angelitozzz_modal_start('carrito', [
    'title'        => __('Resumen de compra', 'angelitozzz'),
    'placement'    => 'right',
    'width'        => '480px',
    'mobile'       => 'fullscreen',
    'motion'       => 'slide',
    'header_class' => 'items-center p-7 max-md:p-5',
    'title_class'  => 'text-h4 text-marca',
    'close_class'  => 'm-0 text-marca',
    // La lista hace scroll por dentro; el pie (subtotal y botones) queda fijo abajo.
    'body_class'   => 'flex flex-col overflow-hidden p-0',
]);
?>
<?php if ($items) : ?>
    <div class="flex min-h-0 flex-1 flex-col gap-4 overflow-y-auto px-7 pb-6 max-md:px-5">
        <ul class="flex flex-col gap-4">
            <?php foreach ($items as $item) :
                $nombre = (string) ($item['nombre'] ?? '');
                ?>
                <li class="flex items-center gap-[14px]">
                    <div class="size-[72px] shrink-0 overflow-hidden rounded-xl">
                        <?= angelitozzz_image($item['imagen'] ?? '', [
                            'class'    => 'size-full object-cover',
                            'alt'      => '',
                            'fallback' => get_theme_file_uri('assets/img/ayudarte-imagen-not-found.webp'),
                        ]) ?>
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                        <p class="text-caption text-tinta-suave"><?= esc_html((string) ($item['tipo'] ?? '')) ?></p>
                        <p class="text-rotulo text-marca"><?= esc_html($nombre) ?></p>
                        <?php if (!empty($item['acceso'])) : ?>
                            <p class="text-caption text-tinta-suave"><?= esc_html((string) $item['acceso']) ?></p>
                        <?php endif; ?>
                    </div>
                    <p class="shrink-0 text-rotulo text-marca"><?= esc_html((string) ($item['precio'] ?? '')) ?></p>
                    <?php // woo: enlace o botón de eliminar del mini carrito (wc_get_cart_remove_url / AJAX). ?>
                    <button type="button" class="grid size-8 shrink-0 cursor-pointer place-items-center rounded-full text-tinta-suave hover:bg-fondo focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-carrito-eliminar="<?= esc_attr((string) ($item['clave'] ?? '')) ?>">
                        <span class="sr-only"><?= esc_html(sprintf(__('Eliminar %s del carrito', 'angelitozzz'), $nombre)) ?></span>
                        <svg class="size-[18px]" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M2.25 4.5h13.5M14.25 4.5V15a1.5 1.5 0 0 1-1.5 1.5h-7.5a1.5 1.5 0 0 1-1.5-1.5V4.5M6 4.5V3a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 12 3v1.5" /></svg>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (!empty($sugerida['nombre'])) : ?>
            <section class="flex flex-col gap-4" aria-labelledby="carrito-sugerida-titulo">
                <h3 id="carrito-sugerida-titulo" class="text-rotulo text-marca"><?= esc_html__('Te puede interesar también', 'angelitozzz') ?></h3>
                <div class="flex items-center gap-[14px] rounded-2xl bg-fondo p-[14px]">
                    <div class="size-16 shrink-0 overflow-hidden rounded-xl">
                        <?= angelitozzz_image($sugerida['imagen'] ?? '', [
                            'class'    => 'size-full object-cover',
                            'alt'      => '',
                            'fallback' => get_theme_file_uri('assets/img/ayudarte-imagen-not-found.webp'),
                        ]) ?>
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                        <p class="text-rotulo text-marca">
                            <a class="focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url((string) ($sugerida['url'] ?? '#')) ?>"><?= esc_html((string) $sugerida['nombre']) ?></a>
                        </p>
                        <?php if (!empty($sugerida['detalle'])) : ?>
                            <p class="text-caption text-tinta-suave"><?= esc_html((string) $sugerida['detalle']) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php // woo: agrega la solución sugerida al carrito (F5). ?>
                    <button type="button" class="inline-flex shrink-0 cursor-pointer items-center whitespace-nowrap rounded-full border-[1.5px] border-marca py-[14px] pl-[28px] pr-[24px] text-rotulo text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-carrito-agregar>
                        <?= esc_html__('Agregar', 'angelitozzz') ?>
                        <span class="sr-only"><?= esc_html((string) $sugerida['nombre']) ?></span>
                    </button>
                </div>
            </section>
        <?php endif; ?>
    </div>

    <div class="flex shrink-0 flex-col gap-[14px] bg-fondo px-7 py-6 max-md:px-5">
        <p class="flex items-start justify-between text-marca">
            <span class="text-rotulo"><?= esc_html__('Subtotal', 'angelitozzz') ?></span>
            <span class="text-h4"><?= esc_html($subtotal) ?></span>
        </p>
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace' => ['title' => __('Ir a pagar', 'angelitozzz'), 'url' => $checkout],
            'clase'  => 'w-full justify-center',
        ]); ?>
        <form method="dialog" class="flex justify-center">
            <button class="cursor-pointer text-rotulo text-marca underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca"><?= esc_html__('Seguir comprando', 'angelitozzz') ?></button>
        </form>
    </div>
<?php else : ?>
    <div class="flex flex-1 flex-col items-center justify-center gap-6 px-7 pb-10 text-center max-md:px-5">
        <p class="text-cuerpo-l text-tinta"><?= esc_html__('Tu carrito está vacío.', 'angelitozzz') ?></p>
        <form method="dialog">
            <button class="cursor-pointer text-rotulo text-marca underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca"><?= esc_html__('Seguir comprando', 'angelitozzz') ?></button>
        </form>
    </div>
<?php endif; ?>
<?php
angelitozzz_modal_end();
