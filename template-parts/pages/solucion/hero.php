<?php

/**
 * Solución · hero (specs/solucion.md, grupo solucion_hero).
 *
 * Dos columnas: texto (etiqueta, h1, subtítulo, descripción, datos, canje y WhatsApp) y la tarjeta
 * de compra (imagen, precio, botones, nota y cupón). Fondo: la misma imagen al 45 % con el degradado
 * beige encima (decorativa). Móvil: una columna, la tarjeta bajo el texto y sin la foto de fondo.
 *
 * $args: campos de solucion_hero (etiqueta, etiqueta_icono, titular, subtitulo, descripcion, datos, imagen, boton,
 * nota, regalo, whatsapp, whatsapp_texto) + contexto (precio, modal_compra…). El enlace de WhatsApp se arma con
 * el número de Ajustes del sitio (angelitozzz_solucion_whatsapp); sin número no se muestra.
 */

defined('ABSPATH') || exit;

$contexto    = (array) ($args['contexto'] ?? []);
$etiqueta    = (string) ($args['etiqueta'] ?? '');
$icono       = in_array($args['etiqueta_icono'] ?? '', ['destellos', 'estrella'], true) ? $args['etiqueta_icono'] : 'ninguno';
$titular     = (string) ($args['titular'] ?? '');
$subtitulo   = (string) ($args['subtitulo'] ?? '');
$descripcion = (string) ($args['descripcion'] ?? '');
$imagen      = $args['imagen'] ?? '';
$boton       = (string) ($args['boton'] ?? '');
$nota        = trim((string) ($args['nota'] ?? ''));
$regalo      = !empty($args['regalo']);
$whatsapp    = !empty($args['whatsapp']) ? angelitozzz_solucion_whatsapp((string) ($args['whatsapp_texto'] ?? '')) : null;
$datos       = array_values(array_filter(
    (array) ($args['datos'] ?? []),
    static fn($dato): bool => is_array($dato) && trim((string) ($dato['texto'] ?? '')) !== ''
));

// Precio del producto de WooCommerce del curso (rebajado = precio de lanzamiento; inc/woocommerce/precios.php).
$precio       = (array) ($contexto['precio'] ?? []);
$precio_texto = '';
if (($precio['actual'] ?? '') !== '') {
    $precio_texto = !empty($precio['desde'])
        /* translators: %s: precio más bajo, p. ej. "$37". */
        ? sprintf(__('Desde %s USD', 'angelitozzz'), $precio['actual'])
        /* translators: %s: precio, p. ej. "$67". */
        : sprintf(__('%s USD', 'angelitozzz'), $precio['actual']);
}
$antes = empty($precio['desde']) ? (string) ($precio['antes'] ?? '') : '';
if ($antes !== '') {
    /* translators: %s: precio normal, p. ej. "$97". */
    $lanzamiento = sprintf(__('Precio de lanzamiento (antes %s)', 'angelitozzz'), $antes);
    $nota        = trim($lanzamiento . ($nota !== '' ? ' · ' . $nota : ''));
}
$datos_compra = (array) ($contexto['datos_compra'] ?? []);
$datos_regalo = (array) ($contexto['datos_regalo'] ?? []);
$con_carrito  = function_exists('wc_get_cart_url');

$no_encontrada = get_theme_file_uri('assets/img/ayudarte-imagen-not-found.webp');
$id_titulo     = wp_unique_id('solucion-hero-titulo-');
$id_cupon      = wp_unique_id('solucion-cupon-');

// Íconos de las cajas de datos (decorativos; el texto de la caja ya dice el dato).
$iconos = [
    'reloj'    => '<svg class="size-[23px] shrink-0" viewBox="0 0 23 23" fill="currentColor" aria-hidden="true" focusable="false"><path d="M11.5 21.56A10.06 10.06 0 1 1 21.56 11.5 10.07 10.07 0 0 1 11.5 21.56Zm0-18.68a8.63 8.63 0 1 0 8.63 8.62 8.63 8.63 0 0 0-8.63-8.62Z" /><path d="m14.8 15.81-4.02-4.01V5.03h1.44v6.17l3.6 3.6-1.02 1.01Z" /></svg>',
    'whatsapp' => '<svg class="size-[22px] shrink-0" viewBox="0 0 22 22" fill="currentColor" aria-hidden="true" focusable="false"><path d="M.92 19.25v-1.83h20.16v1.83H.92Zm2.75-2.75c-.5 0-.94-.18-1.3-.54a1.77 1.77 0 0 1-.53-1.29V4.58c0-.5.18-.93.54-1.29.36-.36.79-.54 1.29-.54h14.67c.5 0 .93.18 1.3.54.35.36.53.79.53 1.29v10.09c0 .5-.18.93-.54 1.29-.36.36-.79.54-1.3.54H3.68Zm0-1.83h14.67V4.58H3.67v10.09Z" /></svg>',
    'barbara'  => '<img class="h-8 w-7 shrink-0 object-contain" src="' . esc_url(get_theme_file_uri('assets/img/solucion/hero-icono-barbara.webp')) . '" alt="" aria-hidden="true" width="28" height="32" loading="lazy">',
];
$regalo_svg = '<svg class="size-[21px] shrink-0 text-terracota" viewBox="0 0 21 21" fill="none" stroke="currentColor" stroke-width="1.575" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.5 7h14c.48 0 .88.39.88.88v1.75c0 .48-.4.87-.88.87h-14a.88.88 0 0 1-.88-.87V7.88c0-.49.4-.88.88-.88ZM10.5 7v11.38" /><path d="M16.63 10.5v6.13a1.75 1.75 0 0 1-1.75 1.75h-8.76a1.75 1.75 0 0 1-1.75-1.75V10.5M6.56 7a2.19 2.19 0 0 1 0-4.38c.85-.01 1.67.4 2.38 1.18.7.78 1.24 1.9 1.56 3.2.32-1.3.86-2.42 1.56-3.2.7-.78 1.53-1.19 2.38-1.18a2.19 2.19 0 0 1 0 4.38" /></svg>';

if ($titular === '') {
    return;
}
?>
<section class="relative overflow-hidden bg-neutro" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="pointer-events-none absolute inset-0 max-lg:hidden" aria-hidden="true">
        <?= angelitozzz_image($imagen, [
            'class'    => 'absolute left-0 top-[10px] h-[835px] w-[730px] object-cover opacity-45',
            'alt'      => '',
            'fallback' => $no_encontrada,
        ]) ?>
        <div class="absolute inset-0 bg-[linear-gradient(270deg,var(--color-neutro)_calc(100%_-_592px),rgb(215_208_181/0.76)_calc(100%_-_161px))]"></div>
    </div>

    <div class="relative mx-auto flex max-w-[1150px] items-center justify-center gap-[107px] px-4 py-22 max-lg:flex-col max-lg:items-stretch max-lg:gap-12 max-md:py-14">
        <div class="flex w-[651px] flex-col items-start gap-[23px] max-lg:w-full">
            <?php get_template_part('template-parts/components/etiqueta', null, [
                'texto'  => $etiqueta,
                // Con ícono, la etiqueta es salvia (estilo tarjeta), como "Nuevo" de Angelita Pro.
                'estilo' => $icono === 'ninguno' ? 'hero' : 'tarjeta',
                'icono'  => $icono,
            ]); ?>

            <div class="flex w-full flex-col gap-[28px]">
                <div class="flex flex-col gap-[10px]">
                    <h1 id="<?= esc_attr($id_titulo) ?>" class="text-h1 text-marca max-md:text-h3 [&_.italic]:font-normal"><?= angelitozzz_highlight($titular) ?></h1>
                    <?php if ($subtitulo !== '') : ?>
                        <p class="w-fit min-w-[345px] border-b border-contorno pb-2 text-cuerpo-xl text-tinta max-md:min-w-0 max-md:text-cuerpo-l"><?= esc_html($subtitulo) ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($descripcion !== '') : ?>
                    <p class="max-w-[492px] text-cuerpo-s text-tinta"><?= angelitozzz_highlight($descripcion) ?></p>
                <?php endif; ?>
            </div>

            <?php if ($datos || $regalo || $whatsapp) : ?>
                <div class="<?= esc_attr('flex flex-col items-start gap-[42px] ' . ($datos ? 'mt-[74px] max-lg:mt-4' : 'mt-[23px] max-lg:mt-0')) ?>">
                    <?php if ($datos) : ?>
                        <ul class="flex flex-wrap gap-x-9 gap-y-4">
                            <?php foreach ($datos as $dato) : ?>
                                <li class="flex min-w-[146px] items-center justify-center gap-1 rounded-[4px] border border-tinta px-4 py-[11px] text-cuerpo-s text-tinta">
                                    <?= $iconos[$dato['icono'] ?? ''] ?? '' // SVG fijo del tema. ?>
                                    <span><?= esc_html($dato['texto']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ($regalo || $whatsapp) : ?>
                        <div class="flex flex-col items-start gap-[23px]">
                            <?php if ($regalo) : ?>
                                <a class="flex items-center gap-2 text-cuerpo text-tinta underline underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url(home_url('/canjear-regalo/')) ?>">
                                    <?= $regalo_svg // SVG fijo del tema. ?>
                                    <span><?= esc_html__('¿Te regalaron este curso? Canjea tu código', 'angelitozzz') ?></span>
                                </a>
                            <?php endif; ?>
                            <?= $whatsapp ? angelitozzz_link($whatsapp, ['class' => 'text-cuerpo text-tinta underline underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca']) : '' ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex w-[375px] shrink-0 flex-col gap-[10px] overflow-hidden rounded-[8px] bg-fondo p-5 max-lg:mx-auto max-lg:w-full max-lg:max-w-[375px]">
            <div class="aspect-[335/184] w-full overflow-hidden rounded-[8px]">
                <?= angelitozzz_image($imagen, [
                    'class'        => 'size-full object-cover',
                    'alt_fallback' => (string) ($contexto['titulo'] ?? ''),
                    'loading'      => 'eager',
                    'fetchpriority' => 'high',
                    'fallback'     => $no_encontrada,
                ]) ?>
            </div>

            <div class="flex flex-col items-center gap-5">
                <?php if ($precio_texto !== '') : ?>
                    <p class="text-center text-h3 text-marca"><?= esc_html($precio_texto) ?></p>
                <?php endif; ?>

                <?php get_template_part('template-parts/components/boton', null, [
                    'texto'    => $boton,
                    'variante' => 'frambuesa',
                    'modal'    => (string) ($contexto['modal_compra'] ?? 'carrito'),
                    'datos'    => $datos_compra, // Venta directa: agrega al carrito y abre el drawer (carrito.js).
                ]); ?>

                <?php if ($regalo && $datos_regalo) {
                    get_template_part('template-parts/components/boton', null, [
                        'texto'    => __('Regalar este curso', 'angelitozzz'),
                        'variante' => 'secundario',
                        'flecha'   => false,
                        // Venta directa: el drawer; con etapas, el selector de edad en modo regalo.
                        'modal'    => (string) ($contexto['modal_compra'] ?? 'carrito'),
                        'datos'    => $datos_regalo, // El curso marcado como regalo (cursos-datos.md → regalo).
                    ]);
                } ?>

                <?php if ($nota !== '') : ?>
                    <p class="w-full px-2 text-cuerpo-s text-tinta"><?= esc_html($nota) ?></p>
                <?php endif; ?>

                <?php
                // Cupón: sin JS, el formulario va al carrito y Woo lo aplica (WC_Form_Handler, nonce del carrito);
                // con JS, carrito.js lo aplica con ?wc-ajax=apply_coupon y muestra el aviso aquí, sin salir.
                if ($con_carrito) : ?>
                <form class="relative w-full px-2" method="post" action="<?= esc_url(wc_get_cart_url()) ?>" data-cupon-hero data-seguridad="<?= esc_attr(wp_create_nonce('apply-coupon')) ?>">
                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce', false); ?>
                    <input type="hidden" name="apply_coupon" value="1">
                    <label class="sr-only" for="<?= esc_attr($id_cupon) ?>"><?= esc_html__('Código de cupón de descuento', 'angelitozzz') ?></label>
                    <input class="w-full border-b border-contorno bg-transparent py-1 pr-9 pl-[3px] text-cuerpo-s text-tinta placeholder:text-tinta focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" id="<?= esc_attr($id_cupon) ?>" type="text" name="coupon_code" placeholder="<?= esc_attr__('Código de cupón de descuento', 'angelitozzz') ?>" autocomplete="off">
                    <button class="absolute right-[13px] top-0 flex size-6 cursor-pointer items-center justify-center rounded-full bg-marca text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" type="submit">
                        <span class="sr-only"><?= esc_html__('Aplicar cupón', 'angelitozzz') ?></span>
                        <svg class="size-[9px]" viewBox="0 0 8.27 8.16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4.04 7.66 7.27 4.08 4.04.5M6.73 4.08H.5" /></svg>
                    </button>
                    <p class="mt-2 text-cuerpo-s text-tinta empty:hidden" role="status" data-cupon-mensaje></p>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
