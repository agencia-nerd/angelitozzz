<?php

/**
 * Modal "Más información" de una etapa (specs/solucion.md → modal-etapa; Figma 3752:5964).
 * Uno por etapa (id etapa-<n>): portada del video con play (el iframe de Vimeo se carga al pulsar y se
 * quita al cerrar: video-portada.js), antetítulo, título, texto, precio del curso con la escala de
 * descuento y "Agregar al carrito" (agrega el curso de la etapa, cierra este modal y abre el drawer: carrito.js).
 * Una etapa sin curso a la venta muestra "En preparación" desactivado, sin precio ni escala.
 *
 * El diseño no tiene encabezado: el título del modal (nombre accesible) se imprime oculto y se repite
 * visible bajo la portada; el botón de cerrar flota sobre la portada.
 *
 * $args: id, etapa (ítem del bloque `etapas`), antetitulo, compra (grupo solucion_compra).
 */

defined('ABSPATH') || exit;

$id         = (string) ($args['id'] ?? '');
$etapa      = (array) ($args['etapa'] ?? []);
$antetitulo = (string) ($args['antetitulo'] ?? '');
$compra     = (array) ($args['compra'] ?? []);

$titulo  = trim((string) ($etapa['modal_titulo'] ?? ''));
$texto   = (string) ($etapa['modal_texto'] ?? '');
$portada = $etapa['modal_portada'] ?? '';
$video   = (string) ($etapa['modal_video'] ?? '');
$precio  = (string) ($etapa['precio'] ?? ''); // Del producto de Woo del curso (single-solucion.php).
$producto = (int) ($etapa['producto_id'] ?? 0);

if ($id === '' || $titulo === '') {
    return;
}

// Escala de descuento: "2 cursos $67 · 3 cursos $97" (de solucion_compra_descuentos). Solo si la etapa se vende.
$escala = [];
foreach ($producto ? (array) ($compra['descuentos'] ?? []) : [] as $fila) {
    $cantidad = (int) ($fila['cantidad'] ?? 0);
    $total    = (float) ($fila['total'] ?? 0);
    if ($cantidad > 1 && $total > 0) {
        /* translators: 1: cantidad de cursos, 2: precio total. */
        $escala[] = sprintf(__('%1$d cursos $%2$s', 'angelitozzz'), $cantidad, number_format_i18n($total, floor($total) == $total ? 0 : 2));
    }
}

$con_portada = !empty($portada);

angelitozzz_modal_start($id, [
    'title'        => $titulo,
    'title_hidden' => true,
    'placement'    => 'center',
    'width'        => '620px',
    'motion'       => 'scale',
    'class'        => 'relative overflow-hidden rounded-tarjeta',
    'header_class' => 'absolute right-4 top-4 z-10 p-0',
    'close_class'  => 'm-0 bg-blanco text-marca hover:bg-fondo',
    'body_class'   => 'p-0',
]);
?>
<?php if ($con_portada) : ?>
    <?php get_template_part('template-parts/components/video-portada', null, [
        'imagen' => $portada,
        'video'  => $video,
        'titulo' => $titulo,
        'clase'  => 'h-80 w-full max-md:h-auto max-md:aspect-[620/320]',
    ]); ?>
<?php endif; ?>

<div class="<?= esc_attr('flex flex-col gap-3 px-8 pb-7 max-md:px-5 ' . ($con_portada ? 'pt-7' : 'pt-16')) ?>">
    <?php if ($antetitulo !== '') : ?>
        <p class="text-caption text-tinta-suave"><?= esc_html($antetitulo) ?></p>
    <?php endif; ?>
    <p class="text-h3 text-marca max-md:text-h4" aria-hidden="true"><?= esc_html($titulo) ?></p>
    <?php if (trim($texto) !== '') : ?>
        <p class="text-cuerpo text-tinta"><?= angelitozzz_highlight($texto) ?></p>
    <?php endif; ?>

    <div class="flex items-center justify-between gap-4 pt-2 max-md:flex-col max-md:items-start">
        <div class="flex flex-col gap-0.5">
            <?php if ($precio !== '') : ?>
                <?php /* translators: %s: precio, p. ej. "$37". */ ?>
                <p class="text-h4 text-marca"><?= esc_html(sprintf(__('%s USD', 'angelitozzz'), $precio)) ?></p>
            <?php endif; ?>
            <?php if ($escala) : ?>
                <p class="text-caption text-tinta-suave"><?= esc_html(implode(' · ', $escala)) ?></p>
            <?php endif; ?>
        </div>
        <button type="button" class="inline-flex shrink-0 cursor-pointer items-center gap-[10px] whitespace-nowrap rounded-full bg-acento py-[14px] pl-[28px] pr-[24px] text-rotulo text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default disabled:opacity-50" data-solucion-agregar data-productos="<?= esc_attr($producto ? (string) $producto : '') ?>"<?= $producto ? '' : ' disabled' ?>>
            <?php if ($producto) : ?>
                <span><?= esc_html__('Agregar al carrito', 'angelitozzz') ?></span>
                <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
            <?php else : ?>
                <span><?= esc_html__('En preparación', 'angelitozzz') ?></span>
            <?php endif; ?>
        </button>
    </div>
</div>
<?php
angelitozzz_modal_end();
