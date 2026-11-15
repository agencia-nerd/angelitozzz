<?php

/**
 * Tarjeta de curso del administrador (specs/_shared.md → curso-admin-card; admin-cursos.md, Figma
 * 4184:30020): portada de 190 px, título, estudiantes, fecha, precio y el botón "Más opciones" con su
 * menú (Editar, Ver en el sitio, Publicar / Pasar a borrador, Eliminar). El menú y las acciones los
 * maneja src/js/modules/admin-cursos.js; sin JS el menú queda oculto y el título enlaza a editar.
 *
 * $args:
 * - datos (array, requerido: un curso de angelitozzz_cursos_admin_lista(): id, titulo, estado
 *          'publicado' | 'borrador', imagen_id, estudiantes, fecha, precio, url_editar, url_sitio)
 * - nivel ('h2' | 'h3', por defecto 'h3')
 */

defined('ABSPATH') || exit;

$datos = (array) ($args['datos'] ?? []);
$id    = (int) ($datos['id'] ?? 0);
$nivel = ($args['nivel'] ?? 'h3') === 'h2' ? 'h2' : 'h3';

if (!$id) {
    return;
}

$titulo      = (string) ($datos['titulo'] ?? '');
$publicado   = ($datos['estado'] ?? '') === 'publicado';
$estudiantes = (int) ($datos['estudiantes'] ?? 0);
$precio      = (string) ($datos['precio'] ?? '');
$url_editar  = (string) ($datos['url_editar'] ?? '');
$url_sitio   = (string) ($datos['url_sitio'] ?? '');
$menu_id     = 'curso-admin-menu-' . $id;

$item = 'flex w-full cursor-pointer items-center rounded-[10px] px-4 py-[10px] text-left text-cuerpo text-tinta hover:bg-fondo focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-marca';
?>
<article class="relative flex h-full flex-col rounded-[20px] border border-neutro bg-blanco"
    data-curso-card
    data-id="<?= esc_attr((string) $id) ?>"
    data-titulo="<?= esc_attr($titulo) ?>"
    data-estudiantes="<?= esc_attr((string) $estudiantes) ?>"
    data-estado="<?= esc_attr($publicado ? 'publicado' : 'borrador') ?>">
    <div class="relative h-[190px] overflow-hidden rounded-t-[19px]">
        <?= angelitozzz_image((int) ($datos['imagen_id'] ?? 0), [
            'class'    => 'size-full object-cover',
            'alt'      => '',
            'loading'  => 'lazy',
            'fallback' => get_theme_file_uri('assets/img/ayudarte-imagen-not-found.webp'),
        ]) ?>
        <span class="<?= esc_attr('absolute left-0 top-0' . ($publicado ? ' hidden' : '')) ?>" data-curso-etiqueta>
            <?php get_template_part('template-parts/components/etiqueta', null, [
                'texto'  => __('Borrador', 'angelitozzz'),
                'estilo' => 'esquina',
            ]); ?>
        </span>
    </div>

    <div class="flex flex-1 flex-col gap-[10px] p-5">
        <<?= $nivel ?> class="text-h5 text-marca">
            <a class="hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url($url_editar) ?>"><?= esc_html($titulo) ?></a>
        </<?= $nivel ?>>

        <ul class="flex flex-wrap items-center gap-x-4 gap-y-1 text-caption text-tinta-suave">
            <li class="flex items-center gap-[6px]">
                <?= angelitozzz_svg('administrador/estudiantes', 'size-4 shrink-0') // SVG fijo del tema. ?>
                <span><?= esc_html(sprintf(
                    /* translators: %d: número de estudiantes. */
                    _n('%d estudiante', '%d estudiantes', $estudiantes, 'angelitozzz'),
                    $estudiantes
                )) ?></span>
            </li>
            <li class="flex items-center gap-[6px]">
                <?= angelitozzz_svg('administrador/fecha', 'size-4 shrink-0') // SVG fijo del tema. ?>
                <span>
                    <span class="sr-only"><?= esc_html($publicado ? __('Publicado el', 'angelitozzz') : __('Creado el', 'angelitozzz')) ?> </span><?= esc_html((string) ($datos['fecha'] ?? '')) ?>
                </span>
            </li>
        </ul>

        <div class="mt-auto flex items-center justify-between gap-4 pt-[38px]">
            <p class="flex flex-col">
                <span class="text-caption text-tinta-suave"><?= esc_html__('Precio', 'angelitozzz') ?></span>
                <span class="text-h4 text-marca"><?= esc_html($precio !== '' ? $precio : '—') ?></span>
            </p>

            <div class="relative" data-curso-menu-contenedor>
                <button class="flex size-10 cursor-pointer items-center justify-center rounded-full border border-neutro bg-fondo text-marca hover:border-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca"
                    type="button"
                    aria-label="<?= esc_attr(sprintf(
                        /* translators: %s: nombre del curso. */
                        __('Más opciones de %s', 'angelitozzz'),
                        $titulo
                    )) ?>"
                    aria-expanded="false"
                    aria-controls="<?= esc_attr($menu_id) ?>"
                    data-curso-menu-boton>
                    <?= angelitozzz_svg('administrador/mas-opciones', 'size-5') // SVG fijo del tema. ?>
                </button>

                <ul class="absolute bottom-[calc(100%+8px)] right-0 z-20 w-[220px] rounded-[15px] border border-neutro bg-blanco p-2 shadow-[0_12px_32px_#00000024]"
                    id="<?= esc_attr($menu_id) ?>" hidden data-curso-menu>
                    <li>
                        <a class="<?= esc_attr($item) ?>" href="<?= esc_url($url_editar) ?>"><?= esc_html__('Editar', 'angelitozzz') ?></a>
                    </li>
                    <?php if ($url_sitio !== '') : ?>
                        <li>
                            <a class="<?= esc_attr($item) ?>" href="<?= esc_url($url_sitio) ?>"><?= esc_html__('Ver en el sitio', 'angelitozzz') ?></a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <button class="<?= esc_attr($item) ?>" type="button"
                            data-accion="<?= esc_attr($publicado ? 'borrador' : 'publicar') ?>"
                            data-texto-publicar="<?= esc_attr__('Publicar', 'angelitozzz') ?>"
                            data-texto-borrador="<?= esc_attr__('Pasar a borrador', 'angelitozzz') ?>">
                            <?= esc_html($publicado ? __('Pasar a borrador', 'angelitozzz') : __('Publicar', 'angelitozzz')) ?>
                        </button>
                    </li>
                    <li>
                        <button class="<?= esc_attr(str_replace('text-tinta', 'text-acento', $item)) ?>" type="button" data-accion="eliminar"><?= esc_html__('Eliminar', 'angelitozzz') ?></button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</article>
