<?php

/**
 * Blog · una sección de categoría (specs/blog.md, sección categoria-especiales).
 * home.php la llama una vez por cada fila de blog_categorias_secciones, en su orden.
 *
 * $args:
 * - nombre   (string, requerido: h2)
 * - slug     (string, requerido: id del título)
 * - total    (int, entradas publicadas de la categoría)
 * - url      (string, archivo de la categoría: "Ver todos")
 * - entradas (array, hasta 3 arrays con los $args de post-card)
 */

defined('ABSPATH') || exit;

$nombre   = trim((string) ($args['nombre'] ?? ''));
$slug     = sanitize_title((string) ($args['slug'] ?? $nombre));
$total    = (int) ($args['total'] ?? 0);
$url      = (string) ($args['url'] ?? '');
$entradas = array_slice((array) ($args['entradas'] ?? []), 0, 3);

if ($nombre === '' || !$entradas) {
    return;
}

$titulo_id = 'categoria-' . $slug;
$conteo    = sprintf(_n('%d artículo', '%d artículos', $total, 'angelitozzz'), $total);
?>
<section class="px-4 py-10" aria-labelledby="<?= esc_attr($titulo_id) ?>">
    <div class="mx-auto flex max-w-sitio flex-col gap-7">
        <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-3">
            <div class="flex flex-wrap items-center gap-3">
                <h2 id="<?= esc_attr($titulo_id) ?>" class="text-h3 text-marca"><?= esc_html($nombre) ?></h2>
                <?php if ($total > 0) : ?>
                    <span class="rounded-full bg-neutro px-3 py-1 text-label-s text-marca"><?= esc_html($conteo) ?></span>
                <?php endif; ?>
            </div>

            <?php if ($url !== '') : ?>
                <a class="group flex items-center gap-2 text-label text-acento focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url($url) ?>">
                    <?= esc_html__('Ver todos', 'angelitozzz') ?>
                    <span class="sr-only"><?= esc_html(sprintf(__('los artículos de %s', 'angelitozzz'), $nombre)) ?></span>
                    <img class="size-[18px] shrink-0 transition-transform group-hover:translate-x-1" src="<?= esc_url(get_theme_file_uri('assets/img/blog/flecha-ver-todos.svg')) ?>" alt="" aria-hidden="true" width="18" height="18">
                </a>
            <?php endif; ?>
        </div>

        <ul class="grid gap-7.5 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($entradas as $entrada) : ?>
                <li><?php get_template_part('template-parts/components/post-card', null, $entrada); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
