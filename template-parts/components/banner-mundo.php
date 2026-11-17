<?php

/**
 * Banner del mes gratis en Mundo Angelitozzz (componente Figma "Banner Mundo" 3586:1518).
 * Enlace, pie y texto del botón salen siempre de Ajustes del sitio (opciones_mes_gratis_mundo);
 * sin enlace no imprime nada. Lo usan la primera lección y la página de gracias.
 *
 * $args:
 * - titulo   (string, opcional: si no viene, el de Ajustes del sitio)
 * - texto    (string, opcional: ídem)
 * - variante ('leccion' | 'confirmacion', por defecto 'leccion')
 */

defined('ABSPATH') || exit;

$opciones = get_field('opciones_mes_gratis_mundo', 'option') ?: [];

$enlace   = trim((string) ($opciones['enlace'] ?? ''));
$titulo   = trim((string) ($args['titulo'] ?? '')) ?: trim((string) ($opciones['titulo'] ?? ''));
$texto    = trim((string) ($args['texto'] ?? '')) ?: trim((string) ($opciones['texto'] ?? ''));
$pie      = trim((string) ($opciones['pie'] ?? ''));
$boton    = trim((string) ($opciones['boton'] ?? ''));
$variante = ($args['variante'] ?? 'leccion') === 'confirmacion' ? 'confirmacion' : 'leccion';

if ($enlace === '' || $boton === '' || angelitozzz_link_attrs(['url' => $enlace]) === '') {
    return;
}

$id_titulo = wp_unique_id('banner-mundo-titulo-');

// En la confirmación (720 px) el botón baja bajo el texto; en la lección va a la derecha.
$fila = $variante === 'confirmacion' ? 'flex-wrap' : 'lg:flex-nowrap flex-wrap';
?>
<section class="<?= esc_attr('relative mx-auto flex w-full items-center gap-8 overflow-hidden rounded-tarjeta bg-[linear-gradient(156.57deg,#1c3a3e_11.54%,#234549_57.69%,#3e6b6d_88.46%)] py-8 pl-8 pr-10 shadow-[0_16px_40px_rgba(36,69,74,0.18)] max-md:flex-col max-md:items-center max-md:px-6 max-md:text-center ' . ($variante === 'confirmacion' ? 'max-w-[720px] ' : '') . $fila) ?>" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <img class="pointer-events-none absolute -top-[30px] right-[8%] h-[72px] w-[74px] rotate-[165deg]" src="<?= esc_url(get_theme_file_uri('assets/img/compra/banner-vector-decorativo.svg')) ?>" alt="" aria-hidden="true" width="74" height="72" loading="lazy">

    <span class="relative size-24 shrink-0 overflow-hidden rounded-full border-[3px] border-estrella bg-fondo shadow-[0_0_16px_2px_rgba(247,245,240,0.15)]">
        <img class="size-full object-contain" src="<?= esc_url(get_theme_file_uri('assets/img/compra/banner-logo-mundo.webp')) ?>" alt="" aria-hidden="true" width="96" height="96" loading="lazy">
    </span>

    <div class="flex min-w-0 flex-1 basis-[280px] flex-col gap-2 max-md:basis-auto">
        <h2 id="<?= esc_attr($id_titulo) ?>" class="text-h3 text-fondo max-md:text-h4"><?= esc_html($titulo) ?></h2>
        <?php if ($texto !== '') : ?>
            <p class="text-cuerpo text-neutro"><?= angelitozzz_highlight($texto) ?></p>
        <?php endif; ?>
        <?php if ($pie !== '') : ?>
            <p class="text-cuerpo italic text-blanco/80"><?= esc_html($pie) ?></p>
        <?php endif; ?>
    </div>

    <div class="<?= esc_attr('relative shrink-0 ' . ($variante === 'confirmacion' ? 'w-full pl-32 max-md:pl-0' : '')) ?>">
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace'   => ['url' => $enlace, 'title' => $boton, 'target' => '_blank'],
            'variante' => 'claro',
            'flecha'   => false,
        ]); ?>
    </div>
</section>
