<?php

/**
 * Solución · relacionados (specs/solucion.md, sin campos): hasta 3 soluciones distintas de la actual,
 * en la versión corta de producto-card (sin descripción ni beneficios, botón "Más información").
 * Título y bajada fijos del tema; fondo rosa con lunas decorativas.
 *
 * $args:
 * - soluciones (array de argumentos de producto-card; f5: wp-query, angelitozzz_query_related())
 */

defined('ABSPATH') || exit;

$soluciones = array_slice(array_values(array_filter((array) ($args['soluciones'] ?? []), 'is_array')), 0, 3);

if (!$soluciones) {
    return;
}

$id_titulo = wp_unique_id('solucion-relacionados-titulo-');
?>
<section class="fondo-lunas-rosa bg-fondo-rosa px-4 py-22 max-md:py-14" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="mx-auto flex max-w-[1106px] flex-col gap-[47px] max-md:gap-10">
        <div class="flex flex-col gap-5">
            <h2 id="<?= esc_attr($id_titulo) ?>" class="text-h1 text-marca max-md:text-h3"><?= esc_html__('También podría interesarte...', 'angelitozzz') ?></h2>
            <p class="text-cuerpo text-tinta"><?= esc_html__('Descubre otros cursos diseñados para acompañarte en diferentes etapas del desarrollo del sueño de tu bebé.', 'angelitozzz') ?></p>
        </div>

        <ul class="grid grid-cols-1 justify-items-center gap-y-14 md:grid-cols-2 md:gap-x-8 lg:grid-cols-[repeat(3,346px)] lg:justify-between [&>li]:w-full [&>li]:max-w-[346px]">
            <?php foreach ($soluciones as $solucion) :
                $edad = trim((string) ($solucion['edad'] ?? ''));
                // La edad va entre paréntesis bajo el nombre, como en el diseño: "(4 a 24 meses · Desde $67)".
                if ($edad !== '' && $edad[0] !== '(') {
                    $edad = '(' . $edad . ')';
                }
                ?>
                <li class="flex">
                    <?php get_template_part('template-parts/components/producto-card', null, [
                        'imagen'         => $solucion['imagen'] ?? '',
                        'etiqueta'       => $solucion['etiqueta'] ?? '',
                        'etiqueta_icono' => $solucion['etiqueta_icono'] ?? 'ninguno',
                        'titulo'         => $solucion['titulo'] ?? '',
                        'edad'           => $edad,
                        'boton'          => $solucion['boton'] ?? '',
                    ]); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
