<?php

/**
 * Artículo del blog · escrito por (specs/articulo-blog.md, escrito-por).
 * Avatar, nombre e "Información biográfica" del autor de la entrada (perfil de usuario de WordPress).
 * Los textos son fijos e iguales en todos los artículos. "Conoce a Bárbara" lleva a su página; "Ver soluciones de
 * sueño", a la tienda de WooCommerce (sin WooCommerce queda en "#" y el componente boton lo muestra desactivado).
 */

defined('ABSPATH') || exit;

$autor_id  = (int) get_post_field('post_author', get_the_ID());
$nombre    = (string) get_the_author_meta('display_name', $autor_id);
$biografia = trim((string) get_the_author_meta('description', $autor_id));
$conoce    = get_page_by_path('conoce-a-barbara');
$tienda    = function_exists('wc_get_page_permalink') ? (string) wc_get_page_permalink('shop') : '';
?>
<aside class="px-4 py-5" aria-label="<?= esc_attr__('Sobre la autora', 'angelitozzz') ?>">
    <div class="mx-auto flex max-w-[1040px] items-center gap-7 rounded-tarjeta bg-blanco p-8 max-md:flex-col max-md:items-start max-md:gap-5 max-md:p-6">
        <?= get_avatar($autor_id, 120, '', '', ['class' => 'size-30 shrink-0 rounded-full object-cover max-md:size-22', 'extra_attr' => 'aria-hidden="true"']) ?>

        <div class="flex min-w-0 flex-1 flex-col items-start gap-2 max-md:w-full">
            <p class="text-caption text-tinta-suave"><?= esc_html__('Escrito por', 'angelitozzz') ?></p>
            <h2 class="text-h4 text-marca"><?= esc_html($nombre) ?></h2>
            <?php if ($biografia !== '') : ?>
                <p class="text-cuerpo-s"><?= esc_html($biografia) ?></p>
            <?php endif; ?>

            <div class="flex flex-wrap items-center gap-3 pt-2 max-md:w-full max-md:flex-col max-md:items-stretch">
                <?php
                get_template_part('template-parts/components/boton', null, [
                    'enlace'   => ['url' => $conoce ? get_permalink($conoce) : '', 'title' => __('Conoce a Bárbara', 'angelitozzz')],
                    'variante' => 'secundario',
                    'flecha'   => false,
                    'clase'    => 'max-md:justify-center',
                ]);
                get_template_part('template-parts/components/boton', null, [
                    'enlace'   => ['url' => $tienda !== '' ? $tienda : '#', 'title' => __('Ver soluciones de sueño', 'angelitozzz')],
                    'variante' => 'frambuesa',
                    'clase'    => 'max-md:justify-center',
                ]);
                ?>
            </div>
        </div>
    </div>
</aside>
