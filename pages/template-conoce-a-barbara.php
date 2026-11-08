<?php

/**
 * Template Name: Conoce a Bárbara
 *
 * Historia de Bárbara (specs/conoce-a-barbara.md).
 */

defined('ABSPATH') || exit;

get_header();

$cta_final = get_field('conoce_a_barbara_cta_final') ?: [];
?>

<main id="main-content" class="bg-fondo">
    <?php
    get_template_part('template-parts/pages/conoce-a-barbara/hero');
    get_template_part('template-parts/pages/conoce-a-barbara/reflexion');
    get_template_part('template-parts/pages/conoce-a-barbara/seccion-blog');

    // Cierre: componente compartido cta-final.
    get_template_part('template-parts/components/cta-final', null, [
        'titulo'      => (string) ($cta_final['titulo'] ?? ''),
        'descripcion' => (string) ($cta_final['descripcion'] ?? ''),
        'boton'       => $cta_final['boton'] ?? null,
    ]);
    ?>
</main>

<?php
get_footer();
