<?php

/**
 * Solución · layout `testimonios` (specs/solucion.md → secciones): testimonios propios de la solución
 * en el componente compartido testimonio-destacado.
 *
 * $args: fila del flexible (fondo, titulo, items[texto, autora]) + contexto + indice.
 */

defined('ABSPATH') || exit;

get_template_part('template-parts/components/testimonio-destacado', null, [
    'titulo'      => (string) ($args['titulo'] ?? ''),
    'testimonios' => (array) ($args['items'] ?? []),
    'fondo'       => (string) ($args['fondo'] ?? 'beige'),
]);
