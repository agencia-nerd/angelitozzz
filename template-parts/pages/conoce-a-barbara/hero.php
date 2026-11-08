<?php

/**
 * Conoce a Bárbara · hero (specs/conoce-a-barbara.md, grupo conoce_a_barbara_hero).
 * Lee su grupo ACF; el contenido inicial está en inc/acf/seed/conoce-a-barbara.php.
 * Las decoraciones (estrellas, destello, luna) son assets fijos del tema, recortados del frame de la página.
 */

defined('ABSPATH') || exit;

$hero       = get_field('conoce_a_barbara_hero') ?: [];
$antetitulo = (string) ($hero['antetitulo'] ?? '');
$titulo     = (string) ($hero['titulo'] ?? '');
$bajada     = (string) ($hero['bajada'] ?? '');
$texto      = (string) ($hero['texto'] ?? '');
$imagen     = $hero['imagen'] ?? null;

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
