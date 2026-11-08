<?php

/**
 * Página de entradas del blog (specs/blog.md). WordPress la usa para la página fijada en Ajustes → Lectura.
 * Los textos del hero y las categorías de las secciones salen del grupo ACF de esa página.
 * Sin búsqueda: una sección por categoría elegida con sus 3 entradas más recientes (no pagina).
 * Con búsqueda (?q=): la consulta principal, que wp-query filtra y pagina, en blog-listado.
 */

defined('ABSPATH') || exit;

get_header();

$blog_id   = (int) get_option('page_for_posts');
$hero      = get_field('blog_hero', $blog_id) ?: [];
$secciones = get_field('blog_categorias', $blog_id)['secciones'] ?? [];
$buscando  = angelitozzz_query_is_filtered();

$categorias = [];
if (!$buscando) {
    foreach ((array) $secciones as $seccion) {
        $termino = get_term((int) ($seccion['categoria'] ?? 0), 'category');
        if (!$termino instanceof WP_Term) {
            continue;
        }

        // Lista secundaria de solo lectura: no toca la consulta principal ni el loop global.
        $ids = get_posts([
            'post_type'        => 'post',
            'post_status'      => 'publish',
            'cat'              => $termino->term_id,
            'posts_per_page'   => 3,
            'fields'           => 'ids',
            'no_found_rows'    => true,
            'ignore_sticky_posts' => true,
        ]);

        $categorias[] = [
            'nombre'   => $termino->name,
            'slug'     => $termino->slug,
            'total'    => (int) $termino->count,
            'url'      => get_category_link($termino),
            'entradas' => array_map('angelitozzz_post_card_args', array_map('intval', $ids)),
        ];
    }
}
?>

<main id="main-content">
    <?php
    get_template_part('template-parts/components/hero-blog', null, [
        'antetitulo'  => (string) ($hero['antetitulo'] ?? ''),
        'titulo'      => (string) get_post_field('post_title', $blog_id),
        'bajada'      => (string) ($hero['bajada'] ?? ''),
        'activa'      => '',
    ]);

    if ($buscando) {
        get_template_part('template-parts/components/blog-listado', null, [
            'titulo' => __('Resultados de la búsqueda', 'angelitozzz'),
        ]);
    }
    ?>

    <?php if (!$buscando) : ?>
        <?php // Mismas zonas que blog-listado: una búsqueda sin recargar reemplaza las secciones por la grilla. ?>
        <div class="bg-fondo">
            <p class="sr-only" data-query="total" aria-live="polite"></p>
            <div class="pt-12 pb-16" data-query="resultados">
                <?php foreach ($categorias as $categoria) {
                    get_template_part('template-parts/pages/blog/categoria', null, $categoria);
                } ?>
            </div>
            <div data-query="paginacion"></div>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
