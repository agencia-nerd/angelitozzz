<?php

/**
 * Drawer del carrito "Resumen de compra" (specs/solucion.md → drawer-carrito; Figma 3752:5054).
 * Panel lateral derecho de 480 px. Se imprime una vez en footer.php (todo el sitio): lo abren el ícono
 * del carrito del header y los botones de compra de las landings. Lista con scroll y pie fijo.
 *
 * El contenido sale de WC()->cart (template-parts/components/carrito-contenido.php) y se cambia sin recargar con
 * los fragmentos de Woo (inc/woocommerce/carrito.php, src/js/modules/carrito.js).
 */

defined('ABSPATH') || exit;

if (!function_exists('angelitozzz_carrito_datos')) {
    return;
}

angelitozzz_modal_start('carrito', [
    'title'        => __('Resumen de compra', 'angelitozzz'),
    'placement'    => 'right',
    'width'        => '480px',
    'mobile'       => 'fullscreen',
    'motion'       => 'slide',
    'header_class' => 'items-center p-7 max-md:p-5',
    'title_class'  => 'text-h4 text-marca',
    'close_class'  => 'm-0 text-marca',
    // La lista hace scroll por dentro; el pie (subtotal y botones) queda fijo abajo.
    'body_class'   => 'flex flex-col overflow-hidden p-0',
]);

get_template_part('template-parts/components/carrito-contenido');

angelitozzz_modal_end();
