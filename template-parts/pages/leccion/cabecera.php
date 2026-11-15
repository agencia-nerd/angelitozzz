<?php

/**
 * Lección · cabecera (specs/leccion.md, Figma 4176:28045): volver a mis cursos, el nombre del curso
 * (h1) y "Marcar como completo". El botón llama a la ruta REST angelitozzz/v1/lecciones/<id>/completar
 * del plugin (src/js/modules/leccion-completar.js): aparece solo con JS y solo con el curso comprado
 * (una vista previa no guarda avance).
 *
 * $args:
 * - leccion_id (int), curso_id (int)
 * - con_acceso (bool: inscripción vigente o quien gestiona cursos)
 * - completada (bool: estado actual de la lección para la usuaria)
 */

defined('ABSPATH') || exit;

$leccion_id = (int) ($args['leccion_id'] ?? 0);
$curso_id   = (int) ($args['curso_id'] ?? 0);
$con_acceso = (bool) ($args['con_acceso'] ?? false);
$completada = (bool) ($args['completada'] ?? false);

$mis_cursos = function_exists('angelitozzz_cursos_url_pagina')
    ? angelitozzz_cursos_url_pagina('cuenta', 'cursos')
    : home_url('/cuenta/cursos/');
?>
<div class="flex items-center justify-between gap-6 max-md:flex-col max-md:items-stretch">
    <div class="flex min-w-0 items-center gap-[34px] max-md:gap-4">
        <a class="flex size-[43px] shrink-0 items-center justify-center rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url($mis_cursos) ?>">
            <img class="size-[43px]" src="<?= esc_url(get_theme_file_uri('assets/img/leccion/volver.svg')) ?>" alt="" width="43" height="43">
            <span class="sr-only"><?= esc_html__('Volver a mis cursos', 'angelitozzz') ?></span>
        </a>
        <h1 class="min-w-0 text-h2 text-tinta max-md:text-h3"><?= esc_html(get_the_title($curso_id)) ?></h1>
    </div>

    <?php if ($con_acceso) : ?>
        <div class="flex shrink-0 flex-col items-end gap-2 max-md:items-stretch" data-leccion-completar-raiz>
            <button class="group flex h-[50px] w-[260px] cursor-pointer items-center justify-center gap-[14px] rounded-[15px] border border-[#9ec0c0] bg-blanco text-cuerpo text-tinta focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-progress disabled:opacity-60 max-md:w-full" type="button" hidden
                aria-pressed="<?= $completada ? 'true' : 'false' ?>"
                data-leccion-completar="<?= esc_attr((string) $leccion_id) ?>"
                data-url="<?= esc_url(rest_url('angelitozzz/v1/lecciones/' . $leccion_id . '/completar')) ?>"
                data-nonce="<?= esc_attr(wp_create_nonce('wp_rest')) ?>">
                <span class="flex size-6 items-center justify-center rounded-full border border-[#9ec0c0] text-[#9ec0c0] group-aria-pressed:border-[#9cb09d] group-aria-pressed:bg-[#9cb09d] group-aria-pressed:text-blanco">
                    <?= angelitozzz_svg('leccion/check', 'h-3 w-[14px]') // SVG fijo del tema. ?>
                </span>
                <span class="group-aria-pressed:hidden"><?= esc_html__('Marcar como completo', 'angelitozzz') ?></span>
                <span class="hidden group-aria-pressed:inline"><?= esc_html__('Completada', 'angelitozzz') ?></span>
            </button>
            <p class="sr-only" aria-live="polite" data-leccion-completar-aviso></p>
            <p class="max-w-[260px] text-cuerpo-s text-acento" role="alert" data-leccion-completar-error hidden></p>
        </div>
    <?php endif; ?>
</div>
