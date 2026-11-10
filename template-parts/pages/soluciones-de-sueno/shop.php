<?php

/**
 * Soluciones de sueño · shop (specs/soluciones-de-sueno.md, grupo soluciones_de_sueno_shop).
 *
 * Por ahora solo diseño: el encabezado es texto fijo (pasa a ACF después) y las tarjetas son las 4 del diseño
 * como array fijo. Al conectar WooCommerce el array se cambia por el bucle de productos.
 * Sin filtros, orden ni contador (nota del usuario).
 */

defined('ABSPATH') || exit;

$por_pagina = 12;
$img        = static fn(string $archivo): string => get_theme_file_uri('assets/img/' . $archivo);

$tarjetas = [
    [
        'imagen'         => '', // Vacía a propósito: el componente muestra la imagen por defecto "not found".
        'etiqueta'       => 'Nuevo · Desde $97',
        'etiqueta_icono' => 'destellos',
        'titulo'         => 'Angelita Pro',
        'edad'           => '(4 a 24 meses)',
        'descripcion'    => 'Tu coach de sueño con inteligencia artificial, entrenada con el método Angelitozzz. Disponible 24/7 por WhatsApp — sin turnos, sin horarios, sin esperas.',
        'beneficios'     => [
            'Plan integral de sueño — noches y siestas',
            'Respuestas inmediatas a tus dudas en tiempo real',
            'Seguimiento paso a paso las 24 horas',
            'Acceso desde cualquier lugar por WhatsApp',
            'Empieza esta noche',
        ],
        'boton'          => ['title' => 'Quiero conocer a Angelita', 'url' => '#'],
        'loading'        => 'eager',
    ],
    [
        'imagen'         => $img('shop-a-dormir-por-fin.webp'),
        'etiqueta'       => 'Asesoría con Bárbara · $399',
        'etiqueta_icono' => 'estrella',
        'titulo'         => 'A Dormir Por Fin',
        'edad'           => '(4 meses a 4 años)',
        'descripcion'    => 'Bárbara diseña el plan específico para tu bebé y te acompaña de cerca durante 3 semanas.',
        'beneficios'     => [
            'Plan personalizado para noches y/o siestas',
            'WhatsApp ilimitado con Bárbara durante 3 semanas',
            'Ajustes diarios según la respuesta de tu bebé',
            'Acceso premium directo con Bárbara',
        ],
        'boton'          => ['title' => 'Quiero mi asesoría personal', 'url' => '#'],
        'loading'        => 'eager',
    ],
    [
        'imagen'         => $img('shop-recien-nacidos-go.webp'),
        'etiqueta'       => 'Curso Online · $97',
        'etiqueta_icono' => 'ninguno',
        'titulo'         => 'Recién Nacidos Go',
        'edad'           => '(0 a 12 semanas)',
        'descripcion'    => 'Todo lo que necesitas saber para que tu recién nacido duerma mejor — técnicas suaves, rutinas claras y 100% compatible con la lactancia.',
        'beneficios'     => [
            '11 videos cortos y prácticos',
            'PDF con teoría, rutinas y horarios',
            'Acceso por 6 meses',
            'Bonus: 4 sesiones de coaching grupal en vivo con Bárbara',
        ],
        'boton'          => ['title' => 'Ver curso', 'url' => '#'],
        'loading'        => 'eager',
    ],
    [
        'imagen'         => $img('shop-horarios-ideales.webp'),
        'etiqueta'       => 'Curso Online · Desde $37',
        'etiqueta_icono' => 'ninguno',
        'titulo'         => 'Cursos de Horarios Ideales',
        'edad'           => '(4 meses a 3 años)',
        'descripcion'    => 'Aprende a crear el horario ideal de sueño para tu bebé según su edad. Olvida las tablas genéricas y deja de adivinar.',
        'beneficios'     => [
            'Videos explicativos por etapa',
            'Guías de horarios descargables',
            'Ejemplos prácticos para días difíciles',
            'Disponible para 4–9, 9–15 y 15–36 meses',
        ],
        'boton'          => ['title' => 'Ver cursos por edad', 'url' => '#'],
        'loading'        => 'lazy',
    ],
];
?>
<section class="px-4 py-22 max-md:py-14" aria-labelledby="soluciones-de-sueno-shop-titulo">
    <div class="mx-auto flex max-w-[451px] flex-col gap-6 text-center max-md:max-w-none">
        <h1 id="soluciones-de-sueno-shop-titulo" class="text-h1 text-marca max-md:text-h3">Soluciones de sueño</h1>
        <p class="text-body-l text-tinta">Encuentra la solución perfecta para ti — según la edad de tu bebé y lo que necesitas hoy.</p>
    </div>

    <div class="mx-auto mt-18 max-w-[1103px]">
        <h2 class="sr-only">Todos los cursos</h2>

        <?php
        // woo: woocommerce_before_shop_loop (sin contador ni orden)
        // woo: woocommerce_product_loop_start
        ?>
        <ul class="grid grid-cols-1 justify-items-center gap-y-14 md:grid-cols-2 md:gap-x-8 md:gap-y-20 lg:grid-cols-[repeat(3,346px)] lg:justify-between [&>li]:w-full [&>li]:max-w-[346px]" data-query="resultados">
            <?php
            // woo: bucle de productos (wc_get_template_part content product)
            foreach ($tarjetas as $tarjeta) {
                get_template_part('woocommerce/content-product', null, $tarjeta);
            }
            ?>
        </ul>
        <?php
        // woo: woocommerce_product_loop_end
        // woo: woocommerce_after_shop_loop (el "Ver más")
        ?>

        <?php if (count($tarjetas) > $por_pagina) : ?>
            <nav class="mt-20 flex justify-center" aria-label="<?= esc_attr__('Paginación', 'angelitozzz') ?>" data-query="paginacion">
                <?php get_template_part('template-parts/components/boton', null, [
                    'enlace'   => ['title' => 'Ver más', 'url' => '#'],
                    'variante' => 'secundario',
                    'flecha'   => false,
                ]); ?>
            </nav>
        <?php endif; ?>

        <?php // woo: woocommerce_no_products_found ?>
    </div>
</section>
