<?php

/**
 * Contenido del drawer del carrito "Resumen de compra" (specs/solucion.md → drawer-carrito): lista con scroll,
 * sugerida y pie fijo con el subtotal; o el estado vacío. Lo imprime template-parts/modals/carrito.php y lo
 * devuelve como fragmento inc/woocommerce/carrito.php: la raíz (div[data-carrito-contenido]) se cambia entera
 * después de agregar o quitar, sin recargar (src/js/modules/carrito.js).
 */

defined('ABSPATH') || exit;

$carrito  = angelitozzz_carrito_datos();
$items    = $carrito['items'];
$sugerida = $carrito['sugerida'];
$no_hay   = get_theme_file_uri('assets/img/ayudarte-imagen-not-found.webp');
$ajax     = class_exists('WC_AJAX') ? WC_AJAX::get_endpoint('%%endpoint%%') : '';
// Lo que ya está en el carrito ("id" o "id:regalo"): carrito.js no vuelve a agregarlo (se vende de a uno).
$presentes = implode(',', array_map(static fn($item) => $item['producto_id'] . ($item['regalo'] ? ':regalo' : ''), $items));
?>
<div class="flex min-h-0 flex-1 flex-col aria-busy:opacity-60" data-carrito-contenido data-wc-ajax="<?= esc_attr($ajax) ?>" data-en-carrito="<?= esc_attr($presentes) ?>">
<?php if ($items) : ?>
    <div class="flex min-h-0 flex-1 flex-col gap-4 overflow-y-auto px-7 pb-6 max-md:px-5">
        <ul class="flex flex-col gap-4">
            <?php foreach ($items as $item) : ?>
                <li class="flex items-center gap-[14px]">
                    <div class="size-[72px] shrink-0 overflow-hidden rounded-xl">
                        <?= angelitozzz_image($item['imagen'], [
                            'class'    => 'size-full object-cover',
                            'alt'      => '',
                            'fallback' => $no_hay,
                        ]) ?>
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                        <p class="text-caption text-tinta-suave"><?= esc_html($item['tipo']) ?></p>
                        <p class="text-rotulo text-marca"><?= esc_html($item['nombre']) ?></p>
                        <?php if ($item['acceso'] !== '') : ?>
                            <p class="text-caption text-tinta-suave"><?= esc_html($item['acceso']) ?></p>
                        <?php endif; ?>
                    </div>
                    <p class="shrink-0 text-rotulo text-marca"><?= esc_html($item['precio']) ?></p>
                    <?php // Sin JS, el enlace quita la línea con la URL de Woo (con nonce); con JS, carrito.js lo hace sin recargar. ?>
                    <a class="grid size-8 shrink-0 cursor-pointer place-items-center rounded-full text-tinta-suave hover:bg-fondo focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url($item['quitar']) ?>" role="button" data-carrito-eliminar="<?= esc_attr($item['clave']) ?>">
                        <span class="sr-only"><?= esc_html(sprintf(__('Eliminar %s del carrito', 'angelitozzz'), $item['nombre'])) ?></span>
                        <svg class="size-[18px]" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M2.25 4.5h13.5M14.25 4.5V15a1.5 1.5 0 0 1-1.5 1.5h-7.5a1.5 1.5 0 0 1-1.5-1.5V4.5M6 4.5V3a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 12 3v1.5" /></svg>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($sugerida) : ?>
            <section class="flex flex-col gap-4" aria-labelledby="carrito-sugerida-titulo">
                <h3 id="carrito-sugerida-titulo" class="text-rotulo text-marca"><?= esc_html__('Te puede interesar también', 'angelitozzz') ?></h3>
                <div class="flex items-center gap-[14px] rounded-2xl bg-fondo p-[14px]">
                    <div class="size-16 shrink-0 overflow-hidden rounded-xl">
                        <?= angelitozzz_image($sugerida['imagen'], [
                            'class'    => 'size-full object-cover',
                            'alt'      => '',
                            'fallback' => $no_hay,
                        ]) ?>
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                        <p class="text-rotulo text-marca">
                            <a class="focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url($sugerida['url']) ?>"><?= esc_html($sugerida['nombre']) ?></a>
                        </p>
                        <?php if ($sugerida['detalle'] !== '') : ?>
                            <p class="text-caption text-tinta-suave"><?= esc_html($sugerida['detalle']) ?></p>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="inline-flex shrink-0 cursor-pointer items-center whitespace-nowrap rounded-full border-[1.5px] border-marca py-[14px] pl-[28px] pr-[24px] text-rotulo text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default disabled:opacity-50" data-carrito-agregar data-productos="<?= esc_attr(implode(',', $sugerida['productos'])) ?>">
                        <?= esc_html__('Agregar', 'angelitozzz') ?>
                        <span class="sr-only"><?= esc_html($sugerida['nombre']) ?></span>
                    </button>
                </div>
            </section>
        <?php endif; ?>
    </div>

    <div class="flex shrink-0 flex-col gap-[14px] bg-fondo px-7 py-6 max-md:px-5">
        <p class="flex items-start justify-between text-marca">
            <span class="text-rotulo"><?= esc_html__('Subtotal', 'angelitozzz') ?></span>
            <span class="text-h4"><?= esc_html($carrito['subtotal']) ?></span>
        </p>
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace' => ['title' => __('Ir a pagar', 'angelitozzz'), 'url' => $carrito['checkout']],
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
</div>
