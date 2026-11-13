<?php
defined('ABSPATH') || exit;

/**
 * Tutor LMS carga sus hojas de estilo en todas las páginas. Fuera de las páginas de Tutor no hacen
 * falta y, como no están en una capa de CSS, le ganan a las utilidades de Tailwind. Sus clases
 * `.text-label` y `.text-body` chocaban con los tokens del tema, que por eso se llaman
 * text-rotulo y text-cuerpo. Aquí se quitan donde no hay contenido de Tutor.
 *
 * No se usa el filtro `tutor_should_load_legacy_scripts`: con `false`, Tutor no apaga sus assets,
 * sino que carga en su lugar los del sistema nuevo (tutor-core.min.css y la fuente Inter de Google).
 */

/**
 * ¿La página actual muestra contenido de Tutor LMS (curso, lección, listado de cursos, panel,
 * login o registro de Tutor, o un shortcode/bloque de Tutor)?
 */
function angelitozzz_es_pagina_tutor(): bool
{
    if (!function_exists('tutor') || !function_exists('tutor_utils')) {
        return false;
    }

    $es_tutor = (function_exists('is_single_course') && is_single_course(true))
        || is_post_type_archive(tutor()->course_post_type)
        || is_tax(['course-category', 'course-tag'])
        || tutor_utils()->is_dashboard_page();

    if (!$es_tutor && is_singular()) {
        $id        = get_queried_object_id();
        $paginas   = array_map('intval', [
            tutor_utils()->get_option('tutor_dashboard_page_id'),
            tutor_utils()->get_option('student_register_page'),
            tutor_utils()->get_option('instructor_register_page'),
        ]);
        $contenido = (string) get_post_field('post_content', $id);
        $es_tutor  = in_array($id, $paginas, true)
            || str_contains($contenido, '[tutor_')
            || str_contains($contenido, '<!-- wp:tutor-gutenberg/');
    }

    return (bool) apply_filters('angelitozzz_es_pagina_tutor', $es_tutor);
}

function angelitozzz_tutor_quitar_estilos(): void
{
    if (angelitozzz_es_pagina_tutor()) {
        return;
    }

    foreach (['tutor', 'tutor-icon', 'tutor-frontend', 'tutor-cart-button', 'tutor-plyr'] as $handle) {
        wp_dequeue_style($handle);
    }
}
// Después de Tutor, que encola en la prioridad 10.
add_action('wp_enqueue_scripts', 'angelitozzz_tutor_quitar_estilos', 100);
