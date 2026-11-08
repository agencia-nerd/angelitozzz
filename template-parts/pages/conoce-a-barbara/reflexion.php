<?php

/**
 * Conoce a Bárbara · reflexión (specs/conoce-a-barbara.md, grupo conoce_a_barbara_reflexion).
 * Lee su grupo ACF; el contenido inicial está en inc/acf/seed/conoce-a-barbara.php.
 */

defined('ABSPATH') || exit;

$reflexion = get_field('conoce_a_barbara_reflexion') ?: [];
$cita      = (string) ($reflexion['cita'] ?? '');
$imagen    = $reflexion['imagen'] ?? null;
$texto     = (string) ($reflexion['texto'] ?? '');
$mision    = (string) ($reflexion['mision'] ?? '');
$cierre    = (string) ($reflexion['cierre'] ?? '');
$firma     = (string) ($reflexion['firma'] ?? '');
$cargo     = (string) ($reflexion['cargo'] ?? '');
$boton     = $reflexion['boton'] ?? null;

$deco = static fn(string $archivo): string => esc_url(get_theme_file_uri('assets/img/conoce-a-barbara/' . $archivo));
?>
<section class="relative overflow-hidden px-4 py-14 max-md:py-10" aria-labelledby="conoce-a-barbara-reflexion-cita">
    <img class="pointer-events-none absolute left-[calc(50%-665px)] top-[95px] w-[33px] max-xl:left-2 max-xl:top-6 max-md:w-5" src="<?= $deco('reflexion-estrella.webp') ?>" alt="" aria-hidden="true" width="33" height="33" loading="lazy">
    <img class="pointer-events-none absolute left-[calc(50%-77px)] top-[563px] w-10 max-lg:hidden" src="<?= $deco('reflexion-corazon.webp') ?>" alt="" aria-hidden="true" width="40" height="36" loading="lazy">

    <div class="relative mx-auto flex max-w-sitio flex-col gap-14 max-md:gap-10">
        <h2 id="conoce-a-barbara-reflexion-cita" class="mx-auto max-w-[900px] text-center text-[40px] font-medium italic leading-[1.15] text-marca max-md:text-[28px]"><?= angelitozzz_highlight($cita) ?></h2>

        <div class="flex flex-col items-center gap-20 max-lg:gap-10 lg:flex-row">
            <div class="aspect-[11/13] w-full max-w-[440px] shrink-0 overflow-hidden rounded-[32px] lg:w-[440px]">
                <?= angelitozzz_image($imagen, [
                    'class'        => 'size-full object-cover object-[65%_center]',
                    'alt_fallback' => $cita,
                ]) ?>
            </div>
            <div class="flex-1 text-body-l [&>p:not(:last-child)]:mb-4.5">
                <?= wpautop(angelitozzz_highlight($texto), false) ?>
            </div>
        </div>

        <div class="mx-auto flex max-w-[820px] flex-col items-center gap-3.5 text-center">
            <p class="text-h4 text-marca max-md:text-h5"><?= angelitozzz_highlight($mision) ?></p>
            <?php if ($cierre !== '') : ?>
                <p class="text-body-l [&_br]:mt-2 [&_br]:block [&_br]:content-['']"><?= angelitozzz_highlight($cierre) ?></p>
            <?php endif; ?>
            <?php if ($firma !== '') : ?>
                <p class="flex items-center gap-2 text-h5 text-marca">
                    <?= esc_html($firma) ?>
                    <img class="size-[22px] shrink-0" src="<?= $deco('reflexion-firma-corazon.svg') ?>" alt="" aria-hidden="true" width="22" height="22" loading="lazy">
                </p>
            <?php endif; ?>
            <?php if ($cargo !== '') : ?>
                <p class="text-body-s text-tinta-suave"><?= esc_html($cargo) ?></p>
            <?php endif; ?>
            <div class="mt-2">
                <?php get_template_part('template-parts/components/boton', null, [
                    'enlace'   => $boton,
                    'variante' => 'frambuesa',
                ]); ?>
            </div>
        </div>
    </div>
</section>
