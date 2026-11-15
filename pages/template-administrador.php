<?php

/**
 * Template Name: Administrador de cursos
 *
 * Administrador de Bárbara (specs/admin-escritorio.md, admin-cursos.md, admin-constructor.md): el menú
 * lateral (cuenta-menu con menu "instructora") y la vista de la URL (/administrador/,
 * /administrador/cursos/, /administrador/cursos/nuevo/, /administrador/cursos/<id>/editar/). Las URL,
 * el acceso (capacidad angelitozzz_gestionar_cursos) y los datos son del plugin angelitozzz-cursos;
 * esta plantilla solo pinta (template-parts/pages/administrador/<vista>.php).
 */

defined('ABSPATH') || exit;

$plugin = function_exists('angelitozzz_cursos_vista_admin');
$datos  = $plugin ? angelitozzz_cursos_vista_admin() : ['vista' => 'escritorio', 'curso_id' => 0, 'paso' => 1, 'pagina' => 1];
$vista  = in_array($datos['vista'], ['escritorio', 'cursos', 'constructor'], true) ? $datos['vista'] : 'escritorio';

get_header();
?>

<main id="main-content" class="bg-fondo">
    <div class="mx-auto flex max-w-[1920px] gap-[60px] px-[135px] py-30 max-xl:px-10 max-lg:flex-col max-lg:gap-8 max-md:px-4 max-md:py-10">
        <?php if (!$plugin || !angelitozzz_cursos_puede_gestionar()) : ?>
            <h1 class="sr-only"><?= esc_html__('Administrador', 'angelitozzz') ?></h1>
            <p class="text-cuerpo text-tinta"><?= esc_html__('El administrador de cursos no está disponible por ahora. Vuelve a intentarlo más tarde.', 'angelitozzz') ?></p>
        <?php else : ?>
            <?php get_template_part('template-parts/components/cuenta-menu', null, [
                'menu'   => 'instructora',
                'activo' => $vista === 'escritorio' ? 'escritorio' : 'cursos',
            ]); ?>

            <div class="flex min-w-0 flex-1 flex-col gap-[13px] max-md:gap-6">
                <?php if ($vista !== 'constructor') : ?>
                    <?php // "Crear nuevo curso" (admin-escritorio.md y admin-cursos.md): tarjeta verde con el estilo de cuenta-pestanas. ?>
                    <a class="flex h-[94px] w-[173px] shrink-0 flex-col items-center justify-center gap-2 self-end rounded-[15px] bg-marca px-3 text-center text-cuerpo-l text-blanco hover:bg-marca-suave focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca max-md:w-full"
                        href="<?= esc_url(angelitozzz_cursos_url_admin('nuevo')) ?>">
                        <?= angelitozzz_svg('administrador/crear-curso', 'size-[35px]') // SVG fijo del tema. ?>
                        <span><?= esc_html__('Crear nuevo curso', 'angelitozzz') ?></span>
                    </a>
                <?php endif; ?>

                <?php get_template_part('template-parts/pages/administrador/' . $vista, null, $datos); ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
