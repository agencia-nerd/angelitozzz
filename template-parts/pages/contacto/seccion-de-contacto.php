<?php

/**
 * Contacto: encabezado y formulario (wp-form).
 * Grupo ACF: contacto_seccion_de_contacto (specs/contacto.md).
 */

defined('ABSPATH') || exit;

$grupo     = get_field('contacto_seccion_de_contacto') ?: [];
$titulo    = (string) ($grupo['titulo'] ?? '');
$subtitulo = (string) ($grupo['subtitulo'] ?? '');
$id_titulo = wp_unique_id('contacto-titulo-');
?>
<section class="px-4 py-22 max-md:py-16" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="mx-auto flex w-full max-w-[591px] flex-col items-center gap-19 max-md:gap-12">
        <header class="flex w-full max-w-[392px] flex-col items-center gap-11.5 text-center">
            <h1 id="<?= esc_attr($id_titulo) ?>" class="text-display text-marca max-md:text-h1"><?= angelitozzz_highlight($titulo) ?></h1>
            <?php if ($subtitulo !== '') : ?>
                <p class="text-body-s"><?= angelitozzz_highlight($subtitulo) ?></p>
            <?php endif; ?>
        </header>

        <?php get_template_part('template-parts/forms/contacto'); ?>
    </div>
</section>
