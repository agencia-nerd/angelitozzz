<?php
defined('ABSPATH') || exit;

/*
 * Entradas del blog: datos de una entrada listos para template-parts/components/post-card.php.
 * Lo usan home.php, category.php y los resultados de búsqueda (specs/blog.md).
 */

// Minutos de lectura del contenido: palabras / 200, redondeado hacia arriba, mínimo 1.
function angelitozzz_tiempo_lectura(int $post_id): int
{
    $texto    = wp_strip_all_tags(strip_shortcodes((string) get_post_field('post_content', $post_id)));
    $palabras = count(preg_split('/\s+/u', trim($texto), -1, PREG_SPLIT_NO_EMPTY) ?: []);
    return max(1, (int) ceil($palabras / 200));
}

// $args de post-card para una entrada. La etiqueta de esquina es su primera categoría real.
function angelitozzz_post_card_args(int $post_id): array
{
    $categorias = get_the_category($post_id);

    return [
        'imagen'    => get_post_thumbnail_id($post_id) ?: null,
        'categoria' => $categorias ? $categorias[0]->name : '',
        'fecha'     => get_the_date('j \d\e F \d\e Y', $post_id),
        'lectura'   => sprintf(__('%d min de lectura', 'angelitozzz'), angelitozzz_tiempo_lectura($post_id)),
        'titulo'    => get_the_title($post_id),
        'resumen'   => get_the_excerpt($post_id),
        'url'       => get_permalink($post_id),
    ];
}
