<?php

/**
 * Inicio · Beneficios del sueño saludable (specs/home.md, grupo home_beneficios).
 * Composición tipo móvil de cuna: los colgantes y sus posiciones son fijos y siguen el orden
 * del repetidor (ítem 1 bajo el cubo, 2 bajo la carita, 3 corazón, 4 luna, 5 chupete).
 * Escritorio: grilla de 5 columnas; la nube con el título se superpone en la misma celda (sin absolutos).
 */

defined('ABSPATH') || exit;

$beneficios = get_field('home_beneficios') ?: [];

$titulo = (string) ($beneficios['titulo'] ?? '');
$items  = array_values(array_filter(array_map(
    fn($fila) => trim((string) ($fila['texto'] ?? '')),
    (array) ($beneficios['items'] ?? [])
), 'strlen'));

// Colgante de cada posición: archivo de la figura, medidas del SVG, cuerda (x, largo y color, en
// unidades del SVG) y ancho del texto en el diseño. La cuerda se dibuja aparte para que
// src/js/modules/colgantes.js la doble; sin JS queda recta, como en el diseño.
$colgantes = [
    ['archivo' => 'beneficios-colgante-cubo.svg', 'ancho' => 88, 'alto' => 267, 'cuerda_x' => 44.75, 'cuerda' => 203, 'color' => '#9ec0c0', 'texto' => 'lg:max-w-[258px]'],
    ['archivo' => 'beneficios-colgante-carita.svg', 'ancho' => 83, 'alto' => 411, 'cuerda_x' => 41.57, 'cuerda' => 330, 'color' => '#e4ad9e', 'texto' => 'lg:max-w-[266px]'],
    ['archivo' => 'beneficios-colgante-corazon.svg', 'ancho' => 81, 'alto' => 558, 'cuerda_x' => 40.86, 'cuerda' => 530, 'color' => '#492343', 'texto' => 'lg:max-w-[280px]'],
    ['archivo' => 'beneficios-colgante-luna.svg', 'ancho' => 93, 'alto' => 410, 'cuerda_x' => 28.08, 'cuerda' => 390, 'color' => '#b7a45c', 'texto' => 'lg:max-w-[259px]'],
    ['archivo' => 'beneficios-colgante-chupete.svg', 'ancho' => 99, 'alto' => 278, 'cuerda_x' => 35.75, 'cuerda' => 190, 'color' => '#d8b11e', 'texto' => 'lg:max-w-[198px]'],
];
?>
<section class="overflow-hidden px-4 pb-22 max-lg:pt-14" aria-labelledby="home-beneficios-titulo">
    <div class="mx-auto flex max-w-[1485px] flex-col gap-10 lg:grid lg:gap-0">
        <div class="relative z-10 mx-auto grid w-full max-w-[486px] lg:col-start-1 lg:row-start-1 lg:mt-[69px] lg:translate-x-[22px] lg:self-start">
            <img class="col-start-1 row-start-1 w-full" src="<?= esc_url(get_theme_file_uri('assets/img/home/beneficios-nube-central.svg')) ?>" alt="" aria-hidden="true" width="486" height="291" loading="lazy">
            <h2 id="home-beneficios-titulo" class="col-start-1 row-start-1 mt-[26.5%] w-[76%] justify-self-center self-start text-center text-h3 text-marca max-md:text-h5"><?= angelitozzz_highlight($titulo) ?></h2>
        </div>

        <ul class="grid gap-10 md:grid-cols-2 lg:col-start-1 lg:row-start-1 lg:grid-cols-5 lg:gap-0" data-colgantes>
            <?php foreach ($items as $i => $texto) :
                $colgante = $colgantes[$i] ?? null;
            ?>
                <li class="flex flex-col items-center text-center">
                    <?php if ($colgante) : ?>
                        <div class="flex h-[100px] items-end justify-center overflow-y-clip lg:h-auto lg:items-start lg:overflow-visible" aria-hidden="true">
                            <div class="grid select-none" data-colgante data-cuerda-x="<?= esc_attr($colgante['cuerda_x']) ?>" data-cuerda="<?= esc_attr($colgante['cuerda']) ?>">
                                <svg class="col-start-1 row-start-1 overflow-visible" width="<?= esc_attr($colgante['ancho']) ?>" height="<?= esc_attr($colgante['alto']) ?>" viewBox="<?= esc_attr('0 0 ' . $colgante['ancho'] . ' ' . $colgante['alto']) ?>" fill="none" focusable="false">
                                    <path d="<?= esc_attr('M' . $colgante['cuerda_x'] . ' 0L' . $colgante['cuerda_x'] . ' ' . $colgante['cuerda']) ?>" stroke="<?= esc_attr($colgante['color']) ?>" stroke-width="1.5" stroke-linecap="round" data-colgante-cuerda />
                                </svg>
                                <img class="col-start-1 row-start-1 max-w-none" src="<?= esc_url(get_theme_file_uri('assets/img/home/' . $colgante['archivo'])) ?>" alt="" width="<?= esc_attr($colgante['ancho']) ?>" height="<?= esc_attr($colgante['alto']) ?>" loading="lazy" draggable="false" data-colgante-figura>
                            </div>
                        </div>
                    <?php endif; ?>
                    <p class="<?= esc_attr('mt-4 max-w-[280px] text-cuerpo lg:mt-12 ' . ($colgante['texto'] ?? '')) ?>"><?= esc_html($texto) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
