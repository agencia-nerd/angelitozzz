<?php

/**
 * Etiqueta (componente Figma "Etiqueta").
 *
 * $args:
 * - texto  (string, requerido)
 * - estilo ('tarjeta' | 'hero' | 'esquina', por defecto 'tarjeta')
 * - icono  ('ninguno' | 'destellos' | 'estrella', por defecto 'ninguno')
 * - color  ('salvia' | 'estrella' | 'suave' | 'neutro', por defecto 'salvia': fondo del estilo 'tarjeta';
 *           lo usan los estados del historial de pedidos)
 */

defined('ABSPATH') || exit;

$texto  = trim((string) ($args['texto'] ?? ''));
$estilo = $args['estilo'] ?? 'tarjeta';
$icono  = $args['icono'] ?? 'ninguno';
$color  = $args['color'] ?? 'salvia';

if ($texto === '') {
    return;
}

// 'hero' se ajusta con el diseño del blog cuando se aplique esa página (Inicio no lo usa).
$colores = [
    'salvia'   => 'bg-etiqueta text-blanco',
    'estrella' => 'bg-estrella text-blanco',
    'suave'    => 'bg-tinta-suave text-blanco',
    'neutro'   => 'bg-neutro text-marca',
];
$estilos = [
    'tarjeta' => 'rounded-full px-[14px] py-[6px] ' . ($colores[$color] ?? $colores['salvia']),
    'hero'    => 'rounded-full bg-blanco px-[14px] py-[6px] text-marca',
    'esquina' => 'rounded-br-etiqueta bg-etiqueta py-[7px] pl-[18px] pr-4 text-blanco',
];
$iconos = [
    'destellos' => 'etiqueta-icono-destellos.svg',
    'estrella'  => 'etiqueta-icono-estrella.svg',
];
$archivo = $iconos[$icono] ?? '';
?>
<span class="<?= esc_attr('inline-flex items-center gap-[6px] text-rotulo-s whitespace-nowrap ' . ($estilos[$estilo] ?? $estilos['tarjeta'])) ?>">
    <?php if ($archivo !== '') : ?>
        <img class="size-[14px] shrink-0" src="<?= esc_url(get_theme_file_uri('assets/img/' . $archivo)) ?>" alt="" aria-hidden="true" width="14" height="14">
    <?php endif; ?>
    <?= esc_html($texto) ?>
</span>
