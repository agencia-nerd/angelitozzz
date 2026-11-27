<?php

/**
 * Resumen de compra (carrito 4151:10157 y checkout 4156:9961; rediseño del 2026-11-27): tarjeta como las del
 * checkout por pasos, título a la izquierda, los cursos con su ícono, las filas sin dos puntos y el total destacado;
 * no llama a WooCommerce: los datos los arma la plantilla de Woo.
 *
 * $args:
 * - items     (array de ['tipo' => 'Curso', 'nombre' => string, 'precio' => string ya formateado], requerido)
 * - subtotal  (string ya formateado, requerido)
 * - descuento (string ya formateado con su signo, opcional: sin valor no se imprime la fila)
 * - impuesto  (array ['etiqueta' => 'IGV', 'monto' => string], opcional: sin monto no se imprime la fila)
 * - total     (string ya formateado, requerido)
 *
 * Los montos pueden traer el HTML de precio de Woo (wc_price): se imprimen con wp_kses_post().
 */

defined('ABSPATH') || exit;

$items = array_values(array_filter(
    (array) ($args['items'] ?? []),
    static fn($item): bool => is_array($item) && trim((string) ($item['nombre'] ?? '')) !== ''
));
$subtotal  = trim((string) ($args['subtotal'] ?? ''));
$descuento = trim((string) ($args['descuento'] ?? ''));
$impuesto  = (array) ($args['impuesto'] ?? []);
$impuesto_etiqueta = trim((string) ($impuesto['etiqueta'] ?? ''));
$impuesto_monto    = trim((string) ($impuesto['monto'] ?? ''));
$total     = trim((string) ($args['total'] ?? ''));

$id_titulo = wp_unique_id('resumen-compra-');

// Filas de totales (sin dos puntos: etiqueta a la izquierda, monto a la derecha); el total va aparte, destacado.
$filas = [];
if ($subtotal !== '') {
    $filas[] = [__('Subtotal', 'angelitozzz'), $subtotal, false];
}
if ($descuento !== '') {
    $filas[] = [__('Descuento', 'angelitozzz'), $descuento, true];
}
if ($impuesto_monto !== '') {
    $filas[] = [$impuesto_etiqueta !== '' ? $impuesto_etiqueta : __('Impuesto', 'angelitozzz'), $impuesto_monto, false];
}
?>
<section class="flex w-full max-w-[345px] flex-col gap-5 rounded-[12px] border border-[#bcb28a] bg-blanco/60 p-6 text-cuerpo-s text-tinta" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <h2 id="<?= esc_attr($id_titulo) ?>" class="text-h5 text-marca"><?= esc_html__('Resumen de compra', 'angelitozzz') ?></h2>

    <?php if ($items) : ?>
        <ul class="flex flex-col gap-3">
            <?php foreach ($items as $item) : ?>
                <li class="flex items-start gap-3">
                    <?php // Ícono del curso en un círculo suave (decorativo). ?>
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-fondo-rosa/60" aria-hidden="true">
                        <img class="h-[15px] w-[14px]" src="<?= esc_url(get_theme_file_uri('assets/img/compra/resumen-icono-regalo.svg')) ?>" alt="" width="14" height="15">
                    </span>
                    <div class="flex min-w-0 flex-1 flex-col">
                        <span class="text-caption text-tinta-suave"><?= esc_html((string) ($item['tipo'] ?? __('Curso', 'angelitozzz'))) ?></span>
                        <span class="text-cuerpo-s text-tinta"><?= esc_html((string) $item['nombre']) ?></span>
                    </div>
                    <span class="shrink-0 text-right text-cuerpo-s text-tinta"><?= wp_kses_post((string) ($item['precio'] ?? '')) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($filas) : ?>
        <dl class="flex flex-col gap-2 border-t border-[#bcb28a]/60 pt-4">
            <?php foreach ($filas as [$etiqueta, $monto, $es_descuento]) : ?>
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-tinta-suave"><?= esc_html($etiqueta) ?></dt>
                    <dd class="<?= esc_attr($es_descuento ? 'text-right text-marca' : 'text-right') ?>"><?= wp_kses_post($monto) ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
    <?php endif; ?>

    <?php if ($total !== '') : ?>
        <dl class="flex items-baseline justify-between gap-4 border-t border-[#bcb28a]/60 pt-4">
            <dt class="text-cuerpo text-tinta"><?= esc_html__('Total', 'angelitozzz') ?></dt>
            <dd class="text-right text-h5 text-marca"><?= wp_kses_post($total) ?></dd>
        </dl>
    <?php endif; ?>
</section>
