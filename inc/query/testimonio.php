<?php
defined('ABSPATH') || exit;

/*
 * Listado "testimonios" (contenido testimonio). Lo genera wp-query (generate.mjs) desde
 * specs/testimonios.md, su spec de detalle y _shared.md: no se edita a mano. Cambia el spec y vuelve a generar.
 */
return [
    'contenido' => 'testimonio',
    'listado' => 'testimonios',
    'post_type' => 'testimonio',
    'taxonomias' => [
        'categoria_testimonio',
    ],
    'por_pagina' => 5,
    'paginacion' => 'numeros',
    'al_filtrar' => 'auto',
    'movil' => 'igual',
    'relacionadas' => 'categoria_testimonio',
    'filtros' => [
        'categoria' => [
            'tipo' => 'taxonomia',
            'vista' => 'chips/pastillas (radios con aspecto de pastilla; "Todos" primero = sin filtro)',
            'fuente' => 'categoria_testimonio',
            'etiqueta' => '',
            'varios' => 'no',
            'conteo' => 'ninguno',
        ],
    ],
];
