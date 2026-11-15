<?php

/**
 * Landing de una solución (specs/solucion.md): hero, bloques flexibles, relacionados y,
 * con etapas, el selector de edad y un modal por etapa. El drawer del carrito va en footer.php.
 *
 * F4, solo maquetación: los datos salen de inc/solucion-muestra.php (f5: get_field() de los
 * grupos solucion_hero, solucion_compra y solucion_secciones; woo: precio del producto).
 */

defined('ABSPATH') || exit;

get_header();

$slug  = (string) get_post_field('post_name');
$datos = angelitozzz_solucion_muestra($slug); // f5: get_field('solucion_hero'), get_field('solucion_compra'), get_field('solucion_secciones')

$compra   = $datos['compra'];
$tipo     = (string) ($compra['tipo'] ?? 'directa');
$contexto = [
    'slug'         => $slug,
    'titulo'       => get_the_title(),
    'tipo'         => $tipo,
    'compra'       => $compra,
    'precio'       => $datos['precio'], // woo: precio del producto de cada curso.
    'modal_compra' => angelitozzz_solucion_modal_compra($tipo),
    'boton_compra' => (string) ($datos['hero']['boton'] ?? ''),
];

$layouts = angelitozzz_solucion_layouts();
$etapas  = null;
?>

<main id="main-content" class="fondo-nubes">
    <?php
    get_template_part('template-parts/pages/solucion/hero', null, $datos['hero'] + ['contexto' => $contexto]);

    foreach ($datos['bloques'] as $n => $fila) {
        $layout = (string) ($fila['acf_fc_layout'] ?? '');
        if (!in_array($layout, $layouts, true)) {
            continue; // Un layout desconocido no rompe la página.
        }
        if ($layout === 'etapas' && $etapas === null) {
            $etapas = $fila;
        }
        get_template_part('template-parts/pages/solucion/' . $layout, null, $fila + ['contexto' => $contexto, 'indice' => $n]);
    }

    get_template_part('template-parts/pages/solucion/relacionados', null, [
        'soluciones' => angelitozzz_solucion_muestra_relacionadas($slug), // f5: angelitozzz_query_related()
    ]);
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
