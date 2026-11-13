<?php

/**
 * Cierre de página (componente Figma "CTA final"): fondo rosa, título, descripción opcional y botón.
 *
 * $args:
 * - titulo      (string, requerido)
 * - descripcion (string, opcional: se oculta si viene vacía)
 * - boton       (array link, requerido)
 */

defined('ABSPATH') || exit;

$titulo      = (string) ($args['titulo'] ?? '');
$descripcion = (string) ($args['descripcion'] ?? '');
$boton       = $args['boton'] ?? '';

if ($titulo === '') {
    return;
}

$id = wp_unique_id('cta-final-');
?>
<section class="relative overflow-hidden bg-fondo-rosa px-4 py-26 max-md:py-20" aria-labelledby="<?= esc_attr($id) ?>">
    <img class="pointer-events-none absolute left-[46px] top-7 w-[120px] max-md:left-3 max-md:top-3 max-md:w-[70px]" src="<?= esc_url(get_theme_file_uri('assets/img/cta-final-destellos-arriba.svg')) ?>" alt="" aria-hidden="true" width="120" height="85" loading="lazy">
    <img class="pointer-events-none absolute bottom-[9px] left-[56px] w-[99px] max-md:left-3 max-md:w-[60px]" src="<?= esc_url(get_theme_file_uri('assets/img/cta-final-destellos-abajo.svg')) ?>" alt="" aria-hidden="true" width="99" height="39" loading="lazy">
    <img class="pointer-events-none absolute left-[256px] top-[203px] w-[248px] max-lg:hidden" src="<?= esc_url(get_theme_file_uri('assets/img/cta-final-destello-grande.svg')) ?>" alt="" aria-hidden="true" width="248" height="614" loading="lazy">
    <img class="pointer-events-none absolute right-[167px] top-[98px] w-[68px] max-lg:right-4 max-lg:top-4 max-md:w-10" src="<?= esc_url(get_theme_file_uri('assets/img/cta-final-destello-derecha.svg')) ?>" alt="" aria-hidden="true" width="68" height="71" loading="lazy">

    <div class="relative mx-auto flex max-w-sitio flex-col items-center gap-5 text-center">
        <h2 id="<?= esc_attr($id) ?>" class="text-h2 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
        <?php if ($descripcion !== '') : ?>
            <p class="text-cuerpo-l"><?= angelitozzz_highlight($descripcion) ?></p>
        <?php endif; ?>
        <div class="mt-2">
            <?php get_template_part('template-parts/components/boton', null, [
                'enlace'   => $boton,
                'variante' => 'frambuesa',
            ]); ?>
        </div>
    </div>
</section>
