<?php

/**
 * Slider de testimonios (componente Figma "Testimonios · Tarjetas").
 * El JS vive en src/js/modules/testimonios-slider.js (Swiper, no automático).
 *
 * $args:
 * - titulo      (string, requerido)
 * - testimonios (array de ['autora', 'detalle', 'estrellas', 'texto'], requerido)
 * - boton       (array link: lleva a la página Testimonios)
 */

defined('ABSPATH') || exit;

$titulo      = (string) ($args['titulo'] ?? '');
$testimonios = array_filter((array) ($args['testimonios'] ?? []), static fn($t) => !empty($t['texto']));
$boton       = $args['boton'] ?? '';

if ($titulo === '' || !$testimonios) {
    return;
}

$id = wp_unique_id('testimonios-');

// "ZZZ" decorativo del pie: posición, tamaño y giro de cada letra (diseño a 1920 px, en em sobre 18 px
// para que en móvil se reduzca entera cambiando solo el tamaño de letra del contenedor).
$letras_z = [
    'left-[-0.87%] top-[3.024em] text-[2.5em] rotate-[18deg]',
    'left-[6.15%] top-[2.622em] text-[2.67em] rotate-[17deg]',
    'left-[13.23%] top-[1.869em] text-[3.06em] rotate-[14deg]',
    'left-[20.47%] top-[1.174em] text-[3.5em] rotate-[9deg]',
    'left-[27.81%] top-[0.598em] text-[4em] rotate-[4deg]',
    'left-[35.16%] top-[0.228em] text-[4.39em]',
    'left-[42.19%] top-[0.024em] text-[4.67em] rotate-[-2deg]',
    'left-[49.22%] top-[-0.013em] text-[4.72em] rotate-[-2deg]',
    'left-[56.16%] top-[0.131em] text-[4.67em] rotate-[1deg]',
    'left-[62.85%] top-[0.38em] text-[4.39em] rotate-[5deg]',
    'left-[69.51%] top-[0.73em] text-[4.11em] rotate-[10deg]',
    'left-[76.23%] top-[1.06em] text-[3.83em] rotate-[15deg]',
    'left-[83.13%] top-[1.249em] text-[3.78em] rotate-[17deg]',
    'left-[90.09%] top-[1.235em] text-[3.78em] rotate-[18deg]',
    'left-[97.17%] top-[0.958em] text-[4.06em] rotate-[16deg]',
];
?>
<section class="relative overflow-hidden bg-neutro pb-[181px] pt-26 max-lg:pb-36 max-lg:pt-16" aria-labelledby="<?= esc_attr($id) ?>-titulo">
    <div class="pointer-events-none absolute inset-x-0 bottom-2 h-[10.56em] text-[18px] font-medium leading-none text-fondo opacity-40 max-md:text-[8px]" aria-hidden="true">
        <?php foreach ($letras_z as $clases) : ?>
            <span class="<?= esc_attr('absolute ' . $clases) ?>">Z</span>
        <?php endforeach; ?>
    </div>
    <img class="pointer-events-none absolute right-[-19px] top-26 w-[260px] max-lg:hidden" src="<?= esc_url(get_theme_file_uri('assets/img/testimonios-nube-derecha.svg')) ?>" alt="" aria-hidden="true" width="260" height="113" loading="lazy">
    <img class="pointer-events-none absolute bottom-[-20px] left-[-18px] w-[300px] max-md:w-[180px]" src="<?= esc_url(get_theme_file_uri('assets/img/testimonios-nube-izquierda.svg')) ?>" alt="" aria-hidden="true" width="300" height="180" loading="lazy">
    <img class="pointer-events-none absolute left-[81.25%] top-[527px] size-4 max-lg:hidden" src="<?= esc_url(get_theme_file_uri('assets/img/testimonios-detalle-destello.svg')) ?>" alt="" aria-hidden="true" width="16" height="16" loading="lazy">

    <div class="relative mx-auto max-w-sitio px-4 lg:px-0">
        <div class="relative flex items-center justify-between gap-6">
            <img class="pointer-events-none absolute -left-[75px] -top-4 w-11 max-xl:hidden" src="<?= esc_url(get_theme_file_uri('assets/img/testimonios-detalle-estrella.svg')) ?>" alt="" aria-hidden="true" width="44" height="50" loading="lazy">
            <h2 id="<?= esc_attr($id) ?>-titulo" class="text-h2 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
            <div class="flex shrink-0 gap-3 max-md:hidden">
                <button type="button" class="flex size-12 items-center justify-center rounded-full border border-neutro bg-blanco text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-slider-anterior>
                    <span class="sr-only"><?= esc_html__('Anterior', 'angelitozzz') ?></span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15.83 10H4.17M10 15.83 4.17 10 10 4.17" /></svg>
                </button>
                <button type="button" class="flex size-12 items-center justify-center rounded-full border border-neutro bg-blanco text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-slider-siguiente>
                    <span class="sr-only"><?= esc_html__('Siguiente', 'angelitozzz') ?></span>
                    <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4.17 10h11.66M10 4.17 15.83 10 10 15.83" /></svg>
                </button>
            </div>
        </div>

        <div class="swiper swiper-alto-auto testimonios-slider mt-16 overflow-visible max-md:mt-10" data-testimonios-slider aria-label="<?= esc_attr($titulo) ?>">
            <ul class="swiper-wrapper">
                <?php foreach ($testimonios as $testimonio) :
                    $estrellas = max(1, min(5, (int) ($testimonio['estrellas'] ?? 5)));
                    $autora    = (string) ($testimonio['autora'] ?? '');
                    $detalle   = (string) ($testimonio['detalle'] ?? '');
                ?>
                    <li class="swiper-slide transition-opacity">
                        <figure class="relative flex h-full min-h-[320px] flex-col gap-[14px] rounded-media bg-blanco p-7">
                            <div class="flex gap-[2px] text-estrella">
                                <span class="sr-only"><?= esc_html(sprintf(__('%d de 5 estrellas', 'angelitozzz'), $estrellas)) ?></span>
                                <?php for ($i = 0; $i < $estrellas; $i++) : ?>
                                    <svg class="size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false"><path d="M7.68 1.53a.36.36 0 0 1 .64 0l1.54 3.12c.1.2.25.38.43.52.19.13.4.22.63.25l3.44.5a.36.36 0 0 1 .2.61l-2.49 2.43a1.44 1.44 0 0 0-.41 1.25l.59 3.43a.36.36 0 0 1-.52.37l-3.08-1.62a1.44 1.44 0 0 0-1.32 0l-3.08 1.62a.36.36 0 0 1-.51-.37l.59-3.43a1.44 1.44 0 0 0-.41-1.25L1.44 6.53a.36.36 0 0 1 .2-.6l3.44-.5c.23-.04.44-.13.63-.26.18-.14.33-.32.43-.52z" /></svg>
                                <?php endfor; ?>
                            </div>
                            <svg class="absolute right-[26px] top-6 size-[30px] text-fondo-rosa" viewBox="0 0 30 30" fill="currentColor" aria-hidden="true" focusable="false"><path d="M20 3.75a2.5 2.5 0 0 0-2.5 2.5v7.5a2.5 2.5 0 0 0 2.5 2.5c.69 0 1.25.56 1.25 1.25v1.25a2.5 2.5 0 0 1-2.5 2.5c-.69 0-1.25.56-1.25 1.25V25c0 .69.56 1.25 1.25 1.25a7.5 7.5 0 0 0 7.5-7.5V6.25a2.5 2.5 0 0 0-2.5-2.5zm-13.75 0a2.5 2.5 0 0 0-2.5 2.5v7.5a2.5 2.5 0 0 0 2.5 2.5c.69 0 1.25.56 1.25 1.25v1.25A2.5 2.5 0 0 1 5 21.25c-.69 0-1.25.56-1.25 1.25V25c0 .69.56 1.25 1.25 1.25a7.5 7.5 0 0 0 7.5-7.5V6.25a2.5 2.5 0 0 0-2.5-2.5z" /></svg>
                            <blockquote class="text-cuerpo"><?= angelitozzz_highlight($testimonio['texto']) ?></blockquote>
                            <figcaption class="mt-auto flex items-center gap-3 border-t border-neutro pt-4">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-fondo-rosa text-acento" aria-hidden="true">
                                    <svg class="size-[18px]" viewBox="0 0 18 18" fill="currentColor" focusable="false"><path d="M14.25 10.5c1.12-1.1 2.25-2.41 2.25-4.13a4.13 4.13 0 0 0-4.13-4.12c-1.32 0-2.25.38-3.37 1.5-1.13-1.12-2.06-1.5-3.38-1.5A4.13 4.13 0 0 0 1.5 6.38c0 1.72 1.13 3.03 2.25 4.12L9 15.75z" /></svg>
                                </span>
                                <span class="flex flex-col gap-[2px]">
                                    <span class="text-rotulo text-marca"><?= esc_html($autora) ?></span>
                                    <?php if ($detalle !== '') : ?>
                                        <span class="text-caption text-tinta-suave"><?= esc_html($detalle) ?></span>
                                    <?php endif; ?>
                                </span>
                            </figcaption>
                        </figure>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="mt-14 flex items-center justify-center gap-4 max-md:mt-8">
            <button type="button" class="flex size-12 shrink-0 items-center justify-center rounded-full border border-neutro bg-blanco text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca md:hidden" data-slider-anterior>
                <span class="sr-only"><?= esc_html__('Anterior', 'angelitozzz') ?></span>
                <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15.83 10H4.17M10 15.83 4.17 10 10 4.17" /></svg>
            </button>
            <div class="slider-puntos" data-slider-puntos></div>
            <button type="button" class="flex size-12 shrink-0 items-center justify-center rounded-full border border-neutro bg-blanco text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca md:hidden" data-slider-siguiente>
                <span class="sr-only"><?= esc_html__('Siguiente', 'angelitozzz') ?></span>
                <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4.17 10h11.66M10 4.17 15.83 10 10 15.83" /></svg>
            </button>
        </div>

        <div class="mt-14 flex justify-center max-md:mt-10">
            <?php get_template_part('template-parts/components/boton', null, [
                'enlace'   => $boton,
                'variante' => 'secundario',
                'flecha'   => false,
            ]); ?>
        </div>
    </div>

    <div class="pointer-events-none absolute left-0 top-[267px] z-10 h-[340px] w-[220px] bg-linear-to-r from-neutro to-neutro/0 max-lg:hidden" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-[267px] z-10 h-[340px] w-[220px] bg-linear-to-l from-neutro to-neutro/0 max-lg:hidden" aria-hidden="true"></div>
</section>
