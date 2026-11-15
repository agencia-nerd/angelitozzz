<?php

/**
 * Template Name: Iniciar sesión
 *
 * Inicio de sesión (specs/iniciar-sesion.md). Los formularios los procesa el plugin
 * angelitozzz-cursos; esta plantilla solo pinta (template-parts/pages/iniciar-sesion/formulario.php).
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main-content" class="bg-fondo">
    <?php get_template_part('template-parts/pages/iniciar-sesion/formulario'); ?>
</main>

<?php
get_footer();
