<?php

/**
 * Lección · temario (specs/leccion.md, Figma 4176:27857): los módulos del curso con sus lecciones y,
 * al final de cada módulo, los materiales del curso: archivos privados con la dirección firmada del
 * plugin y el ícono de su tipo (angelitozzz_icono_material(), inc/leccion.php). Se imprime dos veces:
 * en la columna (escritorio, con encabezado) y dentro del acordeón (móvil, sin encabezado); el JS de
 * "Marcar como completo" actualiza las dos copias por data-leccion-estado.
 *
 * $args:
 * - temario    (array de angelitozzz_cursos_temario(): módulos con lecciones y materiales)
 * - con_acceso (bool: sin compra, las lecciones que no son vista previa van con candado y sin enlace)
 * - encabezado (bool: ícono y "Contenido del curso" como h2; por defecto false)
 */

defined('ABSPATH') || exit;

$temario    = (array) ($args['temario'] ?? []);
$con_acceso = (bool) ($args['con_acceso'] ?? false);
$encabezado = (bool) ($args['encabezado'] ?? false);

if (!$temario) {
    return;
}

$img = static fn(string $nombre): string => esc_url(get_theme_file_uri('assets/img/leccion/' . $nombre . '.svg'));

// Duración en m:ss (de _angelitozzz_duracion, en segundos).
$duracion = static fn(int $segundos): string => $segundos > 0 ? sprintf('%d:%02d', intdiv($segundos, 60), $segundos % 60) : '';

$fila = 'flex h-[50px] items-center gap-[10px] rounded-[15px] px-[15px] text-cuerpo text-tinta';
$foco = 'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
?>
<div class="flex flex-col gap-[50px] max-lg:gap-6">
    <?php if ($encabezado) : ?>
        <div class="flex items-center gap-[22px]">
            <?= angelitozzz_svg('leccion/contenido', 'h-[41px] w-[47px] shrink-0 text-marca') // SVG fijo del tema. ?>
            <h2 class="text-h2 text-marca"><?= esc_html__('Contenido del curso', 'angelitozzz') ?></h2>
        </div>
    <?php endif; ?>

    <div class="flex flex-col gap-6">
        <?php foreach ($temario as $modulo) :
            $lecciones  = (array) ($modulo['lecciones'] ?? []);
            $materiales = $con_acceso ? (array) ($modulo['materiales'] ?? []) : [];
            if (!$lecciones && !$materiales) {
                continue;
            }
            $titulo_modulo = trim((string) ($modulo['titulo'] ?? ''));
            ?>
            <section class="flex flex-col gap-[10px]">
                <?php if ($titulo_modulo !== '') : ?>
                    <h3 class="flex h-[50px] items-center justify-center rounded-[15px] bg-marca px-4 text-center text-h5 text-blanco"><?= esc_html($titulo_modulo) ?></h3>
                <?php endif; ?>

                <ul class="flex flex-col gap-[10px]">
                    <?php foreach ($lecciones as $leccion) :
                        $id          = (int) ($leccion['id'] ?? 0);
                        $titulo      = (string) ($leccion['titulo'] ?? '');
                        $completada  = !empty($leccion['completada']);
                        $actual      = !empty($leccion['actual']);
                        $bloqueada   = !$con_acceso && empty($leccion['vista_previa']);
                        $tiempo      = $duracion((int) ($leccion['duracion'] ?? 0));
                        $estado      = $completada ? __('completada', 'angelitozzz') : __('pendiente', 'angelitozzz');
                        $clase       = $fila . ' ' . ($actual ? 'bg-[#fafeff] text-marca' : 'bg-blanco');
                        ?>
                        <li>
                            <?php if ($bloqueada) : ?>
                                <div class="<?= esc_attr($clase . ' text-tinta-suave') ?>" title="<?= esc_attr($titulo) ?>">
                                    <svg class="size-6 shrink-0 text-etiqueta" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 8.25h10.5a1.5 1.5 0 0 1 1.5 1.5V15a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5V9.75a1.5 1.5 0 0 1 1.5-1.5Z" /><path d="M5.25 8.25v-3a3.75 3.75 0 0 1 7.5 0v3" /></svg>
                                    <span class="min-w-0 flex-1 truncate"><?= esc_html($titulo) ?></span>
                                    <span class="sr-only">(<?= esc_html__('disponible al comprar el curso', 'angelitozzz') ?>)</span>
                                    <?php if ($tiempo !== '') : ?><span class="shrink-0"><?= esc_html($tiempo) ?></span><?php endif; ?>
                                </div>
                            <?php else : ?>
                                <a class="<?= esc_attr($clase . ' ' . $foco . ($actual ? '' : ' hover:bg-[#fafeff]')) ?>" href="<?= esc_url((string) ($leccion['url'] ?? '')) ?>" title="<?= esc_attr($titulo) ?>"<?= $actual ? ' aria-current="page"' : '' ?>>
                                    <img class="size-6 shrink-0" src="<?= $img('video') ?>" alt="" width="24" height="24" loading="lazy">
                                    <span class="min-w-0 flex-1 truncate"><?= esc_html($titulo) ?></span>
                                    <?php if ($actual) : ?><span class="sr-only">(<?= esc_html__('lección actual', 'angelitozzz') ?>)</span><?php endif; ?>
                                    <?php if ($tiempo !== '') : ?><span class="shrink-0 text-cuerpo-s"><?= esc_html($tiempo) ?></span><?php endif; ?>
                                    <span class="flex size-6 shrink-0 items-center justify-center rounded-full border border-[#9ec0c0] text-transparent data-[completada=true]:border-[#9cb09d] data-[completada=true]:bg-[#9cb09d] data-[completada=true]:text-blanco"
                                        data-leccion-estado="<?= esc_attr((string) $id) ?>" data-completada="<?= $completada ? 'true' : 'false' ?>">
                                        <?= angelitozzz_svg('leccion/check', 'h-3 w-[14px]') // SVG fijo del tema. ?>
                                    </span>
                                    <span class="sr-only" data-leccion-estado-texto="<?= esc_attr((string) $id) ?>">(<?= esc_html($estado) ?>)</span>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>

                    <?php foreach ($materiales as $material) :
                        $nombre = (string) ($material['nombre'] ?? '');
                        $icono  = angelitozzz_icono_material($material);
                        ?>
                        <li>
                            <a class="<?= esc_attr($fila . ' bg-blanco hover:bg-[#fafeff] ' . $foco) ?>" href="<?= esc_url((string) ($material['url_descarga'] ?? $material['url'] ?? '')) ?>">
                                <img class="size-6 shrink-0" src="<?= $img($icono === 'documento' ? 'pdf' : 'tipo-' . $icono) ?>" alt="" width="24" height="24" loading="lazy">
                                <span class="min-w-0 flex-1 truncate"><span class="sr-only"><?= esc_html__('Descargar', 'angelitozzz') ?> </span><?= esc_html($nombre) ?></span>
                                <?= angelitozzz_svg('leccion/descargar', 'size-[22px] shrink-0 text-marca') // SVG fijo del tema. ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endforeach; ?>
    </div>
</div>
