<?php

/**
 * Mundo Angelitozzz · cierre (specs/mundo-angelitozzz.md, grupo mundo_angelitozzz_cta_mundo).
 * No es el componente cta-final: tiene beneficios, precio y nota.
 * Lee su grupo ACF; el contenido inicial está en inc/acf/seed/mundo-angelitozzz.php.
 */

defined('ABSPATH') || exit;

$grupo      = get_field('mundo_angelitozzz_cta_mundo') ?: [];
$titulo     = (string) ($grupo['titulo'] ?? '');
$beneficios = (array) ($grupo['beneficios'] ?? []);
$precio     = (string) ($grupo['precio'] ?? '');
$nota       = (string) ($grupo['nota'] ?? '');

$img = static fn(string $archivo): string => esc_url(get_theme_file_uri('assets/img/mundo-angelitozzz/' . $archivo));

// El enlace de suscripción es el del hero (el pago se hace fuera del sitio).
$hero = get_field('mundo_angelitozzz_hero') ?: [];
?>
<section class="relative overflow-hidden bg-fondo-rosa px-4 py-16 lg:py-26" aria-labelledby="mundo-angelitozzz-cta-titulo">
    <img class="pointer-events-none absolute left-1/2 top-0 w-[120px] -translate-x-[calc(50%+250px)] lg:w-[248px] lg:-translate-x-[814px] max-xl:lg:-translate-x-[calc(50%+130px)]" src="<?= $img('cta-destello-grande.svg') ?>" alt="" aria-hidden="true" width="248" height="262">
    <img class="pointer-events-none absolute left-1/2 top-[208px] w-[120px] translate-x-[448px] max-xl:translate-x-[calc(50%+250px)] max-lg:hidden" src="<?= $img('cta-destello-mediano.svg') ?>" alt="" aria-hidden="true" width="120" height="125">
    <img class="pointer-events-none absolute left-1/2 top-[98px] w-[68px] translate-x-[679px] max-xl:translate-x-[calc(50%+320px)] max-lg:hidden" src="<?= $img('cta-destello-pequeno.svg') ?>" alt="" aria-hidden="true" width="68" height="71">

    <div class="relative mx-auto flex max-w-sitio flex-col items-center gap-6 text-center">
        <h2 id="mundo-angelitozzz-cta-titulo" class="text-h3 text-marca lg:text-display"><?= angelitozzz_highlight($titulo) ?></h2>
        <ul class="flex flex-col gap-3 text-left lg:flex-row lg:gap-7">
            <?php foreach ($beneficios as $fila) : ?>
                <li class="flex items-center gap-2 text-cuerpo text-marca lg:text-cuerpo-l">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-marca" aria-hidden="true">
                        <img class="size-[14px]" src="<?= $img('cta-check.svg') ?>" alt="" width="14" height="14">
                    </span>
                    <?= esc_html((string) ($fila['texto'] ?? '')) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if ($precio !== '') : ?>
            <p class="text-rotulo text-marca-suave"><?= esc_html($precio) ?></p>
        <?php endif; ?>
        <div class="mt-2">
            <?php get_template_part('template-parts/components/boton', null, [
                'enlace'   => ['url' => (string) ($hero['enlace_suscripcion'] ?? ''), 'target' => '_blank'],
                'texto'    => (string) ($grupo['boton'] ?? ''),
                'variante' => 'frambuesa',
            ]); ?>
        </div>
        <?php if ($nota !== '') : ?>
            <p class="text-cuerpo-s text-tinta-suave"><?= esc_html($nota) ?></p>
        <?php endif; ?>
    </div>
</section>
