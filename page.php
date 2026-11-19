<?php
/*
 * Página con contenido del editor: Privacidad, Términos y cualquier página sin plantilla propia.
 * Creada por wp-woocommerce (el tema no tenía page.php e index.php solo imprime el extracto); desde ahora es del
 * proyecto.
 *
 * Carrito y Finalizar compra también pasan por aquí: su contenido es el shortcode de Woo, que imprime las
 * plantillas de woocommerce/ con su propio diseño (título y anchos incluidos), así que van sin envoltorio.
 * Las demás páginas usan el aspecto del contenido de los artículos (src/css/articulo-contenido.css).
 */
defined('ABSPATH') || exit;

$es_woo = function_exists('is_cart') && (is_cart() || is_checkout());

get_header();
?>
<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <?php if ($es_woo) : ?>
            <?php the_content(); ?>
        <?php else : ?>
            <article class="mx-auto flex max-w-[1100px] flex-col gap-10 px-6 py-[120px] max-md:px-4 max-md:py-16">
                <h1 class="text-display text-marca max-md:text-[36px] max-md:leading-[1.1]"><?= esc_html(get_the_title()) ?></h1>
                <div class="articulo-contenido">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endif; ?>
    <?php endwhile; ?>
</main>
<?php
get_footer();
