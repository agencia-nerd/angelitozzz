<?php

/**
 * Formulario "newsletter" del footer (definición: inc/forms/newsletter.php).
 * Diseño: pastilla blanca con el correo y el botón verde "Suscribirme".
 */

defined('ABSPATH') || exit;

$form_id        = 'newsletter';
$fallback_email = angelitozzz_form_recipients('newsletter')[0] ?? '';
?>
<form class="relative w-full lg:w-[374px]" <?php angelitozzz_form_attrs($form_id); ?>>
    <noscript>
        <p class="mb-2 text-caption">Para suscribirte necesitas activar JavaScript. También puedes escribirnos a <a class="underline" href="mailto:<?php echo esc_attr(antispambot($fallback_email)); ?>"><?php echo esc_html(antispambot($fallback_email)); ?></a>.</p>
    </noscript>
    <p class="sr-only">Los campos con * son obligatorios.</p>

    <label class="sr-only" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'email')); ?>">Correo electrónico *</label>
    <div class="flex items-center gap-3 rounded-[28px] bg-blanco py-1 pl-6 pr-1 focus-within:outline-2 focus-within:outline-marca has-[[aria-invalid=true]]:outline-2 has-[[aria-invalid=true]]:outline-acento">
        <input class="min-h-11 min-w-0 flex-1 bg-transparent text-base placeholder:text-[#8a8a8a] focus:outline-none" placeholder="Ingresa tu correo electrónico" <?php angelitozzz_field_attrs($form_id, 'email'); ?>>
        <button type="submit" class="flex h-11 shrink-0 items-center justify-center gap-2 rounded-[28px] bg-marca px-5 text-label text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blanco data-loading:opacity-70" data-loading-label="Enviando...">
            <span data-submit-label>Suscribirme</span>
            <svg class="h-2 w-[13px]" viewBox="0 0 13.94 8.65" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m10 8.15 3.44-3.82L10 .5M12.5 4.33H.5" /></svg>
        </button>
    </div>
    <p class="mt-2 pl-6 text-caption text-contorno empty:hidden" <?php angelitozzz_error_attrs($form_id, 'email'); ?>></p>

    <?php angelitozzz_form_honeypot(); ?>

    <p class="pl-6 text-caption empty:hidden data-[state=error]:text-contorno data-[state=success]:text-marca" data-form-status role="status" aria-live="polite"></p>
</form>
