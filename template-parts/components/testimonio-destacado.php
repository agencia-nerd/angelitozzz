<?php

/**
 * Testimonio destacado (componente Figma "Testimonio · Destacado"; landings de soluciones).
 * Un testimonio a la vez (Swiper, no automático: src/js/modules/testimonio-destacado.js): cita grande
 * en itálica y autora, flechas blancas a los lados y puntos debajo. Con un solo testimonio no hay
 * flechas ni puntos. Decoración fija: fila de "Z" y nubes.
 *
 * $args:
 * - titulo      (string, requerido)
 * - testimonios (array de ['texto', 'autora'], requerido; el texto va sin comillas: se ponen solas)
 * - fondo       ('crema' | 'rosa' | 'beige' | 'salvia' | 'blanco', por defecto 'beige')
 */

defined('ABSPATH') || exit;

$titulo      = (string) ($args['titulo'] ?? '');
$fondo       = (string) ($args['fondo'] ?? 'beige');
$testimonios = array_values(array_filter(
    (array) ($args['testimonios'] ?? []),
    static fn ($fila): bool => is_array($fila) && trim((string) ($fila['texto'] ?? '')) !== ''
));

if (!$testimonios) {
    return;
}

$fondos = [
    'crema'  => 'bg-fondo',
    'rosa'   => 'bg-fondo-rosa',
    'beige'  => 'bg-neutro',
    'salvia' => 'bg-etiqueta',
    'blanco' => 'bg-blanco',
];
$varios = count($testimonios) > 1;
$id     = wp_unique_id('testimonio-destacado-');
$img    = static fn(string $nombre): string => get_theme_file_uri('assets/img/solucion/testimonios-' . $nombre . '.svg');

// Fila de "Z" del diseño (posición en % del ancho de 1920 px, altura desde arriba de la fila, tamaño y giro).
$zetas = [
    'left-[-0.87%] top-[136px] text-[45px] rotate-18',
    'left-[6.15%] top-[126px] text-[48px] rotate-17',
    'left-[13.23%] top-[103px] text-[55px] rotate-14',
    'left-[20.47%] top-[74px] text-[63px] rotate-9',
    'left-[27.81%] top-[43px] text-[72px] rotate-4',
    'left-[35.16%] top-[18px] text-[79px]',
    'left-[42.19%] top-[2px] text-[84px] -rotate-2',
    'left-[49.22%] top-[-1px] text-[85px] -rotate-2',
    'left-[56.16%] top-[11px] text-[84px] rotate-1',
    'left-[62.85%] top-[30px] text-[79px] rotate-5',
    'left-[69.51%] top-[54px] text-[74px] rotate-10',
    'left-[76.22%] top-[73px] text-[69px] rotate-15',
    'left-[83.13%] top-[85px] text-[68px] rotate-17',
    'left-[90.09%] top-[84px] text-[68px] rotate-18',
    'left-[97.17%] top-[70px] text-[73px] rotate-16',
];
$flecha = 'absolute top-[calc(50%-7px)] z-10 flex size-12 -translate-y-1/2 items-center justify-center rounded-full border border-neutro bg-blanco text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca max-md:hidden';
?>
<section class="<?= esc_attr('relative overflow-hidden px-4 pb-[181px] pt-24 max-md:pb-32 max-md:pt-14 ' . ($fondos[$fondo] ?? $fondos['beige'])) ?>" aria-labelledby="<?= esc_attr($id) ?>-titulo" data-testimonio-destacado>
    <div class="pointer-events-none absolute inset-x-0 bottom-2 h-[190px] select-none opacity-40 max-md:h-[120px] max-md:scale-75 max-md:origin-bottom" aria-hidden="true">
        <?php foreach ($zetas as $clases) : ?>
            <span class="<?= esc_attr('absolute font-medium leading-normal text-fondo ' . $clases) ?>">Z</span>
        <?php endforeach; ?>
    </div>
    <img class="pointer-events-none absolute left-[89.58%] top-10 h-[104px] w-[240px] max-lg:hidden" src="<?= esc_url($img('nube-derecha')) ?>" alt="" aria-hidden="true" width="240" height="104" loading="lazy">
    <div class="pointer-events-none absolute left-[-3.13%] top-[434px] h-[168px] w-[280px] max-lg:hidden" aria-hidden="true">
        <img class="size-full" src="<?= esc_url($img('nube-izquierda')) ?>" alt="" width="280" height="168" loading="lazy">
        <img class="absolute left-[72px] top-[50px] h-2 w-[25px] -scale-y-100" src="<?= esc_url($img('nube-ojo-1')) ?>" alt="" width="25" height="8" loading="lazy">
        <img class="absolute left-[116px] top-[50px] h-2 w-[25px] rotate-180" src="<?= esc_url($img('nube-ojo-2')) ?>" alt="" width="25" height="8" loading="lazy">
    </div>

    <div class="relative mx-auto flex max-w-sitio flex-col items-center gap-[42px] text-center max-md:gap-8">
        <h2 id="<?= esc_attr($id) ?>-titulo" class="text-h2 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>

        <div class="relative w-full">
            <?php if ($varios) : ?>
                <button type="button" class="<?= esc_attr($flecha . ' left-0') ?>" data-slider-anterior>
                    <span class="sr-only"><?= esc_html__('Testimonio anterior', 'angelitozzz') ?></span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15.83 10H4.17M10 15.83 4.17 10 10 4.17" /></svg>
                </button>
                <button type="button" class="<?= esc_attr($flecha . ' right-0') ?>" data-slider-siguiente>
                    <span class="sr-only"><?= esc_html__('Testimonio siguiente', 'angelitozzz') ?></span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4.17 10h11.66M10 4.17 15.83 10 10 15.83" /></svg>
                </button>
            <?php endif; ?>

            <div class="swiper mx-auto max-w-[820px]" aria-label="<?= esc_attr(wp_strip_all_tags($titulo)) ?>" <?= $varios ? 'data-testimonio-destacado-slider' : '' ?>>
                <ul class="swiper-wrapper">
                    <?php foreach ($testimonios as $testimonio) : ?>
                        <li class="swiper-slide">
                            <figure class="flex flex-col items-center gap-5">
                                <blockquote class="text-h4 italic text-marca max-md:text-h5">
                                    <p>“<?= angelitozzz_highlight((string) $testimonio['texto']) ?>”</p>
                                </blockquote>
                                <?php if (trim((string) ($testimonio['autora'] ?? '')) !== '') : ?>
                                    <figcaption class="text-rotulo text-tinta-suave"><?= esc_html((string) $testimonio['autora']) ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if ($varios) : ?>
                <div class="slider-puntos mt-7" data-slider-puntos></div>
                <div class="mt-6 flex justify-center gap-4 md:hidden">
                    <button type="button" class="flex size-12 items-center justify-center rounded-full border border-neutro bg-blanco text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-slider-anterior>
                        <span class="sr-only"><?= esc_html__('Testimonio anterior', 'angelitozzz') ?></span>
                        <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15.83 10H4.17M10 15.83 4.17 10 10 4.17" /></svg>
                    </button>
                    <button type="button" class="flex size-12 items-center justify-center rounded-full border border-neutro bg-blanco text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-slider-siguiente>
                        <span class="sr-only"><?= esc_html__('Testimonio siguiente', 'angelitozzz') ?></span>
                        <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4.17 10h11.66M10 4.17 15.83 10 10 15.83" /></svg>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
