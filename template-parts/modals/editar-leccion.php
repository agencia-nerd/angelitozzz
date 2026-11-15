<?php

/**
 * Modal "Editar lección" / "Nueva lección" del constructor (specs/admin-constructor.md → modal-leccion,
 * Figma 3773:5409). Título, enlace de Vimeo con su vista previa (miniatura y duración, leídas por la API
 * del plugin), descripción con el editor (src/js/modules/editor-texto.js, Tiptap), descargables en PDF
 * e interruptor de vista previa gratuita. Pie fijo con "Cancelar" y "Guardar lección".
 *
 * Lo maneja src/js/modules/constructor-contenido.js: cambia el título, el antetítulo ("Módulo N ·
 * Lección N", que es la descripción del modal) y los valores al abrir, y guarda por la API.
 */

defined('ABSPATH') || exit;

$campo    = 'w-full rounded-[12px] border border-neutro bg-fondo px-6 py-4 text-cuerpo text-tinta placeholder:text-tinta-suave focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca aria-invalid:border-acento max-sm:px-4';
$etiqueta = 'text-rotulo text-marca';
$ayuda    = 'text-caption text-tinta-suave';
$error    = 'text-caption text-acento empty:hidden';
$barra    = 'flex size-8 cursor-pointer items-center justify-center rounded-[8px] border border-neutro bg-blanco text-marca hover:border-marca aria-pressed:bg-marca aria-pressed:text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';

angelitozzz_modal_start('editar-leccion', [
    'title'             => __('Editar lección', 'angelitozzz'),
    /* translators: 1: número del módulo, 2: número de la lección. */
    'description'       => sprintf(__('Módulo %1$d · Lección %2$d', 'angelitozzz'), 1, 1),
    'placement'         => 'center',
    'width'             => '860px',
    'mobile'            => 'center',
    'backdrop_close'    => false,
    'close_label'       => __('Cerrar', 'angelitozzz'),
    'class'             => 'rounded-[20px]',
    'header_class'      => 'items-center border-b border-neutro px-8 pb-5 pt-1 max-sm:px-4',
    'title_class'       => 'text-h4 text-marca',
    'description_class' => 'order-first mx-8 mt-6 text-caption text-tinta-suave max-sm:mx-4 max-sm:mt-4',
    'close_class'       => 'text-marca',
    'body_class'        => 'flex flex-col overflow-hidden p-0',
]);
?>
<form class="flex min-h-0 flex-1 flex-col" novalidate data-leccion-form
    data-titulo-nueva="<?= esc_attr__('Nueva lección', 'angelitozzz') ?>"
    data-titulo-editar="<?= esc_attr__('Editar lección', 'angelitozzz') ?>"
    data-antetitulo="<?= esc_attr__('Módulo %1$d · Lección %2$d', 'angelitozzz') ?>"
    data-descartar="<?= esc_attr__('Tienes cambios sin guardar en esta lección. ¿Quieres descartarlos?', 'angelitozzz') ?>"
    data-video-conectado="<?= esc_attr__('Video conectado', 'angelitozzz') ?>"
    data-video-duracion="<?= esc_attr__('Duración: %s · se detecta sola', 'angelitozzz') ?>"
    data-video-leyendo="<?= esc_attr__('Leyendo el video…', 'angelitozzz') ?>"
    data-video-error="<?= esc_attr__('No pudimos leer ese video. Revisa que el enlace sea de Vimeo y que el video permita insertarse en otros sitios.', 'angelitozzz') ?>"
    data-quitar="<?= esc_attr__('Quitar %s', 'angelitozzz') ?>"
    data-pendiente="<?= esc_attr__('se sube al guardar', 'angelitozzz') ?>"
    data-solo-pdf="<?= esc_attr__('Solo se aceptan archivos PDF de hasta 20 MB.', 'angelitozzz') ?>"
    data-guardando="<?= esc_attr__('Guardando…', 'angelitozzz') ?>">

    <div class="flex min-h-0 flex-1 flex-col gap-7 overflow-y-auto overscroll-contain px-8 py-6 max-sm:gap-6 max-sm:px-4">
        <div class="rounded-[12px] border border-acento p-4 text-cuerpo-s text-acento empty:hidden" role="alert" data-leccion-error></div>

        <div class="flex flex-col gap-2">
            <label class="<?= esc_attr($etiqueta) ?>" for="leccion-titulo"><?= esc_html__('Título de la lección', 'angelitozzz') ?></label>
            <p class="<?= esc_attr($ayuda) ?>" id="leccion-titulo-ayuda"><?= esc_html__('Se muestra arriba del video.', 'angelitozzz') ?></p>
            <input class="<?= esc_attr($campo) ?>" id="leccion-titulo" name="titulo" type="text" required maxlength="200" autocomplete="off"
                aria-describedby="leccion-titulo-ayuda" placeholder="<?= esc_attr__('Ej. Conoce a Bárbara', 'angelitozzz') ?>">
            <p class="<?= esc_attr($error) ?>" id="leccion-titulo-error" data-error-de="titulo"></p>
        </div>

        <div class="flex flex-col gap-2">
            <label class="<?= esc_attr($etiqueta) ?>" for="leccion-video"><?= esc_html__('Video (enlace de Vimeo)', 'angelitozzz') ?></label>
            <p class="<?= esc_attr($ayuda) ?>" id="leccion-video-ayuda"><?= esc_html__('Bárbara sube el video a Vimeo y pega aquí el enlace.', 'angelitozzz') ?></p>
            <input class="<?= esc_attr($campo) ?>" id="leccion-video" name="video" type="url" inputmode="url" autocomplete="off"
                aria-describedby="leccion-video-ayuda" placeholder="https://vimeo.com/123456789">
            <p class="<?= esc_attr($error) ?>" id="leccion-video-error" role="alert" data-error-de="video"></p>
            <div class="mt-2 flex items-center gap-4" hidden data-video-vista>
                <img class="h-[100px] w-[180px] shrink-0 rounded-[12px] bg-neutro object-cover max-sm:h-[72px] max-sm:w-[128px]" alt="" width="180" height="100" data-video-miniatura>
                <p class="flex flex-col gap-1">
                    <span class="text-rotulo text-marca" data-video-estado></span>
                    <span class="text-caption text-tinta-suave" data-video-duracion></span>
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <p class="<?= esc_attr($etiqueta) ?>" id="leccion-descripcion-etiqueta"><?= esc_html__('Descripción de la lección', 'angelitozzz') ?></p>
            <p class="<?= esc_attr($ayuda) ?>" id="leccion-descripcion-ayuda"><?= esc_html__('Es el texto que la mamá ve debajo del video. Se edita como un documento.', 'angelitozzz') ?></p>
            <div class="overflow-hidden rounded-[12px] border border-neutro bg-blanco focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-marca"
                data-editor
                data-etiqueta-id="leccion-descripcion-etiqueta"
                data-ayuda-id="leccion-descripcion-ayuda"
                data-pedir-enlace="<?= esc_attr__('Pega el enlace (https://…). Déjalo vacío para quitarlo.', 'angelitozzz') ?>"
                data-enlace-invalido="<?= esc_attr__('Ese enlace no es válido: tiene que empezar con https://, http:// o mailto:.', 'angelitozzz') ?>">
                <div class="flex gap-2 border-b border-neutro bg-fondo px-4 py-3" role="toolbar" aria-label="<?= esc_attr__('Formato del texto', 'angelitozzz') ?>" aria-controls="leccion-descripcion">
                    <button class="<?= esc_attr($barra) ?>" type="button" aria-pressed="false" data-editor-accion="negrita" aria-label="<?= esc_attr__('Negrita', 'angelitozzz') ?>">
                        <?= angelitozzz_svg('administrador/editor-negrita', 'size-4') // SVG fijo del tema. ?>
                    </button>
                    <button class="<?= esc_attr($barra) ?>" type="button" aria-pressed="false" data-editor-accion="cursiva" aria-label="<?= esc_attr__('Cursiva', 'angelitozzz') ?>">
                        <?= angelitozzz_svg('administrador/editor-cursiva', 'size-4') // SVG fijo del tema. ?>
                    </button>
                    <button class="<?= esc_attr($barra) ?>" type="button" aria-pressed="false" data-editor-accion="lista" aria-label="<?= esc_attr__('Lista', 'angelitozzz') ?>">
                        <?= angelitozzz_svg('administrador/editor-lista', 'size-4') // SVG fijo del tema. ?>
                    </button>
                    <button class="<?= esc_attr($barra) ?>" type="button" aria-pressed="false" data-editor-accion="enlace" aria-label="<?= esc_attr__('Enlace', 'angelitozzz') ?>">
                        <?= angelitozzz_svg('administrador/editor-enlace', 'size-4') // SVG fijo del tema. ?>
                    </button>
                </div>
                <div id="leccion-descripcion" data-editor-area></div>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <p class="<?= esc_attr($etiqueta) ?>" id="leccion-descargables-etiqueta"><?= esc_html__('Descargables (opcional)', 'angelitozzz') ?></p>
            <p class="<?= esc_attr($ayuda) ?>" id="leccion-descargables-ayuda"><?= esc_html__('PDFs que la mamá puede descargar desde esta lección.', 'angelitozzz') ?></p>
            <ul class="flex flex-wrap gap-3 empty:hidden" aria-labelledby="leccion-descargables-etiqueta" data-materiales></ul>
            <label class="inline-flex cursor-pointer items-center gap-2 self-start rounded-full border border-neutro bg-fondo px-4 py-2 text-cuerpo-s text-marca hover:border-marca has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-marca">
                <input class="sr-only" type="file" accept="application/pdf,.pdf" multiple aria-describedby="leccion-descargables-ayuda" data-leccion-pdf>
                <?= angelitozzz_svg('administrador/mas', 'size-4') // SVG fijo del tema. ?>
                <span><?= esc_html__('Agregar PDF', 'angelitozzz') ?></span>
            </label>
            <p class="<?= esc_attr($error) ?>" role="alert" data-error-de="materiales"></p>
        </div>

        <?php // Interruptor (role="switch"): el input real queda accesible; la pista es decorativa. ?>
        <label class="flex cursor-pointer items-center gap-4 self-start" for="leccion-vista-previa">
            <span class="relative inline-flex shrink-0">
                <input class="peer sr-only" id="leccion-vista-previa" name="vista_previa" type="checkbox" role="switch" value="1">
                <span class="h-7 w-12 rounded-full bg-neutro transition-colors peer-checked:bg-acento peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-marca motion-reduce:transition-none" aria-hidden="true"></span>
                <span class="absolute left-1 top-1 size-5 rounded-full bg-blanco shadow transition-transform peer-checked:translate-x-5 motion-reduce:transition-none" aria-hidden="true"></span>
            </span>
            <span class="text-cuerpo text-tinta"><?= esc_html__('Lección de vista previa gratuita (se puede ver sin comprar)', 'angelitozzz') ?></span>
        </label>
    </div>

    <div class="flex flex-none justify-end gap-4 border-t border-neutro px-8 py-5 max-sm:flex-col-reverse max-sm:px-4">
        <button class="inline-flex cursor-pointer items-center justify-center gap-[10px] whitespace-nowrap rounded-full border-[1.5px] border-marca px-[28px] py-[14px] text-rotulo text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca"
            type="button" data-leccion-cancelar><?= esc_html__('Cancelar', 'angelitozzz') ?></button>
        <?php get_template_part('template-parts/components/boton', null, [
            'texto'  => __('Guardar lección', 'angelitozzz'),
            'enviar' => true,
            'clase'  => 'justify-center',
            'datos'  => ['data-leccion-guardar' => '1'],
        ]); ?>
    </div>
</form>
<?php
angelitozzz_modal_end();
