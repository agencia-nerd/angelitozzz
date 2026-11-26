<?php

/**
 * Modal de confirmación para eliminar (specs/admin-constructor.md → modal-eliminar, Figma 3687:4560;
 * admin-cursos.md → Eliminar). Uno solo por página, id `eliminar`: lo usan la lista de cursos (curso,
 * y curso con estudiantes, que solo ofrece pasarlo a borrador) y el constructor (lección, módulo y
 * descargable). El JS (pedirConfirmacion() de src/js/modules/admin-cursos.js) pone el título, el texto
 * y los botones de cada caso a partir de data-textos.
 *
 * Orden visual: ícono, título, texto y botones. El cuerpo usa display: contents para que el ícono
 * (order-1) y los botones (order-4) queden alrededor del título (cabecera, order-2) y del texto
 * (descripción, order-3), que son el nombre y la descripción accesibles del alertdialog.
 */

defined('ABSPATH') || exit;

$textos = [
    'curso' => [
        'titulo' => __('¿Eliminar este curso?', 'angelitozzz'),
        /* translators: %s: nombre del curso. */
        'texto'  => __('Se enviará “%s” a la papelera junto con sus lecciones y dejará de venderse.', 'angelitozzz'),
    ],
    'curso_con_estudiantes' => [
        'titulo'      => __('Este curso no se puede eliminar', 'angelitozzz'),
        /* translators: 1: nombre del curso, 2: número de estudiantes. */
        'texto'       => __('“%1$s” tiene %2$s con acceso, por eso no se puede eliminar. Puedes pasarlo a borrador: sale del sitio y de la venta, y quienes ya lo compraron conservan su acceso.', 'angelitozzz'),
        'una'         => __('1 estudiante', 'angelitozzz'),
        /* translators: %d: número de estudiantes. */
        'varias'      => __('%d estudiantes', 'angelitozzz'),
        'alternativa' => __('Pasar a borrador', 'angelitozzz'),
    ],
    'leccion' => [
        'titulo' => __('¿Eliminar esta lección?', 'angelitozzz'),
        /* translators: %s: título de la lección. */
        'texto'  => __('Se eliminará “%s” y su texto. El video seguirá guardado en Archivos privados.', 'angelitozzz'),
    ],
    'modulo' => [
        'titulo' => __('¿Eliminar este módulo?', 'angelitozzz'),
        /* translators: 1: título del módulo, 2: número de lecciones. */
        'texto'  => __('Se eliminarán “%1$s” y sus %2$d lecciones. Los videos seguirán guardados en Archivos privados.', 'angelitozzz'),
    ],
    'descargable' => [
        'titulo' => __('¿Quitar este descargable?', 'angelitozzz'),
        /* translators: %s: nombre del archivo. */
        'texto'  => __('Se quitará “%s” de este curso. Seguirá guardado en Archivos privados.', 'angelitozzz'),
    ],
    'inscritas' => __('Las alumnas inscritas dejarán de ver este contenido.', 'angelitozzz'),
    'confirmar' => __('Sí, eliminar', 'angelitozzz'),
];

angelitozzz_modal_start('eliminar', [
    'title'             => $textos['curso']['titulo'],
    'description'       => sprintf($textos['descargable']['texto'], __('este elemento', 'angelitozzz')),
    'role'              => 'alertdialog',
    'placement'         => 'center',
    'width'             => '560px',
    'mobile'            => 'center',
    'motion'            => 'scale',
    'class'             => 'items-center gap-4 rounded-tarjeta px-10 pb-10 pt-11 text-center max-sm:px-5 max-sm:pb-6 max-sm:pt-8',
    'header_class'      => 'order-2 justify-center p-0',
    'title_class'       => 'text-h3 text-marca',
    'close_class'       => 'hidden',
    'description_class' => 'order-3 m-0 p-0 text-cuerpo text-tinta',
    'body_class'        => 'contents',
]);
?>
<div class="order-1 flex size-12 items-center justify-center rounded-full border-2 border-terracota text-terracota" aria-hidden="true" data-textos="<?= esc_attr(wp_json_encode($textos)) ?>">
    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" focusable="false"><path d="M12 6v8" /><path d="M12 18.5v.01" /></svg>
</div>

<form class="order-4 mt-4 flex flex-wrap justify-center gap-4 max-sm:w-full max-sm:flex-col-reverse" method="dialog" data-eliminar-botones>
    <?php get_template_part('template-parts/components/boton', null, [
        'texto'    => __('Cancelar', 'angelitozzz'),
        'variante' => 'secundario',
        'flecha'   => false,
        'enviar'   => true,
        'clase'    => 'justify-center max-sm:w-full',
        'datos'    => ['data-eliminar-cancelar' => '1'],
    ]); ?>
    <?php get_template_part('template-parts/components/boton', null, [
        'texto'    => __('Pasar a borrador', 'angelitozzz'),
        'variante' => 'verde',
        'enviar'   => true,
        'clase'    => 'justify-center max-sm:w-full',
        'datos'    => ['data-eliminar-alternativa' => '1'],
    ]); ?>
    <?php get_template_part('template-parts/components/boton', null, [
        'texto'  => $textos['confirmar'],
        'enviar' => true,
        'clase'  => 'justify-center max-sm:w-full',
        'datos'  => ['data-eliminar-confirmar' => '1'],
    ]); ?>
</form>
<?php
angelitozzz_modal_end();
