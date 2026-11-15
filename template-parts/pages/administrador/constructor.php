<?php

/**
 * Administrador · constructor de cursos (specs/admin-constructor.md → pasos, Figma 4185:30575): migas,
 * h1 del paso, pastillas "1 · Básico > 2 · Contenido > 3 · Publicar" y el parcial del paso
 * (template-parts/pages/administrador/constructor-<paso>.php).
 *
 * Los datos del curso salen de angelitozzz_cursos_admin_curso() del plugin y todo se guarda por su API
 * REST (angelitozzz/v1, nonce wp_rest). La raíz [data-constructor] lleva lo que necesita el JS:
 * data-api (la base de rest_url(); el JS le agrega cada ruta con urlApi() de admin-comun.js, sin suponer
 * enlaces permanentes), data-nonce, data-curso (0 en un curso nuevo), data-paso y data-url-cursos.
 *
 * $args: curso_id (int, 0 = nuevo) y paso (1, 2 o 3), de angelitozzz_cursos_vista_admin().
 */

defined('ABSPATH') || exit;

$curso_id = (int) ($args['curso_id'] ?? 0);
$paso     = $curso_id ? (int) ($args['paso'] ?? 1) : 1;
$paso     = in_array($paso, [1, 2, 3], true) ? $paso : 1;
$curso    = angelitozzz_cursos_admin_curso($curso_id);
$parciales = [1 => 'basico', 2 => 'contenido', 3 => 'publicar'];

$pasos = [
    1 => __('1 · Básico', 'angelitozzz'),
    2 => __('2 · Contenido', 'angelitozzz'),
    3 => __('3 · Publicar', 'angelitozzz'),
];
?>
<div class="flex max-w-[1108px] flex-col gap-[30px] max-md:gap-6"
    data-constructor
    data-api="<?= esc_url(rest_url('angelitozzz/v1/')) ?>"
    data-nonce="<?= esc_attr(wp_create_nonce('wp_rest')) ?>"
    data-curso="<?= esc_attr((string) $curso_id) ?>"
    data-paso="<?= esc_attr((string) $paso) ?>"
    data-url-cursos="<?= esc_url(angelitozzz_cursos_url_admin('cursos')) ?>"
    data-error-generico="<?= esc_attr__('No pudimos guardar el cambio. Revisa tu conexión y vuelve a intentarlo.', 'angelitozzz') ?>">

    <div class="flex flex-col gap-5">
        <nav aria-label="<?= esc_attr__('Migas', 'angelitozzz') ?>">
            <ol class="flex flex-wrap items-center gap-1 text-caption text-tinta-suave">
                <li>
                    <a class="underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url(angelitozzz_cursos_url_admin('cursos')) ?>"><?= esc_html__('Cursos', 'angelitozzz') ?></a>
                </li>
                <li aria-hidden="true">/</li>
                <li aria-current="page"><?= esc_html($curso_id ? (string) ($curso['titulo'] ?? '') : __('Nuevo curso', 'angelitozzz')) ?></li>
            </ol>
        </nav>

        <h1 class="text-h2 text-marca max-md:text-h3"><?= esc_html(angelitozzz_titulo_paso_constructor($curso_id, $paso)) ?></h1>

        <ol class="-mx-4 flex items-center gap-[14px] overflow-x-auto px-4 pb-1 md:mx-0 md:px-0" aria-label="<?= esc_attr__('Pasos del curso', 'angelitozzz') ?>">
            <?php foreach ($pasos as $numero => $texto) :
                $activo     = $numero === $paso;
                $disponible = $curso_id > 0;
                $pastilla   = 'inline-flex shrink-0 items-center whitespace-nowrap rounded-full px-[11px] py-1 text-rotulo-s ';
                ?>
                <li class="flex shrink-0 items-center gap-[14px]">
                    <?php if ($numero > 1) : ?>
                        <svg class="size-3 shrink-0 text-tinta-suave" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m4.5 2.5 3.5 3.5-3.5 3.5" /></svg>
                    <?php endif; ?>
                    <?php if ($activo) : ?>
                        <span class="<?= esc_attr($pastilla . 'bg-etiqueta text-blanco') ?>" aria-current="step"><?= esc_html($texto) ?></span>
                    <?php elseif ($disponible) : ?>
                        <a class="<?= esc_attr($pastilla . 'bg-blanco text-marca hover:bg-fondo focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca') ?>"
                            href="<?= esc_url(angelitozzz_cursos_url_admin('editar', $curso_id, $numero)) ?>"><?= esc_html($texto) ?></a>
                    <?php else : ?>
                        <?php // Curso nuevo: los pasos 2 y 3 se abren al guardar el paso 1 (no son enlaces). ?>
                        <span class="<?= esc_attr($pastilla . 'bg-blanco text-tinta-suave opacity-60') ?>"><?= esc_html($texto) ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>

    <noscript>
        <p class="rounded-[15px] border border-acento bg-blanco p-5 text-cuerpo text-acento"><?= esc_html__('El constructor de cursos necesita JavaScript. Actívalo en tu navegador para crear o editar cursos.', 'angelitozzz') ?></p>
    </noscript>

    <?php get_template_part('template-parts/pages/administrador/constructor-' . $parciales[$paso], null, [
        'curso'    => $curso,
        'curso_id' => $curso_id,
    ]); ?>
</div>
