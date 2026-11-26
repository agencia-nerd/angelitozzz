<?php
defined('ABSPATH') || exit;

/*
 * Constructor · paso 2 (specs/admin-constructor.md → modal-leccion y modal-archivos): el video y los
 * descargables se suben a "Archivos privados" con el script de subida por partes del plugin
 * (handle angelitozzz-cursos-subida, window.AngelitozzzSubida). El plugin lo registra; el tema lo encola
 * solo donde se usa. src/js/modules/constructor-contenido.js lo lee al momento de subir.
 */

add_action('wp_enqueue_scripts', 'angelitozzz_constructor_subida');

/**
 * Encola la subida solo en el paso 2 del constructor (y si el plugin está activo).
 */
function angelitozzz_constructor_subida(): void
{
    if (!is_page_template('pages/template-administrador.php') || !function_exists('angelitozzz_cursos_vista_admin')) {
        return;
    }
    $datos = angelitozzz_cursos_vista_admin();
    if (($datos['vista'] ?? '') !== 'constructor' || (int) ($datos['curso_id'] ?? 0) === 0 || (int) ($datos['paso'] ?? 1) !== 2) {
        return;
    }
    if (wp_script_is('angelitozzz-cursos-subida', 'registered')) {
        wp_enqueue_script('angelitozzz-cursos-subida');
    }
}

/**
 * URL del ícono de cada tipo de material (los del temario de la lección), para los chips y las filas
 * que pinta el JS: documento, hoja, presentacion, imagen y audio, más video (un video sin portada en
 * "Elegir de mis archivos").
 *
 * @return array<string, string>
 */
function angelitozzz_iconos_material_urls(): array
{
    $archivos = [
        'documento'    => 'pdf',
        'hoja'         => 'tipo-hoja',
        'presentacion' => 'tipo-presentacion',
        'imagen'       => 'tipo-imagen',
        'audio'        => 'tipo-audio',
        'video'        => 'video',
    ];
    return array_map(static fn(string $archivo): string => get_theme_file_uri('assets/img/leccion/' . $archivo . '.svg'), $archivos);
}
