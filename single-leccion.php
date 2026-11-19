<?php

/**
 * Lección (specs/leccion.md): temario del curso, video, banner de Mundo o mensaje de Angelita Pro,
 * y las pestañas Resumen y Materiales. La misma plantilla sirve a todas las lecciones.
 *
 * El acceso lo controla el plugin angelitozzz-cursos antes de imprimir (sin sesión → iniciar sesión;
 * sin inscripción → la solución; la vista previa se ve sin comprar). Los datos salen solo de sus
 * funciones públicas angelitozzz_cursos_*; esta plantilla solo pinta.
 */

defined('ABSPATH') || exit;

get_header();
the_post();

$leccion_id = (int) get_the_ID();
$curso_id   = (int) wp_get_post_parent_id($leccion_id);
$plugin     = function_exists('angelitozzz_cursos_temario');

$temario    = $plugin ? angelitozzz_cursos_temario($curso_id, $leccion_id) : [];
// Con inscripción vigente (o quien gestiona cursos): guarda avance y ve los PDF del curso. Una vista previa sin compra, no.
$con_acceso = $plugin && is_user_logged_in() && angelitozzz_cursos_puede_ver_curso($curso_id, get_current_user_id());
$primera    = $plugin && angelitozzz_cursos_es_primera_leccion($leccion_id);

// Datos del curso que guarda el constructor (tipo y "Mes gratis en Mundo"), leídos del plugin.
$datos_curso = $plugin && function_exists('angelitozzz_cursos_datos_curso') ? angelitozzz_cursos_datos_curso($curso_id) : [];
$tipo_curso  = (string) ($datos_curso['tipo'] ?? '');
$mes_mundo   = !empty($datos_curso['mes_mundo']);

$completada = false;
foreach ($temario as $modulo) {
    foreach ((array) ($modulo['lecciones'] ?? []) as $fila) {
        if ((int) ($fila['id'] ?? 0) === $leccion_id) {
            $completada = !empty($fila['completada']);
        }
    }
}

$args_temario = ['temario' => $temario, 'con_acceso' => $con_acceso];
?>

<main id="main-content" class="bg-fondo">
    <div class="mx-auto grid max-w-[1551px] gap-x-[60px] gap-y-8 px-4 py-30 max-lg:py-10 lg:grid-cols-[458px_minmax(0,1033px)]">
        <div class="flex min-w-0 flex-col gap-8 lg:col-start-2 lg:row-start-1">
            <?php
            get_template_part('template-parts/pages/leccion/cabecera', null, [
                'leccion_id' => $leccion_id,
                'curso_id'   => $curso_id,
                'con_acceso' => $con_acceso,
                'completada' => $completada,
            ]);
            ?>

            <div class="lg:hidden">
                <?php get_template_part('template-parts/accordions/leccion-temario', null, $args_temario); ?>
            </div>

            <?php
            if ($primera && $tipo_curso === 'suscripcion') {
                get_template_part('template-parts/pages/leccion/mensaje-angelita-pro');
            }

            get_template_part('template-parts/pages/leccion/video', null, ['leccion_id' => $leccion_id]);

            if ($primera && $mes_mundo) {
                get_template_part('template-parts/components/banner-mundo', null, ['variante' => 'leccion']);
            }

            get_template_part('template-parts/pages/leccion/pestanas', null, ['leccion_id' => $leccion_id]);
            ?>
        </div>

        <aside class="max-lg:hidden lg:col-start-1 lg:row-start-1" aria-label="<?= esc_attr__('Contenido del curso', 'angelitozzz') ?>">
            <?php get_template_part('template-parts/pages/leccion/temario', null, $args_temario + ['encabezado' => true]); ?>
        </aside>
    </div>
</main>

<?php
get_footer();
