<?php

/**
 * Inicio · ¿Cómo puedo ayudarte? (specs/home.md, grupo home_como_ayudarte).
 * Cada tarjeta es una fila del repetidor `items` (componente producto-card).
 * Carrusel: src/js/modules/carrusel-tarjetas.js (Swiper, no automático).
 */

defined('ABSPATH') || exit;

$ayudarte = get_field('home_como_ayudarte') ?: [];

$titulo = (string) ($ayudarte['titulo'] ?? '');
$bajada = (string) ($ayudarte['bajada'] ?? '');
$items  = [];
foreach ((array) ($ayudarte['items'] ?? []) as $fila) {
    $items[] = [
        'imagen'         => $fila['imagen'] ?? '', // Vacía: el componente usa la imagen por defecto.
        'etiqueta'       => (string) ($fila['etiqueta'] ?? ''),
        'etiqueta_icono' => (string) ($fila['etiqueta_icono'] ?? 'ninguno'),
        'titulo'         => (string) ($fila['titulo'] ?? ''),
        'edad'           => (string) ($fila['edad'] ?? ''),
        'descripcion'    => (string) ($fila['descripcion'] ?? ''),
        'boton'          => $fila['boton'] ?? null,
    ];
}

$flecha_clases = 'absolute top-[160px] z-10 hidden size-[54px] items-center justify-center rounded-full border-2 border-contorno bg-fondo text-contorno focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca md:flex';
?>
<section class="relative overflow-hidden py-22 max-md:py-14" aria-labelledby="home-como-ayudarte-titulo">
    <div class="relative mx-auto flex max-w-[485px] flex-col gap-[15px] px-4 text-center">
        <img class="pointer-events-none absolute -top-5 right-[59px] w-[15px] max-md:right-4" src="<?= esc_url(get_theme_file_uri('assets/img/home/ayudarte-zzz.svg')) ?>" alt="" aria-hidden="true" width="15" height="16">
        <h2 id="home-como-ayudarte-titulo" class="text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
        <?php if ($bajada !== '') : ?>
            <p class="text-body-s"><?= esc_html($bajada) ?></p>
        <?php endif; ?>
    </div>

    <div class="relative mx-auto mt-[54px] max-w-[1480px] px-4 xl:px-0" data-carrusel-tarjetas>
        <button type="button" class="<?= esc_attr($flecha_clases . ' md:left-2 min-[1700px]:-left-[86px]') ?>" data-slider-anterior>
            <span class="sr-only"><?= esc_html__('Anterior', 'angelitozzz') ?></span>
            <svg class="size-[15px]" viewBox="0 0 15 15" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M14.25 7.5H1.75M7.5 13.5l-6-6 6-6" /></svg>
        </button>
        <button type="button" class="<?= esc_attr($flecha_clases . ' md:right-2 min-[1700px]:-right-[86px]') ?>" data-slider-siguiente>
            <span class="sr-only"><?= esc_html__('Siguiente', 'angelitozzz') ?></span>
            <svg class="size-[15px]" viewBox="0 0 15 15" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M.75 7.5h12.5M7.5 1.5l6 6-6 6" /></svg>
        </button>

        <div class="swiper swiper-alto-auto overflow-visible" aria-label="<?= esc_attr($titulo) ?>">
            <ul class="swiper-wrapper">
                <?php foreach ($items as $item) : ?>
                    <li class="swiper-slide">
                        <?php get_template_part('template-parts/components/producto-card', null, $item); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
