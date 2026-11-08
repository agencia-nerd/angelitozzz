<?php

/**
 * Artículo del blog · cabecera (specs/articulo-blog.md, cabecera-del-articulo).
 * Todo sale de la entrada actual (dentro del loop de single.php): migas de pan, categoría, título (h1),
 * autor con su avatar de WordPress, fecha, minutos de lectura e imagen destacada.
 */

defined('ABSPATH') || exit;

$post_id    = get_the_ID();
$categorias = get_the_category($post_id);
$categoria  = $categorias ? $categorias[0] : null;
$blog_id    = (int) get_option('page_for_posts');
$blog_url   = $blog_id ? get_permalink($blog_id) : home_url('/');
$blog_nombre = $blog_id ? (string) get_post_field('post_title', $blog_id) : __('Blog', 'angelitozzz');
$autor_id   = (int) get_post_field('post_author', $post_id);
$minutos    = angelitozzz_tiempo_lectura($post_id);

// Imagen destacada: alt del adjunto o, si está vacío, el título de la entrada.
$imagen_id = (int) get_post_thumbnail_id($post_id);
$imagen    = '';
if ($imagen_id) {
    $alt    = trim((string) get_post_meta($imagen_id, '_wp_attachment_image_alt', true));
    $imagen = wp_get_attachment_image($imagen_id, 'full', false, [
        'class'         => 'size-full object-cover',
        'alt'           => $alt !== '' ? $alt : get_the_title($post_id),
        'loading'       => 'eager',
        'fetchpriority' => 'high',
        'sizes'         => '(min-width: 1392px) 1360px, calc(100vw - 32px)',
    ]);
}
?>
<header class="px-4 pt-18 pb-12 max-md:pt-10 max-md:pb-8">
    <div class="mx-auto flex max-w-[1360px] flex-col items-start gap-4.5">
        <nav aria-label="<?= esc_attr__('Migas de pan', 'angelitozzz') ?>">
            <ol class="flex flex-wrap items-center gap-x-2 text-caption text-tinta-suave">
                <li><a class="hover:text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url($blog_url) ?>"><?= esc_html($blog_nombre) ?></a></li>
                <?php if ($categoria) : ?>
                    <li aria-hidden="true">/</li>
                    <li><a class="hover:text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url(get_category_link($categoria)) ?>"><?= esc_html($categoria->name) ?></a></li>
                <?php endif; ?>
            </ol>
        </nav>

        <?php if ($categoria) {
            get_template_part('template-parts/components/etiqueta', null, [
                'texto'  => $categoria->name,
                'estilo' => 'tarjeta',
            ]);
        } ?>

        <h1 class="max-w-[1100px] text-display text-marca max-md:text-[36px] max-md:leading-[1.1]"><?= esc_html(get_the_title($post_id)) ?></h1>

        <div class="flex items-center gap-3.5">
            <?= get_avatar($autor_id, 44, '', '', ['class' => 'size-11 shrink-0 rounded-full object-cover', 'extra_attr' => 'aria-hidden="true"']) ?>
            <div class="flex min-w-0 flex-col gap-0.5">
                <p class="text-label text-marca"><?= esc_html(sprintf(__('Por %s', 'angelitozzz'), get_the_author_meta('display_name', $autor_id))) ?></p>
                <p class="text-caption text-tinta-suave">
                    <time datetime="<?= esc_attr(get_the_date('c', $post_id)) ?>"><?= esc_html(get_the_date('j \d\e F \d\e Y', $post_id)) ?></time>
                    · <?= esc_html(sprintf(__('%d min de lectura', 'angelitozzz'), $minutos)) ?>
                </p>
            </div>
        </div>

        <?php if ($imagen !== '') : ?>
            <div class="aspect-[17/8] w-full overflow-hidden rounded-tarjeta max-md:aspect-[4/3] max-md:rounded-media">
                <?= $imagen ?>
            </div>
        <?php endif; ?>
    </div>
</header>
