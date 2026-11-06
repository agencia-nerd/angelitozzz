<?php
defined('ABSPATH') || exit;

function angelitozzz_setup()
{
    load_theme_textdomain('angelitozzz', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);

    // Logo del header (specs/opciones.md, sección header): Personalizar > Identidad del sitio. 87 × 63 en el diseño.
    add_theme_support('custom-logo', [
        'height'      => 63,
        'width'       => 87,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Ubicaciones de la tabla "Menús" de specs/_shared.md.
    register_nav_menus([
        'principal'        => __('Menú principal', 'angelitozzz'),
        'cabecera-derecha' => __('Header, a la derecha', 'angelitozzz'),
        'pie-navegacion'   => __('Pie: navegación', 'angelitozzz'),
        'pie-legal'        => __('Pie: legal', 'angelitozzz'),
        'pie-ayuda'        => __('Pie: ayuda', 'angelitozzz'),
    ]);
}
add_action('after_setup_theme', 'angelitozzz_setup');
