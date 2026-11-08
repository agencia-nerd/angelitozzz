<?php

/**
 * Template Name: Contacto
 *
 * Formulario de contacto y tarjeta de contacto directo (specs/contacto.md).
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main-content" class="bg-fondo">
    <?php
    get_template_part('template-parts/pages/contacto/seccion-de-contacto');
    get_template_part('template-parts/pages/contacto/seccion-de-cta');
    ?>
</main>

<?php
get_footer();
