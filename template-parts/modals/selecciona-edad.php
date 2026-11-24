<?php

/**
 * Modal "Selecciona la edad de tu bebé" (specs/solucion.md → modal-selecciona-edad; Figma 3653:2355).
 * Solo con solucion_compra_tipo = etapas: lo abren el botón de compra del hero y el de la galería.
 * Una casilla por etapa del bloque `etapas`; src/js/modules/solucion-compra.js calcula el total
 * (precio de cada curso, o el total de solucion_compra_descuentos si coincide la cantidad) y el ahorro.
 * Cada casilla lleva el ID del producto de Woo de su etapa: "Agregar al carrito" agrega los marcados, cierra este
 * modal y abre el drawer (carrito.js); el descuento lo aplica el carrito (inc/woocommerce/descuentos.php).
 * Una etapa sin curso a la venta se ve con la casilla desactivada y "En preparación"; si ninguna se vende, el
 * modal no se imprime (la landing muestra "Próximamente").
 *
 * $args: etapas (items del bloque `etapas`), compra (grupo solucion_compra), contexto.
 */

defined('ABSPATH') || exit;

$etapas = array_values(array_filter((array) ($args['etapas'] ?? []), 'is_array'));
$compra = (array) ($args['compra'] ?? []);

if (!$etapas) {
    return;
}

// Escala de descuento por cantidad: {"2": 67, "3": 97}.
$descuentos = [];
foreach ((array) ($compra['descuentos'] ?? []) as $fila) {
    $cantidad = (int) ($fila['cantidad'] ?? 0);
    $total    = (float) ($fila['total'] ?? 0);
    if ($cantidad > 1 && $total > 0) {
        $descuentos[$cantidad] = $total;
    }
}

// El precio y el producto de cada etapa los pone single-solucion.php desde Woo. Sin ninguna a la venta, no hay modal.
if (!array_filter($etapas, static fn($etapa): bool => !empty($etapa['producto_id']))) {
    return;
}

$estrellas = get_theme_file_uri('assets/img/solucion/selecciona-edad-estrellas.svg');

angelitozzz_modal_start('selecciona-edad', [
    'title'             => __('Selecciona la edad de tu bebé', 'angelitozzz'),
    'description'       => __('Por la compra de dos o tres cursos, consigue un precio especial.', 'angelitozzz'),
    'placement'         => 'center',
    'width'             => '568px',
    'motion'            => 'scale',
    'class'             => 'rounded-media bg-fondo',
    'header_class'      => 'px-8 pt-[27px] max-md:px-5',
    'title_class'       => 'text-cuerpo-xl text-marca',
    'description_class' => 'mx-8 mt-3 text-cuerpo-s text-tinta max-md:mx-5',
    'close_class'       => 'text-tinta',
    'body_class'        => 'px-8 pb-[37px] pt-[49px] max-md:px-5',
]);
?>
<div data-solucion-edades data-descuentos="<?= esc_attr(wp_json_encode((object) $descuentos)) ?>">
    <fieldset>
        <legend class="sr-only"><?= esc_html__('Etapas', 'angelitozzz') ?></legend>
        <ul class="flex flex-col gap-7">
            <?php foreach ($etapas as $n => $etapa) :
                $id_casilla = 'selecciona-edad-' . ($n + 1);
                $a_la_venta = !empty($etapa['producto_id']);
                ?>
                <li>
                    <label class="<?= esc_attr('flex items-center gap-[19px] ' . ($a_la_venta ? 'cursor-pointer' : 'cursor-default opacity-50')) ?>" for="<?= esc_attr($id_casilla) ?>">
                        <span class="relative grid size-6 shrink-0 place-items-center">
                            <input class="peer size-6 cursor-pointer appearance-none rounded-lg border border-tinta bg-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default" type="checkbox" id="<?= esc_attr($id_casilla) ?>" name="etapas[]" value="<?= esc_attr((string) (int) ($etapa['producto_id'] ?? 0)) ?>" data-precio="<?= esc_attr((string) (float) ($etapa['precio_numero'] ?? 0)) ?>"<?= $a_la_venta ? '' : ' disabled' ?>>
                            <svg class="pointer-events-none absolute hidden h-[13px] w-[14px] text-tinta peer-checked:block" viewBox="0 0 13.75 12.92" fill="currentColor" aria-hidden="true" focusable="false"><path d="M13.28.17c.48.31.62.96.3 1.44L6.5 12.44a1.04 1.04 0 0 1-1.6.17L.31 8.03a1.04 1.04 0 1 1 1.47-1.47l3.68 3.68L11.84.47a1.04 1.04 0 0 1 1.44-.3" /></svg>
                        </span>
                        <span class="relative h-[74px] w-[90px] shrink-0 overflow-hidden rounded-xl" aria-hidden="true">
                            <?= angelitozzz_image($etapa['imagen'] ?? '', ['class' => 'size-full object-cover', 'alt' => '']) ?>
                            <img class="absolute inset-0 size-full" src="<?= esc_url($estrellas) ?>" alt="" width="90" height="74" loading="lazy">
                        </span>
                        <span class="flex flex-col text-cuerpo-s text-tinta">
                            <span><?= esc_html((string) ($etapa['edad'] ?? '')) ?></span>
                            <?php if (!$a_la_venta) : ?>
                                <span><?= esc_html__('En preparación', 'angelitozzz') ?></span>
                            <?php endif; ?>
                        </span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>

    <div class="mt-9 border-t border-tinta pt-[13px]">
        <div class="ml-auto flex w-[188px] flex-col items-stretch gap-1 max-md:w-full" aria-live="polite">
            <p class="flex items-baseline justify-between gap-4">
                <span class="text-cuerpo text-tinta"><?= esc_html__('Total:', 'angelitozzz') ?></span>
                <span class="text-cuerpo-xl text-tinta" data-solucion-total>$0.00</span>
            </p>
            <p class="rounded-[2px] bg-[#e9e4da] py-[3px] text-center text-cuerpo-s text-tinta" data-solucion-ahorro hidden></p>
        </div>
    </div>

    <div class="mt-5 flex justify-center">
        <button type="button" class="inline-flex cursor-pointer items-center gap-[10px] whitespace-nowrap rounded-full bg-acento py-[14px] pl-[28px] pr-[24px] text-rotulo text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default disabled:opacity-50" data-solucion-agregar disabled>
            <span><?= esc_html__('Agregar al carrito', 'angelitozzz') ?></span>
            <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
        </button>
    </div>
</div>
<?php
angelitozzz_modal_end();
