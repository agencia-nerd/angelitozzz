<?php

/**
 * Administrador · constructor de cursos (specs/admin-constructor.md). Se construye en el bloque 6b de
 * la F4; mientras tanto, solo el encabezado y un aviso.
 */

defined('ABSPATH') || exit;

$curso_id = (int) ($args['curso_id'] ?? 0);
$paso     = (int) ($args['paso'] ?? 1);
?>
<div class="flex max-w-[1108px] flex-col gap-6">
    <h2 class="text-h3 text-marca"><?= esc_html(angelitozzz_titulo_paso_constructor($curso_id, $paso)) ?></h2>
    <p class="text-cuerpo text-tinta"><?= esc_html__('El constructor de cursos estará disponible muy pronto.', 'angelitozzz') ?></p>
    <?php get_template_part('template-parts/components/boton', null, [
        'enlace'   => ['url' => angelitozzz_cursos_url_admin('cursos'), 'title' => __('Volver a cursos', 'angelitozzz')],
        'variante' => 'secundario',
    ]); ?>
</div>
