<?php
defined('ABSPATH') || exit;

/*
 * Precios de las soluciones (specs/solucion.md: el precio nunca es un campo, sale del producto de Woo del curso).
 * Precio rebajado = precio de lanzamiento; si hay rebaja, el normal es el "antes". Con etapas, "Desde" el más bajo.
 * El tema solo lee: el producto de cada curso lo da el plugin (angelitozzz_cursos_producto_de()).
 */

// Los precios del diseño no llevan ".00" ("$97"): Woo quita los decimales en cero en todo su HTML de precios
// (carrito, checkout, gracias, correos), igual que angelitozzz_precio_texto().
add_filter('woocommerce_price_trim_zeros', '__return_true');

/**
 * Monto como texto plano con la moneda de Woo: "$67" o "$67.50" (sin decimales si es entero).
 */
function angelitozzz_precio_texto(float $monto): string
{
    if (!function_exists('wc_price')) {
        return '';
    }
    $decimales = floor($monto) == $monto ? 0 : wc_get_price_decimals();
    return html_entity_decode(wp_strip_all_tags(wc_price($monto, ['decimals' => $decimales])), ENT_QUOTES, 'UTF-8');
}

/**
 * Producto de Woo de un curso que se puede comprar (publicado y con precio), o null.
 */
function angelitozzz_curso_producto(int $curso_id): ?WC_Product
{
    if (!$curso_id || !function_exists('angelitozzz_cursos_producto_de') || !function_exists('wc_get_product')) {
        return null;
    }
    $producto = wc_get_product(angelitozzz_cursos_producto_de($curso_id));
    return $producto instanceof WC_Product && $producto->is_purchasable() ? $producto : null;
}

/**
 * Precio de un curso: `actual` y `antes` (null sin rebaja) como números, y el ID de su producto.
 *
 * @return array{actual: ?float, antes: ?float, producto_id: int}
 */
function angelitozzz_curso_precio(int $curso_id): array
{
    $producto = angelitozzz_curso_producto($curso_id);
    if (!$producto || $producto->get_price() === '') {
        return ['actual' => null, 'antes' => null, 'producto_id' => 0];
    }
    $normal = (float) $producto->get_regular_price();
    $actual = (float) $producto->get_price();
    return [
        'actual'      => $actual,
        'antes'       => $producto->is_on_sale() && $normal > $actual ? $normal : null,
        'producto_id' => $producto->get_id(),
    ];
}

/**
 * Cursos de las etapas de una solución (bloque flexible `etapas`, campo `curso` de cada ítem), en orden.
 *
 * @return int[]
 */
function angelitozzz_solucion_cursos_etapas(int $post_id): array
{
    $bloques = (array) ((get_field('solucion_secciones', $post_id) ?: [])['bloques'] ?? []);
    foreach ($bloques as $fila) {
        if (is_array($fila) && ($fila['acf_fc_layout'] ?? '') === 'etapas') {
            return array_values(array_filter(array_map(
                static fn($item) => (int) (is_array($item) ? ($item['curso'] ?? 0) : 0),
                (array) ($fila['items'] ?? [])
            )));
        }
    }
    return [];
}

/**
 * Cursos que vende una solución: los de sus etapas (tipo `etapas`) o los de solucion_compra.cursos.
 *
 * @return int[]
 */
function angelitozzz_solucion_cursos(int $post_id): array
{
    $compra = get_field('solucion_compra', $post_id) ?: [];
    if (($compra['tipo'] ?? '') === 'etapas') {
        return angelitozzz_solucion_cursos_etapas($post_id);
    }
    return array_values(array_filter(array_map('intval', (array) ($compra['cursos'] ?? []))));
}

/**
 * Precio de una solución para la landing, los relacionados y el botón de la galería.
 * - directa / suscripcion: la suma de sus cursos (casi siempre uno); `antes` solo si alguno tiene rebaja.
 * - etapas: el más bajo de sus etapas, con `desde` y sin `antes`.
 * `productos`: los IDs a agregar al carrito con el botón de compra (vacío con etapas: se eligen en el selector).
 *
 * @return array{actual: string, antes: string, desde: bool, productos: int[]}
 */
function angelitozzz_solucion_precio(int $post_id): array
{
    $vacio  = ['actual' => '', 'antes' => '', 'desde' => false, 'productos' => []];
    $compra = get_field('solucion_compra', $post_id) ?: [];
    $cursos = angelitozzz_solucion_cursos($post_id);
    if (!$cursos) {
        return $vacio;
    }

    $precios = array_filter(array_map('angelitozzz_curso_precio', $cursos), static fn($p) => $p['actual'] !== null);
    if (!$precios) {
        return $vacio;
    }

    if (($compra['tipo'] ?? '') === 'etapas') {
        $minimo = min(array_column($precios, 'actual'));
        return ['actual' => angelitozzz_precio_texto($minimo), 'antes' => '', 'desde' => true, 'productos' => []];
    }

    $actual = array_sum(array_column($precios, 'actual'));
    $antes  = array_sum(array_map(static fn($p) => $p['antes'] ?? $p['actual'], $precios));
    return [
        'actual'    => angelitozzz_precio_texto($actual),
        'antes'     => $antes > $actual ? angelitozzz_precio_texto($antes) : '',
        'desde'     => false,
        'productos' => array_column($precios, 'producto_id'),
    ];
}
