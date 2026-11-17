<?php

/**
 * Soluciones de sueño · shop (specs/soluciones-de-sueno.md, grupo soluciones_de_sueno_shop).
 *
 * Encabezado de la página de opciones "Archivo de solucion" y tarjetas del bucle principal de `solucion`
 * (wp-query: 12 por página y "Ver más" que carga las siguientes sin recargar; sin JS, lleva a /page/2/).
 * Sin filtros, orden ni contador visible (nota del usuario): el total queda solo para lectores de pantalla.
 */

defined('ABSPATH') || exit;

$shop   = get_field('soluciones_de_sueno_shop', 'option') ?: [];
$titulo = (string) ($shop['titulo'] ?? '');
$bajada = (string) ($shop['bajada'] ?? '');

// Primera pantalla: las 3 primeras tarjetas de la primera página se cargan de inmediato.
$inmediatas = is_paged() ? 0 : 3;
?>
<section class="px-4 py-22 max-md:py-14" aria-labelledby="soluciones-de-sueno-shop-titulo">
    <div class="mx-auto flex max-w-[451px] flex-col gap-6 text-center max-md:max-w-none">
        <h1 id="soluciones-de-sueno-shop-titulo" class="text-h1 text-marca max-md:text-h3"><?= esc_html($titulo) ?></h1>
        <?php if (trim($bajada) !== '') : ?>
            <p class="text-cuerpo-l text-tinta"><?= angelitozzz_highlight($bajada) ?></p>
        <?php endif; ?>
    </div>

    <div class="mx-auto mt-18 max-w-[1103px]">
        <h2 class="sr-only"><?= esc_html__('Todos los cursos', 'angelitozzz') ?></h2>
        <p class="sr-only" data-query="total" aria-live="polite"><?= esc_html(angelitozzz_query_total()['texto']) ?></p>

        <div data-query="resultados">
            <?php if (have_posts()) : ?>
                <ul class="grid grid-cols-1 justify-items-center gap-y-14 md:grid-cols-2 md:gap-x-8 md:gap-y-20 lg:grid-cols-[repeat(3,346px)] lg:justify-between [&>li]:w-full [&>li]:max-w-[346px]" data-query-items>
                    <?php $i = 0; ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <li class="flex">
                            <?php get_template_part('template-parts/components/producto-card', null, angelitozzz_solucion_tarjeta_args(get_the_ID()) + [
                                'loading' => $i++ < $inmediatas ? 'eager' : 'lazy',
                            ]); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <p class="text-center text-cuerpo-l text-tinta"><?= esc_html__('Pronto tendremos nuevas soluciones.', 'angelitozzz') ?></p>
            <?php endif; ?>
        </div>

        <div data-query="paginacion">
            <?php angelitozzz_query_pagination([
                'more_text' => __('Ver más', 'angelitozzz'),
                // El enlace del motor con el aspecto del botón `boton` variante secundario, sin flecha.
                'class'     => 'mt-20 flex justify-center [&>a]:inline-flex [&>a]:items-center [&>a]:gap-[10px] [&>a]:rounded-full [&>a]:border-[1.5px] [&>a]:border-marca [&>a]:py-[14px] [&>a]:px-[28px] [&>a]:text-rotulo [&>a]:whitespace-nowrap [&>a]:text-marca [&>a]:focus-visible:outline-2 [&>a]:focus-visible:outline-offset-2 [&>a]:focus-visible:outline-marca',
            ]); ?>
        </div>
    </div>
</section>
