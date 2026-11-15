<?php

/**
 * Acordeón de preguntas de una solución (wp-accordion; specs/solucion.md → layout `preguntas`).
 * Mismo diseño que la página Preguntas frecuentes: check del diseño como indicador (.faq-check),
 * separador beige y sin ícono de abrir. Varias abiertas a la vez; la primera empieza abierta.
 * Las preguntas que pasan de `visibles` van en un grupo [data-ver-mas-extra]: sin JS se ven todas;
 * con JS las oculta el "Ver más" de la sección (src/js/modules/ver-mas.js).
 *
 * $args:
 * - id        (string, requerido: id único del acordeón)
 * - preguntas (array de pregunta + respuesta; ya filtradas: todas con respuesta)
 * - visibles  (int, por defecto 5)
 */

defined('ABSPATH') || exit;

$id        = (string) ($args['id'] ?? 'solucion-preguntas');
$preguntas = (array) ($args['preguntas'] ?? []);
$visibles  = max(1, (int) ($args['visibles'] ?? 5));

if (!$preguntas) {
    return;
}

$pintar = static function (array $fila, bool $abierta = false): void {
    angelitozzz_accordion_item_start((string) $fila['pregunta'], ['open' => $abierta]);
    echo '<p>', angelitozzz_highlight((string) $fila['respuesta']), '</p>';
    angelitozzz_accordion_item_end();
};

angelitozzz_accordion_start($id, [
    'icon'          => 'none',
    'class'         => 'flex w-full max-w-[774px] flex-col gap-4 py-5',
    'item_class'    => 'relative pb-[54px] max-md:pb-6 after:absolute after:bottom-0 after:left-[26px] after:right-0 after:border-b after:border-neutro max-md:after:left-6',
    'summary_class' => 'max-md:items-start',
    'title_class'   => 'faq-check relative pl-6 text-cuerpo-l text-tinta',
    'panel_class'   => 'pt-[54px] text-cuerpo-s text-tinta max-md:pt-6',
]);

foreach (array_slice($preguntas, 0, $visibles) as $i => $fila) {
    $pintar($fila, $i === 0);
}

$extra = array_slice($preguntas, $visibles);
if ($extra) : ?>
    <div id="<?= esc_attr($id . '-mas') ?>" class="flex flex-col gap-4" data-ver-mas-extra>
        <?php foreach ($extra as $fila) {
            $pintar($fila);
        } ?>
    </div>
<?php endif;

angelitozzz_accordion_end();
