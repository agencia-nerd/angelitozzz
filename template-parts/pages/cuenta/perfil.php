<?php

/**
 * Cuenta · Mi perfil (specs/usuario-mi-perfil.md, Figma 4164:26871 y 4164:27124): dos pestañas,
 * Datos personales y Seguridad, que cambian sin recargar (cuenta-pestanas en modo tabs).
 * Los formularios se envían a las acciones angelitozzz_perfil_datos y angelitozzz_perfil_contrasena
 * del plugin angelitozzz-cursos, que sanea, valida y guarda solo en la usuaria de la sesión.
 * El resultado vuelve como mensaje de un solo uso (angelitozzz_cursos_mensaje()).
 */

defined('ABSPATH') || exit;

if (!function_exists('angelitozzz_cursos_datos_perfil')) {
    echo '<p class="text-cuerpo text-tinta">' . esc_html__('Tu perfil no está disponible por ahora. Vuelve a intentarlo más tarde.', 'angelitozzz') . '</p>';
    return;
}

$perfil  = angelitozzz_cursos_datos_perfil() ?? [];
$mensaje = angelitozzz_cursos_mensaje();
$campos  = (array) ($mensaje['campos'] ?? []);
$error   = ($mensaje['tipo'] ?? '') === 'error';
$url     = angelitozzz_cursos_url_pagina('cuenta', 'perfil');

// Pestaña abierta: la que trae la URL (el JS la anota al cambiar, así el envío vuelve a ella) o, con un
// error sin campos devueltos (solo los da Datos personales), Seguridad.
$pedida = isset($_GET['pestana']) ? sanitize_key(wp_unslash($_GET['pestana'])) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- solo elige qué pestaña ver.
$activa = in_array($pedida, ['datos', 'seguridad'], true) ? $pedida : (($error && !$campos) ? 'seguridad' : 'datos');

// Valores: lo que escribió (si volvió con error) o lo guardado.
$valor = static fn(string $campo): string => (string) ($campos[$campo] ?? $perfil[$campo] ?? '');
$correo_guardado  = (string) ($perfil['correo'] ?? '');
$correo_pendiente = (string) ($perfil['correo_pendiente'] ?? '');
$cambio_correo    = strtolower($valor('correo')) !== strtolower($correo_guardado);

$etiqueta_clase = 'text-cuerpo-l text-tinta';
$campo_clase    = 'h-[50px] w-full rounded-[15px] border-[0.5px] bg-blanco px-5 text-cuerpo-s text-tinta focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca ' . (($error && $activa === 'datos') ? 'border-acento' : 'border-[#dfdfdf]');
$error_datos    = $error && $activa === 'datos';
$error_clave    = $error && $activa === 'seguridad';

// Campo de texto del diseño (etiqueta encima y caja blanca).
$campo = static function (array $c) use ($etiqueta_clase, $campo_clase, $error_datos): void {
    ?>
    <div class="flex flex-col gap-[10px]">
        <label class="<?= esc_attr($etiqueta_clase) ?>" for="<?= esc_attr($c['id']) ?>"><?= esc_html($c['etiqueta']) ?></label>
        <input class="<?= esc_attr($campo_clase) ?>" type="<?= esc_attr($c['tipo'] ?? 'text') ?>" id="<?= esc_attr($c['id']) ?>" name="<?= esc_attr($c['name']) ?>"
            value="<?= esc_attr($c['valor'] ?? '') ?>" autocomplete="<?= esc_attr($c['autocomplete'] ?? 'off') ?>"
            <?= !empty($c['requerido']) ? 'required' : '' ?>
            <?= $error_datos ? 'aria-invalid="true" aria-describedby="perfil-mensaje"' : '' ?>>
    </div>
    <?php
};

// Campo de contraseña con el ojo (src/js/modules/ver-contrasena.js).
$clave = static function (string $id, string $name, string $etiqueta, string $autocomplete, bool $error = false) use ($etiqueta_clase): void {
    ?>
    <div class="flex flex-col gap-[10px]">
        <label class="<?= esc_attr($etiqueta_clase) ?>" for="<?= esc_attr($id) ?>"><?= esc_html($etiqueta) ?></label>
        <div class="<?= esc_attr('flex h-[50px] w-full items-center gap-[10px] rounded-[15px] border-[0.5px] bg-blanco px-5 focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-marca ' . ($error ? 'border-acento' : 'border-[#dfdfdf]')) ?>" data-ver-contrasena>
            <input class="min-w-0 flex-1 bg-transparent text-cuerpo-s text-tinta outline-none" type="password" id="<?= esc_attr($id) ?>" name="<?= esc_attr($name) ?>" autocomplete="<?= esc_attr($autocomplete) ?>"
                <?= $error ? 'aria-invalid="true" aria-describedby="perfil-mensaje"' : '' ?>>
            <button class="flex size-6 shrink-0 cursor-pointer items-center justify-center rounded-full text-etiqueta hover:text-marca focus-visible:outline-2 focus-visible:outline-marca" type="button" aria-pressed="false" aria-controls="<?= esc_attr($id) ?>" hidden data-ver-contrasena-boton>
                <span class="sr-only"><?= esc_html__('Mostrar contraseña', 'angelitozzz') ?></span>
                <svg class="size-[18px]" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M1.5 9S3.75 3.75 9 3.75 16.5 9 16.5 9 14.25 14.25 9 14.25 1.5 9 1.5 9Z" /><circle cx="9" cy="9" r="2.25" />
                </svg>
            </button>
        </div>
    </div>
    <?php
};

$aviso = static function (string $panel) use ($mensaje, $error, $activa): void {
    if ($panel !== $activa) {
        return;
    }
    get_template_part('template-parts/components/aviso-formulario', null, [
        'texto' => (string) ($mensaje['texto'] ?? ''),
        'tipo'  => $error ? 'error' : 'aviso',
        'id'    => 'perfil-mensaje',
    ]);
};
?>
<div class="flex flex-col gap-[50px] max-md:gap-8" data-perfil>
    <?php get_template_part('template-parts/components/cuenta-pestanas', null, [
        'modo'     => 'tabs',
        'activa'   => $activa,
        'etiqueta' => __('Mi perfil', 'angelitozzz'),
        'pestanas' => [
            ['id' => 'datos', 'texto' => __('Datos personales', 'angelitozzz'), 'icono' => 'pestana-datos'],
            ['id' => 'seguridad', 'texto' => __('Seguridad', 'angelitozzz'), 'icono' => 'pestana-seguridad'],
        ],
    ]); ?>

    <section id="datos-panel" role="tabpanel" aria-labelledby="datos-pestana" tabindex="0" class="focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marca">
        <h2 class="sr-only"><?= esc_html__('Datos personales', 'angelitozzz') ?></h2>
        <form class="flex max-w-[1020px] flex-col gap-[35px]" method="post" action="<?= esc_url(angelitozzz_cursos_url_envio()) ?>" data-enviar-una-vez>
            <?php angelitozzz_cursos_campos_ocultos('angelitozzz_perfil_datos', $url); ?>
            <?php $aviso('datos'); ?>

            <div class="grid grid-cols-2 gap-x-5 gap-y-[35px] max-md:grid-cols-1 max-md:gap-y-6">
                <?php
                $campo(['id' => 'perfil-nombre', 'name' => 'nombre', 'etiqueta' => __('Nombre', 'angelitozzz'), 'valor' => $valor('nombre'), 'autocomplete' => 'given-name', 'requerido' => true]);
                $campo(['id' => 'perfil-apellidos', 'name' => 'apellidos', 'etiqueta' => __('Apellidos', 'angelitozzz'), 'valor' => $valor('apellidos'), 'autocomplete' => 'family-name', 'requerido' => true]);
                ?>
                <div class="flex flex-col gap-[10px]" data-perfil-correo="<?= esc_attr($correo_guardado) ?>">
                    <?php $campo(['id' => 'perfil-correo', 'name' => 'correo', 'tipo' => 'email', 'etiqueta' => __('Correo electrónico', 'angelitozzz'), 'valor' => $valor('correo'), 'autocomplete' => 'email', 'requerido' => true]); ?>
                    <?php if ($correo_pendiente !== '') : ?>
                        <p class="text-caption text-tinta-suave">
                            <?= esc_html(sprintf(
                                /* translators: %s: correo nuevo pendiente de confirmar */
                                __('Pendiente de confirmar: %s. Abre el enlace que te enviamos a ese correo.', 'angelitozzz'),
                                $correo_pendiente
                            )) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <?php $campo(['id' => 'perfil-telefono', 'name' => 'telefono', 'tipo' => 'tel', 'etiqueta' => __('Número de teléfono', 'angelitozzz'), 'valor' => $valor('telefono'), 'autocomplete' => 'tel']); ?>

                <?php // Solo hace falta al cambiar el correo: el JS la oculta mientras el correo sea el guardado. ?>
                <div data-perfil-clave-actual-caja>
                    <?php $clave('perfil-clave-correo', 'clave_actual', __('Contraseña actual (para cambiar el correo)', 'angelitozzz'), 'current-password', $error_datos && $cambio_correo); ?>
                </div>
            </div>

            <div class="flex flex-wrap gap-[38px] max-md:flex-col max-md:gap-4 max-md:[&>*]:w-full max-md:[&_a]:justify-center max-md:[&_button]:justify-center">
                <?php
                get_template_part('template-parts/components/boton', null, ['texto' => __('Guardar cambios', 'angelitozzz'), 'enviar' => true]);
                // Cancelar vuelve a los datos guardados (recarga sin lo escrito).
                get_template_part('template-parts/components/boton', null, ['enlace' => ['url' => $url, 'title' => __('Cancelar', 'angelitozzz')]]);
                ?>
            </div>
        </form>
    </section>

    <section id="seguridad-panel" role="tabpanel" aria-labelledby="seguridad-pestana" tabindex="0" class="focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-marca">
        <h2 class="sr-only"><?= esc_html__('Seguridad', 'angelitozzz') ?></h2>
        <form class="flex max-w-[500px] flex-col gap-[35px]" method="post" action="<?= esc_url(angelitozzz_cursos_url_envio()) ?>" data-enviar-una-vez data-perfil-seguridad>
            <?php angelitozzz_cursos_campos_ocultos('angelitozzz_perfil_contrasena', $url); ?>
            <?php $aviso('seguridad'); ?>

            <?php
            $clave('perfil-clave-actual', 'clave_actual', __('Contraseña actual', 'angelitozzz'), 'current-password', $error_clave);
            $clave('perfil-clave-nueva', 'clave', __('Nueva contraseña', 'angelitozzz'), 'new-password', $error_clave);
            get_template_part('template-parts/components/reglas-contrasena', null, ['campo' => 'perfil-clave-nueva']);
            ?>

            <div class="flex flex-wrap gap-[27px] max-md:flex-col max-md:gap-4 max-md:[&>*]:w-full max-md:[&_a]:justify-center max-md:[&_button]:justify-center">
                <?php
                get_template_part('template-parts/components/boton', null, [
                    'texto'  => __('Guardar contraseña', 'angelitozzz'),
                    'enviar' => true,
                    'datos'  => ['data-perfil-guardar-clave' => '1'],
                ]);
                // Cancelar limpia los campos (recarga en esta pestaña).
                get_template_part('template-parts/components/boton', null, ['enlace' => ['url' => add_query_arg('pestana', 'seguridad', $url), 'title' => __('Cancelar', 'angelitozzz')]]);
                ?>
            </div>
        </form>
    </section>
</div>
