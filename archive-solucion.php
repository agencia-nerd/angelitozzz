<?php

/**
 * Listado de soluciones de sueño (specs/soluciones-de-sueno.md): /soluciones-de-sueno/.
 *
 * F4, solo maquetación: textos fijos hasta el paso ACF y tarjetas de muestra (inc/solucion-muestra.php);
 * wp-query cambia la muestra por el bucle de `solucion`. El número de WhatsApp sale de Ajustes del sitio.
 */

defined('ABSPATH') || exit;

get_header();

$contacto = get_field('opciones_contacto', 'option') ?: [];

// El botón del cierre abre WhatsApp con el número de Ajustes del sitio (solo dígitos); sin número no se muestra.
$whatsapp = preg_replace('/\D+/', '', (string) ($contacto['whatsapp'] ?? ''));
$mensaje  = trim((string) ($contacto['whatsapp_mensaje'] ?? ''));
$boton    = null;

if ($whatsapp !== '') {
    $boton = [
        'url'    => 'https://wa.me/' . $whatsapp . ($mensaje !== '' ? '?text=' . rawurlencode($mensaje) : ''),
        'title'  => 'Escríbeme por WhatsApp',
        'target' => '_blank',
    ];
}
?>

<main id="main-content" class="fondo-nubes">
    <?php
    get_template_part('template-parts/pages/soluciones-de-sueno/shop');

    // Cierre: componente compartido cta-final.
    get_template_part('template-parts/components/cta-final', null, [
        'titulo'      => '¿No sabes por dónde empezar?',
        'descripcion' => 'Cuéntame la edad de tu bebé y te ayudo a encontrar la opción correcta.',
        'boton'       => $boton,
    ]);
    ?>
</main>

<?php
get_footer();
