<?php

/**
 * Modal del coach de sueño virtual (specs/opciones.md: modal-coach y modal-coach-gracias).
 * Lo abre el cintillo (template-parts/components/cintillo-promociones.php). Dentro va el
 * formulario wp-form "coach"; al enviarlo, src/js/modules/modal-coach.js cambia el mismo
 * <dialog> a la pantalla de gracias con el nombre escrito. Textos de Ajustes del sitio
 * (grupos opciones_modal_coach y opciones_modal_coach_gracias).
 */

defined('ABSPATH') || exit;

$coach    = get_field('opciones_modal_coach', 'option') ?: [];
$gracias  = get_field('opciones_modal_coach_gracias', 'option') ?: [];
$contacto = get_field('opciones_contacto', 'option') ?: [];

$titulo = (string) ($coach['titulo'] ?? '');
$texto  = (string) ($coach['texto'] ?? '');

$gracias_titulo  = (string) ($gracias['titulo'] ?? '');
$gracias_texto   = (string) ($gracias['texto'] ?? '');
$gracias_boton   = __('Hablar ahora', 'angelitozzz');
$gracias_mensaje = (string) ($gracias['mensaje'] ?? '');
$gracias_nota    = __('Se abre WhatsApp con el mensaje:', 'angelitozzz');
// Sin número del coach, el botón de WhatsApp queda desactivado.
$whatsapp_coach = (string) ($contacto['whatsapp_coach'] ?? '');

angelitozzz_modal_start('modal-coach', [
    'title'        => $titulo,
    'placement'    => 'center',
    'width'        => '600px',
    'motion'       => 'scale',
    'form_success' => 'keep',
    'class'        => 'rounded-tarjeta shadow-[0_20px_60px_rgba(0,0,0,0.2)]',
    'header_class' => 'pl-11 pr-4 pt-4 max-md:pl-6 in-data-[estado=gracias]:justify-end',
    'title_class'  => 'mt-6 text-h4 text-marca in-data-[estado=gracias]:sr-only',
    'close_class'  => 'bg-fondo text-marca hover:bg-neutro',
    'body_class'   => 'px-11 pb-10 pt-5 max-md:px-6 max-md:pb-8',
]);
?>
<div class="flex flex-col gap-5" data-coach-paso="formulario">
    <p class="text-cuerpo"><?= angelitozzz_highlight($texto) ?></p>
    <?php get_template_part('template-parts/forms/coach'); ?>
</div>

<div class="flex flex-col items-center gap-5 pb-2 pt-4 text-center" data-coach-paso="gracias" hidden
    data-titulo="<?= esc_attr($gracias_titulo) ?>"
    data-mensaje="<?= esc_attr($gracias_mensaje) ?>"
    data-whatsapp="<?= esc_attr(preg_replace('/\D/', '', $whatsapp_coach)) ?>">
    <img class="size-[72px]" src="<?= esc_url(get_theme_file_uri('assets/img/modal-gracias-check.svg')) ?>" alt="" aria-hidden="true" width="72" height="72" loading="lazy">
    <p class="text-h3 text-marca" aria-hidden="true" data-coach-titulo></p>
    <p class="text-cuerpo-l"><?= angelitozzz_highlight($gracias_texto) ?></p>
    <a class="inline-flex items-center gap-[10px] rounded-full bg-acento py-[14px] pl-7 pr-6 text-rotulo text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca aria-disabled:cursor-default aria-disabled:opacity-50" aria-disabled="true" data-coach-whatsapp>
        <span><?= esc_html($gracias_boton) ?></span>
        <span class="sr-only" hidden data-coach-aviso>(<?= esc_html__('se abre en una pestaña nueva', 'angelitozzz') ?>)</span>
        <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
    </a>
    <?php if ($gracias_nota !== '') : ?>
        <p class="text-caption text-tinta-suave"><?= esc_html($gracias_nota) ?> “<span data-coach-mensaje></span>”</p>
    <?php endif; ?>
</div>
<?php
angelitozzz_modal_end();
