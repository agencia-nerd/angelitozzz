<?php

/**
 * Template Name: Crear cuenta
 *
 * Registro de una alumna (specs/crear-cuenta.md). Los formularios los procesa el plugin
 * angelitozzz-cursos; esta plantilla solo pinta (template-parts/pages/crear-cuenta/formulario.php).
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main-content" class="bg-fondo">
    <?php get_template_part('template-parts/pages/crear-cuenta/formulario'); ?>
</main>

<?php
get_footer();
