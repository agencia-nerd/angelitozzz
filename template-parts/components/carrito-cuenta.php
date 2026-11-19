<?php

/**
 * Cantidad del carrito en el ícono del header: el texto para lectores de pantalla y el globo (solo si hay cursos).
 * Lo imprime header.php y lo devuelve como fragmento inc/woocommerce/carrito.php: la raíz
 * (span[data-carrito-cuenta]) se cambia entera al agregar o quitar, sin recargar.
 */

defined('ABSPATH') || exit;

$cantidad = function_exists('angelitozzz_carrito_cantidad') ? angelitozzz_carrito_cantidad() : 0;
?>
<span data-carrito-cuenta>
    <span class="sr-only"><?= esc_html(sprintf(_n('Carrito (%d producto)', 'Carrito (%d productos)', $cantidad, 'angelitozzz'), $cantidad)) ?></span>
    <?php if ($cantidad > 0) : ?>
        <span class="absolute -top-[5px] left-[29px] flex size-5 items-center justify-center rounded-full bg-acento text-[12px] leading-[1.4] text-blanco" aria-hidden="true"><?= esc_html((string) $cantidad) ?></span>
    <?php endif; ?>
</span>
