<?php

/**
 * Landing de una solución (specs/solucion.md): hero, bloques flexibles, relacionados y,
 * con etapas, el selector de edad y un modal por etapa. El drawer del carrito va en footer.php.
 *
 * Los campos son los grupos solucion_hero, solucion_compra y solucion_secciones (wp-acf); los relacionados,
 * angelitozzz_query_related() (wp-query). woo: el precio de la landing y de cada etapa sale todavía de
 * inc/solucion-muestra.php, hasta que se conecte el producto de cada curso (F5-b).
 */

defined('ABSPATH') || exit;

get_header();

$slug    = (string) get_post_field('post_name');
$hero    = get_field('solucion_hero') ?: [];
$compra  = get_field('solucion_compra') ?: [];
$bloques = (array) ((get_field('solucion_secciones') ?: [])['bloques'] ?? []);
// woo: precio del producto de cada curso (F5-b); hasta entonces, el de la muestra.
$precio  = function_exists('angelitozzz_solucion_muestra_precio')
    ? angelitozzz_solucion_muestra_precio($slug)
    : ['actual' => '', 'antes' => '', 'desde' => false];

$tipo     = (string) ($compra['tipo'] ?? 'directa');
$contexto = [
    'slug'         => $slug,
    'titulo'       => get_the_title(),
    'tipo'         => $tipo,
    'compra'       => $compra,
    'precio'       => $precio,
    'modal_compra' => angelitozzz_solucion_modal_compra($tipo),
    'boton_compra' => (string) ($hero['boton'] ?? ''),
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
            // woo: precio del producto del curso de cada etapa (F5-b); hasta entonces, el de la muestra.
            foreach (array_keys((array) ($fila['items'] ?? [])) as $i) {
                $fila['items'][$i]['precio'] = function_exists('angelitozzz_solucion_muestra_precio_etapa')
                    ? angelitozzz_solucion_muestra_precio_etapa($slug)
                    : '';
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
