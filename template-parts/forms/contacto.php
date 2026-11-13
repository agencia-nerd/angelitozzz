<?php

/**
 * Formulario "contacto" de la página Contacto (definición: inc/forms/contacto.php).
 * Diseño: campos de una sola línea inferior (1 px, --color-tinta), sin caja ni fondo.
 */

defined('ABSPATH') || exit;

$form_id        = 'contacto';
$privacy_url    = get_privacy_policy_url();
$fallback_email = angelitozzz_form_recipients('contacto')[0] ?? '';

$label_class = 'text-cuerpo-s text-tinta';
$linea_class = 'min-h-11 w-full rounded-none border-0 border-b border-tinta bg-transparent px-0 text-base text-tinta placeholder:text-tinta-suave focus:outline-none focus-visible:border-b-2 focus-visible:border-marca aria-invalid:border-acento';
$error_class = 'text-caption text-acento empty:hidden';
?>
<form class="relative flex w-full flex-col items-center gap-8.5" <?php angelitozzz_form_attrs($form_id); ?>>
    <noscript>
        <p class="text-caption">Para enviar este formulario necesitas activar JavaScript. También puedes escribirnos a <a class="underline" href="mailto:<?php echo esc_attr(antispambot($fallback_email)); ?>"><?php echo esc_html(antispambot($fallback_email)); ?></a>.</p>
    </noscript>
    <p class="sr-only">Los campos con * son obligatorios.</p>

    <div class="flex w-full flex-col gap-2.25">
        <label class="<?php echo esc_attr($label_class); ?>" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'nombre')); ?>">Nombres y Apellidos: <span aria-hidden="true">*</span></label>
        <input class="<?php echo esc_attr($linea_class); ?>" <?php angelitozzz_field_attrs($form_id, 'nombre'); ?>>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'nombre'); ?>></p>
    </div>

    <div class="flex w-full flex-col gap-8.5 md:flex-row md:justify-between">
        <div class="flex flex-col gap-2.25 md:w-[283px]">
            <label class="<?php echo esc_attr($label_class); ?>" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'email')); ?>">Email: <span aria-hidden="true">*</span></label>
            <input class="<?php echo esc_attr($linea_class); ?>" <?php angelitozzz_field_attrs($form_id, 'email'); ?>>
            <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'email'); ?>></p>
        </div>

        <fieldset class="flex flex-col gap-2.25 md:w-[283px]">
            <legend class="<?php echo esc_attr($label_class . ' mb-2.25'); ?>">Celular (con código de país): <span aria-hidden="true">*</span></legend>
            <div class="flex gap-3">
                <div class="shrink-0">
                    <label class="sr-only" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'celular_pais')); ?>">Código de país</label>
                    <select class="<?php echo esc_attr($linea_class . ' w-[92px]'); ?>" <?php angelitozzz_field_attrs($form_id, 'celular_pais'); ?>>
                        <?php foreach (angelitozzz_field_options($form_id, 'celular_pais') as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected($value, 'pe'); ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="min-w-0 flex-1">
                    <label class="sr-only" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'celular')); ?>">Número de celular</label>
                    <input class="<?php echo esc_attr($linea_class); ?>" <?php angelitozzz_field_attrs($form_id, 'celular'); ?>>
                </div>
            </div>
            <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'celular_pais'); ?>></p>
            <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'celular'); ?>></p>
        </fieldset>
    </div>

    <div class="flex w-full flex-col gap-2.25">
        <label class="<?php echo esc_attr($label_class); ?>" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'curso')); ?>">Seleccione curso <span aria-hidden="true">*</span></label>
        <div class="relative">
            <select class="<?php echo esc_attr($linea_class . ' appearance-none pr-8'); ?>" <?php angelitozzz_field_attrs($form_id, 'curso'); ?>>
                <option value="">Seleccione curso</option>
                <?php foreach (angelitozzz_field_options($form_id, 'curso') as $value => $label) : ?>
                    <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
            <img class="pointer-events-none absolute right-0 top-1/2 size-5 -translate-y-1/2 rotate-180" src="<?php echo esc_url(get_theme_file_uri('assets/img/contacto-icono-desplegable.svg')); ?>" alt="" aria-hidden="true" width="20" height="20">
        </div>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'curso'); ?>></p>
    </div>

    <div class="flex w-full flex-col gap-2.25">
        <label class="<?php echo esc_attr($label_class); ?>" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'mensaje')); ?>">Mensaje: <span aria-hidden="true">*</span></label>
        <textarea class="<?php echo esc_attr($linea_class . ' min-h-[69px] resize-y'); ?>" rows="2" <?php angelitozzz_field_attrs($form_id, 'mensaje'); ?>></textarea>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'mensaje'); ?>></p>
    </div>

    <div class="flex w-full flex-col gap-2">
        <div class="flex items-center gap-3.75">
            <input class="size-[14px] shrink-0 accent-tinta" <?php angelitozzz_field_attrs($form_id, 'privacidad'); ?>>
            <label class="text-cuerpo-s text-tinta" for="<?php echo esc_attr(angelitozzz_field_id($form_id, 'privacidad')); ?>">
                He leído y acepto las
                <a class="underline" href="<?php echo esc_url($privacy_url ?: '#'); ?>">políticas de privacidad</a>
                <span aria-hidden="true">*</span>
            </label>
        </div>
        <p class="<?php echo esc_attr($error_class); ?>" <?php angelitozzz_error_attrs($form_id, 'privacidad'); ?>></p>
    </div>

    <?php angelitozzz_form_honeypot(); ?>

    <button type="submit" class="inline-flex min-h-11 items-center gap-[10px] rounded-full bg-acento py-[14px] pl-[28px] pr-[24px] text-rotulo text-blanco focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca data-loading:opacity-70" data-loading-label="Enviando...">
        <span data-submit-label>Enviar mensaje</span>
        <svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>
    </button>
    <p class="text-caption text-center empty:hidden data-[state=error]:text-acento data-[state=success]:text-marca" data-form-status role="status" aria-live="polite"></p>
</form>
