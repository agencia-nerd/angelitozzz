<?php

/**
 * Solución · layout `beneficios` (specs/solucion.md → secciones): "cómo funciona" / "además incluye".
 * Caja redondeada (beige sobre crema, crema sobre rosa) con título, bajada opcional y 2 o 3 columnas
 * de ícono y texto separadas por líneas verticales; en móvil se apilan con separador horizontal.
 *
 * $args: fila del flexible (fondo, titulo, bajada, items[icono, texto]).
 */

defined('ABSPATH') || exit;

$titulo = (string) ($args['titulo'] ?? '');
$bajada = (string) ($args['bajada'] ?? '');
$fondo  = ($args['fondo'] ?? 'crema') === 'rosa' ? 'rosa' : 'crema';
$iconos = ['libro', 'diamante', 'video'];
$items  = array_slice(array_values(array_filter(
    (array) ($args['items'] ?? []),
    static fn ($item): bool => is_array($item) && trim((string) ($item['texto'] ?? '')) !== ''
)), 0, 3);

if ($titulo === '' || !$items) {
    return;
}

$caja      = $fondo === 'rosa' ? 'bg-fondo' : 'bg-neutro';
$id_titulo = wp_unique_id('solucion-beneficios-');
?>
<section class="<?= esc_attr('px-4 py-22 max-md:py-14 ' . angelitozzz_solucion_fondo($fondo)) ?>" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="<?= esc_attr('mx-auto flex max-w-sitio flex-col items-center gap-[33px] rounded-[30px] p-[55px] text-center max-md:p-8 ' . $caja) ?>">
        <h2 id="<?= esc_attr($id_titulo) ?>" class="text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
        <?php if ($bajada !== '') : ?>
            <p class="text-cuerpo-s text-tinta"><?= angelitozzz_highlight($bajada) ?></p>
        <?php endif; ?>

        <ul class="flex w-full justify-center max-md:flex-col max-md:items-center">
            <?php foreach ($items as $i => $item) :
                $icono = in_array($item['icono'] ?? '', $iconos, true) ? $item['icono'] : 'libro';
                ?>
                <li class="<?= esc_attr('flex w-[250px] flex-col items-center gap-4 px-0 max-md:w-full max-md:max-w-[300px] max-md:py-6 md:min-h-[160px] ' . ($i > 0 ? 'border-contorno md:border-l max-md:border-t' : '')) ?>">
                    <img class="size-[75px]" src="<?= esc_url(get_theme_file_uri('assets/img/solucion/beneficios-' . $icono . '.svg')) ?>" alt="" aria-hidden="true" width="75" height="75" loading="lazy">
                    <p class="px-2 text-cuerpo text-tinta"><?= angelitozzz_highlight((string) $item['texto']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
