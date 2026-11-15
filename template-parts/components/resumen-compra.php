<?php

/**
 * Resumen de compra (carrito 4151:10157 y checkout 4156:9961): caja con borde, título, los cursos
 * del carrito y los totales. No llama a WooCommerce: los datos los arma la plantilla de Woo.
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

// Filas de totales dentro del bloque con margen; el total va aparte, con la línea a todo el ancho.
$filas = [];
if ($subtotal !== '') {
    $filas[] = [__('Subtotal:', 'angelitozzz'), $subtotal];
}
if ($descuento !== '') {
    $filas[] = [__('Descuento:', 'angelitozzz'), $descuento];
}
if ($impuesto_monto !== '') {
    $filas[] = [($impuesto_etiqueta !== '' ? $impuesto_etiqueta : __('Impuesto', 'angelitozzz')) . ':', $impuesto_monto];
}
?>
<section class="w-full max-w-[345px] rounded-[12px] border border-[#bcb28a] text-cuerpo-s text-tinta" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <h2 id="<?= esc_attr($id_titulo) ?>" class="border-b border-[#bcb28a] px-6 pb-[9px] pt-[15px] text-center text-cuerpo-xl text-tinta">
        <?= esc_html__('Resumen de compra', 'angelitozzz') ?>
    </h2>

    <div class="mx-[17.5px] divide-y divide-[#bcb28a]">
        <?php if ($items) : ?>
            <ul class="divide-y divide-[#bcb28a]">
                <?php foreach ($items as $item) : ?>
                    <li class="flex items-center justify-between gap-4 py-[10px] pl-[8.5px] pr-[35.5px]">
                        <div class="min-w-0">
                            <p class="flex items-center gap-[5px]">
                                <img class="h-[15px] w-[14px] shrink-0" src="<?= esc_url(get_theme_file_uri('assets/img/compra/resumen-icono-regalo.svg')) ?>" alt="" aria-hidden="true" width="14" height="15">
                                <span><?= esc_html((string) ($item['tipo'] ?? __('Curso', 'angelitozzz'))) ?></span>
                            </p>
                            <p><?= esc_html((string) $item['nombre']) ?></p>
                        </div>
                        <p class="shrink-0 text-right"><?= wp_kses_post((string) ($item['precio'] ?? '')) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($filas) : ?>
            <dl class="divide-y divide-[#bcb28a]">
                <?php foreach ($filas as [$etiqueta, $monto]) : ?>
                    <div class="flex items-center justify-between gap-4 py-[16px] pl-[8.5px] pr-[35.5px]">
                        <dt><?= esc_html($etiqueta) ?></dt>
                        <dd class="text-right"><?= wp_kses_post($monto) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        <?php endif; ?>
    </div>

    <?php if ($total !== '') : ?>
        <dl class="flex items-center justify-between gap-4 border-t border-[#bcb28a] py-[15px] pl-[26px] pr-[53px]">
            <dt><?= esc_html__('Total:', 'angelitozzz') ?></dt>
            <dd class="text-right"><?= wp_kses_post($total) ?></dd>
        </dl>
    <?php endif; ?>
</section>
