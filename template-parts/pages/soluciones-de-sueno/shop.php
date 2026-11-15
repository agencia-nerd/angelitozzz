<?php

/**
 * Soluciones de sueño · shop (specs/soluciones-de-sueno.md, grupo soluciones_de_sueno_shop).
 *
 * F4, solo maquetación: el encabezado es texto fijo (pasa a ACF después) y las tarjetas son las 4
 * soluciones de muestra (inc/solucion-muestra.php). wp-query cambia la muestra por el bucle de
 * `solucion` y convierte el "Ver más" en "cargar más". Sin filtros, orden ni contador (nota del usuario).
 */

defined('ABSPATH') || exit;

$por_pagina = 12;
$tarjetas   = array_values(angelitozzz_solucion_muestra_tarjetas()); // f5: bucle de wp-query
?>
<section class="px-4 py-22 max-md:py-14" aria-labelledby="soluciones-de-sueno-shop-titulo">
    <div class="mx-auto flex max-w-[451px] flex-col gap-6 text-center max-md:max-w-none">
        <h1 id="soluciones-de-sueno-shop-titulo" class="text-h1 text-marca max-md:text-h3">Soluciones de sueño</h1>
        <p class="text-cuerpo-l text-tinta">Encuentra la solución perfecta para ti — según la edad de tu bebé y lo que necesitas hoy.</p>
    </div>

    <div class="mx-auto mt-18 max-w-[1103px]">
        <h2 class="sr-only">Todos los cursos</h2>

        <?php if ($tarjetas) : ?>
            <ul class="grid grid-cols-1 justify-items-center gap-y-14 md:grid-cols-2 md:gap-x-8 md:gap-y-20 lg:grid-cols-[repeat(3,346px)] lg:justify-between [&>li]:w-full [&>li]:max-w-[346px]" data-query="resultados">
                <?php foreach ($tarjetas as $i => $tarjeta) : ?>
                    <li class="flex">
                        <?php get_template_part('template-parts/components/producto-card', null, [
                            'imagen'         => $tarjeta['imagen'],
                            'etiqueta'       => $tarjeta['etiqueta'],
                            'etiqueta_icono' => $tarjeta['etiqueta_icono'],
                            'titulo'         => $tarjeta['titulo'],
                            'edad'           => $tarjeta['edad'],
                            'descripcion'    => $tarjeta['descripcion'],
                            'beneficios'     => $tarjeta['beneficios'],
                            'boton'          => ['title' => $tarjeta['boton_texto'], 'url' => $tarjeta['url']],
                            // Primera pantalla: las 3 primeras tarjetas se cargan de inmediato.
                            'loading'        => $i < 3 ? 'eager' : 'lazy',
                        ]); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p class="text-center text-cuerpo-l text-tinta" data-query="resultados">Pronto tendremos nuevas soluciones.</p>
        <?php endif; ?>

        <?php if (count($tarjetas) > $por_pagina) : ?>
            <nav class="mt-20 flex justify-center" aria-label="<?= esc_attr__('Paginación', 'angelitozzz') ?>" data-query="paginacion">
                <?php get_template_part('template-parts/components/boton', null, [
                    'enlace'   => ['title' => 'Ver más', 'url' => get_pagenum_link(2)],
                    'variante' => 'secundario',
                    'flecha'   => false,
                ]); ?>
            </nav>
        <?php endif; ?>
    </div>
</section>
