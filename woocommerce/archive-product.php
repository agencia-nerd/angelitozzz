<?php

/**
 * Tienda: página Soluciones de sueño (specs/soluciones-de-sueno.md).
 *
 * Por ahora solo diseño: sin funciones ni hooks de WooCommerce (cada lugar está marcado con `// woo:`).
 * Los textos son fijos hasta el paso ACF; el número de WhatsApp sale de Ajustes del sitio.
 * Los archivos de categoría de producto también usan esta plantilla.
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
    // woo: woocommerce_before_main_content

    get_template_part('template-parts/pages/soluciones-de-sueno/shop');

    // woo: woocommerce_after_main_content

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
