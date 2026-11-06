<?php

/**
 * Portada del sitio. WordPress la usa sola para la página fijada como inicio
 * (no tiene Template Name ni se elige en el admin). Spec: specs/home.md.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main-content" class="fondo-nubes">
    <?php
    get_template_part('template-parts/pages/home/hero');
    get_template_part('template-parts/pages/home/como-ayudarte');
    get_template_part('template-parts/pages/home/quien-soy');
    get_template_part('template-parts/pages/home/seccion-cta');
    get_template_part('template-parts/pages/home/beneficios');
    get_template_part('template-parts/pages/home/seccion-instagram');
    get_template_part('template-parts/pages/home/testimonios');
    get_template_part('template-parts/pages/home/cta-final');
    ?>
</main>

<?php
get_footer();
