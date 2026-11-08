<?php
defined('ABSPATH') || exit;

/*
 * Listado "blog" (contenido post). Lo genera wp-query (generate.mjs) desde
 * specs/blog.md, su spec de detalle y _shared.md: no se edita a mano. Cambia el spec y vuelve a generar.
 */
return [
    'contenido' => 'post',
    'listado' => 'blog',
    'post_type' => 'post',
    'taxonomias' => [
        'category',
    ],
    'por_pagina' => 12,
    'paginacion' => 'numeros',
    'al_filtrar' => 'boton',
    'movil' => 'igual',
    'relacionadas' => 'category',
    'filtros' => [
        'categoria' => [
            'tipo' => 'taxonomia',
            'vista' => 'pastillas (enlaces al archivo de cada categoría; "Todos" lleva a la página Blog)',
            'fuente' => 'category',
            'etiqueta' => '',
            'varios' => 'no',
            'conteo' => 'ninguno',
        ],
        'q' => [
            'tipo' => 'busqueda',
            'vista' => 'campo con lupa y botón "Buscar"',
            'etiqueta' => '',
        ],
    ],
];
