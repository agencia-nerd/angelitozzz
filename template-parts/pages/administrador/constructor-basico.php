<?php

/**
 * Constructor · paso 1 "Básico" (specs/admin-constructor.md → paso-basico, Figma 4185:30609): tarjetas
 * "Información del curso" e "Imagen de portada" y los botones "Guardar borrador" y "Continuar:
 * agregar contenido". Sin "Estado": la visibilidad se elige en el paso 3.
 *
 * Todo se guarda por la API del plugin desde src/js/modules/constructor-basico.js ([data-constructor-basico]):
 * POST cursos (nuevo) o cursos/<id>, y la portada aparte (POST|DELETE cursos/<id>/portada). La
 * validación del navegador es solo ayuda: el servidor devuelve los errores por campo (data.campos).
 *
 * $args: curso (array de angelitozzz_cursos_admin_curso()), curso_id (int, 0 = nuevo).
 */

defined('ABSPATH') || exit;

$curso    = (array) ($args['curso'] ?? []);
$curso_id = (int) ($args['curso_id'] ?? 0);

$tipos = [
    'curso_online' => __('Curso online', 'angelitozzz'),
    'asesoria'     => __('Asesoría personalizada', 'angelitozzz'),
    'suscripcion'  => __('Suscripción', 'angelitozzz'),
];
$duraciones = [
    4  => __('4 meses', 'angelitozzz'),
    6  => __('6 meses', 'angelitozzz'),
    12 => __('12 meses', 'angelitozzz'),
    0  => __('Acceso ilimitado', 'angelitozzz'),
];

$tipo        = (string) ($curso['tipo'] ?? 'curso_online');
$duracion    = (int) ($curso['duracion'] ?? 4);
$portada_url = (string) ($curso['portada_url'] ?? '');

$tarjeta  = 'flex flex-col gap-6 rounded-[20px] bg-blanco p-8 max-md:p-5';
$titulo_t = 'text-h5 text-marca';
$campo    = 'w-full rounded-[12px] border border-neutro bg-fondo px-4 py-3 text-cuerpo text-tinta placeholder:text-tinta-suave focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca aria-invalid:border-acento';
$etiqueta = 'text-rotulo text-marca';
$ayuda    = 'text-caption text-tinta-suave';
$error    = 'text-caption text-acento empty:hidden';
$select   = $campo . ' cursor-pointer appearance-none pr-10';

// Select con su flecha (decorativa) a la derecha.
$con_flecha = static fn(string $control): string => '<div class="relative">' . $control
    . '<svg class="pointer-events-none absolute right-4 top-1/2 size-3 -translate-y-1/2 text-marca" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m2.5 4.5 3.5 3.5 3.5-3.5" /></svg></div>';

// Un campo con su etiqueta, su ayuda opcional y su zona de error (el JS la llena con data.campos).
$campo_html = static function (string $nombre, string $texto, string $control, string $texto_ayuda = '') use ($etiqueta, $ayuda, $error): void {
    $id = 'constructor-' . str_replace('_', '-', $nombre);
    ?>
    <div class="flex min-w-0 flex-col gap-2">
        <label class="<?= esc_attr($etiqueta) ?>" for="<?= esc_attr($id) ?>"><?= esc_html($texto) ?></label>
        <?php if ($texto_ayuda !== '') : ?>
            <p class="<?= esc_attr($ayuda) ?>" id="<?= esc_attr($id . '-ayuda') ?>"><?= esc_html($texto_ayuda) ?></p>
        <?php endif; ?>
        <?= $control // Armado abajo con valores escapados. ?>
        <p class="<?= esc_attr($error) ?>" id="<?= esc_attr($id . '-error') ?>" data-error-de="<?= esc_attr($nombre) ?>"></p>
    </div>
    <?php
};
?>
<form class="flex flex-col gap-[30px] max-md:gap-6" novalidate
    data-constructor-basico
    data-guardado="<?= esc_attr__('Guardado', 'angelitozzz') ?>"
    data-error-portada="<?= esc_attr__('No pudimos subir la imagen. Revisa que sea JPG, PNG o WebP de hasta 8 MB.', 'angelitozzz') ?>"
    data-resumen-errores="<?= esc_attr__('Revisa los campos marcados.', 'angelitozzz') ?>">

    <div class="empty:hidden rounded-[15px] border border-acento bg-blanco p-5 text-cuerpo text-acento" role="alert" tabindex="-1" data-errores></div>

    <section class="<?= esc_attr($tarjeta) ?>" aria-labelledby="constructor-informacion-titulo">
        <h2 id="constructor-informacion-titulo" class="<?= esc_attr($titulo_t) ?>"><?= esc_html__('Información del curso', 'angelitozzz') ?></h2>

        <?php
        $campo_html('titulo', __('Título del curso', 'angelitozzz'), sprintf(
            '<input class="%1$s" id="constructor-titulo" name="titulo" type="text" required maxlength="200" autocomplete="off" value="%2$s" placeholder="%3$s" aria-describedby="constructor-titulo-error">',
            esc_attr($campo),
            esc_attr((string) ($curso['titulo'] ?? '')),
            esc_attr__('Ej. Recién Nacidos Go', 'angelitozzz')
        ));

        $campo_html('descripcion', __('Descripción corta', 'angelitozzz'), sprintf(
            '<textarea class="%1$s min-h-[88px] resize-y" id="constructor-descripcion" name="descripcion" rows="3" placeholder="%3$s" aria-describedby="constructor-descripcion-error">%2$s</textarea>',
            esc_attr($campo),
            esc_textarea((string) ($curso['descripcion'] ?? '')),
            esc_attr__('Ej. Todo lo que necesitas saber para que tu recién nacido duerma mejor — desde los primeros días.', 'angelitozzz')
        ));
        ?>

        <div class="grid grid-cols-1 gap-x-5 gap-y-6 md:grid-cols-2">
            <?php
            $campo_html('edad', __('Categoría (edad)', 'angelitozzz'), sprintf(
                '<input class="%1$s" id="constructor-edad" name="edad" type="text" maxlength="100" autocomplete="off" value="%2$s" placeholder="%3$s" aria-describedby="constructor-edad-error">',
                esc_attr($campo),
                esc_attr((string) ($curso['edad'] ?? '')),
                esc_attr__('Ej. 0 a 12 semanas', 'angelitozzz')
            ));

            $opciones = '';
            foreach ($tipos as $valor => $texto) {
                $opciones .= sprintf('<option value="%1$s"%2$s>%3$s</option>', esc_attr($valor), selected($tipo, $valor, false), esc_html($texto));
            }
            $campo_html('tipo', __('Tipo', 'angelitozzz'), $con_flecha(sprintf(
                '<select class="%1$s" id="constructor-tipo" name="tipo" aria-describedby="constructor-tipo-error">%2$s</select>',
                esc_attr($select),
                $opciones
            )));

            $campo_html('precio', __('Precio (USD)', 'angelitozzz'), sprintf(
                '<input class="%1$s" id="constructor-precio" name="precio" type="number" inputmode="decimal" required min="0" step="0.01" value="%2$s" placeholder="%3$s" aria-describedby="constructor-precio-error">',
                esc_attr($campo),
                esc_attr((string) ($curso['precio'] ?? '')),
                esc_attr__('Ej. 97.00', 'angelitozzz')
            ));

            $campo_html('precio_lanzamiento', __('Precio de lanzamiento (opcional)', 'angelitozzz'), sprintf(
                '<input class="%1$s" id="constructor-precio-lanzamiento" name="precio_lanzamiento" type="number" inputmode="decimal" min="0" step="0.01" value="%2$s" placeholder="%3$s" aria-describedby="constructor-precio-lanzamiento-ayuda constructor-precio-lanzamiento-error">',
                esc_attr($campo),
                esc_attr((string) ($curso['precio_lanzamiento'] ?? '')),
                esc_attr__('Ej. 67.00', 'angelitozzz')
            ), __('Menor que el precio. Déjalo vacío si no hay precio de lanzamiento.', 'angelitozzz'));

            $opciones = '';
            foreach ($duraciones as $valor => $texto) {
                $opciones .= sprintf('<option value="%1$s"%2$s>%3$s</option>', esc_attr((string) $valor), selected($duracion, $valor, false), esc_html($texto));
            }
            $campo_html('duracion', __('Duración del acceso', 'angelitozzz'), $con_flecha(sprintf(
                '<select class="%1$s" id="constructor-duracion" name="duracion" aria-describedby="constructor-duracion-error">%2$s</select>',
                esc_attr($select),
                $opciones
            )));
            ?>
        </div>

        <?php
        // Interruptor (role="switch"): el input real queda accesible; la pista es decorativa. Lo decide Bárbara,
        // también en una suscripción: ahí solo se avisa (constructor-basico.js muestra u oculta el aviso al
        // cambiar el tipo o el interruptor; sin JS se ve si los dos ya venían así).
        $mundo_activo = !empty($curso['mes_mundo']);
        $mundo_aviso  = $tipo === 'suscripcion' && $mundo_activo;
        ?>
        <div class="flex flex-col gap-2 self-start">
            <label class="flex cursor-pointer items-center gap-4 self-start" for="constructor-mes-mundo">
                <span class="relative inline-flex shrink-0">
                    <input class="peer sr-only" id="constructor-mes-mundo" name="mes_mundo" type="checkbox" role="switch" value="1" aria-describedby="constructor-mes-mundo-ayuda constructor-mes-mundo-aviso"<?= checked($mundo_activo, true, false) ?>>
                    <span class="h-7 w-12 rounded-full bg-neutro transition-colors peer-checked:bg-marca peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-marca motion-reduce:transition-none" aria-hidden="true"></span>
                    <span class="absolute left-1 top-1 size-5 rounded-full bg-blanco shadow transition-transform peer-checked:translate-x-5 motion-reduce:transition-none" aria-hidden="true"></span>
                </span>
                <span class="text-cuerpo text-tinta"><?= esc_html__('Incluye mes gratis en Mundo Angelitozzz', 'angelitozzz') ?></span>
            </label>
            <p id="constructor-mes-mundo-ayuda" class="<?= esc_attr($ayuda) ?>"><?= esc_html__('Muestra el banner del mes gratis en la primera lección del curso y el bloque en la página de gracias y en el correo de la compra.', 'angelitozzz') ?></p>
            <div id="constructor-mes-mundo-aviso" role="status" data-mundo-aviso>
                <p class="<?= esc_attr('flex items-start gap-2 text-caption text-acento' . ($mundo_aviso ? '' : ' hidden')) ?>" data-mundo-aviso-texto>
                    <svg class="mt-px size-4 shrink-0" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="8" cy="8" r="7" /><path d="M8 4.5v4.25M8 11.25v.25" /></svg>
                    <span><?= esc_html__('Este curso es de suscripción. Las suscripciones normalmente no incluyen el mes gratis en Mundo: te recomendamos no activarlo.', 'angelitozzz') ?></span>
                </p>
            </div>
        </div>
    </section>

    <section class="<?= esc_attr($tarjeta) ?>" aria-labelledby="constructor-portada-titulo" data-portada data-portada-url="<?= esc_url($portada_url) ?>">
        <h2 id="constructor-portada-titulo" class="<?= esc_attr($titulo_t) ?>"><?= esc_html__('Imagen de portada', 'angelitozzz') ?></h2>

        <?php // Zona para soltar o elegir: es la etiqueta del <input type="file"> real (usable con teclado). ?>
        <div class="<?= esc_attr($portada_url !== '' ? 'hidden' : '') ?>" data-portada-zona>
            <input class="peer sr-only" id="constructor-portada" name="portada" type="file" accept="image/jpeg,image/png,image/webp" aria-describedby="constructor-portada-ayuda constructor-portada-error" data-portada-input>
            <label class="flex cursor-pointer flex-col items-center justify-center gap-3 rounded-[16px] border-2 border-dashed border-terracota px-6 py-10 text-center hover:bg-fondo peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-marca data-[arrastrando]:bg-fondo"
                for="constructor-portada" data-portada-soltar>
                <?= angelitozzz_svg('administrador/subir', 'size-7 text-terracota') // SVG fijo del tema. ?>
                <span class="text-h5 text-marca"><?= esc_html__('Arrastra una imagen o haz clic para subirla', 'angelitozzz') ?></span>
                <span class="text-caption text-tinta-suave" id="constructor-portada-ayuda"><?= esc_html__('JPG, PNG o WebP · Recomendado: hasta 2000 px en el lado más largo, horizontal.', 'angelitozzz') ?></span>
            </label>
        </div>

        <div class="<?= esc_attr('flex flex-col gap-4 sm:flex-row sm:items-end ' . ($portada_url === '' ? 'hidden' : '')) ?>" data-portada-vista>
            <img class="aspect-[3/2] w-full max-w-[420px] rounded-[16px] bg-fondo object-cover" src="<?= esc_url($portada_url) ?>" alt="<?= esc_attr__('Vista previa de la portada', 'angelitozzz') ?>" data-portada-imagen>
            <div class="flex flex-wrap gap-3">
                <button class="inline-flex cursor-pointer items-center rounded-full border-[1.5px] border-marca px-6 py-[10px] text-rotulo text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default disabled:opacity-50" type="button" data-portada-cambiar><?= esc_html__('Cambiar', 'angelitozzz') ?></button>
                <button class="inline-flex cursor-pointer items-center rounded-full border-[1.5px] border-acento px-6 py-[10px] text-rotulo text-acento focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default disabled:opacity-50" type="button" data-portada-quitar><?= esc_html__('Quitar', 'angelitozzz') ?></button>
            </div>
        </div>

        <p class="<?= esc_attr($error) ?>" id="constructor-portada-error" data-error-de="portada"></p>
    </section>

    <div class="flex flex-wrap items-center justify-end gap-4 max-md:flex-col-reverse max-md:[&>*]:w-full max-md:[&>*]:justify-center">
        <?php // "Guardado": visible junto a los botones y anunciado (role="status"). ?>
        <p class="mr-auto flex items-center text-cuerpo text-marca empty:hidden max-md:mr-0 max-md:text-center" role="status" data-estado></p>
        <button class="inline-flex cursor-pointer items-center gap-[10px] whitespace-nowrap rounded-full border-[1.5px] border-marca py-[14px] pl-[28px] pr-[28px] text-rotulo text-marca focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca disabled:cursor-default disabled:opacity-50"
            type="submit" value="borrador" data-accion="borrador"><?= esc_html__('Guardar borrador', 'angelitozzz') ?></button>
        <?php get_template_part('template-parts/components/boton', null, [
            'texto'  => __('Continuar: agregar contenido', 'angelitozzz'),
            'enviar' => true,
            'datos'  => ['data-accion' => 'continuar'],
        ]); ?>
    </div>
</form>
