<?php

/**
 * Conoce a Bárbara · últimos artículos (specs/conoce-a-barbara.md, grupo conoce_a_barbara_seccion_blog).
 * Muestra los artículos que el admin eligió en la relación. Si no elige ninguno, deben salir los 3 últimos
 * del blog; como el blog aún no existe, salen las 3 tarjetas de ejemplo del diseño (sin consulta).
 * Cuando exista el blog, wp-query reemplaza ese respaldo por los 3 últimos reales.
 */

defined('ABSPATH') || exit;

$blog      = get_field('conoce_a_barbara_seccion_blog') ?: [];
$titulo    = (string) ($blog['titulo'] ?? '');
$boton     = $blog['boton'] ?? null;
$articulos = [];

foreach ((array) ($blog['articulos'] ?? []) as $articulo) {
    $post_id = $articulo instanceof WP_Post ? $articulo->ID : (int) $articulo;
    if (get_post_status($post_id) !== 'publish') {
        continue;
    }
    $categorias = get_the_category($post_id);
    $palabras   = str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $post_id)));
    $articulos[] = [
        'imagen'    => get_post_thumbnail_id($post_id) ?: null,
        'categoria' => $categorias ? $categorias[0]->name : '',
        'fecha'     => get_the_date('j \d\e F \d\e Y', $post_id),
        'lectura'   => sprintf(__('%d min de lectura', 'angelitozzz'), max(1, (int) ceil($palabras / 200))),
        'titulo'    => get_the_title($post_id),
        'resumen'   => get_the_excerpt($post_id),
        'url'       => get_permalink($post_id),
    ];
}
// Respaldo temporal (maquetación): ejemplos del diseño, sin enlace hasta que existan los artículos.
if (!$articulos) {
    $img     = static fn(string $archivo): string => get_theme_file_uri('assets/img/blog/' . $archivo);
    $resumen = 'Qué esperar cuando tu bebé protesta y cómo acompañarlo con calma, sin culpa.';
    foreach ([
        [1, '1 de marzo de 2024', 5, 'Aprendizaje del sueño y las lágrimas'],
        [2, '12 de febrero de 2024', 4, '5 señales de que tu bebé está en transición de siestas'],
        [3, '28 de enero de 2024', 6, 'Cómo crear el ambiente ideal para dormir'],
    ] as [$n, $fecha, $min, $nombre]) {
        $articulos[] = [
            'imagen'    => $img("blog-tarjeta-{$n}-imagen.webp"),
            'categoria' => 'Especiales',
            'fecha'     => $fecha,
            'lectura'   => sprintf(__('%d min de lectura', 'angelitozzz'), $min),
            'titulo'    => $nombre,
            'resumen'   => $resumen,
            'url'       => '#',
        ];
    }
}
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
