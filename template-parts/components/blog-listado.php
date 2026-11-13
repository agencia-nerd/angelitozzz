<?php

/**
 * Grilla paginada de entradas de la consulta principal (specs/blog.md): archivo de categoría y
 * resultados de búsqueda del blog. 12 por página, 3 columnas, paginación por números (wp-query).
 * Cada zona data-query lleva su propio espaciado: el JS de wp-query reemplaza la zona entera, y la
 * portada del blog (secciones) tiene las mismas zonas para pasar de una vista a otra sin recargar.
 *
 * $args:
 * - titulo (string, opcional: h2 solo para lectores de pantalla)
 */

defined('ABSPATH') || exit;

$titulo = trim((string) ($args['titulo'] ?? ''));
?>
<div class="bg-fondo">
    <p class="sr-only" data-query="total" aria-live="polite"><?= esc_html(angelitozzz_query_total()['texto']) ?></p>

    <div class="px-4 pt-22 pb-12 max-md:pt-12 max-md:pb-8" data-query="resultados">
        <div class="mx-auto max-w-sitio">
            <?php if ($titulo !== '') : ?>
                <h2 class="sr-only"><?= esc_html($titulo) ?></h2>
            <?php endif; ?>

            <?php if (have_posts()) : ?>
                <ul class="grid gap-7.5 md:grid-cols-2 lg:grid-cols-3" data-query-items>
                    <?php while (have_posts()) : the_post(); ?>
                        <li><?php get_template_part('template-parts/components/post-card', null, angelitozzz_post_card_args(get_the_ID())); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <?php $blog_id = (int) get_option('page_for_posts'); ?>
                <p class="text-center text-cuerpo text-marca">
                    <?= esc_html(angelitozzz_query_is_filtered()
                        ? __('No encontramos artículos con esa búsqueda.', 'angelitozzz')
                        : __('Todavía no hay artículos en esta categoría.', 'angelitozzz')) ?>
                    <a class="text-acento underline underline-offset-2" href="<?= esc_url($blog_id ? get_permalink($blog_id) : home_url('/')) ?>" data-query-ignorar><?= esc_html__('Ver todos los artículos', 'angelitozzz') ?></a>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="px-4 pb-26 max-md:pb-16" data-query="paginacion">
        <?php angelitozzz_query_pagination([
            'prev_text' => '←',
            'next_text' => '→',
            'class'     => 'paginacion',
        ]); ?>
    </div>
</div>
