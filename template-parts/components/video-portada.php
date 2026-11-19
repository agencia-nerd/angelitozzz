<?php

/**
 * Portada de un video de Vimeo con botón play (solucion/galeria y modal de etapa).
 * El iframe no está en el HTML: src/js/modules/video-portada.js lo crea al pulsar play y lo
 * quita al cerrar el modal o al cambiar de diapositiva. Sin video (o con un enlace que no es de
 * Vimeo) es solo la imagen.
 *
 * $args:
 * - imagen  (array imagen | ID | URL, requerido: la portada)
 * - video   (string, opcional: enlace de Vimeo)
 * - titulo  (string, requerido: alt de la portada y nombre del botón play "Reproducir video: …")
 * - clase   (string, opcional: clases del contenedor; da el tamaño y el radio)
 * - loading ('lazy' | 'eager', por defecto 'lazy')
 */

defined('ABSPATH') || exit;

$imagen  = $args['imagen'] ?? '';
$video   = trim((string) ($args['video'] ?? ''));
$titulo  = trim((string) ($args['titulo'] ?? ''));
$clase   = (string) ($args['clase'] ?? '');
$loading = ($args['loading'] ?? 'lazy') === 'eager' ? 'eager' : 'lazy';

// Dirección del reproductor: la arma el plugin de cursos (el único que sabe del proveedor de video).
$src = $video !== '' && function_exists('angelitozzz_cursos_video_url')
    ? angelitozzz_cursos_video_url($video, ['autoplay' => true])
    : '';

$portada = angelitozzz_image($imagen, [
    'class'        => 'absolute inset-0 size-full object-cover',
    'alt'          => $src !== '' ? '' : null, // Con play, el nombre lo da el botón.
    'alt_fallback' => $titulo,
    'loading'      => $loading,
]);

if ($portada === '' && $src === '') {
    return;
}
?>
<div class="<?= esc_attr(trim('relative overflow-hidden ' . $clase)) ?>" <?= $src !== '' ? 'data-video' : '' ?>>
    <?= $portada ?>
    <?php if ($src !== '') : ?>
        <button type="button" class="absolute inset-0 flex items-center justify-center focus-visible:outline-2 focus-visible:-outline-offset-4 focus-visible:outline-blanco" data-video-play data-video-src="<?= esc_url($src) ?>" data-video-titulo="<?= esc_attr($titulo) ?>">
            <span class="flex size-16 items-center justify-center rounded-full bg-fondo text-marca">
                <svg class="size-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M8 5 19 12 8 19z" /></svg>
            </span>
            <span class="sr-only"><?= esc_html(sprintf(__('Reproducir video: %s', 'angelitozzz'), $titulo)) ?></span>
        </button>
    <?php endif; ?>
</div>
