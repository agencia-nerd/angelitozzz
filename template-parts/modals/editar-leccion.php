<?php

/**
 * Modal "Editar lección" / "Nueva lección" del constructor (specs/admin-constructor.md → modal-leccion,
 * Figma 3773:5409). Título, video de "Archivos privados" (subir uno por partes o elegir uno ya subido;
 * con su portada, nombre y duración), descripción con el editor (src/js/modules/editor-texto.js, Tiptap),
 * descargables de cualquier tipo de material (chips con el ícono de su tipo) e interruptor de vista
 * previa gratuita. Pie fijo con "Cancelar" y "Guardar lección" (desactivado mientras sube algo).
 *
 * La subida es la del plugin (window.AngelitozzzSubida, inc/constructor-subida.php) y lo que se sube va
 * enseguida a "Archivos privados"; al guardar, la lección guarda el ID del video y vincula los
 * descargables. "Elegir de mis archivos" es otra vista del mismo modal ([data-leccion-archivos], con
 * template-parts/pages/administrador/lista-archivos.php).
 *
 * Lo maneja src/js/modules/constructor-contenido.js: cambia el título, el antetítulo ("Módulo N ·
 * Lección N", que es la descripción del modal) y los valores al abrir, y guarda por la API.
 *
 * Sin ventanas nativas del navegador ni modales encima de este (regla de wp-modal): el enlace del editor
 * es una fila bajo la barra de formato ([data-editor-enlace]) y "¿Descartar los cambios?" es una vista
 * que reemplaza el contenido del mismo modal ([data-leccion-descartar], con el estilo del modal eliminar).
 */

defined('ABSPATH') || exit;

$campo    = 'w-full rounded-[12px] border border-neutro bg-fondo px-6 py-4 text-cuerpo text-tinta placeholder:text-tinta-suave focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca aria-invalid:border-acento max-sm:px-4';
$etiqueta = 'text-rotulo text-marca';
$ayuda    = 'text-caption text-tinta-suave';
$error    = 'text-caption text-acento empty:hidden';
$chico    = 'inline-flex cursor-pointer items-center justify-center whitespace-nowrap rounded-full border-[1.5px] border-marca px-4 py-2 text-cuerpo-s text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
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
    data-video-duracion="<?= esc_attr__('%1$s · Duración: %2$s · se detecta sola', 'angelitozzz') ?>"
    data-subiendo="<?= esc_attr__('Subiendo %1$s… %2$d %', 'angelitozzz') ?>"
    data-en-espera="<?= esc_attr__('En espera: %s', 'angelitozzz') ?>"
    data-error-video="<?= esc_attr__('Ese archivo no es un video MP4 o WebM.', 'angelitozzz') ?>"
    data-error-corte="<?= esc_attr__('No pudimos terminar de subir el video. Revisa tu conexión e inténtalo de nuevo.', 'angelitozzz') ?>"
    data-error-material="<?= esc_attr__('Ese tipo de archivo no está permitido. Se aceptan PDF, Word, Excel, PowerPoint, JPG, PNG, WebP y MP3.', 'angelitozzz') ?>"
    data-sin-subida="<?= esc_attr__('No pudimos preparar la subida. Recarga la página e inténtalo de nuevo.', 'angelitozzz') ?>"
    data-ya-esta="<?= esc_attr__('Ese archivo ya está en esta lección.', 'angelitozzz') ?>"
    data-quitar="<?= esc_attr__('Quitar %s', 'angelitozzz') ?>"
    data-cancelar-subida="<?= esc_attr__('Cancelar la subida de %s', 'angelitozzz') ?>"
    data-iconos="<?= esc_attr((string) wp_json_encode(angelitozzz_iconos_material_urls())) ?>"
    data-guardando="<?= esc_attr__('Guardando…', 'angelitozzz') ?>">

    <div class="flex min-h-0 flex-1 flex-col gap-7 overflow-y-auto overscroll-contain px-8 py-6 max-sm:gap-6 max-sm:px-4" data-leccion-contenido>
        <div class="rounded-[12px] border border-acento p-4 text-cuerpo-s text-acento empty:hidden" role="alert" data-leccion-error></div>

        <div class="flex flex-col gap-2">
            <label class="<?= esc_attr($etiqueta) ?>" for="leccion-titulo"><?= esc_html__('Título de la lección', 'angelitozzz') ?></label>
            <p class="<?= esc_attr($ayuda) ?>" id="leccion-titulo-ayuda"><?= esc_html__('Se muestra arriba del video.', 'angelitozzz') ?></p>
            <input class="<?= esc_attr($campo) ?>" id="leccion-titulo" name="titulo" type="text" required maxlength="200" autocomplete="off"
                aria-describedby="leccion-titulo-ayuda" placeholder="<?= esc_attr__('Ej. Conoce a Bárbara', 'angelitozzz') ?>">
            <p class="<?= esc_attr($error) ?>" id="leccion-titulo-error" data-error-de="titulo"></p>
        </div>

        <?php // Video: sin video (los dos botones), subiendo (barra y "Cancelar") o con video (portada, nombre y duración). ?>
        <div class="flex flex-col gap-2" role="group" aria-labelledby="leccion-video-etiqueta" aria-describedby="leccion-video-ayuda" data-video>
            <p class="<?= esc_attr($etiqueta) ?>" id="leccion-video-etiqueta"><?= esc_html__('Video de la lección', 'angelitozzz') ?></p>
            <p class="<?= esc_attr($ayuda) ?>" id="leccion-video-ayuda"><?= esc_html__('Sube el video en MP4 desde tu computadora o elige uno que ya subiste. Solo lo ven las alumnas con acceso al curso.', 'angelitozzz') ?></p>
            <input type="hidden" name="video" value="">
            <div class="mt-2 flex items-center gap-5 max-sm:flex-col max-sm:items-stretch">
                <div class="relative flex h-[100px] w-[180px] shrink-0 items-center justify-center overflow-hidden rounded-[12px] bg-neutro text-marca max-sm:w-full max-sm:aspect-video max-sm:h-auto">
                    <img class="absolute inset-0 size-full object-cover" alt="" width="180" height="100" hidden data-video-miniatura>
                    <?= angelitozzz_svg('administrador/video', 'size-8') // SVG fijo del tema. ?>
                </div>
                <div class="flex min-w-0 flex-1 flex-col gap-3">
                    <div class="flex flex-col gap-1" hidden data-video-listo>
                        <p class="text-rotulo text-marca"><?= esc_html__('Video listo', 'angelitozzz') ?></p>
                        <p class="break-words text-caption text-tinta-suave" data-video-detalle></p>
                    </div>
                    <div class="flex flex-col gap-2" hidden data-video-subiendo>
                        <p class="break-words text-cuerpo-s text-marca" id="leccion-video-progreso-texto" data-video-progreso-texto></p>
                        <div class="flex items-center gap-3">
                            <progress class="barra-progreso min-w-0 flex-1" max="100" value="0" aria-labelledby="leccion-video-progreso-texto" data-video-progreso>0 %</progress>
                            <button class="<?= esc_attr($chico) ?>" type="button" data-video-cancelar><?= esc_html__('Cancelar', 'angelitozzz') ?></button>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3 max-sm:flex-col" data-video-botones>
                        <button class="<?= esc_attr($chico) ?>" type="button" data-video-elegir><?= esc_html__('Elegir de mis archivos', 'angelitozzz') ?></button>
                        <?php // Un <input type="file"> real dentro de su <label>: se usa con teclado. ?>
                        <label class="<?= esc_attr($chico . ' border-terracota bg-terracota text-blanco has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-marca') ?>">
                            <input class="sr-only" type="file" accept="video/mp4,video/webm,.mp4,.webm" data-video-subir>
                            <span><?= esc_html__('Subir video', 'angelitozzz') ?></span>
                        </label>
                    </div>
                    <div class="flex flex-wrap gap-3 max-sm:flex-col" hidden data-video-acciones>
                        <button class="<?= esc_attr($chico) ?>" type="button" data-video-cambiar><?= esc_html__('Cambiar video', 'angelitozzz') ?></button>
                        <button class="<?= esc_attr($chico) ?>" type="button" data-video-quitar><?= esc_html__('Quitar video', 'angelitozzz') ?></button>
                    </div>
                </div>
            </div>
            <p class="rounded-[12px] border border-acento p-3 text-caption text-acento empty:hidden" role="alert" data-video-aviso></p>
            <p class="rounded-[12px] border border-estrella bg-fondo p-3 text-caption text-tinta empty:hidden" role="status" data-video-recomendacion></p>
            <p class="<?= esc_attr($error) ?>" id="leccion-video-error" role="alert" data-error-de="video"></p>
        </div>

        <div class="flex flex-col gap-2">
            <p class="<?= esc_attr($etiqueta) ?>" id="leccion-descripcion-etiqueta"><?= esc_html__('Descripción de la lección', 'angelitozzz') ?></p>
            <p class="<?= esc_attr($ayuda) ?>" id="leccion-descripcion-ayuda"><?= esc_html__('Es el texto que la mamá ve debajo del video. Se edita como un documento.', 'angelitozzz') ?></p>
            <div class="overflow-hidden rounded-[12px] border border-neutro bg-blanco focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-marca"
                data-editor
                data-etiqueta-id="leccion-descripcion-etiqueta"
                data-ayuda-id="leccion-descripcion-ayuda"
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
                    <button class="<?= esc_attr($barra) ?>" type="button" aria-pressed="false" aria-expanded="false" aria-controls="leccion-enlace-panel" data-editor-accion="enlace" aria-label="<?= esc_attr__('Enlace', 'angelitozzz') ?>">
                        <?= angelitozzz_svg('administrador/editor-enlace', 'size-4') // SVG fijo del tema. ?>
                    </button>
                </div>
                <?php // Enlace del texto seleccionado (en vez de window.prompt): Enter aplica, Escape cierra solo esta fila. ?>
                <div class="flex flex-col gap-2 border-b border-neutro bg-blanco px-4 py-3" id="leccion-enlace-panel" hidden data-editor-enlace>
                    <label class="<?= esc_attr($etiqueta) ?>" for="leccion-enlace"><?= esc_html__('Enlace', 'angelitozzz') ?></label>
                    <div class="flex flex-wrap items-center gap-2">
                        <input class="<?= esc_attr($campo . ' min-w-[200px] flex-1 py-3') ?>" id="leccion-enlace" type="url" inputmode="url" autocomplete="off"
                            placeholder="https://…" aria-describedby="leccion-enlace-ayuda leccion-enlace-error" data-editor-enlace-campo>
                        <button class="<?= esc_attr($chico . ' border-acento bg-acento text-blanco') ?>" type="button" data-editor-enlace-aplicar><?= esc_html__('Aplicar', 'angelitozzz') ?></button>
                        <button class="<?= esc_attr($chico) ?>" type="button" hidden data-editor-enlace-quitar><?= esc_html__('Quitar enlace', 'angelitozzz') ?></button>
                        <button class="<?= esc_attr($chico) ?>" type="button" data-editor-enlace-cancelar><?= esc_html__('Cancelar', 'angelitozzz') ?></button>
                    </div>
                    <p class="<?= esc_attr($ayuda) ?>" id="leccion-enlace-ayuda"><?= esc_html__('Selecciona el texto y pega el enlace. Tiene que empezar con https://, http:// o mailto:.', 'angelitozzz') ?></p>
                    <p class="<?= esc_attr($error) ?>" id="leccion-enlace-error" role="alert" data-editor-enlace-error></p>
                </div>
                <div id="leccion-descripcion" data-editor-area></div>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <p class="<?= esc_attr($etiqueta) ?>" id="leccion-descargables-etiqueta"><?= esc_html__('Descargables (opcional)', 'angelitozzz') ?></p>
            <p class="<?= esc_attr($ayuda) ?>" id="leccion-descargables-ayuda"><?= esc_html__('Archivos que la mamá puede descargar desde esta lección: PDF, Word, Excel, PowerPoint, imágenes o audio.', 'angelitozzz') ?></p>
            <ul class="flex flex-wrap gap-3 empty:hidden" aria-labelledby="leccion-descargables-etiqueta" data-materiales></ul>
            <div class="flex flex-wrap gap-3">
                <label class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-neutro bg-fondo px-4 py-2 text-cuerpo-s text-marca hover:border-marca has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-marca">
                    <input class="sr-only" type="file" multiple aria-describedby="leccion-descargables-ayuda" data-leccion-material
                        accept=".pdf,.docx,.xlsx,.pptx,.jpg,.jpeg,.png,.webp,.mp3,application/pdf,image/jpeg,image/png,image/webp,audio/mpeg">
                    <?= angelitozzz_svg('administrador/mas', 'size-4') // SVG fijo del tema. ?>
                    <span><?= esc_html__('Subir archivo', 'angelitozzz') ?></span>
                </label>
                <button class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-neutro bg-fondo px-4 py-2 text-cuerpo-s text-marca hover:border-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca"
                    type="button" data-materiales-elegir><?= esc_html__('Elegir de mis archivos', 'angelitozzz') ?></button>
            </div>
            <p class="rounded-[12px] border border-acento p-3 text-caption text-acento empty:hidden" role="alert" data-materiales-aviso></p>
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

    <?php // Elegir de mis archivos (video o descargables): otra vista del mismo modal, sin apilar otro. ?>
    <div class="flex min-h-0 flex-1 flex-col gap-5 overflow-y-auto overscroll-contain px-8 py-6 max-sm:px-4" hidden role="group" aria-labelledby="leccion-archivos-titulo" data-leccion-archivos>
        <button class="inline-flex cursor-pointer items-center gap-2 self-start rounded-[8px] text-cuerpo-s text-marca hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" type="button" data-leccion-volver>
            <span aria-hidden="true">←</span>
            <span><?= esc_html__('Volver a la lección', 'angelitozzz') ?></span>
        </button>
        <h3 class="text-h5 text-marca" id="leccion-archivos-titulo"><?= esc_html__('Elegir de mis archivos', 'angelitozzz') ?></h3>
        <?php get_template_part('template-parts/pages/administrador/lista-archivos', null, ['id' => 'leccion-archivos', 'clase' => 'video']); ?>
    </div>

    <?php // ¿Descartar los cambios? (en vez de window.confirm): reemplaza el contenido y el pie del mismo modal. ?>
    <div class="flex flex-col items-center gap-4 px-10 pb-10 pt-8 text-center max-sm:px-5 max-sm:pb-6" hidden role="group" aria-labelledby="leccion-descartar-titulo" aria-describedby="leccion-descartar-texto" data-leccion-descartar>
        <div class="flex size-12 items-center justify-center rounded-full border-2 border-terracota text-h4 text-terracota" aria-hidden="true">!</div>
        <h3 class="text-h3 text-marca" id="leccion-descartar-titulo"><?= esc_html__('¿Descartar los cambios?', 'angelitozzz') ?></h3>
        <p class="max-w-[440px] text-cuerpo text-tinta" id="leccion-descartar-texto"><?= esc_html__('Tienes cambios sin guardar en esta lección. Si los descartas, se pierden.', 'angelitozzz') ?></p>
        <div class="mt-2 flex justify-center gap-4 max-sm:w-full max-sm:flex-col-reverse">
            <button class="inline-flex cursor-pointer items-center justify-center gap-[10px] whitespace-nowrap rounded-full border-[1.5px] border-marca px-[28px] py-[14px] text-rotulo text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca"
                type="button" data-leccion-seguir><?= esc_html__('Seguir editando', 'angelitozzz') ?></button>
            <?php // Mismo aspecto que el componente boton frambuesa con flecha (que no imprime un <button type="button"> suelto). ?>
            <button class="inline-flex cursor-pointer items-center justify-center gap-[10px] whitespace-nowrap rounded-full bg-acento py-[14px] pl-[28px] pr-[24px] text-rotulo text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca"
                type="button" data-leccion-descartar-confirmar>
                <span><?= esc_html__('Descartar cambios', 'angelitozzz') ?></span>
                <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
            </button>
        </div>
    </div>

    <div class="flex flex-none flex-wrap items-center justify-end gap-4 border-t border-neutro px-8 py-5 max-sm:flex-col-reverse max-sm:px-4" data-leccion-pie>
        <p class="text-caption text-tinta-suave max-sm:text-center" id="leccion-espera" hidden data-leccion-espera><?= esc_html__('Espera a que termine de subir el video.', 'angelitozzz') ?></p>
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
