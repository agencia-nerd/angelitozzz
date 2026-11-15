<?php

/**
 * Tarjeta de un curso con el avance de la alumna (usuario-escritorio: horizontal; usuario-cursos:
 * vertical). Recibe los datos que da el plugin (angelitozzz_cursos_de_usuaria()), ya calculados para
 * la usuaria de la sesión. El pie cambia con el avance: 0 % "Empezar curso", en progreso
 * "Continuar curso", 100 % la etiqueta "Completado" y "Volver a ver".
 *
 * $args:
 * - datos     (array, requerido: titulo, extracto, imagen_id, url, porcentaje, completadas, total)
 * - variante  ('horizontal' | 'vertical', por defecto 'vertical')
 * - lecciones (bool: "N de M lecciones"; por defecto true en vertical y false en horizontal)
 * - nivel     ('h2' | 'h3', por defecto 'h3': título de la tarjeta)
 */

defined('ABSPATH') || exit;

$datos     = (array) ($args['datos'] ?? []);
$variante  = ($args['variante'] ?? 'vertical') === 'horizontal' ? 'horizontal' : 'vertical';
$lecciones = (bool) ($args['lecciones'] ?? $variante === 'vertical');
$nivel     = ($args['nivel'] ?? 'h3') === 'h2' ? 'h2' : 'h3';

$titulo     = trim((string) ($datos['titulo'] ?? ''));
$extracto   = trim((string) ($datos['extracto'] ?? ''));
$imagen_id  = (int) ($datos['imagen_id'] ?? 0);
$url        = (string) ($datos['url'] ?? '');
$porcentaje = max(0, min(100, (int) ($datos['porcentaje'] ?? 0)));
$hechas     = (int) ($datos['completadas'] ?? 0);
$total      = (int) ($datos['total'] ?? 0);

if ($titulo === '') {
    return;
}

$completado = $porcentaje >= 100;
$boton      = $completado
    ? ['texto' => __('Volver a ver', 'angelitozzz'), 'variante' => 'secundario']
    : ['texto' => $porcentaje > 0 ? __('Continuar curso', 'angelitozzz') : __('Empezar curso', 'angelitozzz'), 'variante' => 'frambuesa'];

$id_barra   = wp_unique_id('curso-progreso-');
$horizontal = $variante === 'horizontal';
?>
<article class="<?= esc_attr($horizontal
    ? 'flex gap-5 rounded-[15px] bg-blanco p-5 max-md:flex-col'
    : 'flex h-full flex-col overflow-hidden rounded-[20px] border border-neutro bg-blanco') ?>">
    <div class="<?= esc_attr($horizontal ? 'aspect-[371/198] w-[371px] shrink-0 overflow-hidden rounded-[10px] max-md:w-full' : 'aspect-[353/190] w-full overflow-hidden') ?>">
        <?= angelitozzz_image($imagen_id, [
            'class'        => 'size-full object-cover',
            'alt'          => '',
            'loading'      => 'lazy',
            'fallback'     => get_theme_file_uri('assets/img/ayudarte-imagen-not-found.webp'),
        ]) ?>
    </div>

    <div class="<?= esc_attr($horizontal ? 'flex min-w-0 flex-1 flex-col gap-4 md:pr-[110px]' : 'flex flex-1 flex-col gap-4 p-5') ?>">
        <div class="flex flex-wrap items-center gap-3">
            <<?= $nivel ?> class="text-h5 text-marca"><?= esc_html($titulo) ?></<?= $nivel ?>>
            <?php if ($completado) {
                get_template_part('template-parts/components/etiqueta', null, ['texto' => __('Completado', 'angelitozzz')]);
            } ?>
        </div>

        <?php if ($lecciones && $total > 0) : ?>
            <p class="-mt-2 flex items-center gap-[6px] text-caption text-tinta-suave">
                <?= angelitozzz_svg('cuenta/lecciones', 'size-4 shrink-0') // SVG fijo del tema. ?>
                <?= esc_html(sprintf(
                    /* translators: 1: lecciones completadas, 2: total de lecciones */
                    _n('%1$d de %2$d lección', '%1$d de %2$d lecciones', $total, 'angelitozzz'),
                    $hechas,
                    $total
                )) ?>
            </p>
        <?php endif; ?>

        <?php if ($extracto !== '') : ?>
            <p class="text-cuerpo-s text-tinta"><?= esc_html($extracto) ?></p>
        <?php endif; ?>

        <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between text-caption">
                <span id="<?= esc_attr($id_barra) ?>" class="text-[#859898]"><?= esc_html__('Progreso', 'angelitozzz') ?></span>
                <span class="text-marca"><?= esc_html($porcentaje) ?>%</span>
            </div>
            <?php // Barra nativa (rol progressbar implícito): fondo crema con borde beige y relleno frambuesa. ?>
            <progress class="barra-progreso" max="100" value="<?= esc_attr($porcentaje) ?>" aria-labelledby="<?= esc_attr($id_barra) ?>"><?= esc_html($porcentaje) ?>%</progress>
        </div>

        <?php if ($url !== '') : ?>
            <div class="<?= esc_attr($horizontal ? '' : 'mt-auto pt-4') ?>">
                <?php get_template_part('template-parts/components/boton', null, [
                    'enlace'   => ['url' => $url, 'title' => $boton['texto']],
                    'variante' => $boton['variante'],
                    'texto'    => $boton['texto'],
                ]); ?>
            </div>
        <?php endif; ?>
    </div>
</article>
