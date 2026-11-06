<?php

/**
 * Inicio · ¡Hola, soy Bárbara! (specs/home.md, grupo home_quien_soy).
 */

defined('ABSPATH') || exit;

$titulo = '¡Hola, soy Bárbara!';
$texto  = "Soy Coach de Sueño Pediátrico certificada, mami de dos y fundadora de Angelitozzz desde 2016.\nEn 2012, cuando nació mi hija mayor, conocí de primera mano la desesperación de no dormir y la culpa de no saber cómo resolverlo.\nEn 10 años he acompañado a más de 8.000 familias en 12 países a lograr algo que muchas creían imposible: que su bebé durmiera de corrido — noches y siestas.\nPara que no te pase lo que me pasó a mí.\nPara que tu bebé, tu familia y tú puedan florecer.";
$boton  = ['url' => home_url('/conoce-a-barbara/'), 'title' => 'Conoce mi historia', 'target' => ''];
$imagen = get_theme_file_uri('assets/img/home/quien-soy-foto-barbara.webp');
?>
<section class="bg-neutro px-4 py-22 max-md:py-14" aria-labelledby="home-quien-soy-titulo">
    <div class="mx-auto flex max-w-[1143px] flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-col items-start gap-8 lg:max-w-[589px]">
            <div class="flex flex-col gap-6">
                <h2 id="home-quien-soy-titulo" class="relative w-fit text-h1 text-marca max-md:text-h2">
                    <?= angelitozzz_highlight($titulo) ?>
                    <img class="pointer-events-none absolute -top-9 left-full ml-1 size-[46px] max-md:-top-6 max-md:size-8" src="<?= esc_url(get_theme_file_uri('assets/img/home/quien-soy-corazon.svg')) ?>" alt="" aria-hidden="true" width="46" height="46" loading="lazy">
                </h2>
                <p class="text-body-s"><?= angelitozzz_highlight($texto) ?></p>
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
