<?php

/**
 * Artículo del blog · también te puede interesar (specs/articulo-blog.md, te-puede-interesar).
 * El título y "Ver todo →" son textos fijos, iguales en todos los artículos.
 * Tarjetas (wp-query): las 3 entradas más recientes de la misma categoría, sin la actual; si son menos de 3,
 * se completan con las más recientes del blog (tampoco la actual ni las ya elegidas). Sin otras entradas, la
 * sección no se imprime.
 */

defined('ABSPATH') || exit;

$post_id    = get_the_ID();
$categorias = get_the_category($post_id);
$blog_id    = (int) get_option('page_for_posts');
$ver_todo   = $categorias ? get_category_link($categorias[0]) : ($blog_id ? get_permalink($blog_id) : home_url('/'));

// Relacionadas por categoría (motor de wp-query: inc/query/post.php define 'relacionadas' => 'category').
$ids         = [];
$relacionadas = angelitozzz_query_related($post_id, 3);
while ($relacionadas->have_posts()) {
    $relacionadas->the_post();
    $ids[] = get_the_ID();
}
wp_reset_postdata();

// Relleno: las más recientes del blog, sin la actual ni las que ya salieron.
if (count($ids) < 3) {
    $ids = array_merge($ids, array_map('intval', get_posts([
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 3 - count($ids),
        'post__not_in'        => array_merge([$post_id], $ids),
        'fields'              => 'ids',
        'no_found_rows'       => true,
        'ignore_sticky_posts' => true,
    ])));
}

$tarjetas = array_map('angelitozzz_post_card_args', $ids);

if (!$tarjetas) {
    return;
}
?>
<section class="bg-blanco px-4 py-22 max-md:py-14" aria-labelledby="te-puede-interesar-titulo">
    <div class="mx-auto flex max-w-sitio flex-col gap-7">
        <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-2 max-md:flex-col max-md:items-start">
            <h2 id="te-puede-interesar-titulo" class="text-h3 text-marca"><?= esc_html__('También te puede interesar', 'angelitozzz') ?></h2>
            <a class="text-body-s text-marca hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url($ver_todo) ?>"><?= esc_html__('Ver todo →', 'angelitozzz') ?></a>
        </div>

        <ul class="grid gap-7.5 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($tarjetas as $tarjeta) : ?>
                <li><?php get_template_part('template-parts/components/post-card', null, $tarjeta); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
