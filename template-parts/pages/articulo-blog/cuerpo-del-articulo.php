<?php

/**
 * Artículo del blog · cuerpo (specs/articulo-blog.md, cuerpo-del-articulo).
 * El contenido del editor, con el aspecto del diseño por la clase del contenedor (src/css/articulo-contenido.css).
 * Al final, el consejo de la entrada (specs/entrada.md): texto en el campo de cada entrada, ícono fijo y la
 * etiqueta "Consejo de <primer nombre del autor>" (perfil de usuario). Sin texto, no se imprime.
 */

defined('ABSPATH') || exit;

$consejo  = trim((string) (get_field('entrada_consejo', get_the_ID())['texto'] ?? ''));
$autor_id = (int) get_post_field('post_author', get_the_ID());
$nombre   = trim((string) get_the_author_meta('first_name', $autor_id)) ?: (string) get_the_author_meta('display_name', $autor_id);
$etiqueta = sprintf(__('Consejo de %s', 'angelitozzz'), $nombre);
?>
<div class="px-4 pt-6 pb-18 max-md:pb-12">
    <div class="mx-auto flex max-w-[1040px] flex-col gap-6">
        <div class="articulo-contenido">
            <?php the_content(); ?>
        </div>

        <?php if ($consejo !== '') : ?>
            <aside class="flex items-start gap-4 rounded-media bg-neutro px-7 py-6 max-md:flex-col max-md:p-5"<?= $etiqueta !== '' ? ' aria-label="' . esc_attr($etiqueta) . '"' : '' ?>>
                <img class="size-8 shrink-0" src="<?= esc_url(get_theme_file_uri('assets/img/articulo-blog/consejo-icono-bombilla.svg')) ?>" alt="" aria-hidden="true" width="32" height="32">
                <div class="flex min-w-0 flex-1 flex-col gap-1">
                    <?php if ($etiqueta !== '') : ?>
                        <p class="text-label text-marca"><?= esc_html($etiqueta) ?></p>
                    <?php endif; ?>
                    <p class="text-body text-tinta"><?= angelitozzz_highlight($consejo) ?></p>
                </div>
            </aside>
        <?php endif; ?>
    </div>
</div>
