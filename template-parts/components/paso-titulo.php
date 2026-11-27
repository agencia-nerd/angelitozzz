<?php

/**
 * Título numerado de un paso del checkout (specs/checkout.md, rediseño 2026-11-27): círculo con el número
 * y el título como h2. Lo usan "Tus datos" (form-billing.php) y "Pago" (payment.php).
 *
 * $args:
 * - id     (string, requerido: id del h2, para el aria-labelledby de la tarjeta)
 * - numero (int, requerido)
 * - titulo (string, requerido)
 */

defined('ABSPATH') || exit;

$id     = (string) ($args['id'] ?? '');
$numero = (int) ($args['numero'] ?? 0);
$titulo = trim((string) ($args['titulo'] ?? ''));

if ($titulo === '') {
    return;
}
?>
<h2 <?= $id !== '' ? 'id="' . esc_attr($id) . '" ' : '' ?>class="flex items-center gap-3 text-h5 text-marca">
    <?php if ($numero > 0) : ?>
        <span class="paso-titulo__numero inline-flex size-8 shrink-0 items-center justify-center rounded-full bg-marca text-cuerpo-s text-blanco" aria-hidden="true"><?= esc_html((string) $numero) ?></span>
        <span class="sr-only"><?= esc_html(sprintf(__('Paso %d:', 'angelitozzz'), $numero)) ?></span>
    <?php endif; ?>
    <span><?= esc_html($titulo) ?></span>
</h2>
