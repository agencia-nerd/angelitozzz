<?php

/**
 * Archivo de testimonios, /testimonios/ (specs/testimonios.md).
 * Los textos de la página viven en la página de opciones del archivo. La lista y el filtro son de wp-query.
 */

defined('ABSPATH') || exit;

get_header();

$cta_final = get_field('testimonios_cta_final', 'option') ?: [];
?>

<main id="main-content">
    <?php
    get_template_part('template-parts/pages/testimonios/seccion-testimonios');

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
