<?php

/**
 * Cierre de la tarjeta de confirmación (abre template-parts/components/confirmacion-tarjeta.php).
 *
 * $args:
 * - nota (string, opcional: línea final en itálica, --text-nota, --color-tinta-suave)
 */

defined('ABSPATH') || exit;

$nota = trim((string) ($args['nota'] ?? ''));
?>
    <?php if ($nota !== '') : ?>
        <p class="text-nota italic text-tinta-suave"><?= esc_html($nota) ?></p>
    <?php endif; ?>
</article>
