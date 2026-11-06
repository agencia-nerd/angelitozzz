<?php

/**
 * Botón (componente Figma "Botón"): un enlace con forma de pastilla.
 *
 * $args:
 * - enlace      (array campo link | string URL, requerido)
 * - texto       (string, opcional: reemplaza el texto del enlace)
 * - variante    ('frambuesa' | 'verde' | 'secundario', por defecto 'frambuesa')
 * - flecha      (bool, por defecto true)
 * - desactivado (bool, por defecto false; también se activa si el enlace es '#' o está vacío)
 * - clase       (string, opcional)
 */

defined('ABSPATH') || exit;

$enlace      = $args['enlace'] ?? '';
$texto       = trim((string) ($args['texto'] ?? ''));
$variante    = $args['variante'] ?? 'frambuesa';
$flecha      = (bool) ($args['flecha'] ?? true);
$desactivado = (bool) ($args['desactivado'] ?? false);
$clase       = (string) ($args['clase'] ?? '');

$link = is_array($enlace) ? $enlace : ['url' => (string) $enlace];
if ($texto === '') {
    $texto = trim((string) ($link['title'] ?? ''));
}
if ($texto === '') {
    return;
}
$link['title'] = $texto;

$variantes = [
    'frambuesa'  => 'bg-acento text-blanco',
    'verde'      => 'bg-marca text-fondo',
    'secundario' => 'border-[1.5px] border-marca text-marca',
];
$clases = trim(implode(' ', [
    'inline-flex items-center gap-[10px] rounded-full py-[14px] pl-[28px] pr-[24px] text-label whitespace-nowrap',
    'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca',
    $variantes[$variante] ?? $variantes['frambuesa'],
    $clase,
]));

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
    <?php if ($flecha) : ?>
        <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
            <path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" />
        </svg>
    <?php endif; ?>
</a>
