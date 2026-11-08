<?php

/**
 * Mundo Angelitozzz · hero (specs/mundo-angelitozzz.md, grupo mundo_angelitozzz_hero).
 * Lee su grupo ACF; el contenido inicial está en inc/acf/seed/mundo-angelitozzz.php.
 * El enlace de suscripción es externo (el pago no ocurre en el sitio) y lo comparten los tres botones de la página.
 */

defined('ABSPATH') || exit;

$hero          = get_field('mundo_angelitozzz_hero') ?: [];
$titulo        = (string) ($hero['titulo'] ?? '');
$texto         = (string) ($hero['texto'] ?? '');
$precio        = (string) ($hero['precio'] ?? '');
$regalo_texto  = (string) ($hero['regalo_texto'] ?? '');
$regalo_enlace = (string) ($hero['regalo_enlace'] ?? '');

$img = static fn(string $archivo): string => esc_url(get_theme_file_uri('assets/img/mundo-angelitozzz/' . $archivo));

$suscripcion = (string) ($hero['enlace_suscripcion'] ?? '');

$whatsapp     = preg_replace('/\D/', '', (string) get_field('opciones_contacto_whatsapp', 'option'));
$mensaje      = (string) get_field('opciones_contacto_whatsapp_mensaje', 'option');
$whatsapp_url = $whatsapp !== ''
    ? 'https://wa.me/' . $whatsapp . ($mensaje !== '' ? '?text=' . rawurlencode($mensaje) : '')
    : '';
?>
<section class="relative flex min-h-[808px] items-center overflow-hidden bg-fondo px-4 py-12 lg:py-0" aria-labelledby="mundo-angelitozzz-hero-titulo">
    <img class="pointer-events-none absolute right-[8.49%] top-[125px] w-[328px] max-lg:hidden" src="<?= $img('hero-nube-derecha-ojos.svg') ?>" alt="" aria-hidden="true" width="328" height="196">
    <img class="pointer-events-none absolute left-[-4.27%] top-[459px] w-[524px] max-lg:bottom-0 max-lg:left-[-120px] max-lg:top-auto max-lg:w-[262px]" src="<?= $img('hero-nube-izquierda-ojos.svg') ?>" alt="" aria-hidden="true" width="524" height="315">

    <div class="relative mx-auto flex w-full max-w-sitio items-center justify-center gap-8 max-lg:flex-col lg:gap-[129px]">
        <div class="flex w-full max-w-[507px] flex-col gap-8 lg:gap-[58px]">
            <div class="flex flex-col gap-6">
                <div class="flex flex-col gap-[17px]">
                    <div class="flex flex-col gap-[10px]">
                        <?= angelitozzz_image($hero['logo'] ?? null, [
                            'class'        => 'size-14 rounded-full object-cover lg:size-[75px]',
                            'alt_fallback' => __('Mundo Angelitozzz', 'angelitozzz'),
                        ]) ?>
                        <h1 id="mundo-angelitozzz-hero-titulo" class="max-w-[600px] text-h2 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h1>
                    </div>
                    <p class="text-body-s text-tinta"><?= angelitozzz_highlight($texto) ?></p>
                </div>
                <div class="flex flex-col items-start gap-3">
                    <?php get_template_part('template-parts/components/boton', null, [
                        'enlace'   => ['url' => $suscripcion, 'target' => '_blank'],
                        'texto'    => (string) ($hero['boton'] ?? ''),
                        'variante' => 'frambuesa',
                    ]); ?>
                    <?php if ($precio !== '') : ?>
                        <p class="text-body-s text-tinta"><?= esc_html($precio) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($regalo_texto !== '') : ?>
                <p class="flex flex-wrap items-center gap-2 text-body-s text-tinta">
                    <img class="size-[18px]" src="<?= $img('hero-icono-regalo.svg') ?>" alt="" aria-hidden="true" width="18" height="18">
                    <span><?= esc_html($regalo_texto) ?></span>
                    <?php if ($whatsapp_url !== '' && $regalo_enlace !== '') : ?>
                        <?= angelitozzz_link(['url' => $whatsapp_url, 'title' => $regalo_enlace, 'target' => '_blank'], [
                            'class' => 'text-label-s text-marca underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca',
                        ]) ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="aspect-[477/390] w-full max-w-[477px] shrink-0 overflow-hidden rounded-media">
            <?= angelitozzz_image($hero['imagen'] ?? null, [
                'class'         => 'size-full object-cover',
                'alt_fallback'  => $titulo,
                'loading'       => 'eager',
                'fetchpriority' => 'high',
            ]) ?>
        </div>
    </div>
</section>
