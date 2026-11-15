<?php

/**
 * Solución · layout `lista` (specs/solucion.md → secciones): "qué incluye" / "qué aprenderás".
 * Título, foto a la izquierda con nubes y estrellas decorativas, y lista con check y separadores.
 * Sin foto, la lista se centra. Con más de 5 ítems, el resto queda tras "Ver más" / "Ver menos"
 * (src/js/modules/ver-mas.js): el HTML trae la lista completa y sin JS se ve entera.
 *
 * $args: fila del flexible (fondo, titulo, imagen, items[texto]).
 */

defined('ABSPATH') || exit;

$titulo = (string) ($args['titulo'] ?? '');
$imagen = $args['imagen'] ?? '';
$fondo  = (string) ($args['fondo'] ?? 'crema');
$items  = array_values(array_filter(
    (array) ($args['items'] ?? []),
    static fn ($item): bool => is_array($item) && trim((string) ($item['texto'] ?? '')) !== ''
));

if ($titulo === '' || !$items) {
    return;
}

$visibles  = 5;
$con_extra = count($items) > $visibles;
$foto      = angelitozzz_image($imagen, [
    'class'        => 'size-full object-cover',
    'alt_fallback' => $titulo,
]);
$rosa      = $fondo === 'rosa';
$id_titulo = wp_unique_id('solucion-lista-');
$id_lista  = wp_unique_id('solucion-lista-items-');
$img       = static fn(string $archivo): string => get_theme_file_uri('assets/img/solucion/' . $archivo);
// Sobre rosa, nubes y separadores rosados (como Horarios Ideales); en los demás fondos, beige.
$nube_izquierda = $img($rosa ? 'lista-nube-izquierda-rosa.svg' : 'lista-nube-izquierda.svg');
$nube_superior  = $img($rosa ? 'lista-nube-superior-rosa.svg' : 'lista-nube-superior.svg');
$separador      = $rosa ? 'border-[#e4ad9e]' : 'border-neutro';
?>
<section class="<?= esc_attr('relative overflow-hidden px-4 py-22 max-md:py-14 ' . angelitozzz_solucion_fondo($fondo)) ?>" aria-labelledby="<?= esc_attr($id_titulo) ?>" <?= $con_extra ? 'data-ver-mas-root' : '' ?>>
    <?php if ($foto === '') : ?>
        <img class="pointer-events-none absolute -left-[100px] top-[180px] w-[229px] max-md:hidden" src="<?= esc_url($nube_izquierda) ?>" alt="" aria-hidden="true" width="229" height="138" loading="lazy">
        <img class="pointer-events-none absolute -right-[40px] top-[45%] w-[278px] max-md:hidden" src="<?= esc_url($nube_superior) ?>" alt="" aria-hidden="true" width="278" height="121" loading="lazy">
    <?php endif; ?>

    <div class="mx-auto flex max-w-[1636px] flex-col items-center gap-[112px] max-md:gap-10">
        <h2 id="<?= esc_attr($id_titulo) ?>" class="text-center text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>

        <div class="<?= esc_attr('flex w-full items-center gap-[188px] max-xl:gap-20 max-lg:flex-col max-lg:gap-12 ' . ($foto === '' ? 'justify-center' : 'justify-center lg:justify-between')) ?>">
            <?php if ($foto !== '') : ?>
                <div class="relative w-[414px] shrink-0 max-md:w-full max-md:max-w-[340px] 2xl:ml-[260px]">
                    <img class="pointer-events-none absolute -left-[260px] top-[108px] w-[229px] max-xl:hidden" src="<?= esc_url($nube_izquierda) ?>" alt="" aria-hidden="true" width="229" height="138" loading="lazy">
                    <img class="pointer-events-none absolute -left-[71px] -top-[90px] w-[278px] max-md:-left-6 max-md:-top-14 max-md:w-[160px]" src="<?= esc_url($nube_superior) ?>" alt="" aria-hidden="true" width="278" height="121" loading="lazy">
                    <div class="relative aspect-[414/533] w-full overflow-hidden rounded-media">
                        <?= $foto ?>
                    </div>
                    <img class="pointer-events-none absolute -left-[64px] top-[4px] w-[90px] -rotate-[9.83deg] max-md:-left-4 max-md:w-[56px]" src="<?= esc_url($img('lista-estrella-chica.svg')) ?>" alt="" aria-hidden="true" width="90" height="86" loading="lazy">
                    <img class="pointer-events-none absolute -bottom-[30px] -left-[150px] w-[176px] rotate-[21.13deg] max-md:-bottom-6 max-md:-left-6 max-md:w-[90px]" src="<?= esc_url($img('lista-estrella-grande.svg')) ?>" alt="" aria-hidden="true" width="176" height="169" loading="lazy">
                </div>
            <?php endif; ?>

            <div class="flex w-full max-w-[774px] flex-col items-start gap-[35px]">
                <ul id="<?= esc_attr($id_lista) ?>" class="flex w-full flex-col gap-[35px]">
                    <?php foreach ($items as $i => $item) : ?>
                        <li class="<?= esc_attr('flex gap-[10px] border-b pb-[35px] text-cuerpo-s text-tinta ' . $separador) ?>" <?= $i >= $visibles ? 'data-ver-mas-extra' : '' ?>>
                            <img class="mt-[5px] h-[13px] w-[14px] shrink-0" src="<?= esc_url($img('lista-check.svg')) ?>" alt="" aria-hidden="true" width="14" height="13" loading="lazy">
                            <span><?= angelitozzz_highlight((string) $item['texto']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($con_extra) : ?>
                    <button type="button" class="ml-[10px] flex items-center gap-2.5 text-cuerpo text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca [&[aria-expanded=true]_svg]:rotate-0" data-ver-mas data-ver-mas-menos="<?= esc_attr__('Ver menos', 'angelitozzz') ?>" aria-expanded="true" aria-controls="<?= esc_attr($id_lista) ?>" hidden>
                        <span data-ver-mas-texto><?= esc_html__('Ver más', 'angelitozzz') ?></span>
                        <svg class="size-[10px] shrink-0 rotate-180 text-contorno" viewBox="0 0 10.2 10.31" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M9.57562 4.65278L5.10032 0.625L0.625006 4.65278" />
                            <path d="M5.10284 1.2962L5.10285 9.68741" />
                        </svg>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
