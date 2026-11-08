<?php

/**
 * Formulario "coach" del modal del cintillo (definición: inc/forms/coach.php).
 * Lo imprime template-parts/modals/modal-coach.php. Textos de Ajustes del sitio
 * (grupo opciones_modal_coach en specs/opciones.md).
 */

defined('ABSPATH') || exit;

$form_id        = 'coach';
$privacy_url    = get_privacy_policy_url();
$fallback_email = angelitozzz_form_recipients('coach')[0] ?? '';

$coach          = get_field('opciones_modal_coach', 'option') ?: [];
$ayuda_whatsapp = (string) ($coach['ayuda_whatsapp'] ?? '');
$ayuda_email    = __('Así también te avisamos sobre contenido y novedades', 'angelitozzz');
$aceptacion     = __('Acepto que Angelitozzz use mis datos para contactarme y enviarme información relacionada ·', 'angelitozzz');
$boton          = (string) ($coach['boton'] ?? '');

$campo_class = 'min-h-11 w-full rounded-[12px] border border-neutro bg-fondo text-body focus:outline-2 focus:outline-marca aria-invalid:border-acento';
$input_class = $campo_class . ' px-[18px] py-[14px] placeholder:text-tinta-suave';
$label_class = 'text-label text-marca';
$error_class = 'text-caption text-acento empty:hidden';
$ayuda_class = 'text-caption text-tinta-suave';
?>
<form class="relative flex flex-col gap-5" <?php angelitozzz_form_attrs($form_id); ?>>
    <noscript>
        <p class="text-caption">Para enviar este formulario necesitas activar JavaScript. También puedes escribirnos a <a class="underline" href="mailto:<?php echo esc_attr(antispambot($fallback_email)); ?>"><?php echo esc_html(antispambot($fallback_email)); ?></a>.</p>
    </noscript>
    <p class="text-caption text-tinta-suave">Los campos con <span aria-hidden="true">*</span> son obligatorios.</p>

    <div class="flex flex-col gap-2">
        <label class="<?php echo esc_attr($label_class); ?>" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'nombre')); ?>">¿Cómo te llamas? <span aria-hidden="true">*</span></label>
        <input class="<?php echo esc_attr($input_class); ?>" placeholder="Tu nombre" autofocus <?php angelitozzz_field_attrs($form_id, 'nombre'); ?>>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'nombre'); ?>></p>
    </div>

    <fieldset class="flex flex-col gap-2">
        <legend class="<?php echo esc_attr($label_class . ' mb-2'); ?>">Tu WhatsApp <span aria-hidden="true">*</span></legend>
        <div class="flex gap-[10px]">
            <div class="relative shrink-0">
                <label class="sr-only" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'whatsapp_pais')); ?>">Código de país</label>
                <select class="<?php echo esc_attr($campo_class . ' w-[100px] appearance-none py-[14px] pl-[14px] pr-9'); ?>" <?php angelitozzz_field_attrs($form_id, 'whatsapp_pais'); ?>>
                    <?php foreach (angelitozzz_field_options($form_id, 'whatsapp_pais') as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($value, 'pe'); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
                <svg class="pointer-events-none absolute right-[14px] top-1/2 size-4 -translate-y-1/2 text-marca" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m4 6 4 4 4-4" /></svg>
            </div>
            <div class="flex-1">
                <label class="sr-only" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'whatsapp')); ?>">Número de WhatsApp</label>
                <input class="<?php echo esc_attr($input_class); ?>" placeholder="999 999 999" <?php angelitozzz_field_attrs($form_id, 'whatsapp'); ?>>
            </div>
        </div>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'whatsapp_pais'); ?>></p>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'whatsapp'); ?>></p>
        <p class="<?php echo esc_attr($ayuda_class); ?>"><?php echo esc_html($ayuda_whatsapp); ?></p>
    </fieldset>

    <div class="flex flex-col gap-2">
        <label class="<?php echo esc_attr($label_class); ?>" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'edad_bebe')); ?>">Edad de tu bebé <span aria-hidden="true">*</span></label>
        <div class="relative">
            <select class="<?php echo esc_attr($campo_class . ' appearance-none py-[14px] pl-[18px] pr-12 invalid:text-tinta-suave'); ?>" <?php angelitozzz_field_attrs($form_id, 'edad_bebe'); ?>>
                <option value="">Selecciona una opción</option>
                <?php foreach (angelitozzz_field_options($form_id, 'edad_bebe') as $value => $label) : ?>
                    <option class="text-tinta" value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
            <svg class="pointer-events-none absolute right-[18px] top-1/2 size-[18px] -translate-y-1/2 text-marca" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m4.5 6.75 4.5 4.5 4.5-4.5" /></svg>
        </div>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'edad_bebe'); ?>></p>
    </div>

    <div class="flex flex-col gap-2">
        <label class="<?php echo esc_attr($label_class); ?>" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'email')); ?>">Tu email (opcional)</label>
        <input class="<?php echo esc_attr($input_class); ?>" placeholder="tucorreo@email.com" <?php angelitozzz_field_attrs($form_id, 'email'); ?>>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'email'); ?>></p>
        <p class="<?php echo esc_attr($ayuda_class); ?>"><?php echo esc_html($ayuda_email); ?></p>
    </div>

    <div class="flex flex-col gap-2">
        <div class="flex items-start gap-3">
            <input class="mt-[2px] size-[22px] shrink-0 accent-marca" <?php angelitozzz_field_attrs($form_id, 'consent'); ?>>
            <label class="text-body-s" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'consent')); ?>">
                <?php echo esc_html($aceptacion); ?>
                <?php if ($privacy_url) : ?>
                    <a class="text-acento underline" href="<?php echo esc_url($privacy_url); ?>" target="_blank" rel="noopener">Ver Políticas de privacidad<span class="sr-only"> (se abre en una pestaña nueva)</span></a>
                <?php else : ?>
                    Políticas de privacidad
                <?php endif; ?>
                <span aria-hidden="true">*</span>
            </label>
        </div>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'consent'); ?>></p>
    </div>

    <?php angelitozzz_form_honeypot(); ?>

    <button type="submit" class="flex min-h-11 w-full items-center justify-center gap-[10px] rounded-full bg-acento py-[14px] pl-7 pr-6 text-label text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca data-loading:opacity-70" data-loading-label="Enviando...">
        <span data-submit-label><?php echo esc_html($boton); ?></span>
        <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
    </button>
    <p class="text-caption text-center empty:hidden data-[state=error]:text-acento data-[state=success]:text-marca" data-form-status role="status" aria-live="polite"></p>
</form>
