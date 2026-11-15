<?php

/**
 * Recuadro con el resultado de un formulario (pantallas de acceso): error o aviso, arriba del
 * formulario. El error se anuncia de inmediato (role="alert"); el aviso, con cortesía (role="status").
 *
 * $args:
 * - texto (string, requerido)
 * - tipo  ('error' | 'aviso', por defecto 'error')
 * - id    (string, opcional: para que un campo apunte al mensaje con aria-describedby)
 */

defined('ABSPATH') || exit;

$texto = trim((string) ($args['texto'] ?? ''));
$tipo  = ($args['tipo'] ?? 'error') === 'aviso' ? 'aviso' : 'error';
$id    = sanitize_html_class((string) ($args['id'] ?? ''));

if ($texto === '') {
    return;
}

$clases = $tipo === 'error'
    ? 'border-acento bg-fondo-rosa'
    : 'border-etiqueta bg-fondo';
?>
<div<?= $id !== '' ? ' id="' . esc_attr($id) . '"' : '' ?> class="<?= esc_attr('w-full rounded-xl border px-5 py-[14px] text-left text-cuerpo-s text-tinta ' . $clases) ?>" role="<?= $tipo === 'error' ? 'alert' : 'status' ?>">
    <?= esc_html($texto) ?>
</div>
