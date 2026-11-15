<?php

/**
 * Constructor · paso 2 "Contenido" (specs/admin-constructor.md → paso-contenido, Figma 4186:31108):
 * tarjeta "Módulos y lecciones" con los módulos plegables, sus lecciones y descargables, "+ Agregar
 * lección", "+ Agregar descargable", "+ Agregar módulo" y los botones del pie.
 *
 * Los módulos se pintan aquí desde angelitozzz_cursos_admin_curso() (se ven sin esperar al JS) y
 * src/js/modules/constructor-contenido.js ([data-constructor-contenido]) maneja plegar, ordenar
 * (arrastre y Subir/Bajar), agregar, editar y eliminar por la API del plugin. Las filas nuevas salen de
 * los <template data-plantilla="modulo|leccion|material">.
 *
 * $args: curso (array de angelitozzz_cursos_admin_curso()), curso_id (int).
 */

defined('ABSPATH') || exit;

$curso    = (array) ($args['curso'] ?? []);
$curso_id = (int) ($args['curso_id'] ?? 0);
$modulos  = (array) ($curso['modulos'] ?? []);

if (!$curso_id) {
    return;
}

$textos = [
    'moduloNumero'        => __('Módulo %d', 'angelitozzz'),
    /* translators: %d: número de lecciones. */
    'lecciones'           => __('%d lecciones', 'angelitozzz'),
    'unaLeccion'          => __('1 lección', 'angelitozzz'),
    'videoVimeo'          => __('Video de Vimeo', 'angelitozzz'),
    'sinVideo'            => __('Sin video', 'angelitozzz'),
    'descargable'         => __('Descargable', 'angelitozzz'),
    /* translators: %s: comienzo del texto de la lección. */
    'descripcion'         => __('Descripción: %s', 'angelitozzz'),
    /* translators: %s: título del módulo. */
    'editarModulo'        => __('Editar módulo %s', 'angelitozzz'),
    /* translators: %s: título del módulo. */
    'eliminarModulo'      => __('Eliminar módulo %s', 'angelitozzz'),
    /* translators: %s: título del módulo. */
    'subirModulo'         => __('Subir módulo %s', 'angelitozzz'),
    /* translators: %s: título del módulo. */
    'bajarModulo'         => __('Bajar módulo %s', 'angelitozzz'),
    /* translators: %s: título del módulo. */
    'plegarModulo'        => __('Módulo %s', 'angelitozzz'),
    /* translators: %s: título de la lección. */
    'editarLeccion'       => __('Editar lección %s', 'angelitozzz'),
    /* translators: %s: título de la lección. */
    'eliminarLeccion'     => __('Eliminar lección %s', 'angelitozzz'),
    /* translators: %s: nombre del archivo. */
    'eliminarDescargable' => __('Eliminar descargable %s', 'angelitozzz'),
    /* translators: %s: título de la lección o del archivo. */
    'subir'               => __('Subir %s', 'angelitozzz'),
    /* translators: %s: título de la lección o del archivo. */
    'bajar'               => __('Bajar %s', 'angelitozzz'),
    'ordenGuardado'       => __('Orden guardado', 'angelitozzz'),
    'ordenError'          => __('No pudimos guardar el orden. Lo dejamos como estaba.', 'angelitozzz'),
    'eliminado'           => __('Eliminado', 'angelitozzz'),
    'moduloGuardado'      => __('Módulo guardado', 'angelitozzz'),
    'leccionGuardada'     => __('Lección guardada', 'angelitozzz'),
    /* translators: %s: nombre del archivo. */
    'subiendo'            => __('Subiendo %s…', 'angelitozzz'),
    'descargableAgregado' => __('Descargable agregado', 'angelitozzz'),
    'error'               => __('No pudimos guardar el cambio. Revisa tu conexión y vuelve a intentarlo.', 'angelitozzz'),
];

$icono_boton = 'flex size-8 shrink-0 cursor-pointer items-center justify-center rounded-full text-tinta-suave hover:bg-fondo hover:text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default disabled:opacity-40 max-md:size-11';
// Subir y Bajar: en escritorio solo se ven al tener foco (teclado); en móvil siempre (se ordena sin arrastrar).
$mover_boton = $icono_boton . ' md:sr-only md:focus:not-sr-only';

/**
 * Fila de un ítem: lección (con video o sin video) o descargable del curso.
 */
$fila_item = static function (array $item) use ($textos, $icono_boton, $mover_boton): void {
    $tipo   = ($item['tipo'] ?? '') === 'material' ? 'material' : 'leccion';
    $id     = (int) ($item['id'] ?? 0);
    $titulo = (string) ($tipo === 'material' ? ($item['nombre'] ?? '') : ($item['titulo'] ?? ''));

    if ($tipo === 'material') {
        $linea = $textos['descargable'] . ' · ' . preg_replace('/^PDF · /u', '', (string) ($item['tamano'] ?? ''));
    } elseif ((string) ($item['video'] ?? '') !== '') {
        $linea = $textos['videoVimeo'] . ((string) ($item['duracion_texto'] ?? '') !== '' ? ' · ' . (string) $item['duracion_texto'] : '');
    } else {
        $linea = $textos['sinVideo'];
    }
    $extracto = $tipo === 'leccion' ? (string) ($item['extracto'] ?? '') : '';
    ?>
    <li class="flex items-start gap-3 border-b border-neutro py-4 last:border-b-0 data-[arrastrando]:opacity-40"
        data-item data-tipo="<?= esc_attr($tipo) ?>" data-id="<?= esc_attr((string) $id) ?>" data-titulo="<?= esc_attr($titulo) ?>"
        <?= $tipo === 'leccion' ? 'data-leccion="' . esc_attr((string) wp_json_encode($item)) . '"' : '' ?>>
        <span class="mt-1 flex size-6 shrink-0 cursor-grab items-center justify-center text-tinta-suave active:cursor-grabbing max-md:hidden" data-asa title="<?= esc_attr__('Arrastra para ordenar', 'angelitozzz') ?>" aria-hidden="true">
            <?= angelitozzz_svg('administrador/arrastrar', 'size-4') // SVG fijo del tema. ?>
        </span>
        <span class="mt-1 shrink-0 text-terracota" aria-hidden="true">
            <?= angelitozzz_svg('administrador/' . ($tipo === 'material' ? 'documento' : 'video'), 'size-6') // SVG fijo del tema. ?>
        </span>
        <div class="flex min-w-0 flex-1 flex-col gap-1">
            <p class="truncate text-cuerpo-l text-tinta" data-item-titulo><?= esc_html($titulo) ?></p>
            <p class="text-cuerpo-s text-tinta-suave" data-item-linea><?= esc_html($linea) ?></p>
            <p class="<?= esc_attr('line-clamp-2 text-caption text-tinta-suave' . ($extracto === '' ? ' hidden' : '')) ?>" data-item-desc><?= esc_html($extracto !== '' ? sprintf($textos['descripcion'], $extracto) : '') ?></p>
        </div>
        <div class="flex shrink-0 items-center gap-1 max-md:flex-wrap max-md:justify-end">
            <button class="<?= esc_attr($mover_boton) ?>" type="button" data-mover="arriba" aria-label="<?= esc_attr(sprintf($textos['subir'], $titulo)) ?>">
                <?= angelitozzz_svg('administrador/mover-arriba', 'size-5') // SVG fijo del tema. ?>
            </button>
            <button class="<?= esc_attr($mover_boton) ?>" type="button" data-mover="abajo" aria-label="<?= esc_attr(sprintf($textos['bajar'], $titulo)) ?>">
                <?= angelitozzz_svg('administrador/mover-abajo', 'size-5') // SVG fijo del tema. ?>
            </button>
            <?php if ($tipo === 'leccion') : ?>
                <button class="<?= esc_attr($icono_boton) ?>" type="button" data-item-editar aria-label="<?= esc_attr(sprintf($textos['editarLeccion'], $titulo)) ?>">
                    <?= angelitozzz_svg('administrador/editar', 'size-5') // SVG fijo del tema. ?>
                </button>
            <?php endif; ?>
            <button class="<?= esc_attr($icono_boton) ?>" type="button" data-item-eliminar
                aria-label="<?= esc_attr(sprintf($tipo === 'material' ? $textos['eliminarDescargable'] : $textos['eliminarLeccion'], $titulo)) ?>">
                <?= angelitozzz_svg('administrador/eliminar', 'size-5') // SVG fijo del tema. ?>
            </button>
        </div>
    </li>
    <?php
};

/**
 * Un módulo plegable con sus ítems. $abierto: el primero empieza abierto, como en Figma.
 */
$fila_modulo = static function (array $modulo, int $numero, bool $abierto) use ($textos, $icono_boton, $mover_boton, $fila_item): void {
    $id      = sanitize_key((string) ($modulo['id'] ?? ''));
    $titulo  = (string) ($modulo['titulo'] ?? '');
    $items   = (array) ($modulo['items'] ?? []);
    $cuantas = count(array_filter($items, static fn($i): bool => ($i['tipo'] ?? '') === 'leccion'));
    $panel   = 'modulo-' . ($id !== '' ? $id : 'nuevo') . '-panel';
    $conteo  = $cuantas === 1 ? $textos['unaLeccion'] : sprintf($textos['lecciones'], $cuantas);
    ?>
    <li class="overflow-hidden rounded-[16px] border border-neutro bg-blanco data-[arrastrando]:opacity-40" data-modulo data-id="<?= esc_attr($id) ?>" data-titulo="<?= esc_attr($titulo) ?>">
        <div class="flex items-center gap-2 bg-fondo px-5 py-4 max-md:flex-wrap max-md:px-3" data-modulo-cabecera>
            <span class="flex size-6 shrink-0 cursor-grab items-center justify-center text-tinta-suave active:cursor-grabbing max-md:hidden" data-asa-modulo title="<?= esc_attr__('Arrastra para ordenar', 'angelitozzz') ?>" aria-hidden="true">
                <?= angelitozzz_svg('administrador/arrastrar', 'size-4') // SVG fijo del tema. ?>
            </span>
            <button class="group flex min-w-0 flex-1 cursor-pointer items-center gap-4 rounded-[8px] py-1 text-left text-h5 text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca max-md:gap-2"
                type="button" aria-expanded="<?= $abierto ? 'true' : 'false' ?>" aria-controls="<?= esc_attr($panel) ?>" data-modulo-plegar>
                <span class="shrink-0 -rotate-90 transition-transform group-aria-expanded:rotate-0 motion-reduce:transition-none" aria-hidden="true">
                    <?= angelitozzz_svg('administrador/chevron', 'size-5') // SVG fijo del tema. ?>
                </span>
                <span class="min-w-0 truncate">
                    <span data-modulo-numero><?= esc_html(sprintf($textos['moduloNumero'], $numero)) ?></span>
                    · <span data-modulo-titulo><?= esc_html($titulo) ?></span><span class="group-aria-expanded:hidden" data-modulo-conteo> · <?= esc_html($conteo) ?></span>
                </span>
            </button>
            <div class="flex shrink-0 items-center gap-1">
                <button class="<?= esc_attr($mover_boton) ?>" type="button" data-mover-modulo="arriba" aria-label="<?= esc_attr(sprintf($textos['subirModulo'], $titulo)) ?>">
                    <?= angelitozzz_svg('administrador/mover-arriba', 'size-5') // SVG fijo del tema. ?>
                </button>
                <button class="<?= esc_attr($mover_boton) ?>" type="button" data-mover-modulo="abajo" aria-label="<?= esc_attr(sprintf($textos['bajarModulo'], $titulo)) ?>">
                    <?= angelitozzz_svg('administrador/mover-abajo', 'size-5') // SVG fijo del tema. ?>
                </button>
                <button class="<?= esc_attr($icono_boton) ?>" type="button" data-modulo-editar aria-label="<?= esc_attr(sprintf($textos['editarModulo'], $titulo)) ?>">
                    <?= angelitozzz_svg('administrador/editar', 'size-5') // SVG fijo del tema. ?>
                </button>
                <button class="<?= esc_attr($icono_boton) ?>" type="button" data-modulo-eliminar aria-label="<?= esc_attr(sprintf($textos['eliminarModulo'], $titulo)) ?>">
                    <?= angelitozzz_svg('administrador/eliminar', 'size-5') // SVG fijo del tema. ?>
                </button>
            </div>
        </div>

        <div class="px-5 max-md:px-3" id="<?= esc_attr($panel) ?>" data-modulo-panel<?= $abierto ? '' : ' hidden' ?>>
            <ul class="flex min-h-4 flex-col" data-items>
                <?php foreach ($items as $item) {
                    $fila_item((array) $item);
                } ?>
            </ul>
            <p class="<?= esc_attr('py-4 text-cuerpo-s text-tinta-suave' . ($items ? ' hidden' : '')) ?>" data-modulo-vacio><?= esc_html__('Este módulo aún no tiene lecciones.', 'angelitozzz') ?></p>
            <div class="flex flex-wrap items-center gap-x-8 gap-y-2 border-t border-neutro py-4">
                <button class="inline-flex cursor-pointer items-center gap-2 rounded-[8px] text-cuerpo-l text-terracota hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" type="button" data-agregar-leccion>
                    <?= angelitozzz_svg('administrador/mas', 'size-5') // SVG fijo del tema. ?>
                    <span><?= esc_html__('Agregar lección', 'angelitozzz') ?></span>
                </button>
                <?php // Un <input type="file"> real dentro de su <label>: se usa con teclado. ?>
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-[8px] text-cuerpo-l text-terracota hover:underline has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-marca">
                    <input class="sr-only" type="file" accept="application/pdf,.pdf" data-agregar-descargable>
                    <?= angelitozzz_svg('administrador/mas', 'size-5') // SVG fijo del tema. ?>
                    <span><?= esc_html__('Agregar descargable', 'angelitozzz') ?></span>
                </label>
            </div>
        </div>
    </li>
    <?php
};
?>
<div class="flex flex-col gap-[30px]"
    data-constructor-contenido
    data-estudiantes="<?= esc_attr((string) (int) ($curso['estudiantes'] ?? 0)) ?>"
    data-textos="<?= esc_attr((string) wp_json_encode($textos)) ?>">

    <section class="flex flex-col gap-6 rounded-[20px] bg-blanco p-10 max-md:p-5" aria-labelledby="constructor-contenido-titulo">
        <div class="flex flex-col gap-4">
            <h2 id="constructor-contenido-titulo" class="text-h5 text-marca"><?= esc_html__('Módulos y lecciones', 'angelitozzz') ?></h2>
            <p class="text-cuerpo text-tinta"><?= esc_html__('Arrastra para ordenar. Cada lección tiene su video de Vimeo, un texto corto y sus descargables.', 'angelitozzz') ?></p>
        </div>

        <div class="rounded-[15px] border border-acento bg-blanco p-5 text-cuerpo text-acento empty:hidden" role="alert" data-contenido-error></div>
        <p class="sr-only" aria-live="polite" data-contenido-estado></p>

        <ol class="flex flex-col gap-[30px] max-md:gap-5" data-modulos>
            <?php foreach (array_values($modulos) as $indice => $modulo) {
                $fila_modulo((array) $modulo, $indice + 1, $indice === 0);
            } ?>
        </ol>

        <button class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-[16px] border-[1.5px] border-dashed border-marca py-5 text-cuerpo-l text-marca hover:bg-fondo focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default disabled:opacity-50"
            type="button" data-agregar-modulo>
            <?= angelitozzz_svg('administrador/mas', 'size-5') // SVG fijo del tema. ?>
            <span><?= esc_html__('Agregar módulo', 'angelitozzz') ?></span>
        </button>
    </section>

    <div class="flex flex-wrap justify-end gap-4 max-md:flex-col-reverse max-md:[&>a]:justify-center">
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace'   => ['url' => angelitozzz_cursos_url_admin('editar', $curso_id, 1), 'title' => __('Volver a información', 'angelitozzz')],
            'variante' => 'secundario',
            'flecha'   => false,
        ]); ?>
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace' => ['url' => angelitozzz_cursos_url_admin('editar', $curso_id, 3), 'title' => __('Continuar: publicar', 'angelitozzz')],
        ]); ?>
    </div>

    <?php // Plantillas de las filas nuevas (el JS las rellena). ?>
    <template data-plantilla="modulo"><?php $fila_modulo(['id' => '', 'titulo' => '', 'items' => []], 1, true); ?></template>
    <template data-plantilla="leccion"><?php $fila_item(['tipo' => 'leccion', 'id' => 0]); ?></template>
    <template data-plantilla="material"><?php $fila_item(['tipo' => 'material', 'id' => 0]); ?></template>
</div>

<?php
get_template_part('template-parts/modals/eliminar');
get_template_part('template-parts/modals/editar-leccion');
get_template_part('template-parts/modals/editar-modulo');
