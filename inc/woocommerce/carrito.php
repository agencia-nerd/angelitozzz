<?php
defined('ABSPATH') || exit;

/*
 * Carrito para el drawer "Resumen de compra" y el contador del header (specs/solucion.md → drawer-carrito).
 * Solo lee WC()->cart: agregar y quitar lo hacen los endpoints de Woo (?wc-ajax=add_to_cart / remove_from_cart)
 * desde src/js/modules/carrito.js, y Woo devuelve los fragmentos de aquí para cambiar el drawer y el contador
 * sin recargar. La duración del acceso y la marca de regalo las da el plugin angelitozzz-cursos.
 */

/**
 * Líneas visibles del carrito con lo que pinta el diseño.
 *
 * @return array<int, array<string, mixed>> clave, producto_id, imagen (ID), tipo, nombre, acceso, precio, regalo,
 *                                          quitar (URL sin JS).
 */
function angelitozzz_carrito_items(): array
{
    if (!function_exists('WC') || !WC()->cart) {
        return [];
    }

    $items = [];
    foreach (WC()->cart->get_cart() as $clave => $linea) {
        $producto = apply_filters('woocommerce_cart_item_product', $linea['data'] ?? null, $linea, $clave);
        if (!$producto instanceof WC_Product || !$producto->exists() || ($linea['quantity'] ?? 0) <= 0
            || !apply_filters('woocommerce_widget_cart_item_visible', true, $linea, $clave)) {
            continue;
        }

        $datos  = function_exists('angelitozzz_cursos_datos_producto')
            ? angelitozzz_cursos_datos_producto($producto->get_id())
            : ['curso_id' => 0, 'acceso_texto' => ''];
        $regalo = function_exists('angelitozzz_cursos_linea_carrito_es_regalo') && angelitozzz_cursos_linea_carrito_es_regalo($linea);
        $imagen = $datos['curso_id'] ? (int) get_post_thumbnail_id($datos['curso_id']) : 0;

        $items[] = [
            'clave'       => (string) $clave,
            'producto_id' => $producto->get_id(),
            'imagen'      => $imagen ?: (int) $producto->get_image_id(),
            'tipo'        => $regalo ? __('Curso de regalo', 'angelitozzz') : __('Curso', 'angelitozzz'),
            'nombre'      => (string) apply_filters('woocommerce_cart_item_name', $producto->get_name(), $linea, $clave),
            'acceso'      => (string) $datos['acceso_texto'],
            'precio'      => angelitozzz_precio_texto((float) $producto->get_price()),
            'regalo'      => $regalo,
            'quitar'      => wc_get_cart_remove_url($clave),
        ];
    }
    return $items;
}

/**
 * Subtotal del drawer: productos más los cargos del carrito (el descuento por etapas es un cargo negativo,
 * inc/woocommerce/descuentos.php). Los cupones se ven en el carrito y en el checkout.
 */
function angelitozzz_carrito_subtotal(): string
{
    if (!function_exists('WC') || !WC()->cart) {
        return '';
    }
    $cargos = 0.0;
    foreach (WC()->cart->get_fees() as $cargo) {
        $cargos += (float) $cargo->total;
    }
    return angelitozzz_precio_texto(max(0.0, (float) WC()->cart->get_subtotal() + $cargos));
}

/**
 * Solución sugerida del drawer: la primera de venta directa sin ningún curso en el carrito (las de etapas
 * se eligen en su selector, así que no se agregan desde aquí).
 *
 * @return array<string, mixed> imagen, nombre, detalle ("$67 · 4 a 24 meses"), url, productos; vacío si no hay.
 */
function angelitozzz_carrito_sugerida(array $items): array
{
    $en_carrito = array_column($items, 'producto_id');
    $soluciones = get_posts([
        'post_type'      => 'solucion',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);

    foreach ($soluciones as $solucion) {
        $precio = angelitozzz_solucion_precio($solucion->ID);
        if (!$precio['productos'] || array_intersect($precio['productos'], $en_carrito)) {
            continue;
        }
        $tarjeta = get_field('solucion_tarjeta', $solucion->ID) ?: [];
        $edad    = trim((string) ($tarjeta['edad'] ?? ''), '() ');
        return [
            'imagen'    => (int) get_post_thumbnail_id($solucion->ID),
            'nombre'    => get_the_title($solucion),
            'detalle'   => trim($precio['actual'] . ($edad !== '' ? ' · ' . $edad : '')),
            'url'       => (string) get_permalink($solucion),
            'productos' => $precio['productos'],
        ];
    }
    return [];
}

/**
 * Todo lo que pinta el drawer.
 *
 * @return array{items: array, sugerida: array, subtotal: string, checkout: string}
 */
function angelitozzz_carrito_datos(): array
{
    $items = angelitozzz_carrito_items();
    return [
        'items'    => $items,
        'sugerida' => $items ? angelitozzz_carrito_sugerida($items) : [],
        'subtotal' => angelitozzz_carrito_subtotal(),
        'checkout' => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/finalizar-compra/'),
    ];
}

/**
 * Cantidad de cursos en el carrito (cada curso se vende de a uno).
 */
function angelitozzz_carrito_cantidad(): int
{
    return function_exists('WC') && WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
}

/**
 * HTML de un template part, para los fragmentos.
 */
function angelitozzz_carrito_parte(string $parte): string
{
    ob_start();
    get_template_part('template-parts/components/' . $parte);
    return (string) ob_get_clean();
}

// Fragmentos de Woo: el contenido del drawer y el contador del header se cambian enteros (el selector es el
// elemento raíz de cada parte) después de agregar o quitar, y al cargar una página si el carrito cambió.
add_filter('woocommerce_add_to_cart_fragments', static function (array $fragmentos): array {
    $fragmentos['div[data-carrito-contenido]'] = angelitozzz_carrito_parte('carrito-contenido');
    $fragmentos['span[data-carrito-cuenta]']   = angelitozzz_carrito_parte('carrito-cuenta');
    return $fragmentos;
});
