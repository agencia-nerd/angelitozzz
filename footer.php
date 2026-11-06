<?php

/**
 * Pie del sitio (specs/opciones.md: footer, redes) y el modal del coach que abre el cintillo.
 * Textos y enlaces fijos hasta el paso ACF (columna "valor actual" del spec).
 */

defined('ABSPATH') || exit;

$newsletter_titulo = 'Suscríbete a nuestro blog';
$newsletter_texto  = 'Te enviaremos consejos gratuitos para que tu Angelito aprenda a dormir';
$copyright         = 'Angelitozzz ©2026 Todos los derechos reservados';
$redes_titulo      = 'Síguenos';
// Las URLs de las redes aún no están: se muestran con '#' (sin enlace) hasta el paso ACF.
$redes = [
    ['red' => 'instagram', 'enlace' => '#'],
    ['red' => 'tiktok', 'enlace' => '#'],
    ['red' => 'facebook', 'enlace' => '#'],
];
$nerd = ['url' => 'https://nerd.pe', 'title' => 'NERD', 'target' => '_blank'];

// Enlaces del diseño, solo mientras las ubicaciones no tengan un menú asignado.
$menu_navegacion = [
    ['texto' => 'Soluciones de Sueño', 'url' => '/soluciones-de-sueno/'],
    ['texto' => 'Mundo Angelitozzz', 'url' => '/mundo-angelitozzz/'],
    ['texto' => 'Conoce a Bárbara', 'url' => '/conoce-a-barbara/'],
    ['texto' => 'Testimonios', 'url' => '/testimonios/'],
    ['texto' => 'Blog', 'url' => '/blog/'],
    ['texto' => 'Contacto', 'url' => '/contacto/'],
];
$menu_legal = [
    ['texto' => 'Políticas de privacidad', 'url' => '/politicas-de-privacidad/'],
    ['texto' => 'Términos y condiciones', 'url' => '/terminos-y-condiciones/'],
];
$menu_ayuda = [
    ['texto' => 'Preguntas frecuentes', 'url' => '/preguntas-frecuentes/'],
    ['texto' => 'Soporte', 'url' => '#'],
];

// Ícono de cada red (SVG del tema, no se sube). El círculo lo dibuja el borde del enlace.
$iconos_red = [
    'instagram' => ['nombre' => 'Instagram', 'svg' => '<svg class="size-[27px]" viewBox="0 0 27.41 27.41" fill="none" stroke="currentColor" stroke-width="2.03" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="13.7" cy="13.7" r="5.64" /><rect x="1.02" y="1.02" width="25.38" height="25.38" rx="7.05" /><path d="m21.46 5.96.01-.01" /></svg>'],
    'tiktok'    => ['nombre' => 'TikTok', 'svg' => '<svg class="size-[27px] text-marca" viewBox="16 13.99 23.52 27" fill="currentColor" aria-hidden="true" focusable="false"><path d="M34.66 18.22a6.4 6.4 0 0 1-1.59-4.23h-4.64v18.6a3.89 3.89 0 0 1-3.88 3.75c-2.13 0-3.9-1.74-3.9-3.9 0-2.58 2.49-4.51 5.06-3.72v-4.74C20.53 23.29 16 27.31 16 32.44c0 5 4.14 8.55 8.54 8.55 4.71 0 8.53-3.83 8.53-8.55v-9.43a11 11 0 0 0 6.45 2.07v-4.63s-2.82.13-4.86-2.23" /></svg>'],
    'facebook'  => ['nombre' => 'Facebook', 'svg' => '<svg class="h-[26px] w-[14px] text-marca" viewBox="21.1 14.77 13.62 25.95" fill="currentColor" aria-hidden="true" focusable="false"><path d="M30.18 29.69h3.24l1.3-5.19h-4.54v-2.59c0-1.34 0-2.6 2.6-2.6h1.94v-4.35c-.42-.06-2.02-.18-3.7-.18-3.53 0-6.03 2.15-6.03 6.1v3.62H21.1v5.19h3.89v11.03h5.19z" /></svg>'],
    'youtube'   => ['nombre' => 'YouTube', 'svg' => '<svg class="h-5 w-7 text-marca" viewBox="0 0 28 20" fill="currentColor" aria-hidden="true" focusable="false"><path d="M27.4 3.1A3.5 3.5 0 0 0 25 .6C22.8 0 14 0 14 0S5.2 0 3 .6A3.5 3.5 0 0 0 .6 3.1C0 5.3 0 10 0 10s0 4.7.6 6.9A3.5 3.5 0 0 0 3 19.4c2.2.6 11 .6 11 .6s8.8 0 11-.6a3.5 3.5 0 0 0 2.4-2.5c.6-2.2.6-6.9.6-6.9s0-4.7-.6-6.9M11.2 14.3V5.7l7.3 4.3z" /></svg>'],
];
$clase_red = 'flex size-[55px] items-center justify-center rounded-full border-2 border-contorno text-contorno focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
$aviso     = esc_html__('se abre en una pestaña nueva', 'angelitozzz');
?>
    <footer class="bg-etiqueta">
        <div class="mx-auto max-w-sitio border-t border-contorno/20"></div>

        <div class="mx-auto flex max-w-[1132px] flex-col gap-[52px] px-4 pt-[52px] max-md:gap-10 max-md:pt-10">
            <div class="flex items-center justify-between gap-8 max-lg:flex-col max-lg:items-stretch">
                <div class="flex max-w-[520px] flex-col gap-[9px]">
                    <h2 class="text-h4 text-marca"><?= esc_html($newsletter_titulo) ?></h2>
                    <?php if ($newsletter_texto !== '') : ?>
                        <p class="text-body"><?= angelitozzz_highlight($newsletter_texto) ?></p>
                    <?php endif; ?>
                </div>
                <?php get_template_part('template-parts/forms/newsletter'); ?>
            </div>

            <div class="flex flex-col gap-[66px] max-md:gap-10">
                <nav class="grid grid-cols-2 gap-x-6 gap-y-6 text-body-s lg:flex lg:gap-[192px]" aria-label="<?= esc_attr__('Pie de página', 'angelitozzz') ?>">
                    <?php
                    angelitozzz_menu('pie-navegacion', $menu_navegacion, [
                        'clase' => 'col-span-2 grid grid-flow-col grid-rows-3 gap-x-6 gap-y-6 lg:gap-x-[192px] [&_a:hover]:text-marca [&_a]:whitespace-nowrap',
                    ]);
                    angelitozzz_menu('pie-legal', $menu_legal, [
                        'clase' => 'flex flex-col gap-6 [&_a:hover]:text-marca',
                    ]);
                    angelitozzz_menu('pie-ayuda', $menu_ayuda, [
                        'clase' => 'flex flex-col gap-6 [&_a:hover]:text-marca',
                    ]);
                    ?>
                </nav>

                <div class="flex items-center gap-[62px] max-md:flex-col max-md:gap-6">
                    <?php if ($redes_titulo !== '') : ?>
                        <p class="text-display text-marca max-md:text-h2"><?= esc_html($redes_titulo) ?></p>
                    <?php endif; ?>
                    <ul class="flex items-center gap-10 max-md:gap-6">
                        <?php foreach ($redes as $red) :
                            $icono = $iconos_red[$red['red']] ?? null;
                            if (!$icono) {
                                continue;
                            }
                            $attrs = angelitozzz_link_attrs(['url' => $red['enlace'], 'target' => '_blank'], ['class' => $clase_red]);
                        ?>
                            <li>
                                <?php if ($attrs !== '') : ?>
                                    <a<?= $attrs ?>>
                                        <span class="sr-only"><?= esc_html($icono['nombre']) ?> (<?= $aviso ?>)</span>
                                        <?= $icono['svg'] ?>
                                    </a>
                                <?php else : ?>
                                    <a class="<?= esc_attr($clase_red) ?>" aria-disabled="true">
                                        <span class="sr-only"><?= esc_html($icono['nombre']) ?></span>
                                        <?= $icono['svg'] ?>
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-[52px] border-t border-contorno/20 max-md:mt-10">
            <div class="mx-auto flex max-w-[1140px] items-center justify-between gap-4 px-4 py-6 max-md:flex-col">
                <p class="text-body-s text-marca opacity-50"><?= esc_html($copyright) ?></p>
                <p class="flex items-center gap-[11px] text-body-s">
                    <span>Made with</span>
                    <svg class="h-2.5 w-[11px] text-marca" viewBox="0 0 11 10" fill="currentColor" aria-hidden="true" focusable="false"><path d="M5.5 8.75a.75.75 0 0 1-.39-.11C3.43 7.6 2.69 6.88 2.29 6.44c-.86-.95-1.27-1.93-1.26-2.99C1.05 2.24 2.12 1.25 3.42 1.25c.95 0 1.6.48 1.98.89a.14.14 0 0 0 .2 0c.38-.41 1.04-.89 1.98-.89 1.3 0 2.37.99 2.39 2.2.01 1.06-.4 2.04-1.26 2.99-.4.44-1.13 1.16-2.82 2.2a.75.75 0 0 1-.39.11" /></svg>
                    <span class="sr-only"><?= esc_html__('con amor por', 'angelitozzz') ?></span>
                    <a<?= angelitozzz_link_attrs($nerd, ['class' => 'block focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca']) ?>>
                        <img class="h-[11px] w-[50px]" src="<?= esc_url(get_theme_file_uri('assets/img/logo-nerd.svg')) ?>" alt="NERD" width="50" height="11" loading="lazy">
                        <span class="sr-only">(<?= $aviso ?>)</span>
                    </a>
                </p>
            </div>
        </div>
    </footer>

    <?php get_template_part('template-parts/modals/modal-coach'); ?>
    <?php wp_footer(); ?>
</body>

</html>
