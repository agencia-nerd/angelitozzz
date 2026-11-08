<?php
defined('ABSPATH') || exit;

/*
 * Foto (imagen destacada) de los testimonios de ejemplo.
 * El motor inc/acf/runner.php crea los testimonios de inc/acf/seed/testimonio.php pero no carga su 'imagen':
 * este archivo la pone una sola vez por testimonio, con el mismo método del motor (angelitozzz_acf_attachment).
 * Si el cliente cambia o quita la foto, no se vuelve a pisar.
 */

const ANGELITOZZZ_TESTIMONIOS_FOTOS_OPCION = 'angelitozzz_testimonios_fotos_cargadas';

function angelitozzz_testimonios_fotos_cargar(): void
{
    if (get_option(ANGELITOZZZ_TESTIMONIOS_FOTOS_OPCION) || !function_exists('angelitozzz_acf_attachment')) {
        return;
    }

    $archivo = get_theme_file_path('inc/acf/seed/testimonio.php');
    $seed    = is_readable($archivo) ? require $archivo : [];
    $items   = is_array($seed) ? (array) ($seed['items'] ?? []) : [];
    $tipo    = (string) ($seed['post_type'] ?? 'testimonio');
    $pendiente = false;

    foreach ($items as $item) {
        $seed_id = (string) ($item['id'] ?? '');
        $foto    = (array) ($item['imagen'] ?? []);
        if ($seed_id === '' || empty($foto['_file'])) {
            continue;
        }

        $encontrados = get_posts([
            'post_type'      => $tipo,
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => '_angelitozzz_seed_id',
            'meta_value'     => $seed_id,
        ]);
        if (!$encontrados) {
            // Todavía no se cargó el contenido inicial: se reintenta en la próxima visita.
            $pendiente = true;
            continue;
        }

        $post_id = (int) $encontrados[0];
        if (get_post_meta($post_id, '_angelitozzz_seed_foto', true)) {
            continue;
        }

        $adjunto = angelitozzz_acf_attachment((string) $foto['_file']);
        if (!$adjunto) {
            $pendiente = true;
            continue;
        }

        if (!has_post_thumbnail($post_id)) {
            set_post_thumbnail($post_id, $adjunto);
        }
        if (!empty($foto['alt']) && get_post_meta($adjunto, '_wp_attachment_image_alt', true) === '') {
            update_post_meta($adjunto, '_wp_attachment_image_alt', (string) $foto['alt']);
        }
        update_post_meta($post_id, '_angelitozzz_seed_foto', 1);
    }

    if (!$pendiente) {
        update_option(ANGELITOZZZ_TESTIMONIOS_FOTOS_OPCION, 1, false);
    }
}
add_action('init', 'angelitozzz_testimonios_fotos_cargar', 20);
