<?php

/**
 * Inicio · Beneficios del sueño saludable (specs/home.md, grupo home_beneficios).
 * Composición tipo móvil de cuna: los colgantes y sus posiciones son fijos y siguen el orden
 * del repetidor (ítem 1 bajo el cubo, 2 bajo la carita, 3 corazón, 4 luna, 5 chupete).
 * Escritorio: grilla de 5 columnas; la nube con el título se superpone en la misma celda (sin absolutos).
 */

defined('ABSPATH') || exit;

$titulo = 'Beneficios del sueño saludable';
$items  = [
    'Dormir promueve el sano crecimiento y desarrollo de tu bebé.',
    'Un bebé descansado es más feliz y menos estresado.',
    'Mejora la salud mental materna y fortalece el vínculo madre-bebé.',
    'Dormir bien fomenta su aprendizaje y desarrollo del lenguaje',
    'Mejora la tolerancia a la frustración.',
];

// Colgante de cada posición: archivo, medidas del SVG y ancho del texto en el diseño.
$colgantes = [
    ['archivo' => 'beneficios-colgante-cubo.svg', 'ancho' => 88, 'alto' => 267, 'texto' => 'lg:max-w-[258px]'],
    ['archivo' => 'beneficios-colgante-carita.svg', 'ancho' => 83, 'alto' => 411, 'texto' => 'lg:max-w-[266px]'],
    ['archivo' => 'beneficios-colgante-corazon.svg', 'ancho' => 81, 'alto' => 558, 'texto' => 'lg:max-w-[280px]'],
    ['archivo' => 'beneficios-colgante-luna.svg', 'ancho' => 93, 'alto' => 410, 'texto' => 'lg:max-w-[259px]'],
    ['archivo' => 'beneficios-colgante-chupete.svg', 'ancho' => 99, 'alto' => 278, 'texto' => 'lg:max-w-[198px]'],
];
?>
<section class="overflow-hidden px-4 pb-22 max-lg:pt-14" aria-labelledby="home-beneficios-titulo">
    <div class="mx-auto flex max-w-[1485px] flex-col gap-10 lg:grid lg:gap-0">
        <div class="relative z-10 mx-auto grid w-full max-w-[486px] lg:col-start-1 lg:row-start-1 lg:mt-[69px] lg:translate-x-[22px] lg:self-start">
            <img class="col-start-1 row-start-1 w-full" src="<?= esc_url(get_theme_file_uri('assets/img/home/beneficios-nube-central.svg')) ?>" alt="" aria-hidden="true" width="486" height="291" loading="lazy">
            <h2 id="home-beneficios-titulo" class="col-start-1 row-start-1 mt-[26.5%] w-[76%] justify-self-center self-start text-center text-h3 text-marca max-md:text-h5"><?= angelitozzz_highlight($titulo) ?></h2>
        </div>

        <ul class="grid gap-10 md:grid-cols-2 lg:col-start-1 lg:row-start-1 lg:grid-cols-5 lg:gap-0">
            <?php foreach ($items as $i => $texto) :
                $colgante = $colgantes[$i] ?? null;
            ?>
                <li class="flex flex-col items-center text-center">
                    <?php if ($colgante) : ?>
                        <div class="flex h-[100px] items-end justify-center overflow-hidden lg:h-auto lg:items-start lg:overflow-visible" aria-hidden="true">
                            <img class="max-w-none" src="<?= esc_url(get_theme_file_uri('assets/img/home/' . $colgante['archivo'])) ?>" alt="" width="<?= esc_attr($colgante['ancho']) ?>" height="<?= esc_attr($colgante['alto']) ?>" loading="lazy">
                        </div>
                    <?php endif; ?>
                    <p class="<?= esc_attr('mt-4 max-w-[280px] text-body lg:mt-12 ' . ($colgante['texto'] ?? '')) ?>"><?= esc_html($texto) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
