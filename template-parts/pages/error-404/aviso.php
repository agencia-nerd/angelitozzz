<?php

/**
 * 404 → aviso (specs/error-404.md): tarjeta "No encontramos esta página" con dos caminos, el inicio y
 * Soluciones de sueño. Textos fijos del tema (sin campos ACF), como gracias y canjear regalo.
 */

defined('ABSPATH') || exit;

$soluciones = get_post_type_archive_link('solucion');

get_template_part('template-parts/components/confirmacion-tarjeta', null, [
    'titulo'          => __('No encontramos esta página', 'angelitozzz'),
    'pastilla'        => __('Error 404', 'angelitozzz'),
    'pastilla_estilo' => 'neutro',
    'texto'           => __('Puede que el enlace esté mal escrito o que la página ya no exista. Te dejamos algunos caminos para seguir.', 'angelitozzz'),
]);
?>
    <div class="flex flex-wrap items-center justify-center gap-4 max-md:w-full max-md:flex-col max-md:items-stretch">
        <?php
        get_template_part('template-parts/components/boton', null, [
            'enlace' => home_url('/'),
            'texto'  => __('Ir al inicio', 'angelitozzz'),
            'clase'  => 'justify-center',
        ]);

        if ($soluciones) {
            get_template_part('template-parts/components/boton', null, [
                'enlace'   => $soluciones,
                'texto'    => __('Ver soluciones de sueño', 'angelitozzz'),
                'variante' => 'secundario',
                'clase'    => 'justify-center',
            ]);
        }
        ?>
    </div>
<?php
get_template_part('template-parts/components/confirmacion-tarjeta-fin');
