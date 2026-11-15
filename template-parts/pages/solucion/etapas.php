<?php

/**
 * Solución · layout `etapas` (specs/solucion.md → secciones; Horarios Ideales, 4013:19194): título y una
 * tarjeta por etapa (foto, título, texto y "Más información", que abre el modal `etapa-<n>`).
 * Cada etapa es un curso; sus modales los imprime single-solucion.php.
 *
 * $args: fila del flexible (fondo, titulo, modal_antetitulo, items[curso, edad, imagen, titulo, texto,
 * modal_*]) + contexto + indice.
 */

defined('ABSPATH') || exit;

$titulo = (string) ($args['titulo'] ?? '');
$items  = array_values(array_filter((array) ($args['items'] ?? []), 'is_array'));

if (!$items) {
    return;
}

$id = wp_unique_id('solucion-etapas-');
?>
<section class="<?= esc_attr('px-4 py-22 max-md:py-14 ' . angelitozzz_solucion_fondo((string) ($args['fondo'] ?? 'crema'))) ?>" aria-labelledby="<?= esc_attr($id) ?>-titulo">
    <div class="mx-auto flex max-w-sitio flex-col items-center gap-[139px] max-lg:gap-20 max-md:gap-10">
        <h2 id="<?= esc_attr($id) ?>-titulo" class="text-center text-h2 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>

        <ul class="grid w-full grid-cols-1 justify-items-center gap-14 md:grid-cols-2 lg:grid-cols-[repeat(3,314px)] lg:justify-between lg:gap-x-[81px] lg:gap-y-20">
            <?php foreach ($items as $n => $etapa) : ?>
                <li class="flex w-full max-w-[314px] flex-col items-center text-center">
                    <div class="aspect-[314/292] w-full overflow-hidden rounded-media">
                        <?= angelitozzz_image($etapa['imagen'] ?? '', [
                            'class'        => 'size-full object-cover',
                            'alt_fallback' => wp_strip_all_tags((string) ($etapa['titulo'] ?? '')),
                        ]) ?>
                    </div>
                    <h3 class="mt-[17px] max-w-[254px] text-cuerpo-xl text-tinta"><?= angelitozzz_highlight((string) ($etapa['titulo'] ?? '')) ?></h3>
                    <p class="mt-1 text-cuerpo-s text-tinta"><?= angelitozzz_highlight((string) ($etapa['texto'] ?? '')) ?></p>
                    <div class="mt-auto pt-14">
                        <?php get_template_part('template-parts/components/boton', null, [
                            'texto' => __('Más información', 'angelitozzz'),
                            'modal' => 'etapa-' . ($n + 1),
                        ]); ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
