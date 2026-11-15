<?php

/**
 * Lección · video (specs/leccion.md, Figma 4176:28055): marco 16:9 con el botón play. El reproductor
 * es el de angelitozzz_cursos_video_html() (HTML ya escapado del plugin, el único lugar que conoce al
 * proveedor): va dentro de un <template> y src/js/modules/video-leccion.js lo pone al pulsar play, con
 * autoplay. Así no hay peticiones a Vimeo al abrir la página. Sin JS, el reproductor sale en <noscript>.
 * Sin video, la lección no muestra el marco.
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

$titulo = get_the_title($leccion_id);
?>
<div class="relative aspect-video w-full overflow-hidden rounded-[15px] bg-neutro [&_iframe]:absolute [&_iframe]:inset-0 [&_iframe]:size-full" data-video-leccion>
    <template data-video-leccion-plantilla><?= $html // HTML ya escapado por el plugin. ?></template>
    <noscript><?= $html // HTML ya escapado por el plugin. ?></noscript>
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
</div>
