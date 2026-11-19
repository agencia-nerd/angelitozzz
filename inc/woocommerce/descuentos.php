<?php
defined('ABSPATH') || exit;

/*
 * Descuento por cantidad de las soluciones por etapas (specs/solucion.md → compra; decisión del usuario,
 * 2026-11-18: va en el tema porque la regla vive en el ACF de la solución).
 * Si el carrito tiene N cursos (no regalo) de las etapas de una solución y su repetidor solucion_compra.descuentos
 * tiene una fila con cantidad N, se agrega un cargo negativo "Descuento <solución>" = total − suma de sus precios.
 * Ejemplo de Horarios Ideales: 2 etapas de $37 → $67 (−$7); 3 → $97 (−$14).
 */

/**
 * Soluciones por etapas con su escala: [post_id => ['titulo', 'cursos' => int[], 'escala' => [cantidad => total]]].
 *
 * @return array<int, array{titulo: string, cursos: int[], escala: array<int, float>}>
 */
function angelitozzz_descuentos_etapas(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $cache = [];
    $ids   = get_posts([
        'post_type'      => 'solucion',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'no_found_rows'  => true,
        'meta_query'     => [['key' => 'solucion_compra_tipo', 'value' => 'etapas']], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
    ]);

    foreach ($ids as $id) {
        $escala = [];
        foreach ((array) ((get_field('solucion_compra', $id) ?: [])['descuentos'] ?? []) as $fila) {
            $cantidad = (int) ($fila['cantidad'] ?? 0);
            $total    = (float) ($fila['total'] ?? 0);
            if ($cantidad > 1 && $total > 0) {
                $escala[$cantidad] = $total;
            }
        }
        $cursos = angelitozzz_solucion_cursos_etapas((int) $id);
        if ($escala && $cursos) {
            $cache[(int) $id] = ['titulo' => get_the_title($id), 'cursos' => $cursos, 'escala' => $escala];
        }
    }
    return $cache;
}

add_action('woocommerce_cart_calculate_fees', static function ($carrito): void {
    if (!$carrito instanceof WC_Cart || !function_exists('angelitozzz_cursos_datos_producto')) {
        return;
    }

    foreach (angelitozzz_descuentos_etapas() as $solucion) {
        $cantidad = 0;
        $suma     = 0.0;
        foreach ($carrito->get_cart() as $linea) {
            $producto = $linea['data'] ?? null;
            if (!$producto instanceof WC_Product
                || (function_exists('angelitozzz_cursos_linea_carrito_es_regalo') && angelitozzz_cursos_linea_carrito_es_regalo($linea))
                || !in_array(angelitozzz_cursos_datos_producto((int) $linea['product_id'])['curso_id'], $solucion['cursos'], true)) {
                continue;
            }
            $cantidad += (int) $linea['quantity'];
            $suma     += (float) $producto->get_price() * (int) $linea['quantity'];
        }

        $total = $solucion['escala'][$cantidad] ?? null;
        if ($total !== null && $total < $suma) {
            $carrito->add_fee(
                /* translators: %s: nombre de la solución. */
                sprintf(__('Descuento %s', 'angelitozzz'), $solucion['titulo']),
                round($total - $suma, wc_get_price_decimals()),
                false
            );
        }
    }
});
