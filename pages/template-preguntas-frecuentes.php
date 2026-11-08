<?php

/**
 * Template Name: Preguntas frecuentes
 *
 * Dudas comunes sobre las soluciones y los cursos (specs/preguntas-frecuentes.md).
 */

defined('ABSPATH') || exit;

get_header();

$cta_final = get_field('preguntas_frecuentes_cta_final') ?: [];
$contacto  = get_field('opciones_contacto', 'option') ?: [];

// El botón abre WhatsApp con el número de Ajustes del sitio (solo dígitos); sin número no se muestra.
$whatsapp = preg_replace('/\D+/', '', (string) ($contacto['whatsapp'] ?? ''));
$mensaje  = trim((string) ($contacto['whatsapp_mensaje'] ?? ''));
$boton    = null;

if ($whatsapp !== '') {
    $boton = [
        'url'    => 'https://wa.me/' . $whatsapp . ($mensaje !== '' ? '?text=' . rawurlencode($mensaje) : ''),
        'title'  => (string) ($cta_final['boton_texto'] ?? ''),
        'target' => '_blank',
    ];
}
?>

<main id="main-content" class="bg-fondo">
    <?php
    get_template_part('template-parts/pages/preguntas-frecuentes/seccion-preguntas-frecuentes');

    get_template_part('template-parts/components/cta-final', null, [
        'titulo'      => (string) ($cta_final['titulo'] ?? ''),
        'descripcion' => (string) ($cta_final['descripcion'] ?? ''),
        'boton'       => $boton,
    ]);
    ?>
</main>

<?php
get_footer();
