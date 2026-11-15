<?php

/**
 * Constructor · paso 3 "Publicar" (specs/admin-constructor.md → paso-publicar; Figma 4186:31920 con la
 * decisión del usuario: sin "Qué aprenderás", "Requisitos", "Para quién es" ni "Duración total").
 * Tarjetas "Resumen del curso" (cada dato con "Editar" a su paso), "Antes de publicar" (lo que falta,
 * de angelitozzz_cursos_pendientes_publicar()) y "Visibilidad".
 *
 * Publicar o pasar a borrador va por la API del plugin desde src/js/modules/constructor-publicar.js
 * ([data-constructor-publicar]): POST cursos/<id>/estado. El servidor también comprueba lo que falta.
 *
 * $args: curso (array de angelitozzz_cursos_admin_curso()), curso_id (int).
 */

defined('ABSPATH') || exit;

$curso    = (array) ($args['curso'] ?? []);
$curso_id = (int) ($args['curso_id'] ?? 0);

$tipos = [
    'curso_online' => __('Curso online', 'angelitozzz'),
    'asesoria'     => __('Asesoría personalizada', 'angelitozzz'),
    'suscripcion'  => __('Suscripción', 'angelitozzz'),
];

$duracion = (int) ($curso['duracion'] ?? 0);
$acceso   = $duracion > 0
    /* translators: %d: meses de acceso. */
    ? sprintf(_n('%d mes', '%d meses', $duracion, 'angelitozzz'), $duracion)
    : __('Acceso ilimitado', 'angelitozzz');

$moneda = static function (string $valor): string {
    if ($valor === '') {
        return '';
    }
    return function_exists('wc_price')
        ? html_entity_decode(wp_strip_all_tags(wc_price((float) $valor)), ENT_QUOTES, 'UTF-8')
        : '$' . number_format_i18n((float) $valor, 2);
};

$precio      = $moneda((string) ($curso['precio'] ?? ''));
$lanzamiento = $moneda((string) ($curso['precio_lanzamiento'] ?? ''));
$totales     = (array) ($curso['totales'] ?? []);
$pendientes  = (array) ($curso['pendientes'] ?? []);
$publicado   = ($curso['estado'] ?? '') === 'publicado';
$bloquean    = array_filter($pendientes, static fn($p): bool => !empty($p['bloquea']));

$url_paso_1 = angelitozzz_cursos_url_admin('editar', $curso_id, 1);
$url_paso_2 = angelitozzz_cursos_url_admin('editar', $curso_id, 2);
$sin_dato   = __('Sin completar', 'angelitozzz');

// Filas del resumen: etiqueta, valor y el paso donde se edita.
$filas = [
    ['etiqueta' => __('Título', 'angelitozzz'), 'valor' => (string) ($curso['titulo'] ?? ''), 'paso' => 1],
    ['etiqueta' => __('Categoría (edad)', 'angelitozzz'), 'valor' => (string) ($curso['edad'] ?? ''), 'paso' => 1],
    ['etiqueta' => __('Tipo', 'angelitozzz'), 'valor' => $tipos[$curso['tipo'] ?? ''] ?? '', 'paso' => 1],
    ['etiqueta' => __('Precio', 'angelitozzz'), 'valor' => $precio, 'paso' => 1],
    ['etiqueta' => __('Precio de lanzamiento', 'angelitozzz'), 'valor' => $lanzamiento !== '' ? $lanzamiento : __('Sin precio de lanzamiento', 'angelitozzz'), 'paso' => 1],
    ['etiqueta' => __('Duración del acceso', 'angelitozzz'), 'valor' => $acceso, 'paso' => 1],
    ['etiqueta' => __('Incluye mes gratis en Mundo', 'angelitozzz'), 'valor' => !empty($curso['mes_mundo']) ? __('Sí', 'angelitozzz') : __('No', 'angelitozzz'), 'paso' => 1],
    [
        'etiqueta' => __('Contenido', 'angelitozzz'),
        'valor'    => implode(' · ', array_filter([
            /* translators: %d: número de módulos. */
            sprintf(_n('%d módulo', '%d módulos', (int) ($totales['modulos'] ?? 0), 'angelitozzz'), (int) ($totales['modulos'] ?? 0)),
            /* translators: %d: número de lecciones. */
            sprintf(_n('%d lección', '%d lecciones', (int) ($totales['lecciones'] ?? 0), 'angelitozzz'), (int) ($totales['lecciones'] ?? 0)),
            (string) ($totales['duracion_texto'] ?? '') !== ''
                /* translators: %s: duración total (h:mm:ss o m:ss). */
                ? sprintf(__('Duración total %s', 'angelitozzz'), (string) $totales['duracion_texto'])
                : '',
        ])),
        'paso'     => 2,
    ],
];

$tarjeta  = 'flex flex-col gap-6 rounded-[20px] bg-blanco p-8 max-md:p-5';
$titulo_t = 'text-h5 text-marca';
$editar   = 'shrink-0 text-rotulo-s text-acento underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
$portada  = (string) ($curso['portada_url'] ?? '');
?>
<form class="flex flex-col gap-[30px] max-md:gap-6" novalidate
    data-constructor-publicar
    data-texto-publicar="<?= esc_attr__('Publicar curso', 'angelitozzz') ?>"
    data-texto-borrador="<?= esc_attr__('Guardar como borrador', 'angelitozzz') ?>"
    data-guardado-borrador="<?= esc_attr__('Guardado como borrador', 'angelitozzz') ?>">

    <div class="empty:hidden rounded-[15px] border border-acento bg-blanco p-5 text-cuerpo text-acento" role="alert" tabindex="-1" data-errores></div>

    <section class="<?= esc_attr($tarjeta) ?>" aria-labelledby="constructor-resumen-titulo">
        <h2 id="constructor-resumen-titulo" class="<?= esc_attr($titulo_t) ?>"><?= esc_html__('Resumen del curso', 'angelitozzz') ?></h2>

        <div class="flex items-center gap-5 max-sm:flex-col max-sm:items-start">
            <?php if ($portada !== '') : ?>
                <img class="aspect-[3/2] w-[180px] shrink-0 rounded-[12px] object-cover" src="<?= esc_url($portada) ?>" alt="" loading="lazy">
            <?php else : ?>
                <span class="flex aspect-[3/2] w-[180px] shrink-0 items-center justify-center rounded-[12px] bg-fondo text-caption text-tinta-suave"><?= esc_html__('Sin portada', 'angelitozzz') ?></span>
            <?php endif; ?>
            <a class="<?= esc_attr($editar) ?>" href="<?= esc_url($url_paso_1) ?>">
                <?= esc_html__('Editar', 'angelitozzz') ?><span class="sr-only"> <?= esc_html__('portada', 'angelitozzz') ?></span>
            </a>
        </div>

        <dl class="divide-y divide-neutro border-y border-neutro">
            <?php foreach ($filas as $fila) : ?>
                <div class="flex items-start justify-between gap-4 py-3 max-sm:flex-col max-sm:gap-1">
                    <dt class="w-[220px] shrink-0 text-rotulo text-marca max-sm:w-auto"><?= esc_html($fila['etiqueta']) ?></dt>
                    <dd class="flex flex-1 items-start justify-between gap-4 max-sm:w-full">
                        <span class="<?= esc_attr('text-cuerpo ' . ($fila['valor'] !== '' ? 'text-tinta' : 'text-tinta-suave')) ?>"><?= esc_html($fila['valor'] !== '' ? $fila['valor'] : $sin_dato) ?></span>
                        <a class="<?= esc_attr($editar) ?>" href="<?= esc_url($fila['paso'] === 2 ? $url_paso_2 : $url_paso_1) ?>">
                            <?= esc_html__('Editar', 'angelitozzz') ?><span class="sr-only"> <?= esc_html(mb_strtolower($fila['etiqueta'])) ?></span>
                        </a>
                    </dd>
                </div>
            <?php endforeach; ?>
        </dl>
    </section>

    <section class="<?= esc_attr($tarjeta) ?>" aria-labelledby="constructor-pendientes-titulo">
        <h2 id="constructor-pendientes-titulo" class="<?= esc_attr($titulo_t) ?>"><?= esc_html__('Antes de publicar', 'angelitozzz') ?></h2>

        <?php if (!$pendientes) : ?>
            <p class="flex items-center gap-3 text-cuerpo text-marca">
                <?= angelitozzz_svg('administrador/aviso-check', 'size-[22px] shrink-0 rounded-full bg-marca text-fondo') // SVG fijo del tema. ?>
                <span><?= esc_html__('Todo listo para publicar.', 'angelitozzz') ?></span>
            </p>
        <?php else : ?>
            <ul class="flex flex-col gap-3">
                <?php foreach ($pendientes as $pendiente) :
                    $bloquea = !empty($pendiente['bloquea']);
                    $paso    = in_array($pendiente['clave'] ?? '', ['leccion'], true) ? 2 : 1;
                    ?>
                    <li class="<?= esc_attr('flex items-start gap-3 rounded-[12px] border p-4 ' . ($bloquea ? 'border-acento bg-blanco' : 'border-neutro bg-fondo')) ?>">
                        <span class="<?= esc_attr('flex size-6 shrink-0 items-center justify-center rounded-full border-2 text-rotulo-s ' . ($bloquea ? 'border-acento text-acento' : 'border-terracota text-terracota')) ?>" aria-hidden="true">!</span>
                        <span class="flex flex-1 flex-col gap-1">
                            <span class="text-rotulo-s <?= esc_attr($bloquea ? 'text-acento' : 'text-tinta-suave') ?>"><?= esc_html($bloquea ? __('Falta para publicar', 'angelitozzz') : __('Aviso: no impide publicar', 'angelitozzz')) ?></span>
                            <span class="text-cuerpo text-tinta"><?= esc_html((string) ($pendiente['texto'] ?? '')) ?></span>
                        </span>
                        <?php if (($pendiente['clave'] ?? '') !== 'solucion') : ?>
                            <a class="<?= esc_attr($editar . ' self-center') ?>" href="<?= esc_url($paso === 2 ? $url_paso_2 : $url_paso_1) ?>">
                                <?= esc_html__('Completar', 'angelitozzz') ?><span class="sr-only">: <?= esc_html((string) ($pendiente['texto'] ?? '')) ?></span>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <section class="<?= esc_attr($tarjeta) ?>">
        <fieldset class="flex flex-col gap-4">
            <legend class="<?= esc_attr($titulo_t . ' mb-4') ?>"><?= esc_html__('Visibilidad', 'angelitozzz') ?></legend>
            <?php foreach (['publicado' => __('Publicado — visible en Soluciones de Sueño y a la venta', 'angelitozzz'), 'borrador' => __('Borrador — solo visible para administradores', 'angelitozzz')] as $valor => $texto) : ?>
                <label class="flex cursor-pointer items-center gap-3 text-cuerpo text-tinta">
                    <input class="size-6 shrink-0 cursor-pointer accent-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca" type="radio" name="visibilidad" value="<?= esc_attr($valor) ?>"<?= checked($publicado ? 'publicado' : 'borrador', $valor, false) ?>>
                    <span><?= esc_html($texto) ?></span>
                </label>
            <?php endforeach; ?>
        </fieldset>
        <?php if ($bloquean) : ?>
            <p class="text-caption text-tinta-suave"><?= esc_html__('Para publicar, completa primero lo que falta en "Antes de publicar".', 'angelitozzz') ?></p>
        <?php endif; ?>
    </section>

    <div class="flex flex-wrap items-center justify-end gap-4 max-md:flex-col-reverse max-md:[&>*]:w-full max-md:[&>*]:justify-center">
        <p class="mr-auto text-cuerpo text-marca empty:hidden max-md:mr-0 max-md:text-center" role="status" data-estado></p>
        <?php get_template_part('template-parts/components/boton', null, [
            'enlace'   => ['url' => $url_paso_2, 'title' => __('Volver a contenido', 'angelitozzz')],
            'variante' => 'secundario',
            'flecha'   => false,
        ]); ?>
        <?php get_template_part('template-parts/components/boton', null, [
            'texto'  => $publicado ? __('Publicar curso', 'angelitozzz') : __('Guardar como borrador', 'angelitozzz'),
            'enviar' => true,
            'datos'  => ['data-principal' => '1'],
        ]); ?>
    </div>
</form>
