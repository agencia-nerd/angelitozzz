<?php

defined('ABSPATH') || exit;

/*
 * Administrador de cursos (pages/template-administrador.php): título SEO de cada vista
 * (admin-escritorio.md, admin-cursos.md, admin-constructor.md). El noindex está en
 * inc/paginas-privadas.php; las URL y el acceso son del plugin angelitozzz-cursos.
 */

add_filter('document_title_parts', 'angelitozzz_titulo_administrador');

/**
 * "Administrador | Angelitozzz", "Cursos | Administrador | Angelitozzz" y el título del paso del
 * constructor ("Crear nuevo curso | Administrador | Angelitozzz").
 */
function angelitozzz_titulo_administrador(array $partes): array
{
    if (!is_page_template('pages/template-administrador.php') || !function_exists('angelitozzz_cursos_vista_admin')) {
        return $partes;
    }

    $datos  = angelitozzz_cursos_vista_admin();
    $titulo = __('Administrador', 'angelitozzz');

    if ($datos['vista'] === 'cursos') {
        $titulo = __('Cursos', 'angelitozzz') . ' | ' . $titulo;
    } elseif ($datos['vista'] === 'constructor') {
        $titulo = angelitozzz_titulo_paso_constructor($datos['curso_id'], $datos['paso']) . ' | ' . $titulo;
    }

    $partes['title'] = $titulo;
    unset($partes['page'], $partes['tagline']);
    return $partes;
}

/**
 * Título (h1) de un paso del constructor.
 */
function angelitozzz_titulo_paso_constructor(int $curso_id, int $paso): string
{
    if ($paso === 2) {
        return __('Contenido del curso', 'angelitozzz');
    }
    if ($paso === 3) {
        return __('Publicar curso', 'angelitozzz');
    }
    return $curso_id ? __('Editar curso', 'angelitozzz') : __('Crear nuevo curso', 'angelitozzz');
}
