<?php
defined('ABSPATH') || exit;

/*
 * Páginas de WordPress que crea inc/acf/runner.php. Lo genera wp-acf (generate.mjs) desde specs/_index.md:
 * no se edita a mano.
 */
return [
    'admin-escritorio' => ['titulo' => 'Administrador', 'slug' => 'administrador', 'frente' => false, 'plantilla' => 'pages/template-administrador.php', 'padre' => ''],
    'blog' => ['titulo' => 'Blog', 'slug' => 'blog', 'frente' => false, 'plantilla' => '', 'padre' => '', 'entradas' => true],
    'canjear-regalo' => ['titulo' => 'Canjear regalo', 'slug' => 'canjear-regalo', 'frente' => false, 'plantilla' => 'pages/template-canjear-regalo.php', 'padre' => ''],
    'conoce-a-barbara' => ['titulo' => 'Conoce a Bárbara', 'slug' => 'conoce-a-barbara', 'frente' => false, 'plantilla' => 'pages/template-conoce-a-barbara.php', 'padre' => ''],
    'contacto' => ['titulo' => 'Contacto', 'slug' => 'contacto', 'frente' => false, 'plantilla' => 'pages/template-contacto.php', 'padre' => ''],
    'crear-cuenta' => ['titulo' => 'Crear cuenta', 'slug' => 'crear-cuenta', 'frente' => false, 'plantilla' => 'pages/template-crear-cuenta.php', 'padre' => ''],
    'home' => ['titulo' => 'Inicio', 'slug' => 'inicio', 'frente' => true, 'plantilla' => '', 'padre' => ''],
    'iniciar-sesion' => ['titulo' => 'Iniciar sesión', 'slug' => 'iniciar-sesion', 'frente' => false, 'plantilla' => 'pages/template-iniciar-sesion.php', 'padre' => ''],
    'mundo-angelitozzz' => ['titulo' => 'Mundo Angelitozzz', 'slug' => 'mundo-angelitozzz', 'frente' => false, 'plantilla' => 'pages/template-mundo-angelitozzz.php', 'padre' => ''],
    'preguntas-frecuentes' => ['titulo' => 'Preguntas frecuentes', 'slug' => 'preguntas-frecuentes', 'frente' => false, 'plantilla' => 'pages/template-preguntas-frecuentes.php', 'padre' => ''],
    'recuperar-contrasena' => ['titulo' => 'Recuperar contraseña', 'slug' => 'recuperar-contrasena', 'frente' => false, 'plantilla' => 'pages/template-recuperar-contrasena.php', 'padre' => ''],
    'usuario-escritorio' => ['titulo' => 'Mi cuenta', 'slug' => 'cuenta', 'frente' => false, 'plantilla' => 'pages/template-cuenta.php', 'padre' => ''],
];
