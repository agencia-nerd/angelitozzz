<?php

/**
 * Conoce a Bárbara · últimos artículos (specs/conoce-a-barbara.md, grupo conoce_a_barbara_seccion_blog).
 * Muestra los artículos que el admin eligió en la relación, en su orden. Si no elige ninguno, salen los
 * 3 últimos artículos publicados del blog (lista secundaria: no toca la consulta principal de la página).
 */

defined('ABSPATH') || exit;

$blog   = get_field('conoce_a_barbara_seccion_blog') ?: [];
$titulo = (string) ($blog['titulo'] ?? '');
$boton  = $blog['boton'] ?? null;

$ids = [];
// Un campo vacío llega como '' (no como lista): sin el filtro, el ID 0 haría que get_post_status() mire la página actual.
foreach (array_filter((array) ($blog['articulos'] ?? [])) as $articulo) {
    $post_id = $articulo instanceof WP_Post ? $articulo->ID : (int) $articulo;
    if ($post_id > 0 && get_post_type($post_id) === 'post' && get_post_status($post_id) === 'publish') {
        $ids[] = $post_id;
    }
}

if (!$ids) {
    $ids = array_map('intval', get_posts([
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 3,
        'fields'              => 'ids',
        'no_found_rows'       => true,
        'ignore_sticky_posts' => true,
    ]));
}

$articulos = array_map('angelitozzz_post_card_args', $ids);
?>
<section class="bg-fondo px-4 py-26 max-md:py-16"<?= $titulo !== '' ? ' aria-labelledby="conoce-a-barbara-blog-titulo"' : '' ?>>
    <div class="mx-auto flex max-w-sitio flex-col gap-10">
        <?php if ($titulo !== '') : ?>
            <h2 id="conoce-a-barbara-blog-titulo" class="text-h2 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
        <?php endif; ?>

        <?php if ($articulos) : ?>
            <ul class="grid gap-7.5 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($articulos as $articulo) : ?>
                    <li><?php get_template_part('template-parts/components/post-card', null, $articulo); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="flex justify-center">
            <?php get_template_part('template-parts/components/boton', null, [
                'enlace'   => $boton,
                'variante' => 'frambuesa',
            ]); ?>
        </div>
    </div>
</section>
