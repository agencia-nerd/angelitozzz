<?php

/**
 * TEMPORAL (solo lo de compra): precios de muestra de las soluciones y el drawer del carrito.
 *
 * El contenido de las soluciones ya es ACF (inc/acf/seed/solucion.php). Aquí queda lo que en la F5-b darán
 * WooCommerce (precio del producto de cada curso) y el carrito real. Se borra en la F5-b junto con
 * inc/solucion-muestra/carrito.php.
 */

defined('ABSPATH') || exit;

/** Lee inc/solucion-muestra/<parte>.php (una sola vez por petición). Hoy solo queda `carrito`. */
function angelitozzz_solucion_muestra_parte(string $parte): array
{
    static $partes = [];

    if (!isset($partes[$parte])) {
        $archivo        = get_theme_file_path('inc/solucion-muestra/' . sanitize_key($parte) . '.php');
        $partes[$parte] = is_readable($archivo) ? (array) require $archivo : [];
    }

    return $partes[$parte];
}

/**
 * Precio de muestra de una solución (woo: sale del producto de WooCommerce del curso). `antes` solo con rebaja;
 * `desde` con etapas (el precio más bajo, sin "antes").
 */
function angelitozzz_solucion_muestra_precio(string $slug): array
{
    $precios = [
        'angelita-pro'      => ['actual' => '$67', 'antes' => '$97', 'desde' => false],
        'a-dormir-por-fin'  => ['actual' => '$399', 'antes' => '', 'desde' => false],
        'recien-nacidos-go' => ['actual' => '$67', 'antes' => '$97', 'desde' => false],
        'horarios-ideales'  => ['actual' => '$37', 'antes' => '', 'desde' => true],
    ];

    return $precios[$slug] ?? ['actual' => '', 'antes' => '', 'desde' => false];
}

/** Precio de muestra del curso de cada etapa (woo: producto del curso de la etapa). Las 3 etapas del diseño: $37. */
function angelitozzz_solucion_muestra_precio_etapa(string $slug): string
{
    return $slug === 'horarios-ideales' ? '$37' : '';
}
