<?php

/**
 * Recuperar contraseña · formulario (specs/recuperar-contrasena.md, Figma 4156:24625), en 3 pasos
 * sobre la misma tarjeta:
 * 1. el correo (acción angelitozzz_recuperar del plugin; mismo mensaje exista o no la cuenta);
 * 2. "Revisa tu correo": el aviso del plugin con campos.paso = enviado;
 * 3. con key y login en la URL (el enlace del correo): la nueva contraseña (acción
 *    angelitozzz_nueva_contrasena). Si el enlace venció, se ve el paso 1 con el error.
 */

defined('ABSPATH') || exit;

$plugin  = function_exists('angelitozzz_cursos_campos_ocultos');
$mensaje = $plugin ? angelitozzz_cursos_mensaje() : null;
$campos  = (array) ($mensaje['campos'] ?? []);
$error   = ($mensaje['tipo'] ?? '') === 'error';
$texto   = (string) ($mensaje['texto'] ?? '');

$iniciar_sesion = $plugin ? angelitozzz_cursos_url_pagina('iniciar-sesion') : home_url('/iniciar-sesion/');
$enlace         = 'text-rotulo text-marca underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';

// Paso 3: el enlace del correo trae la clave y el usuario. La clave se comprueba sin consumirla
// (la valida otra vez el plugin al guardar); si venció o no es válida, se vuelve al paso 1 con el error.
$clave_reset = isset($_GET['key']) ? sanitize_text_field(wp_unslash($_GET['key'])) : '';
$login       = isset($_GET['login']) ? sanitize_text_field(wp_unslash($_GET['login'])) : '';
$paso        = 1;

if ($plugin && $clave_reset !== '' && $login !== '') {
    if (check_password_reset_key($clave_reset, $login) instanceof WP_User) {
        $paso = 3;
    } elseif ($texto === '') {
        $error = true;
        $texto = __('El enlace venció o no es válido. Pide uno nuevo.', 'angelitozzz');
    }
} elseif (!$error && ($campos['paso'] ?? '') === 'enviado') {
    $paso = 2;
}

if ($paso === 2) {
    // El texto es el del plugin: el mismo exista o no la cuenta.
    get_template_part('template-parts/components/acceso-tarjeta', null, [
        'titulo' => __('Revisa tu correo', 'angelitozzz'),
        'bajada' => $texto !== '' ? $texto : __('Si el correo está registrado, te enviamos un enlace para crear una nueva contraseña. Revisa también la carpeta de spam.', 'angelitozzz'),
    ]);
    ?>
    <a class="<?= esc_attr($enlace) ?>" href="<?= esc_url($iniciar_sesion) ?>"><?= esc_html__('Volver a iniciar sesión', 'angelitozzz') ?></a>
    <?php
    get_template_part('template-parts/components/acceso-tarjeta-fin');
    return;
}

if ($paso === 3) {
    get_template_part('template-parts/components/acceso-tarjeta', null, [
        'titulo' => __('Crea una nueva contraseña', 'angelitozzz'),
    ]);
    ?>
    <form class="flex w-full flex-col items-center gap-8" method="post" action="<?= esc_url(angelitozzz_cursos_url_envio()) ?>" data-enviar-una-vez>
        <?php angelitozzz_cursos_campos_ocultos('angelitozzz_nueva_contrasena'); ?>
        <input type="hidden" name="key" value="<?= esc_attr($clave_reset) ?>">
        <input type="hidden" name="login" value="<?= esc_attr($login) ?>">

        <?php get_template_part('template-parts/components/aviso-formulario', null, [
            'texto' => $texto,
            'tipo'  => $error ? 'error' : 'aviso',
            'id'    => 'recuperar-mensaje',
        ]); ?>

        <div class="flex w-full flex-col gap-6">
            <?php
            get_template_part('template-parts/components/campo-acceso', null, [
                'id'           => 'recuperar-clave',
                'name'         => 'clave',
                'etiqueta'     => __('Nueva contraseña', 'angelitozzz'),
                'tipo'         => 'password',
                'icono'        => 'candado',
                'autocomplete' => 'new-password',
                'error'        => $error,
                'describe'     => $error ? 'recuperar-mensaje' : '',
            ]);
            echo '<div class="text-left">';
            get_template_part('template-parts/components/reglas-contrasena', null, ['campo' => 'recuperar-clave']);
            echo '</div>';
            get_template_part('template-parts/components/campo-acceso', null, [
                'id'           => 'recuperar-clave2',
                'name'         => 'clave2',
                'etiqueta'     => __('Repite la contraseña', 'angelitozzz'),
                'tipo'         => 'password',
                'icono'        => 'candado',
                'autocomplete' => 'new-password',
                'error'        => $error,
                'describe'     => $error ? 'recuperar-mensaje' : '',
            ]);
            ?>
        </div>

        <?php get_template_part('template-parts/components/boton', null, [
            'texto'  => __('Guardar contraseña', 'angelitozzz'),
            'enviar' => true,
        ]); ?>
    </form>
    <?php
    get_template_part('template-parts/components/acceso-tarjeta-fin');
    return;
}

// Paso 1.
get_template_part('template-parts/components/acceso-tarjeta', null, [
    'titulo' => __('Recupera tu contraseña', 'angelitozzz'),
    'bajada' => __('Escribe tu correo y te enviaremos un enlace para crear una nueva.', 'angelitozzz'),
]);

if (!$plugin) : ?>
    <p class="text-cuerpo text-tinta"><?= esc_html__('La recuperación de contraseña no está disponible por ahora. Vuelve a intentarlo más tarde.', 'angelitozzz') ?></p>
<?php else : ?>
    <form class="flex w-full flex-col items-center gap-8" method="post" action="<?= esc_url(angelitozzz_cursos_url_envio()) ?>" data-enviar-una-vez>
        <?php angelitozzz_cursos_campos_ocultos('angelitozzz_recuperar'); ?>

        <?php get_template_part('template-parts/components/aviso-formulario', null, [
            'texto' => $texto,
            'tipo'  => $error ? 'error' : 'aviso',
            'id'    => 'recuperar-mensaje',
        ]); ?>

        <?php get_template_part('template-parts/components/campo-acceso', null, [
            'id'           => 'recuperar-correo',
            'name'         => 'correo',
            'etiqueta'     => __('Correo electrónico', 'angelitozzz'),
            'tipo'         => 'email',
            'placeholder'  => 'tucorreo@ejemplo.com',
            'icono'        => 'correo',
            'autocomplete' => 'email',
            'valor'        => (string) ($campos['correo'] ?? ''),
            'error'        => $error,
            'describe'     => $error ? 'recuperar-mensaje' : '',
        ]); ?>

        <?php get_template_part('template-parts/components/boton', null, [
            'texto'  => __('Enviar enlace', 'angelitozzz'),
            'enviar' => true,
        ]); ?>

        <a class="<?= esc_attr($enlace) ?>" href="<?= esc_url($iniciar_sesion) ?>"><?= esc_html__('Volver a iniciar sesión', 'angelitozzz') ?></a>
    </form>
<?php endif;

get_template_part('template-parts/components/acceso-tarjeta-fin');
