<?php
defined('ABSPATH') || exit;

/*
 * Tipo de contenido `solucion` (specs/soluciones-de-sueno.md y specs/solucion.md).
 * El tipo lo registra ACF desde acf-json/, con sus URL del spec (/soluciones-de-sueno/ y /soluciones-de-sueno/<slug>/).
 * Aquí: la tarjeta de producto-card de una solución (listado y relacionados). El botón de WhatsApp de las landings es angelitozzz_whatsapp() (inc/helpers/whatsapp.php).
 */

// Las URL del tipo necesitan refrescar las reglas de reescritura una sola vez (también al publicar el tema).
function angelitozzz_solucion_refrescar_reglas(): void
{
    $version = '2';
    if (get_option('angelitozzz_solucion_reglas') !== $version && post_type_exists('solucion')) {
        flush_rewrite_rules(false);
        update_option('angelitozzz_solucion_reglas', $version, false);
    }
}
add_action('init', 'angelitozzz_solucion_refrescar_reglas', 99);

/**
 * Argumentos de producto-card para una solución.
 * - 'completa' (listado): grupo solucion_tarjeta, con descripción y beneficios.
 * - 'corta' (relacionados): etiqueta e ícono del hero, edad con el precio y botón "Más información".
 * Nombre = título, foto = imagen destacada (sin foto, el componente usa la imagen "not found").
 */
function angelitozzz_solucion_tarjeta_args(int $post_id, string $variante = 'completa'): array
{
    $tarjeta = get_field('solucion_tarjeta', $post_id) ?: [];
    $url     = (string) get_permalink($post_id);
    $args    = [
        'imagen' => (int) get_post_thumbnail_id($post_id),
        'titulo' => get_the_title($post_id),
    ];

    if ($variante === 'corta') {
        $hero   = get_field('solucion_hero', $post_id) ?: [];
        $edad   = trim((string) ($tarjeta['edad'] ?? ''), '() ');
        // Precio del producto de Woo de sus cursos (inc/woocommerce/precios.php).
        $precio = function_exists('angelitozzz_solucion_precio')
            ? angelitozzz_solucion_precio($post_id)
            : ['actual' => '', 'desde' => false];
        if ($precio['actual'] !== '') {
            $edad .= ($edad !== '' ? ' · ' : '') . ($precio['desde'] ? __('Desde', 'angelitozzz') . ' ' : '') . $precio['actual'];
        }

        return $args + [
            'etiqueta'       => (string) ($hero['etiqueta'] ?? ''),
            'etiqueta_icono' => (string) ($hero['etiqueta_icono'] ?? 'ninguno'),
            'edad'           => $edad,
            'boton'          => ['title' => __('Más información', 'angelitozzz'), 'url' => $url],
        ];
    }

    $beneficios = array_values(array_filter(array_map(
        static fn($fila) => trim((string) ($fila['texto'] ?? '')),
        (array) ($tarjeta['beneficios'] ?? [])
    )));

    return $args + [
        'etiqueta'       => (string) ($tarjeta['etiqueta'] ?? ''),
        'etiqueta_icono' => (string) ($tarjeta['etiqueta_icono'] ?? 'ninguno'),
        'edad'           => (string) ($tarjeta['edad'] ?? ''),
        'descripcion'    => (string) ($tarjeta['descripcion'] ?? ''),
        'beneficios'     => $beneficios,
        'boton'          => ['title' => (string) ($tarjeta['boton'] ?? ''), 'url' => $url],
    ];
}
