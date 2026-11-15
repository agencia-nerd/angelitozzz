<?php

/**
 * Tarjeta de confirmación (Figma "Regalo listo" 3649:1702, "Canje de regalo" 3640:1593,
 * "Canje exitoso" 3677:1971). Abre la tarjeta: el contenido de cada pantalla va después y
 * template-parts/components/confirmacion-tarjeta-fin.php la cierra.
 *
 * $args:
 * - titulo          (string, requerido)
 * - nivel           ('h1' | 'h2', por defecto 'h1')
 * - icono           ('regalo' | 'check' | 'ninguno', por defecto 'ninguno': SVG de 56 px, decorativo)
 * - pastilla        (string, opcional: "✓ Compra exitosa", "✓ Regalo canjeado")
 * - pastilla_estilo ('verde' | 'neutro', por defecto 'verde')
 * - texto           (string, opcional)
 */

defined('ABSPATH') || exit;

$titulo          = trim((string) ($args['titulo'] ?? ''));
$nivel           = ($args['nivel'] ?? 'h1') === 'h2' ? 'h2' : 'h1';
$icono           = $args['icono'] ?? 'ninguno';
$pastilla        = trim((string) ($args['pastilla'] ?? ''));
$pastilla_estilo = ($args['pastilla_estilo'] ?? 'verde') === 'neutro' ? 'neutro' : 'verde';
$texto           = trim((string) ($args['texto'] ?? ''));

$iconos = [
    'regalo' => 'confirmacion-icono-regalo.svg',
    'check'  => 'confirmacion-icono-check.svg',
];
$archivo = $iconos[$icono] ?? '';

$pastillas = [
    'verde'  => 'bg-marca text-fondo',
    'neutro' => 'bg-neutro text-marca',
];
?>
<article class="mx-auto flex w-full max-w-[720px] flex-col items-center gap-6 rounded-tarjeta bg-blanco px-16 py-14 text-center max-md:px-6 max-md:py-8">
    <?php if ($archivo !== '') : ?>
        <img class="size-14 shrink-0" src="<?= esc_url(get_theme_file_uri('assets/img/compra/' . $archivo)) ?>" alt="" aria-hidden="true" width="56" height="56">
    <?php endif; ?>

    <?php if ($pastilla !== '') : ?>
        <p class="<?= esc_attr('rounded-full px-[14px] py-[6px] text-rotulo ' . $pastillas[$pastilla_estilo]) ?>"><?= esc_html($pastilla) ?></p>
    <?php endif; ?>

    <<?= $nivel ?> class="text-h2 text-marca max-md:text-h3"><?= esc_html($titulo) ?></<?= $nivel ?>>

    <?php if ($texto !== '') : ?>
        <p class="text-cuerpo-l text-tinta"><?= angelitozzz_highlight($texto) ?></p>
    <?php endif; ?>
