<?php

/**
 * Modal "Editar módulo" del constructor (specs/admin-constructor.md → paso-contenido: modal chico con
 * el estilo de modal-leccion). Solo el título del módulo; lo maneja
 * src/js/modules/constructor-contenido.js y guarda con POST cursos/<id>/modulos/<modulo>.
 */

defined('ABSPATH') || exit;

angelitozzz_modal_start('editar-modulo', [
    'title'          => __('Editar módulo', 'angelitozzz'),
    'placement'      => 'center',
    'width'          => '560px',
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
<form class="flex min-h-0 flex-1 flex-col" novalidate data-modulo-form>
    <div class="flex min-h-0 flex-1 flex-col gap-2 overflow-y-auto px-8 py-6 max-sm:px-4">
        <label class="text-rotulo text-marca" for="modulo-titulo"><?= esc_html__('Título del módulo', 'angelitozzz') ?></label>
        <input class="w-full rounded-[12px] border border-neutro bg-fondo px-6 py-4 text-cuerpo text-tinta placeholder:text-tinta-suave focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca aria-invalid:border-acento max-sm:px-4"
            id="modulo-titulo" name="titulo" type="text" required maxlength="200" autocomplete="off" placeholder="<?= esc_attr__('Ej. Bienvenida', 'angelitozzz') ?>">
        <p class="text-caption text-acento empty:hidden" id="modulo-titulo-error" role="alert" data-error-de="titulo"></p>
    </div>
    <div class="flex flex-none justify-end gap-4 border-t border-neutro px-8 py-5 max-sm:flex-col-reverse max-sm:px-4">
        <button class="inline-flex cursor-pointer items-center justify-center gap-[10px] whitespace-nowrap rounded-full border-[1.5px] border-marca px-[28px] py-[14px] text-rotulo text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca"
            type="button" data-modulo-cancelar><?= esc_html__('Cancelar', 'angelitozzz') ?></button>
        <?php get_template_part('template-parts/components/boton', null, [
            'texto'  => __('Guardar módulo', 'angelitozzz'),
            'enviar' => true,
            'clase'  => 'justify-center',
            'datos'  => ['data-modulo-guardar' => '1'],
        ]); ?>
    </div>
</form>
<?php
angelitozzz_modal_end();
