<?php

/**
 * Solución · bloque `experiencia` (specs/solucion.md, solucion_secciones_bloques).
 *
 * Título, texto centrado (las partes en negrita llevan el marcador de wp-helper), foto horizontal
 * redondeada y botón. Móvil: igual, en una columna.
 *
 * $args: fondo, titulo, texto, imagen, boton (+ contexto, indice).
 */

defined('ABSPATH') || exit;

$titulo = (string) ($args['titulo'] ?? '');
$texto  = (string) ($args['texto'] ?? '');
$imagen = $args['imagen'] ?? '';
$boton  = $args['boton'] ?? null;

if ($titulo === '') {
    return;
}

$id_titulo = wp_unique_id('solucion-experiencia-titulo-');
?>
<section class="<?= esc_attr('px-4 py-[104px] max-md:py-14 ' . angelitozzz_solucion_fondo((string) ($args['fondo'] ?? 'rosa'))) ?>" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="mx-auto flex max-w-[1110px] flex-col items-center text-center">
        <h2 id="<?= esc_attr($id_titulo) ?>" class="text-h1 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>

        <?php if ($texto !== '') : ?>
            <p class="mt-4 max-w-[820px] text-cuerpo-s text-tinta"><?= angelitozzz_highlight($texto) ?></p>
        <?php endif; ?>

        <?php $foto = angelitozzz_image($imagen, [
            'class'        => 'size-full object-cover',
            'alt_fallback' => $titulo,
        ]); ?>
        <?php if ($foto !== '') : ?>
            <div class="mt-10 aspect-[623/277] w-full max-w-[623px] overflow-hidden rounded-media">
                <?= $foto ?>
            </div>
        <?php endif; ?>

        <?php if ($boton) : ?>
            <div class="mt-[52px] max-md:mt-10">
                <?php get_template_part('template-parts/components/boton', null, [
                    'enlace'   => $boton,
                    'variante' => 'frambuesa',
                ]); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
