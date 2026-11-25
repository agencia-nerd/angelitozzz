<?php

/**
 * Página no encontrada (specs/error-404.md). WordPress la usa sola para cualquier URL que no existe.
 * Propuesta sin Figma: la tarjeta de gracias y del canje (confirmacion-tarjeta) con caminos para seguir.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main-content" class="bg-fondo">
    <div class="px-4 py-30 max-md:py-14">
        <?php get_template_part('template-parts/pages/error-404/aviso'); ?>
    </div>
</main>

<?php
get_footer();
