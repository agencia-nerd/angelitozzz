<?php

/**
 * Cuenta · escritorio (specs/usuario-escritorio.md, Figma 4164:25045): "Resumen" con los conteos y
 * "Mis cursos" con hasta 3 cursos en progreso. Datos del plugin angelitozzz-cursos, siempre de la
 * usuaria de la sesión.
 */

defined('ABSPATH') || exit;

$resumen = angelitozzz_cursos_resumen();
$cursos  = array_slice(angelitozzz_cursos_de_usuaria('activos'), 0, 3);

$tarjetas = [
    ['numero' => (int) ($resumen['inscritos'] ?? 0), 'texto' => __('Cursos inscritos', 'angelitozzz')],
    ['numero' => (int) ($resumen['completados'] ?? 0), 'texto' => __('Cursos completados', 'angelitozzz')],
    ['numero' => (int) ($resumen['activos'] ?? 0), 'texto' => __('Cursos en progreso', 'angelitozzz')],
];
?>
<div class="flex max-w-[1212px] flex-col gap-[50px] max-md:gap-10">
    <section class="flex flex-col gap-[28px]" aria-labelledby="cuenta-resumen-titulo">
        <h2 id="cuenta-resumen-titulo" class="text-cuerpo-xl text-marca"><?= esc_html__('Resumen', 'angelitozzz') ?></h2>
        <ul class="grid grid-cols-1 gap-5 md:grid-cols-3 lg:grid-cols-[repeat(3,minmax(0,355px))]">
            <?php foreach ($tarjetas as $tarjeta) : ?>
                <li class="flex min-h-[147px] flex-col justify-center gap-[5px] rounded-[15px] bg-blanco py-5 pl-5 pr-[30px]">
                    <span class="text-h1 text-marca"><?= esc_html((string) $tarjeta['numero']) ?></span>
                    <span class="text-cuerpo-l text-tinta"><?= esc_html($tarjeta['texto']) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="flex flex-col gap-[43px] max-md:gap-6" aria-labelledby="cuenta-cursos-titulo">
        <h2 id="cuenta-cursos-titulo" class="text-cuerpo-xl text-marca"><?= esc_html__('Mis cursos', 'angelitozzz') ?></h2>

        <?php if ($cursos) : ?>
            <ul class="flex flex-col gap-5">
                <?php foreach ($cursos as $curso) : ?>
                    <li>
                        <?php get_template_part('template-parts/components/curso-progreso-card', null, [
                            'datos'    => $curso,
                            'variante' => 'horizontal',
                        ]); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <div class="flex flex-col items-start gap-5 rounded-[15px] bg-blanco p-8">
                <p class="text-cuerpo-l text-tinta"><?= esc_html__('Aún no tienes cursos', 'angelitozzz') ?></p>
                <?php get_template_part('template-parts/components/boton', null, [
                    'enlace' => ['url' => home_url('/soluciones-de-sueno/'), 'title' => __('Ver soluciones de sueño', 'angelitozzz')],
                ]); ?>
            </div>
        <?php endif; ?>
    </section>
</div>
