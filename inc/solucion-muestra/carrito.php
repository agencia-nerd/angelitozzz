<?php

/**
 * TEMPORAL (F4): carrito de muestra para el drawer (drawer-carrito 3752:5054). Se borra en la F5,
 * cuando wp-woocommerce conecte el mini carrito con el carrito de WooCommerce.
 */

defined('ABSPATH') || exit;

return [
    // woo: WC()->cart->get_cart()
    'items' => [
        [
            'clave'   => 'muestra-1', // woo: cart_item_key (para eliminar)
            'imagen'  => get_theme_file_uri('assets/img/solucion/recien-nacidos-go/carrito-producto.webp'),
            'tipo'    => 'Curso',
            'nombre'  => 'Recién Nacidos Go',
            'acceso'  => 'Acceso por 4 meses', // f5: duración del acceso del curso (plugin angelitozzz-cursos)
            'precio'  => '$97', // woo: precio formateado
        ],
    ],
    // Sugerida: una solución que no está en el carrito (f5: la misma regla que los relacionados).
    'sugerida' => [
        'imagen' => '', // Angelita Pro no tiene foto: se usa la imagen "not found".
        'nombre' => 'Angelita Pro',
        'detalle' => '$67 · 4 a 24 meses', // woo: precio + edad de la tarjeta
        'url'    => home_url('/soluciones-de-sueno/angelita-pro/'),
    ],
    'subtotal' => '$97', // woo: WC()->cart->get_cart_subtotal()
];
