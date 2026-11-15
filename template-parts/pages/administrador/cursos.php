<?php

/**
 * Administrador · cursos (specs/admin-cursos.md, Figma 4184:29718): todos los cursos (publicados y
 * borradores) en tarjetas curso-admin-card, más recientes primero, 12 por página. Las acciones de
 * cada tarjeta (publicar, borrador, eliminar) van por la API REST del plugin con admin-cursos.js.
 * Aviso "¡Curso publicado!" al volver del constructor (?publicado=<id>) o al publicar desde el menú.
 *
 * $args: los de angelitozzz_cursos_vista_admin() (usa 'pagina').
 */

defined('ABSPATH') || exit;

$pagina = max(1, (int) ($args['pagina'] ?? 1));
$lista  = angelitozzz_cursos_admin_lista(['pagina' => $pagina]);
$cursos = (array) ($lista['cursos'] ?? []);

// Aviso de publicado al volver del constructor: solo si el curso existe y está publicado.
$publicado = isset($_GET['publicado']) ? absint($_GET['publicado']) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Solo elige qué aviso mostrar.
$aviso     = '';
if ($publicado && get_post_type($publicado) === 'curso' && get_post_status($publicado) === 'publish' && function_exists('angelitozzz_cursos_aviso_publicado')) {
    $aviso = angelitozzz_cursos_aviso_publicado($publicado);
}
?>
<section class="mt-[37px] flex flex-col gap-8 max-md:mt-0" aria-labelledby="admin-cursos-titulo"
    data-admin-cursos
    data-api="<?= esc_url(rest_url('angelitozzz/v1/cursos/')) ?>"
    data-nonce="<?= esc_attr(wp_create_nonce('wp_rest')) ?>"
    data-error-generico="<?= esc_attr__('No pudimos guardar el cambio. Revisa tu conexión y vuelve a intentarlo.', 'angelitozzz') ?>">
    <h2 id="admin-cursos-titulo" class="sr-only"><?= esc_html__('Cursos', 'angelitozzz') ?></h2>

    <div class="empty:hidden rounded-[15px] border border-acento bg-blanco p-5 text-cuerpo text-acento" role="alert" data-admin-error></div>

    <?php if ($cursos) : ?>
        <ul class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-[repeat(3,minmax(0,355px))]" data-admin-cursos-lista>
            <?php foreach ($cursos as $curso) : ?>
                <li>
                    <?php get_template_part('template-parts/components/curso-admin-card', null, ['datos' => $curso]); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ((int) ($lista['paginas'] ?? 1) > 1) : ?>
            <nav class="paginacion" aria-label="<?= esc_attr__('Páginas de cursos', 'angelitozzz') ?>">
                <?php for ($n = 1; $n <= (int) $lista['paginas']; $n++) : ?>
                    <?php if ($n === $pagina) : ?>
                        <span class="page-numbers current" aria-current="page"><?= esc_html((string) $n) ?></span>
                    <?php else : ?>
                        <a class="page-numbers" href="<?= esc_url(add_query_arg('pagina', $n, angelitozzz_cursos_url_admin('cursos'))) ?>">
                            <span class="sr-only"><?= esc_html__('Página', 'angelitozzz') ?> </span><?= esc_html((string) $n) ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <div class="<?= esc_attr('flex flex-col items-start gap-5 rounded-[15px] bg-blanco p-8' . ($cursos ? ' hidden' : '')) ?>" data-admin-cursos-vacio>
        <p class="text-cuerpo-l text-tinta"><?= esc_html__('Aún no tienes cursos', 'angelitozzz') ?></p>
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace' => ['url' => angelitozzz_cursos_url_admin('nuevo'), 'title' => __('Crear un curso', 'angelitozzz')],
        ]); ?>
    </div>

    <?php // Aviso (Figma 3687:4671). La región role="status" está siempre en la página para que el lector anuncie el texto nuevo. ?>
    <div class="pointer-events-none fixed right-6 top-6 z-50 max-md:inset-x-4 max-md:bottom-4 max-md:top-auto" role="status" data-admin-aviso>
        <div class="<?= esc_attr('pointer-events-auto items-center gap-[14px] rounded-[14px] bg-marca px-[22px] py-4 text-fondo shadow-[0_12px_32px_#00000024] ' . ($aviso !== '' ? 'flex' : 'hidden')) ?>" data-admin-aviso-tarjeta>
            <?= angelitozzz_svg('administrador/aviso-check', 'size-[22px] shrink-0') // SVG fijo del tema. ?>
            <p class="text-rotulo md:max-w-[520px]" data-admin-aviso-texto><?= esc_html($aviso) ?></p>
            <button class="ml-auto flex size-8 shrink-0 cursor-pointer items-center justify-center rounded-full text-etiqueta hover:text-fondo focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-fondo" type="button"
                aria-label="<?= esc_attr__('Cerrar aviso', 'angelitozzz') ?>" data-admin-aviso-cerrar>
                <?= angelitozzz_svg('administrador/cerrar', 'size-[18px]') // SVG fijo del tema. ?>
            </button>
        </div>
    </div>
</section>

<?php get_template_part('template-parts/modals/eliminar'); ?>
