<?php

/**
 * Preguntas frecuentes: acordeón sobre <details> (wp-accordion) con botón de lotes.
 * Grupo ACF: preguntas_frecuentes_seccion_preguntas_frecuentes (specs/preguntas-frecuentes.md).
 */

defined('ABSPATH') || exit;

$grupo   = get_field('preguntas_frecuentes_seccion_preguntas_frecuentes') ?: [];
$titulo  = (string) ($grupo['titulo'] ?? '');

$preguntas = array_values(array_filter(
    (array) ($grupo['preguntas'] ?? []),
    static fn ($fila): bool => is_array($fila) && trim((string) ($fila['pregunta'] ?? '')) !== ''
));

// Se ven 6 preguntas; cada clic en el botón muestra el siguiente lote (o lo que sobre).

$lote      = 6;
$agrupar   = count($preguntas) > $lote;
$visibles  = $agrupar ? array_slice($preguntas, 0, $lote) : $preguntas;
$ocultos   = $agrupar ? array_chunk(array_slice($preguntas, $lote), $lote) : [];
$id_titulo = wp_unique_id('preguntas-titulo-');
$id_base   = wp_unique_id('preguntas-lote-');

$pintar = static function (array $fila, bool $abierta = false): void {
    angelitozzz_accordion_item_start((string) $fila['pregunta'], ['open' => $abierta]);
    echo '<p>', angelitozzz_highlight((string) ($fila['respuesta'] ?? '')), '</p>';
    angelitozzz_accordion_item_end();
};

if ($titulo === '' && !$preguntas) {
    return;
}
?>
<section class="flex flex-col items-center gap-16 px-4 py-22 max-md:gap-10 max-md:py-14" aria-labelledby="<?= esc_attr($id_titulo) ?>" <?= $agrupar ? 'data-ver-mas-root' : '' ?>>
    <h1 id="<?= esc_attr($id_titulo) ?>" class="text-center text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h1>

    <?php if ($preguntas) : ?>
        <?php
        angelitozzz_accordion_start('preguntas-frecuentes', [
            'icon'          => 'none',
            'class'         => 'flex w-full max-w-[774px] flex-col gap-4 py-5',
            'item_class'    => 'relative pb-[54px] max-md:pb-6 after:absolute after:bottom-0 after:left-[26px] after:right-0 after:border-b after:border-neutro max-md:after:left-6',
            'summary_class' => 'max-md:items-start',
            'title_class'   => 'faq-check relative pl-6 text-body-l text-tinta',
            'panel_class'   => 'pt-[54px] text-body-s text-tinta max-md:pt-6',
        ]);

        foreach ($visibles as $i => $fila) {
            $pintar($fila, $i === 0);
        }

        foreach ($ocultos as $n => $filas) : ?>
            <div id="<?= esc_attr($id_base . '-' . ($n + 1)) ?>" class="flex flex-col gap-4" data-ver-mas-grupo hidden>
                <?php foreach ($filas as $fila) {
                    $pintar($fila);
                } ?>
            </div>
        <?php endforeach;

        angelitozzz_accordion_end();
        ?>

        <?php if ($ocultos) : ?>
            <button type="button" class="-mt-8 flex items-center gap-2.5 text-body-xl text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca max-md:-mt-4" data-ver-mas aria-expanded="false" aria-controls="<?= esc_attr($id_base . '-1') ?>">
                <span><?= esc_html__('Ver más', 'angelitozzz') ?></span>
                <svg class="size-[10px] shrink-0 rotate-180 text-contorno" viewBox="0 0 10.2 10.31" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M9.57562 4.65278L5.10032 0.625L0.625006 4.65278" />
                    <path d="M5.10284 1.2962L5.10285 9.68741" />
                </svg>
            </button>
        <?php endif; ?>
    <?php endif; ?>
</section>
