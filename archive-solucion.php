<?php

/**
 * Listado de soluciones de sueño (specs/soluciones-de-sueno.md): /soluciones-de-sueno/.
 *
 * Textos de la página de opciones "Archivo de solucion" (soluciones_de_sueno_shop y soluciones_de_sueno_cta_final)
 * y tarjetas del bucle principal (wp-query). El número de WhatsApp sale de Ajustes del sitio.
 */

defined('ABSPATH') || exit;

get_header();

$cta = get_field('soluciones_de_sueno_cta_final', 'option') ?: [];

// El botón del cierre abre WhatsApp con el número de Ajustes del sitio; sin número no se muestra.
$boton = angelitozzz_whatsapp((string) ($cta['boton_texto'] ?? ''));
?>

<main id="main-content" class="fondo-nubes">
    <?php
    // Llegó desde un curso o una lección que no tiene y que no está en ninguna solución (?aviso=sin-acceso).
    $aviso_acceso = function_exists('angelitozzz_cursos_aviso_acceso') ? angelitozzz_cursos_aviso_acceso() : '';
    if ($aviso_acceso !== '') : ?>
        <div class="mx-auto max-w-[1150px] px-4 pt-6">
            <?php get_template_part('template-parts/components/aviso-formulario', null, ['texto' => $aviso_acceso, 'tipo' => 'aviso']); ?>
        </div>
    <?php endif;

    get_template_part('template-parts/pages/soluciones-de-sueno/shop');

    // Cierre: componente compartido cta-final.
    get_template_part('template-parts/components/cta-final', null, [
        'titulo'      => (string) ($cta['titulo'] ?? ''),
        'descripcion' => (string) ($cta['descripcion'] ?? ''),
        'boton'       => $boton,
    ]);
    ?>
</main>

<?php
get_footer();
