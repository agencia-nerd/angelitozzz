<?php

/**
 * Temario de la lección en móvil (specs/leccion.md → temario): un solo <details> de wp-accordion,
 * "Contenido del curso", plegado al cargar y sobre el video. Dentro, el mismo temario de la columna
 * de escritorio (template-parts/pages/leccion/temario.php) sin su encabezado: la lección actual queda
 * marcada al abrirlo. En escritorio no se imprime (la vista lo oculta con lg:hidden).
 *
 * $args: temario, con_acceso (los de template-parts/pages/leccion/temario.php).
 */

defined('ABSPATH') || exit;

if (empty($args['temario'])) {
    return;
}

angelitozzz_accordion_start('leccion-temario', [
    'icon'          => 'chevron',
    'heading'       => 2,
    'class'         => 'w-full',
    'item_class'    => 'rounded-[15px] bg-blanco',
    'summary_class' => 'px-[15px] py-3',
    'title_class'   => 'flex items-center gap-3 text-h4 text-marca',
    'icon_class'    => 'text-marca',
    'panel_class'   => 'px-[10px] pb-[10px]',
]);

angelitozzz_accordion_item_start(__('Contenido del curso', 'angelitozzz'));
get_template_part('template-parts/pages/leccion/temario', null, [
    'temario'    => $args['temario'],
    'con_acceso' => !empty($args['con_acceso']),
]);
angelitozzz_accordion_item_end();

angelitozzz_accordion_end();
