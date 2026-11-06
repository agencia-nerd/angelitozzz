<?php

/**
 * Template Name: Conoce a Bárbara
 *
 * Historia de Bárbara (specs/conoce-a-barbara.md).
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main-content" class="bg-fondo">
    <?php
    get_template_part('template-parts/pages/conoce-a-barbara/hero');
    get_template_part('template-parts/pages/conoce-a-barbara/reflexion');
    get_template_part('template-parts/pages/conoce-a-barbara/seccion-blog');

    // Cierre: componente compartido cta-final. Sin descripción en esta página.
    get_template_part('template-parts/components/cta-final', null, [
        'titulo'      => 'La maternidad ya tiene suficientes sacrificios. El sueño no tiene que ser uno de ellos.',
        'descripcion' => '',
        // Lleva a la tienda (Soluciones de sueño), que aún no existe (pendiente).
        'boton'       => ['url' => '#', 'title' => 'Encuentra tu solución', 'target' => ''],
    ]);
    ?>
</main>

<?php
get_footer();
