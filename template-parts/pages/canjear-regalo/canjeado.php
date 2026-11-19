<?php

/**
 * Canjear regalo · canjeado (specs/canjear-regalo.md, Figma 3677:1971): confirmación del canje.
 *
 * $args (último canje de la usuaria, dato de un solo uso del plugin angelitozzz-cursos, F5-c):
 * - curso           (string, requerido: nombre del curso)
 * - meses           (int: 4, 6 o 12; 0 = acceso ilimitado)
 * - primera_leccion (string: URL de la primera lección)
 * - cursos_url      (string: Mis cursos)
 */

defined('ABSPATH') || exit;

$curso  = trim((string) ($args['curso'] ?? ''));
$meses  = (int) ($args['meses'] ?? 0);
$leccion = (string) ($args['primera_leccion'] ?? '');
$cursos = (string) ($args['cursos_url'] ?? home_url('/cuenta/cursos/'));

if ($curso === '') {
    return;
}

$acceso = $meses > 0
    /* translators: %d: meses de acceso al curso */
    ? sprintf(_n('Tu acceso de %d mes empieza hoy.', 'Tu acceso de %d meses empieza hoy.', $meses, 'angelitozzz'), $meses)
    : __('Tu acceso ilimitado empieza hoy.', 'angelitozzz');

get_template_part('template-parts/components/confirmacion-tarjeta', null, [
    'titulo'   => __('¡Listo, ya tienes tu curso!', 'angelitozzz'),
    'icono'    => 'check',
    'pastilla' => __('✓ Regalo canjeado', 'angelitozzz'),
    /* translators: %s: nombre del curso */
    'texto'    => sprintf(__('%s ya está en tu cuenta. Puedes empezar ahora o encontrarlo cuando quieras en “Mis cursos”.', 'angelitozzz'), $curso),
]);
?>
<p class="w-full rounded-xl bg-fondo px-5 py-[14px] text-cuerpo text-marca"><?= esc_html($acceso) ?></p>

<div class="flex flex-col items-center gap-[18px]">
    <?php
    get_template_part('template-parts/components/boton', null, [
        'enlace' => ['url' => $leccion, 'title' => __('Empezar el curso', 'angelitozzz')],
    ]);
    echo angelitozzz_link(
        ['url' => $cursos, 'title' => __('Ir a Mis cursos', 'angelitozzz')],
        ['class' => 'text-rotulo text-marca underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca']
    );
    ?>
</div>
<?php
get_template_part('template-parts/components/confirmacion-tarjeta-fin');
