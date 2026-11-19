<?php

/**
 * Template Name: Canjear regalo
 *
 * Canje de un curso regalado (specs/canjear-regalo.md): el formulario (canje) o, justo después de
 * canjear, la confirmación (canjeado). Nunca se ven juntas.
 *
 * El formulario lo procesa el plugin angelitozzz-cursos (POST → redirección → GET). Tras canjear, el plugin
 * deja el último canje de la usuaria como dato de un solo uso; sin ese dato se ve el formulario vacío.
 */

defined('ABSPATH') || exit;

// Formulario vacío: el código nunca viaja en la URL (specs/canjear-regalo.md), así que no se lee de $_GET.
$canje = ['estado' => 'canje', 'valor' => '', 'error' => ''];

// F5-c: el plugin da el último canje (dato de un solo uso) o el error del envío anterior. Se espera
// ['estado' => 'canjeado', 'curso', 'meses' (0 = ilimitado), 'primera_leccion', 'cursos_url'] o
// ['estado' => 'canje', 'valor', 'error']; la acción y el nonce del formulario van en canje.php.
if (function_exists('angelitozzz_cursos_ultimo_canje')) {
    $ultimo = angelitozzz_cursos_ultimo_canje();
    if (is_array($ultimo) && $ultimo) {
        $canje = $ultimo + $canje;
    }
}

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
