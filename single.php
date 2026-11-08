<?php

/**
 * Artículo del blog (specs/articulo-blog.md). Todo lo que cambia por artículo es nativo de WordPress:
 * título, categoría, fecha, autor, imagen destacada y contenido del editor.
 * Los textos comunes a todos los artículos salen de la página de opciones "Artículo del blog".
 */

defined('ABSPATH') || exit;

get_header();

$cta_final = get_field('articulo_blog_cta_final', 'option') ?: [];
?>

<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('bg-fondo'); ?>>
            <?php
            get_template_part('template-parts/pages/articulo-blog/cabecera-del-articulo');
            get_template_part('template-parts/pages/articulo-blog/cuerpo-del-articulo');
            get_template_part('template-parts/pages/articulo-blog/escrito-por');
            ?>
        </article>

        <?php
        get_template_part('template-parts/pages/articulo-blog/te-puede-interesar');

        get_template_part('template-parts/components/cta-final', null, [
            'titulo'      => (string) ($cta_final['titulo'] ?? ''),
            'descripcion' => (string) ($cta_final['descripcion'] ?? ''),
            'boton'       => $cta_final['boton'] ?? null,
        ]);
        ?>
    <?php endwhile; ?>
</main>

<?php
get_footer();
