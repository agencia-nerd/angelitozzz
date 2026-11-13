<?php

/**
 * Inicio · Mundo Angelitozzz (specs/home.md, grupo home_seccion_cta).
 * El fondo de lunas es un asset decorativo del tema (clase fondo-lunas en src/css/input.css).
 */

defined('ABSPATH') || exit;

$cta = get_field('home_seccion_cta') ?: [];

$logo   = $cta['logo'] ?? null;
$titulo = (string) ($cta['titulo'] ?? '');
$texto  = (string) ($cta['texto'] ?? '');
$puntos = array_filter(array_map(
    fn($fila) => trim((string) ($fila['texto'] ?? '')),
    (array) ($cta['puntos'] ?? [])
), 'strlen');
$precio = (string) ($cta['precio'] ?? '');
$boton  = $cta['boton'] ?? null;
?>
<section class="fondo-lunas px-4 py-22 max-md:py-14" aria-labelledby="home-seccion-cta-titulo">
    <div class="mx-auto flex max-w-[1070px] flex-col items-center gap-8 text-center">
        <div class="flex flex-col items-center gap-8">
            <?= angelitozzz_image($logo, [
                'class' => 'size-[86px] rounded-full object-cover',
                'alt_fallback' => trim(preg_replace('/\s+/', ' ', $titulo)),
            ]) ?>
            <h2 id="home-seccion-cta-titulo" class="text-h3 text-marca max-md:text-h4"><?= angelitozzz_highlight($titulo) ?></h2>
        </div>

        <?php if ($texto !== '') : ?>
            <p class="text-cuerpo"><?= angelitozzz_highlight($texto) ?></p>
        <?php endif; ?>

        <?php if ($puntos) : ?>
            <ul class="lista-puntos text-cuerpo max-md:text-left">
                <?php foreach ($puntos as $punto) : ?>
                    <li><?= esc_html($punto) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($precio !== '') : ?>
            <p class="text-h5 text-marca"><?= esc_html($precio) ?></p>
        <?php endif; ?>

        <?php get_template_part('template-parts/components/boton', null, [
            'enlace'   => $boton,
            'variante' => 'verde',
        ]); ?>
    </div>
</section>
