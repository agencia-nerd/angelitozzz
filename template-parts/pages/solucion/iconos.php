<?php

/**
 * Solución · layout `iconos` (specs/solucion.md → secciones): "es para ti si…" / "por qué este curso".
 * Título, bajada opcional, grilla de 3 columnas (ícono en mancha beige, título opcional y texto) y,
 * opcional, el corazón decorativo y el bloque "NO es para ti". La última fila incompleta se centra.
 *
 * $args: fila del flexible (fondo, corazon, titulo, bajada, items[icono, titulo, texto], no_titulo, no_items).
 */

defined('ABSPATH') || exit;

$titulo    = (string) ($args['titulo'] ?? '');
$bajada    = (string) ($args['bajada'] ?? '');
$corazon   = !empty($args['corazon']);
$no_titulo = trim((string) ($args['no_titulo'] ?? ''));
$no_items  = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) ($args['no_items'] ?? '')) ?: []), 'strlen'));

$iconos = ['reloj-de-arena', 'bebe-luna', 'bebe-sol', 'cuna', 'bebe-envuelto', 'biberon', 'documento', 'reloj'];
$items  = array_values(array_filter(
    (array) ($args['items'] ?? []),
    static fn ($item): bool => is_array($item) && trim((string) ($item['texto'] ?? '')) !== ''
));

if ($titulo === '' || !$items) {
    return;
}

$id_titulo = wp_unique_id('solucion-iconos-');
$img       = static fn(string $archivo): string => get_theme_file_uri('assets/img/solucion/' . $archivo);
?>
<section class="<?= esc_attr('overflow-hidden px-4 py-22 max-md:py-14 ' . angelitozzz_solucion_fondo((string) ($args['fondo'] ?? 'crema'))) ?>" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="mx-auto flex max-w-[1177px] flex-col items-center gap-[106px] max-md:gap-14">
        <div class="flex flex-col items-center gap-5 text-center">
            <div class="relative">
                <?php if ($corazon) : ?>
                    <img class="pointer-events-none absolute -left-[134px] -top-[120px] h-[228px] w-[238px] max-w-none rotate-[14.36deg] max-md:-left-10 max-md:-top-24 max-md:h-[150px] max-md:w-[156px]" src="<?= esc_url($img('iconos-corazon.svg')) ?>" alt="" aria-hidden="true" width="238" height="228" loading="lazy">
                <?php endif; ?>
                <h2 id="<?= esc_attr($id_titulo) ?>" class="relative text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
            </div>
            <?php if ($bajada !== '') : ?>
                <p class="max-w-[760px] text-cuerpo-s text-tinta"><?= angelitozzz_highlight($bajada) ?></p>
            <?php endif; ?>
        </div>

        <ul class="flex w-full flex-wrap justify-center gap-x-[87px] gap-y-11 max-lg:gap-x-8">
            <?php foreach ($items as $item) :
                $icono       = in_array($item['icono'] ?? '', $iconos, true) ? $item['icono'] : 'reloj-de-arena';
                $item_titulo = trim((string) ($item['titulo'] ?? ''));
                ?>
                <li class="flex w-[334px] flex-col items-center gap-3 text-center max-md:w-full max-md:max-w-[334px] md:max-lg:w-[calc(50%-16px)]">
                    <img class="h-[110px] w-[119px]" src="<?= esc_url($img('iconos-' . $icono . '.svg')) ?>" alt="" aria-hidden="true" width="119" height="110" loading="lazy">
                    <div class="flex flex-col">
                        <?php if ($item_titulo !== '') : ?>
                            <h3 class="text-h5 text-marca"><?= esc_html($item_titulo) ?></h3>
                        <?php endif; ?>
                        <p class="<?= esc_attr($item_titulo !== '' ? 'text-cuerpo-s' : 'text-cuerpo') ?>"><?= angelitozzz_highlight((string) $item['texto']) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($no_titulo !== '' && $no_items) : ?>
            <div class="flex max-w-[640px] flex-col items-center gap-[10px] text-center">
                <h3 class="text-h3 text-marca max-md:text-h4"><?= esc_html($no_titulo) ?></h3>
                <ul class="flex flex-col text-cuerpo">
                    <?php foreach ($no_items as $no_item) : ?>
                        <li class="flex items-baseline justify-center gap-2">
                            <svg class="size-[13px] shrink-0 translate-y-[1px]" viewBox="0 0 13 13" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M1 1l11 11M12 1 1 12" /></svg>
                            <span><?= esc_html($no_item) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>
