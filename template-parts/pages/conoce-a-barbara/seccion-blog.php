<?php

/**
 * Conoce a Bárbara · últimos artículos (specs/conoce-a-barbara.md, grupo conoce_a_barbara_seccion_blog).
 * Por ahora solo diseño: las 3 tarjetas son ejemplos fijos del diseño. La relación de artículos
 * (o los 3 últimos si no se elige ninguno) se conecta en el paso de ACF/consultas.
 */

defined('ABSPATH') || exit;

$titulo = 'Últimos artículos de nuestro blog';
$img    = static fn(string $archivo): string => get_theme_file_uri('assets/img/blog/' . $archivo);
$resumen = 'Qué esperar cuando tu bebé protesta y cómo acompañarlo con calma, sin culpa.';

// Ejemplos del diseño: sin enlace hasta que existan los artículos.
$articulos = [
    [
        'imagen'    => $img('blog-tarjeta-1-imagen.webp'),
        'categoria' => 'Especiales',
        'fecha'     => '1 de marzo de 2024',
        'lectura'   => '5 min de lectura',
        'titulo'    => 'Aprendizaje del sueño y las lágrimas',
        'resumen'   => $resumen,
        'url'       => '#',
    ],
    [
        'imagen'    => $img('blog-tarjeta-2-imagen.webp'),
        'categoria' => 'Especiales',
        'fecha'     => '12 de febrero de 2024',
        'lectura'   => '4 min de lectura',
        'titulo'    => '5 señales de que tu bebé está en transición de siestas',
        'resumen'   => $resumen,
        'url'       => '#',
    ],
    [
        'imagen'    => $img('blog-tarjeta-3-imagen.webp'),
        'categoria' => 'Especiales',
        'fecha'     => '28 de enero de 2024',
        'lectura'   => '6 min de lectura',
        'titulo'    => 'Cómo crear el ambiente ideal para dormir',
        'resumen'   => $resumen,
        'url'       => '#',
    ],
];
// Página del blog (Ajustes > Lectura). Aún no existe: el botón queda desactivado hasta entonces.
$pagina_blog = (int) get_option('page_for_posts');
$boton = ['url' => $pagina_blog ? get_permalink($pagina_blog) : '#', 'title' => 'Ver más artículos', 'target' => ''];
?>
<section class="bg-fondo px-4 py-26 max-md:py-16"<?= $titulo !== '' ? ' aria-labelledby="conoce-a-barbara-blog-titulo"' : '' ?>>
    <div class="mx-auto flex max-w-sitio flex-col gap-10">
        <?php if ($titulo !== '') : ?>
            <h2 id="conoce-a-barbara-blog-titulo" class="text-h2 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
        <?php endif; ?>

        <?php if ($articulos) : ?>
            <ul class="grid gap-7.5 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($articulos as $articulo) : ?>
                    <li><?php get_template_part('template-parts/components/post-card', null, $articulo); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="flex justify-center">
            <?php get_template_part('template-parts/components/boton', null, [
                'enlace'   => $boton,
                'variante' => 'frambuesa',
            ]); ?>
        </div>
    </div>
</section>
