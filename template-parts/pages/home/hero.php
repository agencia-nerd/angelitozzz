<?php

/**
 * Inicio · hero (specs/home.md, grupo home_hero).
 */

defined('ABSPATH') || exit;

$hero = get_field('home_hero') ?: [];

$eyebrow      = (string) ($hero['eyebrow'] ?? '');
$titulo       = (string) ($hero['titulo'] ?? '');
$bajada       = (string) ($hero['bajada'] ?? '');
$credenciales = array_filter(array_map(
    fn($fila) => trim((string) ($fila['texto'] ?? '')),
    (array) ($hero['credenciales'] ?? [])
), 'strlen');
$boton  = $hero['boton'] ?? null;
$imagen = $hero['imagen'] ?? null;
?>
<section class="relative overflow-hidden bg-neutro lg:flex lg:items-center" aria-labelledby="home-hero-titulo">
    <img class="pointer-events-none absolute left-0 top-0 w-[120px] lg:w-[240px]" src="<?= esc_url(get_theme_file_uri('assets/img/home/hero-colgante.svg')) ?>" alt="" aria-hidden="true" width="240" height="280">

    <div class="relative flex flex-1 flex-col gap-8 px-4 pb-14 pt-36 lg:py-16 lg:pl-[9.375%] lg:pr-[72px]">
        <div class="flex flex-col gap-[22px]">
            <?php if ($eyebrow !== '') : ?>
                <p class="text-eyebrow text-marca-suave"><?= esc_html($eyebrow) ?></p>
            <?php endif; ?>
            <h1 id="home-hero-titulo" class="text-display text-marca max-lg:text-h2"><?= angelitozzz_highlight($titulo) ?></h1>
            <?php if ($bajada !== '') : ?>
                <p class="max-w-[520px] text-cuerpo-l"><?= angelitozzz_highlight($bajada) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($credenciales) : ?>
            <ul class="fila-puntos flex flex-col gap-1 text-rotulo text-marca-suave lg:flex-row lg:flex-wrap lg:gap-4">
                <?php foreach ($credenciales as $credencial) : ?>
                    <li class="lg:whitespace-nowrap"><?= esc_html($credencial) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div>
            <?php get_template_part('template-parts/components/boton', null, [
                'enlace'   => $boton,
                'variante' => 'frambuesa',
                'flecha'   => true,
            ]); ?>
        </div>
    </div>

    <div class="relative aspect-[3/2] w-full shrink-0 lg:w-[49%]">
        <?= angelitozzz_image($imagen, [
            'class'         => 'size-full object-cover',
            'alt_fallback'  => trim(preg_replace('/\s+/', ' ', $titulo)),
            'loading'       => 'eager',
            'fetchpriority' => 'high',
        ]) ?>
        <img class="pointer-events-none absolute left-[-10.67%] top-0 w-[21.34%] max-lg:hidden" src="<?= esc_url(get_theme_file_uri('assets/img/home/hero-estrellas.svg')) ?>" alt="" aria-hidden="true" width="201" height="618">
        <img class="pointer-events-none absolute left-[71.5%] top-[38.8%] w-[36.3%] rotate-[-9.9deg]" src="<?= esc_url(get_theme_file_uri('assets/img/home/hero-estrella-grande.svg')) ?>" alt="" aria-hidden="true" width="341" height="329">
    </div>
</section>