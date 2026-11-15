<?php

/**
 * Solución · layout `galeria` (specs/solucion.md → secciones): título, bajada, slider de diapositivas
 * (imagen o video de Vimeo; una a la vez, flechas debajo), texto y botón de compra opcional, que hace lo
 * mismo que el del hero. Origen: Angelita Pro 4013:14037, Recién Nacidos Go 4013:18035.
 *
 * $args: fila del flexible (fondo, titulo, bajada, diapositivas[imagen, video], texto, boton, boton_precio)
 * + contexto (ver single-solucion.php) + indice.
 */

defined('ABSPATH') || exit;

$titulo       = (string) ($args['titulo'] ?? '');
$bajada       = (string) ($args['bajada'] ?? '');
$texto        = (string) ($args['texto'] ?? '');
$boton        = trim((string) ($args['boton'] ?? ''));
$boton_precio = !empty($args['boton_precio']);
$contexto     = (array) ($args['contexto'] ?? []);
$diapositivas = array_values(array_filter(
    (array) ($args['diapositivas'] ?? []),
    static fn ($fila): bool => is_array($fila) && !empty($fila['imagen'])
));

if ($titulo === '' && !$diapositivas) {
    return;
}

// woo: el precio sale del producto del curso ("Comprar ahora — $97 USD"; con etapas, "desde").
$precio = (array) ($contexto['precio'] ?? []);
if ($boton !== '' && $boton_precio && !empty($precio['actual'])) {
    $boton .= ' — ' . (!empty($precio['desde']) ? __('desde', 'angelitozzz') . ' ' : '') . $precio['actual'] . ' USD';
}

$varias = count($diapositivas) > 1;
$id     = wp_unique_id('solucion-galeria-');
$destello = static fn(string $nombre): string => get_theme_file_uri('assets/img/solucion/galeria-destello-' . $nombre . '.svg');
?>
<section class="<?= esc_attr('relative overflow-hidden px-4 py-[104px] max-md:py-14 ' . angelitozzz_solucion_fondo((string) ($args['fondo'] ?? 'rosa'))) ?>" aria-labelledby="<?= esc_attr($id) ?>-titulo">
    <div class="pointer-events-none absolute inset-y-0 left-1/2 w-sitio -translate-x-1/2 max-xl:hidden" aria-hidden="true">
        <img class="absolute -left-[90px] top-[31px] h-[25px] w-[34px]" src="<?= esc_url($destello('dorado-izquierda')) ?>" alt="" width="34" height="25" loading="lazy">
        <img class="absolute -left-[29px] top-[122px] h-[132px] w-[151px]" src="<?= esc_url($destello('blanco-izquierda')) ?>" alt="" width="151" height="132" loading="lazy">
        <img class="absolute left-[116px] top-[154px] h-[17px] w-[26px]" src="<?= esc_url($destello('teal-izquierda')) ?>" alt="" width="26" height="17" loading="lazy">
        <img class="absolute left-[1130px] top-[311px] h-[65px] w-[59px]" src="<?= esc_url($destello('dorado-derecha')) ?>" alt="" width="59" height="65" loading="lazy">
        <img class="absolute left-[1110px] top-[615px] h-[31px] w-[29px]" src="<?= esc_url($destello('teal-derecha')) ?>" alt="" width="29" height="31" loading="lazy">
        <img class="absolute left-[1189px] top-[694px] h-[70px] w-[91px]" src="<?= esc_url($destello('blanco-derecha')) ?>" alt="" width="91" height="70" loading="lazy">
    </div>

    <div class="relative mx-auto flex max-w-sitio flex-col items-center text-center">
        <h2 id="<?= esc_attr($id) ?>-titulo" class="text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
        <?php if ($bajada !== '') : ?>
            <p class="mt-4 max-w-[820px] text-cuerpo-s text-tinta"><?= angelitozzz_highlight($bajada) ?></p>
        <?php endif; ?>

        <?php if ($diapositivas) : ?>
            <div class="mt-10 w-full max-w-[614px]" data-solucion-galeria>
                <div class="swiper overflow-hidden rounded-[10px]" aria-label="<?= esc_attr($titulo) ?>">
                    <ul class="swiper-wrapper">
                        <?php foreach ($diapositivas as $n => $diapositiva) : ?>
                            <li class="swiper-slide">
                                <?php get_template_part('template-parts/components/video-portada', null, [
                                    'imagen' => $diapositiva['imagen'],
                                    'video'  => (string) ($diapositiva['video'] ?? ''),
                                    'titulo' => $varias ? sprintf('%s (%d)', wp_strip_all_tags($titulo), $n + 1) : wp_strip_all_tags($titulo),
                                    'clase'  => 'aspect-[614/298] w-full rounded-[10px]',
                                ]); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php if ($varias) : ?>
                    <div class="mt-5 flex justify-center gap-4">
                        <button type="button" class="flex size-12 items-center justify-center rounded-full border-[1.5px] border-marca text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-slider-anterior>
                            <span class="sr-only"><?= esc_html__('Anterior', 'angelitozzz') ?></span>
                            <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15.83 10H4.17M10 15.83 4.17 10 10 4.17" /></svg>
                        </button>
                        <button type="button" class="flex size-12 items-center justify-center rounded-full border-[1.5px] border-marca text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-slider-siguiente>
                            <span class="sr-only"><?= esc_html__('Siguiente', 'angelitozzz') ?></span>
                            <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4.17 10h11.66M10 4.17 15.83 10 10 15.83" /></svg>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($texto !== '') : ?>
            <p class="mt-5 max-w-[600px] text-cuerpo-s text-tinta"><?= angelitozzz_highlight($texto) ?></p>
        <?php endif; ?>

        <?php if ($boton !== '') : ?>
            <div class="mt-8">
                <?php get_template_part('template-parts/components/boton', null, [
                    'texto' => $boton,
                    'modal' => (string) ($contexto['modal_compra'] ?? 'carrito'),
                ]); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
