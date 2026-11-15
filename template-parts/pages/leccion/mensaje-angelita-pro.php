<?php

/**
 * Lección · mensaje de Angelita Pro (specs/leccion.md, Figma 4205:98): solo en la primera lección de
 * un curso de tipo suscripción, arriba del video. Textos fijos del tema (hay un solo curso así).
 */

defined('ABSPATH') || exit;

$img   = static fn(string $ruta): string => esc_url(get_theme_file_uri('assets/img/' . $ruta));
$pasos = [
    __('Mira el video', 'angelitozzz'),
    __('Descarga tu plan', 'angelitozzz'),
    __('Activa a Angelita', 'angelitozzz'),
];
?>
<section class="relative flex items-center gap-6 overflow-hidden rounded-tarjeta bg-fondo-rosa px-8 py-7 shadow-[0_8px_24px_rgba(35,69,73,0.08)] max-md:flex-col max-md:items-start max-md:px-6" aria-labelledby="leccion-angelita-pro-titulo">
    <?php // Decoración (nube dormida y destellos), recortada por la tarjeta. ?>
    <img class="pointer-events-none absolute -right-6 -bottom-10 h-[96px] w-[160px] opacity-70 max-md:hidden" src="<?= $img('acceso/decoracion-nube.svg') ?>" alt="" aria-hidden="true" width="160" height="96">
    <img class="pointer-events-none absolute -right-2 -top-5 h-[46px] w-[90px] opacity-50 max-md:hidden" src="<?= $img('acceso/decoracion-nube.svg') ?>" alt="" aria-hidden="true" width="90" height="46">
    <img class="pointer-events-none absolute right-[100px] top-9 h-[23px] w-[22px] max-md:hidden" src="<?= $img('leccion/angelita-destello.svg') ?>" alt="" aria-hidden="true" width="22" height="23">
    <img class="pointer-events-none absolute right-[284px] bottom-9 h-[15px] w-[14px] max-md:hidden" src="<?= $img('leccion/angelita-destello-chico.svg') ?>" alt="" aria-hidden="true" width="14" height="15">

    <img class="relative size-[88px] shrink-0" src="<?= $img('leccion/angelita-luna.svg') ?>" alt="" aria-hidden="true" width="88" height="88">

    <div class="relative flex flex-col gap-2">
        <p class="text-[14px] font-medium uppercase tracking-[0.08em] text-acento"><?= esc_html__('Antes de empezar', 'angelitozzz') ?></p>
        <h2 id="leccion-angelita-pro-titulo" class="text-[28px] font-medium leading-[1.2] text-marca"><?= esc_html__('Bienvenida a Angelita Pro.', 'angelitozzz') ?></h2>
        <p class="text-cuerpo text-tinta"><?= esc_html__('Tómate unos minutos para ver el video de introducción y descargar tu Plan de Sueño.', 'angelitozzz') ?></p>
        <ol class="flex flex-wrap gap-[10px] max-md:flex-col">
            <?php foreach ($pasos as $n => $paso) : ?>
                <li class="flex items-center gap-[10px] rounded-full bg-blanco py-[7px] pl-[7px] pr-[14px] text-[16px] font-medium text-marca">
                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-marca text-[15px] font-semibold text-fondo" aria-hidden="true"><?= esc_html((string) ($n + 1)) ?></span>
                    <?= esc_html($paso) ?>
                </li>
            <?php endforeach; ?>
        </ol>
        <p class="text-[15px] italic text-marca-suave"><?= esc_html__('Tus 2 semanas con Angelita empiezan cuando tú la actives, no hoy.', 'angelitozzz') ?></p>
    </div>
</section>
