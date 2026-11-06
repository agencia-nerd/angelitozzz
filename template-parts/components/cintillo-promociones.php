<?php

/**
 * Cintillo de promociones (specs/opciones.md, grupo opciones_cintillo_promociones).
 * Todo el cintillo es un <button> que abre el modal del coach (template-parts/modals/modal-coach.php).
 *
 * $args:
 * - texto (string; si está vacío, el cintillo no se muestra)
 * - modal (string, id del <dialog> que abre)
 */

defined('ABSPATH') || exit;

$texto = (string) ($args['texto'] ?? '');
$modal = (string) ($args['modal'] ?? 'modal-coach');

if ($texto === '') {
    return;
}
?>
<button class="flex min-h-14 w-full items-center justify-center gap-3 bg-etiqueta px-4 py-2 text-center text-[20px] font-medium leading-[1.5] text-tinta focus-visible:outline-2 focus-visible:-outline-offset-4 focus-visible:outline-marca max-md:text-body" aria-controls="<?= esc_attr($modal) ?>" <?php angelitozzz_modal_trigger_attrs($modal); ?>>
    <span><?= esc_html($texto) ?></span>
    <svg class="size-5 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4.17 10h11.66M10 4.17 15.83 10 10 15.83" /></svg>
</button>
