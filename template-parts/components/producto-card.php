<?php

/**
 * Tarjeta de curso o servicio (home/como-ayudarte, soluciones-de-sueno/shop).
 *
 * $args:
 * - imagen         (array imagen | ID | URL, o vacío: se usa la imagen por defecto "not found")
 * - etiqueta       (string, opcional)
 * - etiqueta_icono ('ninguno' | 'destellos' | 'estrella')
 * - titulo         (string, requerido)
 * - edad           (string, opcional)
 * - descripcion    (string, requerido; una línea en blanco separa párrafos)
 * - beneficios     (array de strings, opcional)
 * - boton          (array link, requerido)
 * - loading        ('lazy' | 'eager', por defecto 'lazy')
 */

defined('ABSPATH') || exit;

$imagen         = $args['imagen'] ?? '';
$etiqueta       = (string) ($args['etiqueta'] ?? '');
$etiqueta_icono = $args['etiqueta_icono'] ?? 'ninguno';
$titulo         = (string) ($args['titulo'] ?? '');
$edad           = (string) ($args['edad'] ?? '');
$descripcion    = (string) ($args['descripcion'] ?? '');
$beneficios     = array_filter((array) ($args['beneficios'] ?? []), 'strlen');
$boton          = $args['boton'] ?? '';
$loading        = ($args['loading'] ?? 'lazy') === 'eager' ? 'eager' : 'lazy';

if ($titulo === '') {
    return;
}
?>
<article class="flex h-full flex-col items-center gap-6 text-center">
    <div class="flex w-full flex-col items-center gap-5">
        <div class="relative aspect-[346/374] w-full overflow-hidden rounded-tarjeta">
            <?= angelitozzz_image($imagen, [
                'class'        => 'size-full object-cover',
                'alt_fallback' => $titulo,
                'loading'      => $loading,
                'fallback'     => get_theme_file_uri('assets/img/ayudarte-imagen-not-found.webp'),
            ]) ?>
            <?php if ($etiqueta !== '') : ?>
                <div class="absolute left-0 top-0">
                    <?php get_template_part('template-parts/components/etiqueta', null, [
                        'texto'  => $etiqueta,
                        'estilo' => 'esquina',
                        'icono'  => $etiqueta_icono,
                    ]); ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <h3 class="text-body"><?= esc_html($titulo) ?></h3>
            <?php if ($edad !== '') : ?>
                <p class="text-body"><?= esc_html($edad) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($descripcion !== '') : ?>
        <p class="max-w-[314px] text-body-s text-tinta-suave"><?= angelitozzz_highlight($descripcion) ?></p>
    <?php endif; ?>

    <?php if ($beneficios) : ?>
        <ul class="lista-puntos w-full max-w-[314px] text-left text-body-s">
            <?php foreach ($beneficios as $beneficio) : ?>
                <li><?= esc_html($beneficio) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="mt-auto">
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace'   => $boton,
            'variante' => 'frambuesa',
        ]); ?>
    </div>
</article>
