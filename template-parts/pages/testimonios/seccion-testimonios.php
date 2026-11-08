<?php

/**
 * Testimonios · cabecera, filtros, lista y paginación (specs/testimonios.md, grupo testimonios_seccion_testimonios).
 * Los textos de la cabecera salen de la página de opciones del archivo. La lista es la consulta principal del archivo
 * (wp-query, definición en inc/query/testimonio.php): filtro por categoria_testimonio, 5 por página y paginación.
 */

defined('ABSPATH') || exit;

$seccion    = get_field('testimonios_seccion_testimonios', 'option') ?: [];
$antetitulo = (string) ($seccion['antetitulo'] ?? '');
$titulo     = (string) ($seccion['titulo'] ?? '');
$bajada     = (string) ($seccion['bajada'] ?? '');
$todos      = (string) ($seccion['todos'] ?? '');

$categoria = angelitozzz_query_filter('categoria');
$opciones  = (array) ($categoria['opciones'] ?? []);
$hay_marca = (bool) array_filter($opciones, static fn(array $o): bool => !empty($o['marcada']));

$pastilla = 'inline-flex items-center rounded-full border border-neutro bg-blanco px-4.5 py-2 text-label-s text-marca peer-checked:border-marca peer-checked:bg-marca peer-checked:text-fondo peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-marca';
?>
<section class="bg-fondo px-4 py-16 lg:py-26" aria-labelledby="testimonios-titulo">
    <div class="mx-auto flex max-w-sitio flex-col items-center gap-6">
        <div class="flex flex-col items-center gap-6 text-center">
            <?php if ($antetitulo !== '') : ?>
                <p class="text-eyebrow text-marca-suave"><?= esc_html($antetitulo) ?></p>
            <?php endif; ?>
            <h1 id="testimonios-titulo" class="text-h2 font-semibold text-marca lg:text-h1"><?= esc_html($titulo) ?></h1>
            <?php if ($bajada !== '') : ?>
                <p class="text-body-l text-tinta"><?= angelitozzz_highlight($bajada) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($categoria) : ?>
            <form class="relative" method="get" action="<?= esc_url(angelitozzz_query_reset_url()) ?>" data-query="filtros">
                <fieldset class="m-0 border-0 p-0">
                    <legend class="sr-only"><?= esc_html__('Filtrar testimonios por categoría', 'angelitozzz') ?></legend>
                    <div class="flex flex-wrap justify-center gap-3">
                        <label class="cursor-pointer">
                            <input class="peer sr-only" type="radio" name="<?= esc_attr($categoria['name']) ?>" value="" <?= checked(!$hay_marca, true, false) ?>>
                            <span class="<?= esc_attr($pastilla) ?>"><?= esc_html($todos) ?></span>
                        </label>
                        <?php foreach ($opciones as $opcion) : ?>
                            <label class="cursor-pointer">
                                <input class="peer sr-only" type="radio" name="<?= esc_attr($categoria['name']) ?>" value="<?= esc_attr($opcion['valor']) ?>" <?= checked(!empty($opcion['marcada']), true, false) ?>>
                                <span class="<?= esc_attr($pastilla) ?>"><?= esc_html($opcion['etiqueta']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <button class="sr-only focus:not-sr-only focus:mt-3 focus:rounded-full focus:bg-marca focus:px-4.5 focus:py-2 focus:text-label-s focus:text-fondo" type="submit"><?= esc_html__('Filtrar', 'angelitozzz') ?></button>
            </form>
        <?php endif; ?>

        <p class="sr-only" data-query="total" aria-live="polite"><?= esc_html(angelitozzz_query_total()['texto']) ?></p>

        <div class="mt-4 flex w-full flex-col gap-6" data-query="resultados">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post();
                    get_template_part('template-parts/components/testimonio-card', null, ['post_id' => get_the_ID()]);
                endwhile; ?>
            <?php else : ?>
                <p class="text-center text-body text-marca-suave"><?= esc_html__('Aún no hay testimonios en esta categoría.', 'angelitozzz') ?></p>
            <?php endif; ?>
        </div>

        <div data-query="paginacion">
            <?php angelitozzz_query_pagination([
                'prev_text' => '←',
                'next_text' => '→',
                'class'     => 'paginacion',
            ]); ?>
        </div>
    </div>
</section>
