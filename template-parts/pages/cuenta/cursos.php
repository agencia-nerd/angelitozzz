<?php

/**
 * Cuenta · cursos inscritos (specs/usuario-cursos.md, Figma 4164:25480): pestañas por enlace
 * (?pestana=inscritos|activos|completados, por defecto activos como en el diseño) y la grilla de
 * tarjetas con el avance. Datos del plugin angelitozzz-cursos, siempre de la usuaria de la sesión.
 */

defined('ABSPATH') || exit;

$permitidas = ['inscritos', 'activos', 'completados'];
$pedida     = isset($_GET['pestana']) ? sanitize_key(wp_unslash($_GET['pestana'])) : '';
$pestana    = in_array($pedida, $permitidas, true) ? $pedida : 'activos';

$por_pagina = 9;
$pagina     = max(1, isset($_GET['pagina']) ? absint($_GET['pagina']) : 1);

$todos   = angelitozzz_cursos_de_usuaria('inscritos');
$resumen = angelitozzz_cursos_resumen();
$cursos  = $pestana === 'inscritos' ? $todos : angelitozzz_cursos_de_usuaria($pestana);
$paginas = max(1, (int) ceil(count($cursos) / $por_pagina));
$pagina  = min($pagina, $paginas);
$visibles = array_slice($cursos, ($pagina - 1) * $por_pagina, $por_pagina);

$base = angelitozzz_cursos_url_pagina('cuenta', 'cursos');
$url  = static fn(string $id): string => add_query_arg('pestana', $id, $base);

$pestanas = [
    ['id' => 'inscritos', 'texto' => __('Cursos inscritos', 'angelitozzz'), 'icono' => 'pestana-inscritos', 'url' => $url('inscritos'), 'numero' => (int) ($resumen['inscritos'] ?? 0)],
    ['id' => 'activos', 'texto' => __('Cursos activos', 'angelitozzz'), 'icono' => 'pestana-activos', 'url' => $url('activos'), 'numero' => (int) ($resumen['activos'] ?? 0)],
    ['id' => 'completados', 'texto' => __('Completados', 'angelitozzz'), 'icono' => 'pestana-completados', 'url' => $url('completados'), 'numero' => (int) ($resumen['completados'] ?? 0)],
];
?>
<section class="flex max-w-[1105px] flex-col gap-[50px] max-md:gap-8" aria-labelledby="cuenta-cursos-titulo">
    <h2 id="cuenta-cursos-titulo" class="sr-only"><?= esc_html__('Mis cursos', 'angelitozzz') ?></h2>

    <?php get_template_part('template-parts/components/cuenta-pestanas', null, [
        'pestanas' => $pestanas,
        'activa'   => $pestana,
        'modo'     => 'enlaces',
        'etiqueta' => __('Filtrar mis cursos', 'angelitozzz'),
    ]); ?>

    <?php if ($visibles) : ?>
        <ul class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($visibles as $curso) : ?>
                <li>
                    <?php get_template_part('template-parts/components/curso-progreso-card', null, [
                        'datos'    => $curso,
                        'variante' => 'vertical',
                    ]); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($paginas > 1) : ?>
            <nav class="paginacion" aria-label="<?= esc_attr__('Paginación', 'angelitozzz') ?>">
                <?= paginate_links([
                    'base'      => add_query_arg('pagina', '%#%', $url($pestana)),
                    'format'    => '',
                    'current'   => $pagina,
                    'total'     => $paginas,
                    'prev_text' => esc_html__('Anterior', 'angelitozzz'),
                    'next_text' => esc_html__('Siguiente', 'angelitozzz'),
                ]) // HTML de WordPress, ya escapado. ?>
            </nav>
        <?php endif; ?>
    <?php else : ?>
        <div class="flex flex-col items-start gap-5 rounded-[15px] bg-blanco p-8">
            <p class="text-cuerpo-l text-tinta"><?= esc_html__('Aún no tienes cursos aquí', 'angelitozzz') ?></p>
            <?php if ($pestana === 'inscritos') {
                get_template_part('template-parts/components/boton', null, [
                    'enlace' => ['url' => home_url('/soluciones-de-sueno/'), 'title' => __('Ver soluciones de sueño', 'angelitozzz')],
                ]);
            } ?>
        </div>
    <?php endif; ?>
</section>
