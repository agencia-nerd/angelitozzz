<?php

/**
 * Landing de una solución (specs/solucion.md): hero, bloques flexibles, relacionados y,
 * con etapas, el selector de edad y un modal por etapa. El drawer del carrito va en footer.php.
 *
 * Los campos son los grupos solucion_hero, solucion_compra y solucion_secciones (wp-acf); los relacionados,
 * angelitozzz_query_related() (wp-query). El precio de la landing y de cada etapa sale del producto de Woo de
 * cada curso (inc/woocommerce/precios.php); los botones de compra agregan esos productos al carrito y abren el
 * drawer (src/js/modules/carrito.js).
 */

defined('ABSPATH') || exit;

get_header();

$slug    = (string) get_post_field('post_name');
$hero    = get_field('solucion_hero') ?: [];
$compra  = get_field('solucion_compra') ?: [];
$bloques = (array) ((get_field('solucion_secciones') ?: [])['bloques'] ?? []);
$precio  = function_exists('angelitozzz_solucion_precio')
    ? angelitozzz_solucion_precio(get_the_ID())
    : ['actual' => '', 'antes' => '', 'desde' => false, 'productos' => []];

$tipo = (string) ($compra['tipo'] ?? 'directa');
// Venta directa: el botón de compra agrega los productos y abre el drawer; con etapas abre el selector.
$datos_compra = $tipo !== 'etapas' && $precio['productos']
    ? ['data-solucion-agregar' => '', 'data-productos' => implode(',', $precio['productos'])]
    : [];
$contexto = [
    'slug'         => $slug,
    'titulo'       => get_the_title(),
    'tipo'         => $tipo,
    'compra'       => $compra,
    'precio'       => $precio,
    'modal_compra' => angelitozzz_solucion_modal_compra($tipo),
    'boton_compra' => (string) ($hero['boton'] ?? ''),
    'datos_compra' => $datos_compra,
    // "Regalar este curso": los mismos productos, marcados como regalo (plugin angelitozzz-cursos).
    'datos_regalo' => $datos_compra ? $datos_compra + ['data-regalo' => '1'] : [],
];

$layouts = angelitozzz_solucion_layouts();
$etapas  = null;
?>

<main id="main-content" class="fondo-nubes">
    <?php
    get_template_part('template-parts/pages/solucion/hero', null, $hero + ['contexto' => $contexto]);

    foreach ($bloques as $n => $fila) {
        $layout = is_array($fila) ? (string) ($fila['acf_fc_layout'] ?? '') : '';
        if (!in_array($layout, $layouts, true)) {
            continue; // Un layout desconocido no rompe la página.
        }
        if ($layout === 'etapas') {
            // Precio y producto del curso de cada etapa (para el selector de edad y el modal de cada etapa).
            foreach ((array) ($fila['items'] ?? []) as $i => $item) {
                $curso = function_exists('angelitozzz_curso_precio')
                    ? angelitozzz_curso_precio((int) (is_array($item) ? ($item['curso'] ?? 0) : 0))
                    : ['actual' => null, 'producto_id' => 0];
                $fila['items'][$i]['precio']        = $curso['actual'] !== null ? angelitozzz_precio_texto($curso['actual']) : '';
                $fila['items'][$i]['precio_numero'] = (float) ($curso['actual'] ?? 0);
                $fila['items'][$i]['producto_id']   = (int) $curso['producto_id'];
            }
            $etapas ??= $fila;
        }
        get_template_part('template-parts/pages/solucion/' . $layout, null, $fila + ['contexto' => $contexto, 'indice' => $n]);
    }

    // Relacionados: 3 soluciones distintas de la actual (wp-query; sin taxonomía, las más recientes).
    $relacionadas = [];
    $consulta     = angelitozzz_query_related(get_the_ID(), 3);
    while ($consulta->have_posts()) {
        $consulta->the_post();
        $relacionadas[] = angelitozzz_solucion_tarjeta_args(get_the_ID(), 'corta');
    }
    wp_reset_postdata();

    get_template_part('template-parts/pages/solucion/relacionados', null, ['soluciones' => $relacionadas]);
    ?>
</main>

<?php
// Modales de compra por etapas (Horarios Ideales): el selector de edad y uno por etapa.
if ($tipo === 'etapas' && $etapas) {
    get_template_part('template-parts/modals/selecciona-edad', null, [
        'etapas'   => (array) ($etapas['items'] ?? []),
        'compra'   => $compra,
        'contexto' => $contexto,
    ]);

    foreach (array_values((array) ($etapas['items'] ?? [])) as $i => $etapa) {
        get_template_part('template-parts/modals/etapa', null, [
            'id'         => 'etapa-' . ($i + 1),
            'etapa'      => $etapa,
            'antetitulo' => (string) ($etapas['modal_antetitulo'] ?? ''),
            'compra'     => $compra,
        ]);
    }
}

get_footer();
