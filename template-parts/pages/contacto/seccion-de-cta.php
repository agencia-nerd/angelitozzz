<?php

/**
 * Contacto: tarjeta de contacto directo (correo y WhatsApp).
 * Grupo ACF: contacto_seccion_de_cta (specs/contacto.md). El WhatsApp sale de Ajustes del sitio.
 */

defined('ABSPATH') || exit;

$grupo  = get_field('contacto_seccion_de_cta') ?: [];
$titulo = (string) ($grupo['titulo'] ?? '');
$texto  = (string) ($grupo['texto'] ?? '');
$email  = sanitize_email((string) ($grupo['email'] ?? ''));

$contacto = get_field('opciones_contacto', 'option') ?: [];
$whatsapp = preg_replace('/\D+/', '', (string) ($contacto['whatsapp'] ?? ''));
$mensaje  = trim((string) ($contacto['whatsapp_mensaje'] ?? ''));
$boton    = null;

if ($whatsapp !== '') {
    $boton = [
        'url'    => 'https://wa.me/' . $whatsapp . ($mensaje !== '' ? '?text=' . rawurlencode($mensaje) : ''),
        'title'  => __('Escríbenos por WhatsApp', 'angelitozzz'),
        'target' => '_blank',
    ];
}

if ($titulo === '') {
    return;
}

$id_titulo = wp_unique_id('contacto-cta-');
?>
<section class="px-4 py-22 max-md:py-16" aria-labelledby="<?= esc_attr($id_titulo) ?>">
    <div class="relative mx-auto flex w-full max-w-sitio flex-col items-center gap-3.5 overflow-hidden rounded-tarjeta bg-neutro p-16 text-center max-md:px-6 max-md:py-12">
        <img class="pointer-events-none absolute -left-[30px] bottom-9 w-[121px] max-md:w-[60px]" src="<?= esc_url(get_theme_file_uri('assets/img/cta-estrella-contorno-inferior.svg')) ?>" alt="" aria-hidden="true" width="121" height="78" loading="lazy">
        <span class="pointer-events-none absolute bottom-[39px] left-[120px] flex size-[50px] items-center justify-center max-md:hidden" aria-hidden="true">
            <img class="size-[27px]" src="<?= esc_url(get_theme_file_uri('assets/img/cta-destello-dorado-pequeno.svg')) ?>" alt="" width="27" height="27" loading="lazy">
        </span>
        <img class="pointer-events-none absolute right-[30px] top-[31px] w-20 max-md:right-4 max-md:top-4 max-md:w-10" src="<?= esc_url(get_theme_file_uri('assets/img/cta-destello-blanco-superior.svg')) ?>" alt="" aria-hidden="true" width="80" height="69" loading="lazy">

        <h2 id="<?= esc_attr($id_titulo) ?>" class="relative text-h2 text-marca max-md:text-h3"><?= angelitozzz_highlight($titulo) ?></h2>
        <?php if ($texto !== '') : ?>
            <p class="relative text-cuerpo-l"><?= angelitozzz_highlight($texto) ?></p>
        <?php endif; ?>
        <?php if ($email !== '') : ?>
            <a class="relative break-words text-h3 text-marca hover:underline focus-visible:underline max-md:text-h4" href="<?= esc_url('mailto:' . antispambot($email)) ?>"><?= esc_html(antispambot($email)) ?></a>
        <?php endif; ?>
        <?php if ($boton) : ?>
            <div class="relative mt-2">
                <?php get_template_part('template-parts/components/boton', null, [
                    'enlace'   => $boton,
                    'variante' => 'frambuesa',
                ]); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
