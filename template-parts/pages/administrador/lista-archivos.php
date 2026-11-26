<?php

/**
 * "Elegir de mis archivos" (specs/admin-constructor.md → modal-archivos): la lista de "Archivos privados"
 * para elegir un video o un descargable sin salir del constructor. Se pinta dos veces: dentro del modal
 * de la lección (vista [data-leccion-archivos]) y en el modal elegir-archivo (para "+ Agregar descargable"
 * del paso 2).
 *
 * Las filas las pinta src/js/modules/elegir-archivo.js (crearLista()) con GET /archivos del plugin
 * (angelitozzz_cursos_admin_archivos(): 24 por vez, filtro por clase y buscador). Cada fila es un <button>.
 *
 * $args:
 * - id (string): prefijo de los IDs (la vista se pinta dos veces en la misma página).
 * - clase (string): `video` o `material`; el JS la puede cambiar al abrir (data-clase).
 */

defined('ABSPATH') || exit;

$prefijo = sanitize_key((string) ($args['id'] ?? 'lista-archivos'));
$clase   = in_array(($args['clase'] ?? ''), ['video', 'material'], true) ? (string) $args['clase'] : 'material';

$textos = [
    /* translators: %s: nombre del archivo. */
    'elegir'        => __('Elegir %s', 'angelitozzz'),
    'usadoEnUna'    => __('Usado en 1 lección', 'angelitozzz'),
    /* translators: %d: número de lecciones o cursos. */
    'usadoEnVarias' => __('Usado en %d lecciones', 'angelitozzz'),
    'cargando'      => __('Cargando tus archivos…', 'angelitozzz'),
    /* translators: %d: número de archivos. */
    'total'         => __('%d archivos', 'angelitozzz'),
    'totalUno'      => __('1 archivo', 'angelitozzz'),
    'sinBuscar'     => __('No hay archivos con ese nombre.', 'angelitozzz'),
    'error'         => __('No pudimos cargar tus archivos. Inténtalo de nuevo.', 'angelitozzz'),
];

$boton_chico = 'inline-flex cursor-pointer items-center justify-center gap-2 whitespace-nowrap rounded-full border-[1.5px] border-marca px-4 py-2 text-cuerpo-s text-marca hover:bg-fondo focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default disabled:opacity-50';
?>
<div class="flex min-h-0 flex-col gap-4" data-lista-archivos data-lista-id="<?= esc_attr($prefijo) ?>" data-clase="<?= esc_attr($clase) ?>" data-textos="<?= esc_attr((string) wp_json_encode($textos)) ?>">
    <div class="flex flex-col gap-2">
        <label class="text-rotulo text-marca" for="<?= esc_attr($prefijo . '-buscar') ?>"><?= esc_html__('Buscar por nombre', 'angelitozzz') ?></label>
        <input class="w-full rounded-[12px] border border-neutro bg-fondo px-6 py-3 text-cuerpo text-tinta placeholder:text-tinta-suave focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca max-sm:px-4"
            id="<?= esc_attr($prefijo . '-buscar') ?>" type="search" autocomplete="off" enterkeyhint="search"
            placeholder="<?= esc_attr__('Ej. Bienvenida', 'angelitozzz') ?>" data-lista-buscar>
    </div>

    <p class="sr-only" aria-live="polite" data-lista-estado></p>

    <div class="flex flex-col items-start gap-3 rounded-[12px] border border-acento p-4" hidden data-lista-error>
        <p class="text-cuerpo-s text-acento" role="alert" data-lista-error-texto></p>
        <button class="<?= esc_attr($boton_chico) ?>" type="button" data-lista-reintentar><?= esc_html__('Reintentar', 'angelitozzz') ?></button>
    </div>

    <div class="flex flex-col items-start gap-3 rounded-[12px] bg-fondo p-5" hidden data-lista-vacia>
        <p class="text-cuerpo text-tinta"><?= esc_html__('Todavía no subiste archivos de este tipo.', 'angelitozzz') ?></p>
        <button class="<?= esc_attr($boton_chico . ' border-terracota bg-terracota text-blanco hover:bg-terracota') ?>" type="button" data-lista-subir>
            <?= angelitozzz_svg('administrador/mas', 'size-4') // SVG fijo del tema. ?>
            <span><?= esc_html__('Subir archivo', 'angelitozzz') ?></span>
        </button>
    </div>

    <p class="text-cuerpo-s text-tinta-suave" hidden data-lista-sin-resultados><?= esc_html($textos['sinBuscar']) ?></p>

    <ul class="flex flex-col" aria-label="<?= esc_attr__('Tus archivos', 'angelitozzz') ?>" data-lista-filas></ul>

    <p class="text-cuerpo-s text-tinta-suave" hidden data-lista-cargando><?= esc_html($textos['cargando']) ?></p>

    <button class="<?= esc_attr($boton_chico . ' self-center') ?>" type="button" hidden data-lista-mas><?= esc_html__('Cargar más', 'angelitozzz') ?></button>

    <template data-lista-plantilla>
        <li class="border-b border-neutro last:border-b-0">
            <button class="flex w-full cursor-pointer items-center gap-4 rounded-[12px] px-2 py-3 text-left hover:bg-fondo focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-marca" type="button" data-lista-elegir>
                <img class="size-12 shrink-0 rounded-[8px] bg-neutro object-cover" alt="" width="48" height="48" loading="lazy" data-lista-imagen>
                <span class="flex min-w-0 flex-1 flex-col gap-1">
                    <span class="truncate text-cuerpo text-tinta" data-lista-nombre></span>
                    <span class="text-caption text-tinta-suave" data-lista-detalle></span>
                </span>
                <span class="shrink-0 text-caption text-tinta-suave empty:hidden max-sm:hidden" data-lista-uso></span>
            </button>
        </li>
    </template>
</div>
