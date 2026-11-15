<?php

/**
 * Solución · layout `preguntas` (specs/solucion.md → secciones): título y acordeón de wp-accordion
 * (template-parts/accordions/solucion-preguntas.php). Una pregunta sin respuesta no se imprime; sin
 * ninguna con respuesta, la sección no sale. Con más de 5, "Ver más" muestra el resto.
 *
 * $args: fila del flexible (fondo, titulo, items[pregunta, respuesta]).
 */

defined('ABSPATH') || exit;

$titulo    = (string) ($args['titulo'] ?? '');
$visibles  = 5;
$preguntas = array_values(array_filter(
    (array) ($args['items'] ?? []),
    static fn ($fila): bool => is_array($fila)
        && trim((string) ($fila['pregunta'] ?? '')) !== ''
        && trim((string) ($fila['respuesta'] ?? '')) !== ''
));

if ($titulo === '' || !$preguntas) {
    return;
}

$con_extra    = count($preguntas) > $visibles;
$id_titulo    = wp_unique_id('solucion-preguntas-titulo-');
$id_acordeon  = wp_unique_id('solucion-preguntas-');
?>
<section class="<?= esc_attr('flex flex-col items-center gap-16 px-4 py-22 max-md:gap-10 max-md:py-14 ' . angelitozzz_solucion_fondo((string) ($args['fondo'] ?? 'crema'))) ?>" aria-labelledby="<?= esc_attr($id_titulo) ?>" <?= $con_extra ? 'data-ver-mas-root' : '' ?>>
    <h2 id="<?= esc_attr($id_titulo) ?>" class="text-center text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>

    <?php get_template_part('template-parts/accordions/solucion-preguntas', null, [
        'id'        => $id_acordeon,
        'preguntas' => $preguntas,
        'visibles'  => $visibles,
    ]); ?>

    <?php if ($con_extra) : ?>
        <button type="button" class="-mt-8 flex items-center gap-2.5 text-cuerpo-xl text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca max-md:-mt-4" data-ver-mas aria-expanded="true" aria-controls="<?= esc_attr($id_acordeon . '-mas') ?>" hidden>
            <span data-ver-mas-texto><?= esc_html__('Ver más', 'angelitozzz') ?></span>
            <svg class="size-[10px] shrink-0 rotate-180 text-contorno" viewBox="0 0 10.2 10.31" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="M9.57562 4.65278L5.10032 0.625L0.625006 4.65278" />
                <path d="M5.10284 1.2962L5.10285 9.68741" />
            </svg>
        </button>
    <?php endif; ?>
</section>
