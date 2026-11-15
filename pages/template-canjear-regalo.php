<?php

/**
 * Template Name: Canjear regalo
 *
 * Canje de un curso regalado (specs/canjear-regalo.md): el formulario (canje) o, justo después de
 * canjear, la confirmación (canjeado). Nunca se ven juntas.
 *
 * F4, solo maquetación: el estado sale de la muestra angelitozzz_compra_muestra_canje()
 * (f5: el plugin angelitozzz-cursos procesa el formulario y deja el último canje como dato de un
 * solo uso; sin ese dato se ve el formulario).
 */

defined('ABSPATH') || exit;

$canje = angelitozzz_compra_muestra_canje(); // f5: angelitozzz_cursos_* (último canje de la usuaria o error del envío)

get_header();
?>

<main id="main-content" class="bg-fondo">
    <div class="px-4 py-30 max-md:py-14">
        <?php
        if (($canje['estado'] ?? '') === 'canjeado') {
            get_template_part('template-parts/pages/canjear-regalo/canjeado', null, $canje);
        } else {
            get_template_part('template-parts/pages/canjear-regalo/canje', null, $canje);
        }
        ?>
    </div>
</main>

<?php
get_footer();
