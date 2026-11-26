<?php

/**
 * Lección · pestañas Resumen y Materiales (specs/leccion.md, Figma 4176:28059, PD 3901:7716,
 * materiales 3899:7674). role="tablist" con src/js/modules/pestanas.js; sin JS se ven las dos
 * secciones una debajo de la otra. Sin materiales no hay pestaña "Materiales" (ni lista de pestañas).
 *
 * El texto lo entrega el plugin (angelitozzz_cursos_texto_leccion()): the_content y después su lista
 * permitida, con rel="noopener noreferrer" en los enlaces con target. Sin el plugin, no hay texto.
 * Un párrafo que empieza con "PD:" se muestra como cita: solo en la presentación, el texto guardado
 * no cambia.
 *
 * Materiales: archivos privados del plugin (PDF, Word, Excel, PowerPoint, imagen o audio), con el ícono
 * de su tipo (angelitozzz_icono_material(), inc/leccion.php) y su dirección firmada; un audio además se
 * escucha en la tarjeta. Sin sesión el plugin no devuelve ninguno.
 *
 * $args:
 * - leccion_id (int)
 */

defined('ABSPATH') || exit;

$leccion_id = (int) ($args['leccion_id'] ?? 0);
$materiales = function_exists('angelitozzz_cursos_materiales') ? angelitozzz_cursos_materiales($leccion_id) : [];

$texto = function_exists('angelitozzz_cursos_texto_leccion') ? angelitozzz_cursos_texto_leccion($leccion_id) : '';
// "PD:" al inicio de un párrafo → cita (fondo rosa, borde frambuesa) con "PD:" en seminegrita.
$texto = (string) preg_replace('#<p>\s*(?:<strong>\s*PD:\s*</strong>|PD:)#u', '<p class="leccion-pd"><strong>PD:</strong>', $texto);

$pestanas = [
    ['id' => 'leccion-resumen', 'texto' => __('Resumen', 'angelitozzz'), 'icono' => 'resumen'],
];
if ($materiales) {
    $pestanas[] = ['id' => 'leccion-materiales', 'texto' => __('Materiales', 'angelitozzz'), 'icono' => 'materiales'];
}

$pestana = 'flex h-[50px] cursor-pointer items-center justify-center gap-[10px] rounded-[15px] border bg-blanco px-6 text-cuerpo-l text-tinta border-[#dbdbdb] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca aria-selected:border-contorno aria-selected:bg-[#fafeff] aria-selected:text-h5 aria-selected:text-marca max-md:flex-1';
?>
<div class="flex flex-col gap-10">
    <?php if (count($pestanas) > 1) : ?>
        <div class="flex gap-[10px]" role="tablist" aria-label="<?= esc_attr__('Contenido de la lección', 'angelitozzz') ?>" data-pestanas>
            <?php foreach ($pestanas as $i => $p) : ?>
                <button class="<?= esc_attr($pestana) ?>" type="button" role="tab" id="<?= esc_attr($p['id']) ?>-pestana"
                    aria-controls="<?= esc_attr($p['id']) ?>-panel" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" tabindex="<?= $i === 0 ? '0' : '-1' ?>">
                    <?= angelitozzz_svg('leccion/' . $p['icono'], 'h-5 w-4 shrink-0 text-marca') // SVG fijo del tema. ?>
                    <span><?= esc_html($p['texto']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <section id="leccion-resumen-panel"<?= count($pestanas) > 1 ? ' role="tabpanel" aria-labelledby="leccion-resumen-pestana"' : '' ?> class="flex flex-col gap-6">
        <h2 class="text-h3 text-marca"><?= esc_html(get_the_title($leccion_id)) ?></h2>
        <div class="text-cuerpo-l text-tinta [&_a]:text-marca [&_a]:underline [&_p]:mb-[14px] [&_p:last-child]:mb-0 [&_strong]:font-semibold [&_ul]:mb-[14px] [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:mb-[14px] [&_ol]:list-decimal [&_ol]:pl-6 [&_.leccion-pd]:rounded-xl [&_.leccion-pd]:border-l-4 [&_.leccion-pd]:border-acento [&_.leccion-pd]:bg-fondo-rosa [&_.leccion-pd]:px-6 [&_.leccion-pd]:py-[18px]">
            <?= $texto // Contenido de la lección: ya filtrado por la lista permitida del plugin. ?>
        </div>
    </section>

    <?php if ($materiales) : ?>
        <section id="leccion-materiales-panel" role="tabpanel" aria-labelledby="leccion-materiales-pestana" class="flex flex-col gap-4">
            <h2 class="sr-only"><?= esc_html__('Materiales', 'angelitozzz') ?></h2>
            <ul class="flex flex-col gap-4">
                <?php foreach ($materiales as $material) :
                    $nombre   = (string) ($material['nombre'] ?? '');
                    $icono    = angelitozzz_icono_material($material);
                    $url      = (string) ($material['url'] ?? '');
                    // Un audio se escucha con url y se baja entero con url_descarga (plugin 0.10.2).
                    $descarga = (string) ($material['url_descarga'] ?? $url);
                    ?>
                    <li class="flex items-center gap-4 rounded-2xl border border-neutro/45 bg-blanco px-5 py-4 max-md:flex-wrap">
                        <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-fondo-rosa text-acento">
                            <?= angelitozzz_svg($icono === 'documento' ? 'leccion/archivo' : 'leccion/archivo-' . $icono, 'size-[22px]') // SVG fijo del tema. ?>
                        </span>
                        <span class="flex min-w-0 flex-1 flex-col">
                            <span class="text-rotulo text-marca"><?= esc_html($nombre) ?></span>
                            <span class="text-caption text-tinta-suave"><?= esc_html((string) ($material['tamano'] ?? '')) ?></span>
                            <?php if ($icono === 'audio') : ?>
                                <audio class="mt-2 h-10 w-full max-w-[360px]" controls preload="none" controlslist="nodownload" src="<?= esc_url($url) ?>"
                                    aria-label="<?= esc_attr(sprintf(
                                        /* translators: %s: nombre del audio */
                                        __('Escuchar %s', 'angelitozzz'),
                                        $nombre
                                    )) ?>"></audio>
                            <?php endif; ?>
                        </span>
                        <a class="inline-flex shrink-0 items-center gap-2 rounded-full border border-neutro bg-fondo px-[18px] py-[10px] text-cuerpo-s text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca max-md:w-full max-md:justify-center" href="<?= esc_url($descarga) ?>">
                            <?= angelitozzz_svg('leccion/descargar-chico', 'size-[18px]') // SVG fijo del tema. ?>
                            <span><?= esc_html__('Descargar', 'angelitozzz') ?><span class="sr-only"> <?= esc_html($nombre) ?></span></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
</div>
