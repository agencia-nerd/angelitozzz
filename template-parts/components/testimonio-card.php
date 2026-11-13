<?php

/**
 * Tarjeta de testimonio (specs/testimonio.md, sección ficha; la lista es de specs/testimonios.md).
 * No es un enlace: el testimonio no tiene página propia.
 *
 * $args (con `post_id` los datos salen del testimonio; lo que se pase aparte tiene prioridad):
 * - post_id    (int, opcional: el testimonio; lee su ficha ACF, título, contenido, imagen destacada y categoría)
 * - foto       (ID de imagen o array de imagen: la imagen destacada del testimonio)
 * - nombre     (string, requerido: el título del testimonio)
 * - estrellas  (int de 1 a 5, por defecto 5)
 * - categoria  (string: nombre del término de categoria_testimonio)
 * - edad       (string, opcional: si está vacío no se imprime la pastilla)
 * - texto      (string, requerido: el contenido, en texto plano)
 */

defined('ABSPATH') || exit;

$post_id = (int) ($args['post_id'] ?? 0);
$ficha   = $post_id ? (get_field('testimonio_ficha', $post_id) ?: []) : [];
$terminos = $post_id ? get_the_terms($post_id, 'categoria_testimonio') : [];

$nombre    = (string) ($args['nombre'] ?? ($post_id ? get_the_title($post_id) : ''));
$texto     = (string) ($args['texto'] ?? ($post_id ? wp_strip_all_tags((string) get_post_field('post_content', $post_id)) : ''));
$estrellas = max(1, min(5, (int) ($args['estrellas'] ?? ($ficha['estrellas'] ?? 5))));
$categoria = (string) ($args['categoria'] ?? (is_array($terminos) && $terminos ? $terminos[0]->name : ''));
$edad      = (string) ($args['edad'] ?? ($ficha['edad'] ?? ''));
$foto      = $args['foto'] ?? ($post_id ? (get_post_thumbnail_id($post_id) ?: null) : null);

if ($nombre === '' || $texto === '') {
    return;
}
?>
<article class="flex gap-8 rounded-tarjeta bg-blanco p-8 max-md:flex-col max-md:gap-5 max-md:p-6">
    <div class="size-40 shrink-0 overflow-hidden rounded-media max-md:size-30">
        <?= angelitozzz_image($foto, [
            'class'        => 'aspect-square size-full object-cover',
            'alt_fallback' => $nombre,
        ]) ?>
    </div>
    <div class="flex min-w-0 flex-1 flex-col gap-2.5">
        <div class="flex flex-wrap items-center gap-3">
            <h2 class="text-h4 text-marca"><?= esc_html($nombre) ?></h2>
            <div class="flex gap-0.5" role="img" aria-label="<?= esc_attr(sprintf(__('%d de 5 estrellas', 'angelitozzz'), $estrellas)) ?>">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <svg class="<?= esc_attr('size-4.5 ' . ($i <= $estrellas ? 'text-estrella' : 'text-neutro')) ?>" viewBox="0 0 18 18" fill="currentColor" aria-hidden="true" focusable="false"><path d="M8.64 1.72a.76.76 0 0 1 1.36 0l1.73 3.51c.11.23.28.43.49.58.21.15.45.25.7.28l3.88.57a.76.76 0 0 1 .42 1.3l-2.8 2.73a1.5 1.5 0 0 0-.4 1.33l.66 3.86a.76.76 0 0 1-1.1.8L9.74 13.94a1.5 1.5 0 0 0-1.4 0L4.8 15.76a.76.76 0 0 1-1.1-.8l.67-3.86a1.5 1.5 0 0 0-.4-1.33L1.62 7.35a.76.76 0 0 1 .42-1.3l3.87-.57c.26-.04.5-.13.7-.28.21-.15.38-.35.5-.58z" /></svg>
                <?php endfor; ?>
            </div>
        </div>
        <?php if ($categoria !== '' || $edad !== '') : ?>
            <div class="flex flex-wrap items-center gap-2">
                <?php get_template_part('template-parts/components/etiqueta', null, [
                    'texto'  => $categoria,
                    'estilo' => 'tarjeta',
                ]); ?>
                <?php if ($edad !== '') : ?>
                    <span class="rounded-full bg-fondo px-3.5 py-1.5 text-rotulo-s text-marca"><?= esc_html($edad) ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <p class="text-cuerpo text-tinta"><?= esc_html($texto) ?></p>
    </div>
</article>
