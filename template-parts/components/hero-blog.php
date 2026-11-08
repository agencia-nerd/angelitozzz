<?php

/**
 * Hero del blog (specs/blog.md, sección hero-blog). Lo usan home.php y category.php.
 * Buscador y pastillas son los filtros del listado: un solo <form method="get"> que conecta wp-query.
 *
 * $args:
 * - antetitulo (string, opcional)
 * - titulo     (string, requerido: h1)
 * - bajada     (string, opcional)
 * - activa     (string, slug de la categoría actual; vacío = la primera pastilla)
 *
 * El placeholder del buscador, su botón y la primera pastilla son textos fijos del componente.
 */

defined('ABSPATH') || exit;

$antetitulo  = trim((string) ($args['antetitulo'] ?? ''));
$titulo      = trim((string) ($args['titulo'] ?? ''));
$bajada      = trim((string) ($args['bajada'] ?? ''));
$activa      = (string) ($args['activa'] ?? '');
$placeholder = __('Busca un tema: siestas, regresión, horarios…', 'angelitozzz');
$boton       = __('Buscar', 'angelitozzz');
$todos       = __('Todos', 'angelitozzz');

$blog_id  = (int) get_option('page_for_posts');
$blog_url = $blog_id ? get_permalink($blog_id) : home_url('/');
$img      = static fn(string $archivo): string => get_theme_file_uri('assets/img/blog/' . $archivo);

// Filtros del listado (wp-query): el texto buscado y las categorías con al menos una entrada.
$busqueda = angelitozzz_query_filter('q');
$buscado  = is_string($busqueda['valor'] ?? null) ? $busqueda['valor'] : '';
$sin_categoria = get_term((int) get_option('default_category'), 'category');

// Cada pastilla lleva al archivo de su categoría (no a ?categoria=): así "Ver todos" y la pastilla son la misma página.
$categorias = [];
foreach ((array) (angelitozzz_query_filter('categoria')['opciones'] ?? []) as $opcion) {
    if ($sin_categoria instanceof WP_Term && $opcion['valor'] === $sin_categoria->slug) {
        continue;
    }
    $termino = get_term_by('slug', $opcion['valor'], 'category');
    if (!$termino instanceof WP_Term) {
        continue;
    }
    $categorias[] = [
        'slug'   => $termino->slug,
        'nombre' => $opcion['etiqueta'],
        'url'    => get_category_link($termino),
    ];
}

$pastilla        = 'block rounded-full px-4.5 py-2 text-label-s whitespace-nowrap transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
$pastilla_activa = $pastilla . ' bg-marca text-fondo';
$pastilla_normal = $pastilla . ' bg-blanco text-marca hover:bg-neutro';
?>
<section class="relative overflow-hidden bg-fondo-rosa px-4 py-16 md:py-24" aria-labelledby="blog-hero-titulo">
    <img class="pointer-events-none absolute left-[4%] top-6 h-[30px] w-[30px] md:left-[13.54%] md:top-[17.59%] md:h-[61px] md:w-[60px]" src="<?= esc_url($img('hero-estrella-grande.svg')) ?>" alt="" aria-hidden="true" width="60" height="61">
    <span class="pointer-events-none absolute right-[13.54%] top-[70%] hidden size-10 items-center justify-center md:flex" aria-hidden="true">
        <img class="h-[21px] w-[21px]" src="<?= esc_url($img('hero-destello.svg')) ?>" alt="" width="21" height="21">
    </span>

    <div class="relative mx-auto flex max-w-sitio flex-col items-center gap-4 text-center">
        <?php // Zona propia: al buscar desde una categoría sin recargar, el h1 pasa al del Blog como en una carga normal. ?>
        <div class="flex flex-col items-center gap-4" data-query="cabecera">
            <?php if ($antetitulo !== '') : ?>
                <p class="text-eyebrow text-marca-suave"><?= esc_html($antetitulo) ?></p>
            <?php endif; ?>

            <h1 id="blog-hero-titulo" class="text-display text-marca"><?= esc_html($titulo) ?></h1>

            <?php if ($bajada !== '') : ?>
                <p class="text-body-xl text-marca"><?= angelitozzz_highlight($bajada) ?></p>
            <?php endif; ?>
        </div>

        <form class="flex w-full flex-col items-center" method="get" action="<?= esc_url($blog_url) ?>" role="search" data-query="filtros">
            <div class="flex w-full max-w-[620px] items-center gap-3 rounded-full border border-neutro bg-blanco py-2 pl-5.5 pr-2 shadow-[0_6px_20px_0_rgba(36,69,74,0.08)] focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-marca">
                <img class="size-5 shrink-0" src="<?= esc_url($img('hero-buscador-icono.svg')) ?>" alt="" aria-hidden="true" width="20" height="20">
                <label for="blog-buscador" class="sr-only"><?= esc_html__('Buscar en el blog', 'angelitozzz') ?></label>
                <input
                    id="blog-buscador"
                    class="min-w-0 flex-1 bg-transparent text-body text-tinta placeholder:text-tinta-suave focus:outline-none"
                    type="search"
                    name="q"
                    value="<?= esc_attr($buscado) ?>"
                    placeholder="<?= esc_attr($placeholder) ?>">
                <button class="shrink-0 rounded-full bg-acento px-5 py-2.5 text-label-s text-blanco transition-opacity hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" type="submit">
                    <?= esc_html($boton) ?>
                </button>
            </div>

            <nav class="w-full pt-3" aria-label="<?= esc_attr__('Categorías del blog', 'angelitozzz') ?>">
                <ul class="relative -mx-4 flex gap-2.5 overflow-x-auto px-4 pb-1 md:mx-0 md:flex-wrap md:justify-center md:overflow-visible md:px-0 md:pb-0" data-pastillas>
                    <li class="shrink-0">
                        <a class="<?= esc_attr($activa === '' ? $pastilla_activa : $pastilla_normal) ?>" href="<?= esc_url($blog_url) ?>" data-query-ignorar<?php if ($activa === '') : ?> aria-current="page"<?php endif; ?>><?= esc_html($todos) ?></a>
                    </li>
                    <?php foreach ($categorias as $categoria) : ?>
                        <?php $es_activa = $categoria['slug'] === $activa; ?>
                        <li class="shrink-0">
                            <a class="<?= esc_attr($es_activa ? $pastilla_activa : $pastilla_normal) ?>" href="<?= esc_url($categoria['url']) ?>" data-query-ignorar<?php if ($es_activa) : ?> aria-current="page"<?php endif; ?>><?= esc_html($categoria['nombre']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </form>
    </div>
</section>
