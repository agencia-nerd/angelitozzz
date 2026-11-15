<?php

/**
 * Template Name: Mi cuenta
 *
 * Cuenta de la alumna (specs/usuario-escritorio.md, usuario-mi-perfil.md, usuario-cursos.md,
 * usuario-historial-pedido.md): el menú lateral y la vista de la URL (/cuenta/, /cuenta/perfil/,
 * /cuenta/cursos/, /cuenta/pedidos/). Los endpoints y la redirección sin sesión son del plugin
 * angelitozzz-cursos; esta plantilla solo pinta (template-parts/pages/cuenta/<vista>.php).
 */

defined('ABSPATH') || exit;

$plugin = function_exists('angelitozzz_cursos_activo');
$vista  = $plugin ? angelitozzz_cursos_vista_cuenta() : 'escritorio';

get_header();
?>

<main id="main-content" class="bg-fondo">
    <div class="mx-auto flex max-w-[1920px] gap-[60px] px-[135px] py-30 max-xl:px-10 max-lg:flex-col max-lg:gap-8 max-md:px-4 max-md:py-10">
        <?php if (!$plugin) : ?>
            <h1 class="sr-only"><?= esc_html__('Mi cuenta', 'angelitozzz') ?></h1>
            <p class="text-cuerpo text-tinta"><?= esc_html__('Tu cuenta no está disponible por ahora. Vuelve a intentarlo más tarde.', 'angelitozzz') ?></p>
        <?php else : ?>
            <?php get_template_part('template-parts/components/cuenta-menu', null, ['activo' => $vista]); ?>

            <div class="min-w-0 flex-1">
                <?php get_template_part('template-parts/pages/cuenta/' . $vista); ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
