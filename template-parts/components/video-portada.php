<?php

/**
 * Portada de un video con botón play (solucion/galeria y modal de etapa; specs/_shared.md → video-portada).
 * El video es un archivo MP4 o WebM público de la Biblioteca de medios (campo `file` de ACF). El <video>
 * no está en el HTML: src/js/modules/video-portada.js lo crea al pulsar play (así no se descarga antes) y
 * lo quita al cerrar el modal o al cambiar de diapositiva. Sin video, o con un archivo que no es MP4 ni
 * WebM, es solo la imagen.
 *
 * $args:
 * - imagen  (array imagen | ID | URL, requerido: la portada)
 * - video   (array de un campo `file` de ACF | ID de adjunto | URL, opcional)
 * - titulo  (string, requerido: alt de la portada y nombre del botón play "Reproducir video: …")
 * - clase   (string, opcional: clases del contenedor; da el tamaño y el radio)
 * - loading ('lazy' | 'eager', por defecto 'lazy')
 */

defined('ABSPATH') || exit;

$imagen  = $args['imagen'] ?? '';
$video   = $args['video'] ?? '';
$titulo  = trim((string) ($args['titulo'] ?? ''));
$clase   = (string) ($args['clase'] ?? '');
$loading = ($args['loading'] ?? 'lazy') === 'eager' ? 'eager' : 'lazy';

// Dirección y tipo del video, solo si es MP4 o WebM.
$src  = '';
$tipo = '';
if (is_array($video)) {
    $src  = (string) ($video['url'] ?? '');
    $tipo = (string) ($video['mime_type'] ?? '');
} elseif (is_numeric($video) && (int) $video > 0) {
    $src  = (string) wp_get_attachment_url((int) $video);
    $tipo = (string) get_post_mime_type((int) $video);
} elseif (is_string($video) && trim($video) !== '') {
    $src  = trim($video);
    $tipo = (string) (wp_check_filetype((string) wp_parse_url($src, PHP_URL_PATH))['type'] ?? '');
}
if (!in_array($tipo, ['video/mp4', 'video/webm'], true) || $src === '') {
    $src  = '';
    $tipo = '';
}

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
        <button type="button" class="absolute inset-0 flex items-center justify-center focus-visible:outline-2 focus-visible:-outline-offset-4 focus-visible:outline-blanco"
            data-video-play data-video-src="<?= esc_url($src) ?>" data-video-tipo="<?= esc_attr($tipo) ?>" data-video-titulo="<?= esc_attr($titulo) ?>">
            <span class="flex size-16 items-center justify-center rounded-full bg-fondo text-marca">
                <svg class="size-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M8 5 19 12 8 19z" /></svg>
            </span>
            <span class="sr-only"><?= esc_html(sprintf(__('Reproducir video: %s', 'angelitozzz'), $titulo)) ?></span>
        </button>
    <?php endif; ?>
</div>
