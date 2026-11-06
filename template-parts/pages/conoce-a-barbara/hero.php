<?php

/**
 * Conoce a Bárbara · hero (specs/conoce-a-barbara.md, grupo conoce_a_barbara_hero).
 * Textos e imagen fijos hasta el paso ACF: cada uno está en la columna "valor actual" del spec.
 * Las decoraciones (estrellas, destello, luna) son assets fijos del tema, recortados del frame de la página.
 */

defined('ABSPATH') || exit;

$antetitulo = 'Conoce a Bárbara';
$titulo     = 'Hola, soy Bárbara.';
$bajada     = 'Del agotamiento al propósito — mi historia.';
$texto      = "Hola, soy Bárbara — fundadora de Angelitozzz y mamá de dos angelitos que transformaron mi vida de la manera más intensa... y dolorosa.\n\nCuando nació mi hija Emma, la maternidad me golpeó con una fuerza que no esperaba. A pesar de estar informada y “lista”, terminé envuelta en noches infinitas, llanto constante, dolor físico y una angustia que no podía nombrar. Me enfrenté a una Depresión Post Parto que me hizo sentir aislada, agotada, perdida. Me sentía una extraña en mi propia historia.\n\nY lo más duro: sentía culpa por no estar disfrutando la experiencia que se suponía debía ser la más hermosa de mi vida.";
$imagen     = get_theme_file_uri('assets/img/conoce-a-barbara/hero-foto-barbara.webp');

$deco = static fn(string $archivo): string => esc_url(get_theme_file_uri('assets/img/conoce-a-barbara/' . $archivo));
?>
<section class="relative overflow-hidden px-4 py-14 max-md:py-10" aria-labelledby="conoce-a-barbara-hero-titulo">
    <img class="pointer-events-none absolute -top-[39px] left-[calc(50%-628px)] w-[65px] max-xl:left-2 max-md:-top-5 max-md:w-8" src="<?= $deco('hero-estrella-rosa.webp') ?>" alt="" aria-hidden="true" width="65" height="103">
    <img class="pointer-events-none absolute -top-[39px] left-[calc(50%+558px)] w-[38px] max-xl:left-auto max-xl:right-2 max-md:-top-5 max-md:w-5" src="<?= $deco('hero-estrella-rosa-chica.webp') ?>" alt="" aria-hidden="true" width="38" height="55">
    <img class="pointer-events-none absolute left-[calc(50%+34px)] top-[166px] w-6 max-md:hidden" src="<?= $deco('hero-destello-amarillo.webp') ?>" alt="" aria-hidden="true" width="24" height="27">
    <img class="pointer-events-none absolute left-[calc(50%+613px)] top-[593px] w-[49px] max-xl:hidden" src="<?= $deco('hero-luna.webp') ?>" alt="" aria-hidden="true" width="49" height="48">

    <div class="relative mx-auto max-w-sitio">
        <div class="flex flex-col items-center gap-3 text-center">
            <?php if ($antetitulo !== '') : ?>
                <p class="text-eyebrow text-marca-suave"><?= esc_html($antetitulo) ?></p>
            <?php endif; ?>
            <h1 id="conoce-a-barbara-hero-titulo" class="flex items-center justify-center gap-3 text-display text-marca max-md:text-h3">
                <span><?= angelitozzz_highlight($titulo) ?></span>
                <img class="h-[42px] w-[46px] shrink-0 max-md:h-[29px] max-md:w-8" src="<?= $deco('hero-corazon.svg') ?>" alt="" aria-hidden="true" width="46" height="42">
            </h1>
            <?php if ($bajada !== '') : ?>
                <p class="text-body-xl text-marca max-md:text-body-l"><?= angelitozzz_highlight($bajada) ?></p>
            <?php endif; ?>
        </div>

        <div class="mt-14 flex flex-col items-center gap-20 max-lg:gap-10 lg:flex-row">
            <div class="flex-1 text-body-l [&>p:not(:last-child)]:mb-4.5">
                <?= wpautop(angelitozzz_highlight($texto), false) ?>
            </div>
            <div class="aspect-[23/28] w-full max-w-[460px] shrink-0 overflow-hidden rounded-[32px] lg:w-[460px]">
                <?= angelitozzz_image($imagen, [
                    'class'         => 'size-full object-cover',
                    'alt_fallback'  => $titulo,
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                ]) ?>
            </div>
        </div>
    </div>
</section>
