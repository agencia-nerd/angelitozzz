<?php

/**
 * Inicio · Mundo Angelitozzz (specs/home.md, grupo home_seccion_cta).
 * El fondo de lunas es un asset decorativo del tema (clase fondo-lunas en src/css/input.css).
 */

defined('ABSPATH') || exit;

$logo   = get_theme_file_uri('assets/img/home/cta-logo-mundo-angelitozzz.webp');
$titulo = "El sueño de tu bebé cambia todo el tiempo.\nY cada vez que cambia, aparecen nuevas preguntas.\nMundo Angelitozzz es el lugar donde siempre hay respuestas.";
$texto  = 'Cada semana, una sesión en vivo conmigo para resolver lo que está pasando ahora mismo con el sueño de tu bebé. Más talleres grabados, recursos al instante y una comunidad de mamás que ya pasaron por donde tú estás.';
$puntos = [
    'Sesiones en vivo semanales con Bárbara — resuelves dudas en tiempo real',
    'Talleres y recursos para cada etapa, disponibles al instante',
    'Chat privado con mamás que entienden lo que estás viviendo',
];
$precio = 'Desde $14/mes · Cancela cuando quieras';
$boton  = ['url' => home_url('/mundo-angelitozzz/'), 'title' => 'Quiero tener siempre una respuesta', 'target' => ''];
?>
<section class="fondo-lunas px-4 py-22 max-md:py-14" aria-labelledby="home-seccion-cta-titulo">
    <div class="mx-auto flex max-w-[1070px] flex-col items-center gap-8 text-center">
        <div class="flex flex-col items-center gap-8">
            <?= angelitozzz_image($logo, [
                'class' => 'size-[86px] rounded-full object-cover',
                'alt_fallback' => 'Mundo Angelitozzz',
            ]) ?>
            <h2 id="home-seccion-cta-titulo" class="text-h3 text-marca max-md:text-h4"><?= angelitozzz_highlight($titulo) ?></h2>
        </div>

        <?php if ($texto !== '') : ?>
            <p class="text-body"><?= angelitozzz_highlight($texto) ?></p>
        <?php endif; ?>

        <?php if ($puntos) : ?>
            <ul class="lista-puntos text-body max-md:text-left">
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
