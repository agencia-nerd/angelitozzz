<?php

/**
 * Gracias · regalo-listo (specs/gracias.md, Figma "Regalo listo" 3649:1702): una tarjeta por regalo
 * con su código para compartir y el botón "Copiar código" (src/js/modules/copiar-codigo.js).
 *
 * $args:
 * - regalo (array: curso, codigo). F4: muestra de inc/compra-muestra.php (f5: código del plugin).
 * - nivel  ('h1' | 'h2': h1 si es la primera tarjeta de la página)
 */

defined('ABSPATH') || exit;

$regalo = (array) ($args['regalo'] ?? []);
$curso  = trim((string) ($regalo['curso'] ?? ''));
$codigo = trim((string) ($regalo['codigo'] ?? ''));
$nivel  = ($args['nivel'] ?? 'h1') === 'h2' ? 'h2' : 'h1';

if ($codigo === '') {
    return;
}
?>
<div class="w-full" data-copiar-raiz>
    <?php
    get_template_part('template-parts/components/confirmacion-tarjeta', null, [
        'titulo'   => __('¡Tu regalo está listo!', 'angelitozzz'),
        'nivel'    => $nivel,
        'icono'    => 'regalo',
        'pastilla' => __('✓ Compra exitosa', 'angelitozzz'),
        /* translators: %s: nombre del curso regalado */
        'texto'    => sprintf(__('Regalaste %s. Te enviamos este código por correo: compártelo con quien quieras y podrá entrar al curso con él.', 'angelitozzz'), $curso),
    ]);
    ?>
    <p class="w-full rounded-xl border-2 border-dashed border-[#c4704a] bg-fondo px-6 py-[18px] text-[34px] font-semibold leading-[1.2] tracking-[2.72px] whitespace-nowrap text-marca select-all max-md:text-[28px]">
        <span class="sr-only"><?= esc_html__('Código de regalo:', 'angelitozzz') ?></span>
        <?= esc_html($codigo) ?>
    </p>

    <button type="button" class="inline-flex cursor-pointer items-center gap-[10px] whitespace-nowrap rounded-full bg-acento py-[14px] pl-[28px] pr-[24px] text-rotulo text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" data-copiar-codigo="<?= esc_attr($codigo) ?>">
        <span data-copiar-texto><?= esc_html__('Copiar código', 'angelitozzz') ?></span>
        <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
    </button>
    <p class="sr-only" aria-live="polite" data-copiar-aviso></p>

    <?php
    get_template_part('template-parts/components/confirmacion-tarjeta-fin', null, [
        'nota' => __('El código es válido por 6 meses desde la compra. El acceso al curso empieza el día del canje.', 'angelitozzz'),
    ]);
    ?>
</div>
