<?php

/**
 * Administrador · escritorio (specs/admin-escritorio.md, Figma 4184:29180): "Resumen" con estudiantes,
 * cursos y ganancias, y la tabla "Cursos" con las inscritas de cada curso publicado. Datos del plugin
 * angelitozzz-cursos (exigen la capacidad angelitozzz_gestionar_cursos).
 */

defined('ABSPATH') || exit;

$resumen = angelitozzz_cursos_admin_resumen();
$lista   = angelitozzz_cursos_admin_lista(['estado' => 'publicados', 'orden' => 'titulo']);
$cursos  = (array) ($lista['cursos'] ?? []);

// El diseño solo trae el ícono de estudiantes; las otras dos tarjetas quedan sin ícono (spec).
$tarjetas = [
    ['numero' => number_format_i18n((int) ($resumen['estudiantes'] ?? 0)), 'texto' => __('Estudiantes en total', 'angelitozzz'), 'icono' => 'administrador/resumen-estudiantes'],
    ['numero' => number_format_i18n((int) ($resumen['cursos'] ?? 0)), 'texto' => __('Total de cursos', 'angelitozzz'), 'icono' => ''],
    ['numero' => (string) ($resumen['ganancias_texto'] ?? ''), 'texto' => __('Ganancias totales', 'angelitozzz'), 'icono' => ''],
];
?>
<div class="flex max-w-[1108px] flex-col gap-[49px] max-md:gap-10">
    <section class="flex flex-col gap-[28px]" aria-labelledby="admin-resumen-titulo">
        <h2 id="admin-resumen-titulo" class="text-cuerpo-xl text-marca"><?= esc_html__('Resumen', 'angelitozzz') ?></h2>
        <ul class="grid grid-cols-1 gap-5 md:grid-cols-3 lg:grid-cols-[repeat(3,minmax(0,355px))]">
            <?php foreach ($tarjetas as $tarjeta) : ?>
                <li class="flex min-h-[147px] items-center justify-between gap-4 rounded-[15px] bg-blanco py-5 pl-5 pr-[30px]">
                    <span class="flex min-w-0 flex-col gap-[5px]">
                        <span class="text-h1 break-words text-marca max-xl:text-h3"><?= esc_html($tarjeta['numero']) ?></span>
                        <span class="text-cuerpo-l text-tinta"><?= esc_html($tarjeta['texto']) ?></span>
                    </span>
                    <?php if ($tarjeta['icono'] !== '') : ?>
                        <?= angelitozzz_svg($tarjeta['icono'], 'h-[40px] w-[49px] shrink-0 text-contorno') // SVG fijo del tema. ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="flex flex-col gap-[28px]" aria-labelledby="admin-cursos-titulo">
        <h2 id="admin-cursos-titulo" class="text-cuerpo-xl text-marca"><?= esc_html__('Cursos', 'angelitozzz') ?></h2>

        <?php if ($cursos) : ?>
            <table class="w-full border-separate border-spacing-0 text-left max-md:block md:[&_tbody_tr:last-child_td:first-child]:rounded-bl-[15px] md:[&_tbody_tr:last-child_td:last-child]:rounded-br-[15px]">
                <caption class="sr-only"><?= esc_html__('Cursos publicados y sus estudiantes inscritos', 'angelitozzz') ?></caption>
                <thead class="max-md:sr-only">
                    <tr>
                        <th scope="col" class="h-[60px] w-[45%] rounded-tl-[15px] border-b-[1.5px] border-[#c1c1c1] bg-blanco px-[26px] text-h5 font-medium text-marca"><?= esc_html__('Curso', 'angelitozzz') ?></th>
                        <th scope="col" class="h-[60px] rounded-tr-[15px] border-b-[1.5px] border-[#c1c1c1] bg-blanco px-[26px] text-h5 font-medium text-marca"><?= esc_html__('Estudiantes inscritos', 'angelitozzz') ?></th>
                    </tr>
                </thead>
                <tbody class="max-md:block">
                    <?php foreach ($cursos as $curso) : ?>
                        <tr class="max-md:mb-3 max-md:flex max-md:flex-col max-md:gap-1 max-md:rounded-[15px] max-md:bg-blanco max-md:p-5">
                            <td class="h-[60px] border-b-[1.5px] border-[#c1c1c1] bg-blanco px-[26px] text-cuerpo-l text-tinta max-md:h-auto max-md:border-0 max-md:p-0">
                                <a class="underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" href="<?= esc_url((string) $curso['url_editar']) ?>">
                                    <?= esc_html((string) $curso['titulo']) ?>
                                </a>
                            </td>
                            <td class="h-[60px] border-b-[1.5px] border-[#c1c1c1] bg-blanco px-[26px] text-cuerpo-l text-tinta max-md:h-auto max-md:border-0 max-md:p-0 max-md:text-cuerpo max-md:text-tinta-suave">
                                <span class="md:sr-only"><?= esc_html__('Estudiantes inscritos:', 'angelitozzz') ?> </span><?= esc_html(number_format_i18n((int) $curso['estudiantes'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="flex flex-col items-start gap-5 rounded-[15px] bg-blanco p-8">
                <p class="text-cuerpo-l text-tinta"><?= esc_html__('Aún no tienes cursos', 'angelitozzz') ?></p>
                <?php get_template_part('template-parts/components/boton', null, [
                    'enlace' => ['url' => angelitozzz_cursos_url_admin('nuevo'), 'title' => __('Crear un curso', 'angelitozzz')],
                ]); ?>
            </div>
        <?php endif; ?>
    </section>
</div>
