<?php

/**
 * Modal "Elegir de mis archivos" del paso 2 (specs/admin-constructor.md → modal-archivos): lo abre
 * "+ Agregar descargable" de un módulo para vincular un material ya subido a "Archivos privados". Desde
 * "Editar lección" la misma lista es una vista de ese modal (sin apilar modales), no este.
 *
 * La lista es template-parts/pages/administrador/lista-archivos.php (solo materiales) y la maneja
 * src/js/modules/constructor-contenido.js con crearLista() de elegir-archivo.js.
 */

defined('ABSPATH') || exit;

angelitozzz_modal_start('elegir-archivo', [
    'title'          => __('Elegir de mis archivos', 'angelitozzz'),
    'placement'      => 'center',
    'width'          => '720px',
    'mobile'         => 'center',
    'backdrop_close' => false,
    'close_label'    => __('Cerrar', 'angelitozzz'),
    'class'          => 'rounded-[20px]',
    'header_class'   => 'items-center border-b border-neutro px-8 pb-5 pt-6 max-sm:px-4',
    'title_class'    => 'text-h4 text-marca',
    'close_class'    => 'text-marca',
    'body_class'     => 'flex flex-col overflow-hidden p-0',
]);
?>
<div class="flex min-h-0 flex-1 flex-col gap-4 overflow-y-auto overscroll-contain px-8 py-6 max-sm:px-4" data-elegir-archivo>
    <p class="rounded-[12px] border border-acento p-3 text-cuerpo-s text-acento empty:hidden" role="alert" data-elegir-error></p>
    <?php get_template_part('template-parts/pages/administrador/lista-archivos', null, ['id' => 'elegir-archivo-lista', 'clase' => 'material']); ?>
</div>
<?php
angelitozzz_modal_end();
