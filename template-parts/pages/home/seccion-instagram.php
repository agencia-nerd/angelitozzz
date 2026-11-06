<?php

/**
 * Inicio · ¡Síguenos en Instagram! (specs/home.md, grupo home_seccion_instagram).
 * Imágenes de la galería del admin (no hay conexión con Instagram).
 */

defined('ABSPATH') || exit;

$instagram = get_field('home_seccion_instagram') ?: [];

$titulo   = (string) ($instagram['titulo'] ?? '');
$imagenes = array_values(array_filter((array) ($instagram['imagenes'] ?? [])));
$boton    = $instagram['boton'] ?? null;
$total    = count($imagenes);
?>
<section class="px-4 py-22 max-md:py-14" aria-labelledby="home-seccion-instagram-titulo">
    <div class="mx-auto flex max-w-sitio flex-col items-center gap-[57px] max-md:gap-10">
        <div class="flex w-full flex-col gap-[49px] max-md:gap-8">
            <h2 id="home-seccion-instagram-titulo" class="text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
            <?php if ($imagenes) : ?>
                <ul class="-mx-4 flex snap-x snap-mandatory gap-4 overflow-x-auto px-4 md:mx-0 md:grid md:grid-cols-3 md:overflow-visible md:px-0 lg:flex lg:justify-between lg:gap-0">
                    <?php foreach ($imagenes as $i => $imagen) : ?>
                        <li class="aspect-[333/466] w-[80%] shrink-0 snap-start overflow-hidden rounded-media md:w-auto lg:w-[333px]">
                            <?= angelitozzz_image($imagen, [
                                'class'        => 'size-full object-cover',
                                'alt_fallback' => $titulo . ', ' . sprintf(__('foto %d de %d', 'angelitozzz'), $i + 1, $total),
                            ]) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace'   => $boton,
            'variante' => 'frambuesa',
        ]); ?>
    </div>
</section>
