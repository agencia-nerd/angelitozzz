<?php

/**
 * Lección · video (specs/leccion.md → video, Figma 4176:28055): marco 16:9 con la portada del video y
 * el botón play. El reproductor es el de angelitozzz_cursos_video_html(): HTML ya escapado del plugin
 * (el único lugar que conoce al proveedor), un <video> con la dirección firmada, la marca de agua con
 * el correo y el botón "Pantalla completa". Va dentro de un <template> y src/js/modules/video-leccion.js
 * lo pone al pulsar play: al abrir la página solo se pide la portada. El mismo JS mueve la marca, hace
 * la pantalla completa del marco entero y renueva la dirección si vence (ruta REST del plugin). Los
 * estilos del reproductor están en src/css/leccion-video.css. Sin JS, el reproductor sale en
 * <noscript>, encima del botón. Sin video, la lección no muestra el marco.
 *
 * $args:
 * - leccion_id (int)
 */

defined('ABSPATH') || exit;

$leccion_id = (int) ($args['leccion_id'] ?? 0);
$html       = function_exists('angelitozzz_cursos_video_html') ? angelitozzz_cursos_video_html($leccion_id) : '';

if ($html === '') {
    return;
}

$titulo  = get_the_title($leccion_id);
$portada = function_exists('angelitozzz_cursos_video_portada') ? angelitozzz_cursos_video_portada($leccion_id) : '';
?>
<div class="leccion-video relative aspect-video w-full overflow-hidden rounded-[15px] bg-neutro" data-video-leccion
    data-video-leccion-url="<?= esc_url(rest_url('angelitozzz/v1/lecciones/' . $leccion_id . '/video')) ?>"
    data-video-leccion-nonce="<?= esc_attr(wp_create_nonce('wp_rest')) ?>"
    data-texto-salir="<?= esc_attr__('Salir de pantalla completa', 'angelitozzz') ?>"
    data-texto-error="<?= esc_attr__('No pudimos cargar el video. Recarga la página.', 'angelitozzz') ?>">
    <template data-video-leccion-plantilla><?= $html // HTML ya escapado por el plugin. ?></template>
    <?php if ($portada !== '') : ?>
        <img class="absolute inset-0 size-full object-cover" src="<?= esc_url($portada) ?>" alt="" data-video-leccion-portada>
    <?php endif; ?>
    <button class="absolute inset-0 flex cursor-pointer items-center justify-center focus-visible:outline-2 focus-visible:-outline-offset-4 focus-visible:outline-marca" type="button" data-video-leccion-play>
        <span class="flex size-[88px] items-center justify-center rounded-full bg-fondo text-marca shadow-[0_8px_24px_rgba(35,69,73,0.12)] max-md:size-16">
            <?= angelitozzz_svg('leccion/play', 'size-8 max-md:size-6') // SVG fijo del tema. ?>
        </span>
        <span class="sr-only"><?= esc_html(sprintf(
            /* translators: %s: título de la lección */
            __('Reproducir video: %s', 'angelitozzz'),
            $titulo
        )) ?></span>
    </button>
    <p class="absolute inset-x-4 bottom-4 rounded-xl bg-blanco px-4 py-3 text-cuerpo-s text-tinta" role="alert" hidden data-video-leccion-error></p>
    <noscript><div class="absolute inset-0 z-10"><?= $html // HTML ya escapado por el plugin. ?></div></noscript>
</div>
