<?php

/**
 * Botón (componente Figma "Botón"): un enlace con forma de pastilla.
 *
 * $args:
 * - enlace      (array campo link | string URL, requerido salvo con `modal` + `texto`)
 * - texto       (string, opcional: reemplaza el texto del enlace)
 * - variante    ('frambuesa' | 'verde' | 'secundario' | 'claro', por defecto 'frambuesa'; 'claro' va sobre fondos oscuros)
 * - flecha      (bool, por defecto true)
 * - desactivado (bool, por defecto false; también se activa si el enlace es '#' o está vacío)
 * - clase       (string, opcional)
 * - modal       (string, opcional: id de un modal de wp-modal; imprime un <button> que lo abre en vez de un enlace)
 * - datos       (array, opcional: atributos data-* extra del <button> con `modal`, p. ej. ['data-etapa' => '1'])
 */

defined('ABSPATH') || exit;

$enlace      = $args['enlace'] ?? '';
$texto       = trim((string) ($args['texto'] ?? ''));
$variante    = $args['variante'] ?? 'frambuesa';
$flecha      = (bool) ($args['flecha'] ?? true);
$desactivado = (bool) ($args['desactivado'] ?? false);
$clase       = (string) ($args['clase'] ?? '');
$modal       = trim((string) ($args['modal'] ?? ''));
$datos       = (array) ($args['datos'] ?? []);

$link = is_array($enlace) ? $enlace : ['url' => (string) $enlace];
if ($texto === '') {
    $texto = trim((string) ($link['title'] ?? ''));
}
if ($texto === '') {
    return;
}
$link['title'] = $texto;

$variantes = [
    'frambuesa'  => 'bg-acento text-blanco focus-visible:outline-marca',
    'verde'      => 'bg-marca text-fondo focus-visible:outline-marca',
    'secundario' => 'border-[1.5px] border-marca text-marca focus-visible:outline-marca',
    'claro'      => 'bg-fondo text-marca focus-visible:outline-fondo',
];
$clases = trim(implode(' ', [
    'inline-flex items-center gap-[10px] rounded-full py-[14px] pl-[28px] pr-[24px] text-rotulo whitespace-nowrap',
    'focus-visible:outline-2 focus-visible:outline-offset-2',
    $variantes[$variante] ?? $variantes['frambuesa'],
    $clase,
]));

$flecha_svg = '<svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>';

// Con `modal`: un <button> que abre ese modal (es una acción, no una navegación).
if ($modal !== '') : ?>
    <button class="<?= esc_attr($clases . ' cursor-pointer disabled:cursor-default disabled:opacity-50') ?>"<?php
        angelitozzz_modal_trigger_attrs($modal);
        foreach ($datos as $nombre => $valor) {
            if (str_starts_with((string) $nombre, 'data-')) {
                echo ' ', esc_attr((string) $nombre), '="', esc_attr((string) $valor), '"';
            }
        }
        echo $desactivado ? ' disabled' : '';
    ?>>
        <span><?= esc_html($texto) ?></span>
        <?= $flecha ? $flecha_svg : '' // SVG fijo del tema. ?>
    </button>
<?php
    return;
endif;

$attrs = $desactivado ? '' : angelitozzz_link_attrs($link, ['class' => $clases]);
$data  = $attrs !== '' ? angelitozzz_link_data($link) : null;
?>
<?php if ($attrs === '') : ?>
    <a class="<?= esc_attr($clases . ' cursor-default opacity-50') ?>" aria-disabled="true">
<?php else : ?>
    <a<?= $attrs ?>>
<?php endif; ?>
    <span><?= esc_html($texto) ?></span>
    <?php if ($data && $data['blank']) : ?>
        <span class="sr-only">(<?= esc_html__('se abre en una pestaña nueva', 'angelitozzz') ?>)</span>
    <?php endif; ?>
    <?= $flecha ? $flecha_svg : '' // SVG fijo del tema. ?>
</a>
