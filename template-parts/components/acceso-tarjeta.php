<?php

/**
 * Tarjeta de acceso (Figma "Tarjeta" y "Decoración acceso": iniciar-sesion 4156:23796,
 * crear-cuenta 4156:23743, recuperar-contrasena 4156:24625). Abre el fondo crema con la decoración
 * y la tarjeta blanca con el logo de Mundo, el h1 y la bajada; el formulario de cada pantalla va
 * después y template-parts/components/acceso-tarjeta-fin.php la cierra.
 *
 * $args:
 * - titulo (string, requerido: el h1)
 * - bajada (string, opcional)
 */

defined('ABSPATH') || exit;

$titulo = trim((string) ($args['titulo'] ?? ''));
$bajada = trim((string) ($args['bajada'] ?? ''));
$img    = static fn(string $archivo): string => get_theme_file_uri('assets/img/acceso/' . $archivo);
?>
<section class="overflow-hidden bg-fondo px-4 py-30 max-md:py-10">
    <div class="relative mx-auto max-w-[560px]">
        <?php // Decoración (fija del tema, no editable). En pantallas chicas se oculta para no tapar la tarjeta. ?>
        <img class="pointer-events-none absolute -left-[93px] top-0 h-[39px] w-[55px] max-lg:hidden" src="<?= esc_url($img('decoracion-destello.svg')) ?>" alt="" aria-hidden="true" width="55" height="39">
        <img class="pointer-events-none absolute -left-[90px] top-[630px] h-[21px] w-[34px] max-lg:hidden" src="<?= esc_url($img('decoracion-destello-rosa.svg')) ?>" alt="" aria-hidden="true" width="34" height="21">
        <img class="pointer-events-none absolute -left-[298px] top-[652px] h-[156px] w-[260px] max-lg:hidden" src="<?= esc_url($img('decoracion-nube.svg')) ?>" alt="" aria-hidden="true" width="260" height="156">
        <img class="pointer-events-none absolute -right-[127px] top-[100px] size-11 -scale-y-100 max-lg:hidden" src="<?= esc_url($img('decoracion-luna.svg')) ?>" alt="" aria-hidden="true" width="44" height="44">

        <div class="relative flex flex-col items-center gap-8 rounded-[28px] bg-blanco p-14 text-center shadow-[0_12px_40px_rgba(36,69,74,0.08)] max-md:p-8">
            <img class="size-[132px] object-contain" src="<?= esc_url(get_theme_file_uri('assets/img/compra/banner-logo-mundo.webp')) ?>" alt="<?= esc_attr__('Mundo Angelitozzz', 'angelitozzz') ?>" width="132" height="132">

            <div class="flex flex-col gap-8">
                <h1 class="text-h2 text-marca max-md:text-h3"><?= esc_html($titulo) ?></h1>
                <?php if ($bajada !== '') : ?>
                    <p class="text-cuerpo-l text-tinta"><?= esc_html($bajada) ?></p>
                <?php endif; ?>
            </div>
