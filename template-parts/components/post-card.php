<?php

/**
 * Tarjeta de blog (componente Figma "Tarjeta de blog"). Toda la tarjeta es el enlace al artículo.
 *
 * $args:
 * - imagen    (array imagen | ID | URL, requerido)
 * - categoria (string, requerido: etiqueta de esquina)
 * - fecha     (string, requerido: "1 de marzo de 2024"; la tarjeta antepone "Publicado el")
 * - lectura   (string, requerido: "5 min de lectura")
 * - titulo    (string, requerido)
 * - resumen   (string, requerido)
 * - url       (string, requerido; '#' o vacío = sin enlace)
 */

defined('ABSPATH') || exit;

$imagen    = $args['imagen'] ?? '';
$categoria = (string) ($args['categoria'] ?? '');
$fecha     = (string) ($args['fecha'] ?? '');
$lectura   = (string) ($args['lectura'] ?? '');
$titulo    = (string) ($args['titulo'] ?? '');
$resumen   = (string) ($args['resumen'] ?? '');
$url       = (string) ($args['url'] ?? '');

if ($titulo === '') {
    return;
}

$clases = 'group flex h-full flex-col overflow-hidden rounded-media border border-neutro bg-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
$attrs  = $url !== '' && $url !== '#' ? angelitozzz_link_attrs(['url' => $url], ['class' => $clases]) : '';
$meta   = array_filter([$fecha !== '' ? sprintf(__('Publicado el %s', 'angelitozzz'), $fecha) : '', $lectura]);
?>
<?php if ($attrs !== '') : ?>
    <a<?= $attrs ?>>
<?php else : ?>
    <div class="<?= esc_attr($clases) ?>">
<?php endif; ?>
    <div class="relative aspect-[5/3] w-full overflow-hidden">
        <?= angelitozzz_image($imagen, [
            'class'        => 'size-full object-cover',
            'alt'          => '',
        ]) ?>
        <?php if ($categoria !== '') : ?>
            <span class="absolute left-0 top-0">
                <?php get_template_part('template-parts/components/etiqueta', null, [
                    'texto'  => $categoria,
                    'estilo' => 'esquina',
                ]); ?>
            </span>
        <?php endif; ?>
    </div>
    <div class="flex flex-1 flex-col items-start gap-2.5 p-6">
        <?php if ($meta) : ?>
            <p class="text-caption text-tinta-suave"><?= esc_html(implode(' · ', $meta)) ?></p>
        <?php endif; ?>
        <h3 class="text-h4 text-marca"><?= esc_html($titulo) ?></h3>
        <?php if ($resumen !== '') : ?>
            <p class="text-body-s"><?= esc_html($resumen) ?></p>
        <?php endif; ?>
        <span class="mt-auto flex items-center gap-2 pt-1.5 text-label text-acento" aria-hidden="true">
            <?= esc_html__('Leer artículo', 'angelitozzz') ?>
            <svg class="size-[18px] shrink-0 transition-transform group-hover:translate-x-1" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
        </span>
    </div>
<?php if ($attrs !== '') : ?>
    </a>
<?php else : ?>
    </div>
<?php endif; ?>
