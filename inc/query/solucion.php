<?php
defined('ABSPATH') || exit;

/*
 * Listado "soluciones-de-sueno" (contenido solucion). Lo genera wp-query (generate.mjs) desde
 * specs/soluciones-de-sueno.md, su spec de detalle y _shared.md: no se edita a mano. Cambia el spec y vuelve a generar.
 */
return [
    'contenido' => 'solucion',
    'listado' => 'soluciones-de-sueno',
    'post_type' => 'solucion',
    'taxonomias' => [],
    'por_pagina' => 12,
    'paginacion' => 'cargar_mas',
    'al_filtrar' => 'auto',
    'movil' => 'igual',
    'relacionadas' => '',
    'filtros' => [],
];
