<?php

/**
 * Cabecera del sitio (specs/opciones.md: cintillo-promociones, header).
 * Cintillo y header salen en todas las páginas; ninguna plantilla los repite.
 */

defined('ABSPATH') || exit;

$cintillo = get_field('opciones_cintillo_promociones', 'option') ?: [];

// Enlaces del diseño, solo mientras las ubicaciones no tengan un menú asignado (Apariencia > Menús).
$menu_principal = [
    ['texto' => 'Soluciones de Sueño', 'url' => '/soluciones-de-sueno/'],
    ['texto' => 'Blog', 'url' => '/blog/'],
    ['texto' => 'Mundo Angelitozzz', 'url' => '/mundo-angelitozzz/'],
    ['texto' => 'Conoce a Bárbara', 'url' => '/conoce-a-barbara/'],
    ['texto' => 'Testimonios', 'url' => '/testimonios/'],
];
$menu_derecha = [
    ['texto' => 'Contacto', 'url' => '/contacto/'],
];
// WooCommerce aún no está definido (_shared.md): cuenta y carrito apuntan a '#' y el contador es el del diseño.
$cuenta           = ['url' => '#', 'title' => 'Iniciar sesión', 'target' => ''];
$carrito          = ['url' => '#', 'title' => 'Carrito', 'target' => ''];
$carrito_cantidad = 1;

$clase_cuenta  = 'flex h-[46px] items-center gap-2 rounded-full border border-neutro bg-blanco pl-[14px] pr-[18px] text-rotulo-s text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
$clase_carrito = 'relative flex size-[46px] items-center justify-center rounded-full border border-neutro bg-blanco text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
$attrs_cuenta  = angelitozzz_link_attrs($cuenta, ['class' => $clase_cuenta]);
$attrs_carrito = angelitozzz_link_attrs($carrito, ['class' => $clase_carrito]);
$icono_persona = '<svg class="size-[22px] shrink-0" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="11" cy="7.33" r="4.13" /><path d="M3.67 19.25a7.33 7.33 0 0 1 14.66 0" /></svg>';
// "Iniciar sesión": el mismo enlace en la barra (escritorio) y en el panel del menú móvil.
$cuenta_html = '<a' . ($attrs_cuenta !== '' ? $attrs_cuenta : ' class="' . esc_attr($clase_cuenta) . '" aria-disabled="true"') . '>'
    . $icono_persona . '<span class="whitespace-nowrap">' . esc_html($cuenta['title']) . '</span></a>';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-full focus:bg-blanco focus:px-5 focus:py-3 focus:text-rotulo focus:text-marca" href="#main-content"><?= esc_html__('Saltar al contenido', 'angelitozzz') ?></a>

    <?php get_template_part('template-parts/components/cintillo-promociones', null, [
        'texto' => (string) ($cintillo['texto'] ?? ''),
        'modal' => 'modal-coach',
    ]); ?>

    <header class="relative z-30 bg-fondo shadow-header">
        <div class="mx-auto flex max-w-[1364px] items-center justify-between gap-6 px-4 py-4">
            <div class="shrink-0 [&_img]:h-[63px] [&_img]:w-auto max-lg:[&_img]:h-12">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a class="text-h4 text-marca" href="<?= esc_url(home_url('/')) ?>" rel="home"><?php bloginfo('name'); ?></a>
                <?php endif; ?>
            </div>

            <nav class="max-lg:hidden" aria-label="<?= esc_attr__('Principal', 'angelitozzz') ?>">
                <?php angelitozzz_menu('principal', $menu_principal, [
                    'clase'        => 'flex items-center gap-6 text-cuerpo-s xl:gap-11 [&_a:hover]:text-marca [&_a]:whitespace-nowrap [&_[aria-current]]:text-marca',
                    'clase_enlace' => '',
                ]); ?>
            </nav>

            <div class="flex items-center gap-[38px] max-lg:gap-3">
                <nav class="max-lg:hidden" aria-label="<?= esc_attr__('Contacto', 'angelitozzz') ?>">
                    <?php angelitozzz_menu('cabecera-derecha', $menu_derecha, [
                        'clase'        => 'flex items-center gap-6 text-cuerpo [&_a:hover]:text-marca [&_[aria-current]]:text-marca',
                        'clase_enlace' => '',
                    ]); ?>
                </nav>
                <div class="flex items-center gap-3">
                    <div class="max-lg:hidden"><?= $cuenta_html ?></div>

                    <a<?= $attrs_carrito !== '' ? $attrs_carrito : ' class="' . esc_attr($clase_carrito) . '" aria-disabled="true"' ?>>
                        <span class="sr-only"><?= esc_html(sprintf(_n('Carrito (%d producto)', 'Carrito (%d productos)', $carrito_cantidad, 'angelitozzz'), $carrito_cantidad)) ?></span>
                        <svg class="h-5 w-[23px]" viewBox="0 0 22.67 20.23" fill="none" stroke="currentColor" stroke-width="1.29" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M.65.65h2.7l4.03 13.39c.22.73.89 1.23 1.65 1.23h9.23c.75 0 1.42-.49 1.65-1.21l2.04-6.48c.35-1.12-.48-2.25-1.65-2.25H5.19" /><circle cx="17.82" cy="18.39" r="1.2" /><circle cx="9.87" cy="18.39" r="1.2" /></svg>
                        <?php if ($carrito_cantidad > 0) : ?>
                            <span class="absolute -top-[5px] left-[29px] flex size-5 items-center justify-center rounded-full bg-acento text-[12px] leading-[1.4] text-blanco" aria-hidden="true"><?= esc_html($carrito_cantidad) ?></span>
                        <?php endif; ?>
                    </a>

                    <button type="button" class="flex size-[46px] items-center justify-center rounded-full border border-neutro bg-blanco text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca lg:hidden" aria-expanded="false" aria-controls="menu-movil" data-menu-toggle>
                        <span class="sr-only"><?= esc_html__('Menú', 'angelitozzz') ?></span>
                        <svg class="size-[22px]" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M3.67 6.42h14.66M3.67 11h14.66M3.67 15.58h14.66" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="menu-movil" class="border-t border-neutro bg-fondo px-4 pb-8 pt-4 lg:hidden" hidden data-menu-panel>
            <nav aria-label="<?= esc_attr__('Principal', 'angelitozzz') ?>">
                <?php angelitozzz_menu('principal', $menu_principal, [
                    'clase'        => 'flex flex-col text-cuerpo [&_a]:block [&_a]:py-3 [&_span]:block [&_span]:py-3 [&_[aria-current]]:text-marca',
                    'clase_enlace' => '',
                ]); ?>
                <?php angelitozzz_menu('cabecera-derecha', $menu_derecha, [
                    'clase'        => 'flex flex-col border-t border-neutro text-cuerpo [&_a]:block [&_a]:py-3 [&_[aria-current]]:text-marca',
                    'clase_enlace' => '',
                ]); ?>
            </nav>
            <div class="mt-4 flex"><?= $cuenta_html ?></div>
        </div>
    </header>
