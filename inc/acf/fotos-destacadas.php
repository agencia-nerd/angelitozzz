<?php
defined('ABSPATH') || exit;

/*
 * Imagen destacada de los elementos de ejemplo (testimonios y entradas del blog).
 * El motor inc/acf/runner.php crea los elementos de inc/acf/seed/<contenido>.php pero no carga su 'imagen':
 * este archivo la pone una sola vez por elemento, con el mismo método del motor (angelitozzz_acf_attachment).
 * Si el cliente cambia o quita la foto, no se vuelve a pisar.
 */

// Seed de cada contenido => opción con la firma de los elementos cuyas fotos ya se cargaron. Si el seed suma
// elementos, la firma cambia y se vuelve a revisar (cada elemento tiene su propia marca: nada se duplica).
const ANGELITOZZZ_FOTOS_DESTACADAS = [
    'testimonio.php' => 'angelitozzz_testimonios_fotos_cargadas',
    'entrada.php'    => 'angelitozzz_entradas_fotos_cargadas',
];

function angelitozzz_fotos_destacadas_cargar(): void
{
    if (!function_exists('angelitozzz_acf_attachment')) {
        return;
    }

    foreach (ANGELITOZZZ_FOTOS_DESTACADAS as $seed_archivo => $opcion) {
        $archivo = get_theme_file_path('inc/acf/seed/' . $seed_archivo);
        $seed    = is_readable($archivo) ? require $archivo : [];
        $items   = is_array($seed) ? (array) ($seed['items'] ?? []) : [];
        $firma   = md5(implode(',', array_map(static fn($item) => (string) ($item['id'] ?? ''), $items)));
        if (get_option($opcion) === $firma) {
            continue;
        }
        $tipo    = (string) ($seed['post_type'] ?? 'post');
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

        if (!$pendiente && $items) {
            update_option($opcion, $firma, false);
        }
    }
}
add_action('init', 'angelitozzz_fotos_destacadas_cargar', 20);
