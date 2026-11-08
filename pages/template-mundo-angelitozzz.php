<?php

/**
 * Template Name: Mundo Angelitozzz
 *
 * Comunidad de suscripción mensual (specs/mundo-angelitozzz.md).
 */

defined('ABSPATH') || exit;

get_header();

// Testimonios propios de esta página (independientes de Inicio y del tipo `testimonio`).
$testimonios_grupo = get_field('mundo_angelitozzz_testimonios') ?: [];

$testimonios = [];
foreach ((array) ($testimonios_grupo['testimonios'] ?? []) as $fila) {
    $testimonios[] = [
        'texto'     => (string) ($fila['texto'] ?? ''),
        'autora'    => (string) ($fila['autora'] ?? ''),
        'detalle'   => (string) ($fila['detalle'] ?? ''),
        'estrellas' => (int) ($fila['estrellas'] ?? 0),
    ];
}
?>

<main id="main-content" class="overflow-x-clip">
    <?php
    get_template_part('template-parts/pages/mundo-angelitozzz/hero');
    get_template_part('template-parts/pages/mundo-angelitozzz/promocinar-marca');
    get_template_part('template-parts/pages/mundo-angelitozzz/que-incluye-mundo');

    get_template_part('template-parts/components/testimonios-slider', null, [
        'titulo'      => (string) ($testimonios_grupo['titulo'] ?? ''),
        'testimonios' => $testimonios,
        'boton'       => $testimonios_grupo['boton'] ?? null,
    ]);

    get_template_part('template-parts/pages/mundo-angelitozzz/cta-mundo');
    ?>
</main>

<?php
get_footer();
