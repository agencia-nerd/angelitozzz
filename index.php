<?php
// Fallback obligatorio de WP: se usa cuando ninguna otra plantilla aplica.
get_header();
?>
<main id="main-content">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article>
                <h2><a href="<?= esc_url(get_permalink()) ?>"><?= esc_html(get_the_title()) ?></a></h2>
                <?php the_excerpt(); ?>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php esc_html_e('No se encontró contenido.', 'angelitozzz'); ?></p>
    <?php endif; ?>
</main>
<?php
get_footer();
