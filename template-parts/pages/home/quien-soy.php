<?php

/**
 * Inicio · ¡Hola, soy Bárbara! (specs/home.md, grupo home_quien_soy).
 */

defined('ABSPATH') || exit;

$quien_soy = get_field('home_quien_soy') ?: [];

$titulo = (string) ($quien_soy['titulo'] ?? '');
$texto  = (string) ($quien_soy['texto'] ?? '');
$boton  = $quien_soy['boton'] ?? null;
$imagen = $quien_soy['imagen'] ?? null;
?>
<section class="bg-neutro px-4 py-22 max-md:py-14" aria-labelledby="home-quien-soy-titulo">
    <div class="mx-auto flex max-w-[1143px] flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-col items-start gap-8 lg:max-w-[589px]">
            <div class="flex flex-col gap-6">
                <h2 id="home-quien-soy-titulo" class="relative w-fit text-h1 text-marca max-md:text-h2">
                    <?= angelitozzz_highlight($titulo) ?>
                    <img class="pointer-events-none absolute -top-9 left-full ml-1 size-[46px] max-md:-top-6 max-md:size-8" src="<?= esc_url(get_theme_file_uri('assets/img/home/quien-soy-corazon.svg')) ?>" alt="" aria-hidden="true" width="46" height="46" loading="lazy">
                </h2>
                <p class="text-cuerpo-s"><?= angelitozzz_highlight($texto) ?></p>
            </div>
            <?php get_template_part('template-parts/components/boton', null, [
                'enlace'   => $boton,
                'variante' => 'frambuesa',
            ]); ?>
        </div>

        <div class="order-first aspect-[482/487] w-full shrink-0 overflow-hidden rounded-media lg:order-none lg:w-[482px]">
            <?= angelitozzz_image($imagen, [
                'class'        => 'size-full object-cover',
                'alt_fallback' => $titulo,
            ]) ?>
        </div>
    </div>
</section>
