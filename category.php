<?php

/**
 * Archivo de una categoría del blog: "Ver todos" de cada sección (specs/blog.md, notas de página).
 * Reutiliza hero-blog (h1 = nombre de la categoría, su pastilla activa) y blog-listado con la consulta
 * principal, que wp-query filtra y pagina (inc/query/post.php).
 */

defined('ABSPATH') || exit;

get_header();

$termino     = get_queried_object();
$nombre      = $termino instanceof WP_Term ? $termino->name : '';
$slug        = $termino instanceof WP_Term ? $termino->slug : '';
$descripcion = $termino instanceof WP_Term ? trim(wp_strip_all_tags($termino->description)) : '';

// El resto de textos del hero son los de la página de entradas (Ajustes → Lectura).
$hero = get_field('blog_hero', (int) get_option('page_for_posts')) ?: [];
?>

<main id="main-content">
    <?php
    get_template_part('template-parts/components/hero-blog', null, [
        'antetitulo'  => (string) ($hero['antetitulo'] ?? ''),
        'titulo'      => $nombre,
        'bajada'      => $descripcion !== '' ? $descripcion : (string) ($hero['bajada'] ?? ''),
        'activa'      => $slug,
    ]);

    get_template_part('template-parts/components/blog-listado', null, [
        'titulo' => sprintf(__('Artículos de %s', 'angelitozzz'), $nombre),
    ]);
    ?>
</main>

<?php
get_footer();
