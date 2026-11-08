<?php
defined('ABSPATH') || exit;

/*
 * Páginas de WordPress que crea inc/acf/runner.php. Lo genera wp-acf (generate.mjs) desde specs/_index.md:
 * no se edita a mano.
 */
return [
    'conoce-a-barbara' => ['titulo' => 'Conoce a Bárbara', 'slug' => 'conoce-a-barbara', 'frente' => false, 'plantilla' => 'pages/template-conoce-a-barbara.php', 'padre' => ''],
    'contacto' => ['titulo' => 'Contacto', 'slug' => 'contacto', 'frente' => false, 'plantilla' => 'pages/template-contacto.php', 'padre' => ''],
    'home' => ['titulo' => 'Inicio', 'slug' => 'inicio', 'frente' => true, 'plantilla' => '', 'padre' => ''],
    'mundo-angelitozzz' => ['titulo' => 'Mundo Angelitozzz', 'slug' => 'mundo-angelitozzz', 'frente' => false, 'plantilla' => 'pages/template-mundo-angelitozzz.php', 'padre' => ''],
    'preguntas-frecuentes' => ['titulo' => 'Preguntas frecuentes', 'slug' => 'preguntas-frecuentes', 'frente' => false, 'plantilla' => 'pages/template-preguntas-frecuentes.php', 'padre' => ''],
];
