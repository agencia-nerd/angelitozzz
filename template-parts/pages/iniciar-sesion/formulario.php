<?php

/**
 * Iniciar sesión · formulario (specs/iniciar-sesion.md, Figma 4156:23796).
 * Se envía a la acción angelitozzz_iniciar_sesion del plugin angelitozzz-cursos (nonce, límite de
 * intentos y mensaje que no revela si el correo existe). El resultado vuelve como mensaje de un solo uso.
 */

defined('ABSPATH') || exit;

$plugin  = function_exists('angelitozzz_cursos_campos_ocultos');
$mensaje = $plugin ? angelitozzz_cursos_mensaje() : null;
$campos  = (array) ($mensaje['campos'] ?? []);
$error   = ($mensaje['tipo'] ?? '') === 'error';

// Vuelta tras entrar: la página de origen (solo del propio sitio y nunca otra página de acceso). Sin
// origen va vacía y el plugin elige el inicio de cada rol (alumna: /cuenta/; instructora: /administrador/).
$pedida = isset($_GET['redirect_to']) ? wp_unslash($_GET['redirect_to']) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- lo valida angelitozzz_cursos_redirect_seguro().
$volver = $plugin ? angelitozzz_cursos_redirect_seguro($pedida, '') : '';
if ($volver !== '' && function_exists('angelitozzz_cursos_es_pagina_acceso') && angelitozzz_cursos_es_pagina_acceso($volver)) {
    $volver = '';
}

$crear_cuenta = $plugin ? angelitozzz_cursos_url_pagina('crear-cuenta') : home_url('/crear-cuenta/');
if ($plugin && $volver !== '') {
    $crear_cuenta = add_query_arg('redirect_to', rawurlencode($volver), $crear_cuenta);
}
$recuperar = $plugin ? angelitozzz_cursos_url_pagina('recuperar-contrasena') : home_url('/recuperar-contrasena/');

$enlace = 'text-rotulo text-marca underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';

get_template_part('template-parts/components/acceso-tarjeta', null, [
    'titulo' => __('Inicia sesión', 'angelitozzz'),
    'bajada' => __('Entra para ver tus cursos, tu historial y tus regalos.', 'angelitozzz'),
]);

if (!$plugin) : ?>
    <p class="text-cuerpo text-tinta"><?= esc_html__('El acceso no está disponible por ahora. Vuelve a intentarlo más tarde.', 'angelitozzz') ?></p>
<?php else : ?>
    <form class="flex w-full flex-col items-center gap-8" method="post" action="<?= esc_url(angelitozzz_cursos_url_envio()) ?>" data-enviar-una-vez>
        <?php angelitozzz_cursos_campos_ocultos('angelitozzz_iniciar_sesion', $volver); ?>

        <?php get_template_part('template-parts/components/aviso-formulario', null, [
            'texto' => (string) ($mensaje['texto'] ?? ''),
            'tipo'  => $error ? 'error' : 'aviso',
            'id'    => 'iniciar-sesion-mensaje',
        ]); ?>

        <div class="flex w-full flex-col gap-6">
            <?php
            get_template_part('template-parts/components/campo-acceso', null, [
                'id'           => 'iniciar-sesion-correo',
                'name'         => 'correo',
                'etiqueta'     => __('Correo electrónico', 'angelitozzz'),
                'tipo'         => 'email',
                'placeholder'  => 'tucorreo@ejemplo.com',
                'icono'        => 'correo',
                'autocomplete' => 'username',
                'valor'        => (string) ($campos['correo'] ?? ''),
                'error'        => $error,
                'describe'     => $error ? 'iniciar-sesion-mensaje' : '',
            ]);
            get_template_part('template-parts/components/campo-acceso', null, [
                'id'           => 'iniciar-sesion-clave',
                'name'         => 'clave',
                'etiqueta'     => __('Contraseña', 'angelitozzz'),
                'tipo'         => 'password',
                'placeholder'  => '••••••••',
                'icono'        => 'candado',
                'autocomplete' => 'current-password',
                'error'        => $error,
                'describe'     => $error ? 'iniciar-sesion-mensaje' : '',
            ]);
            ?>
        </div>

        <a class="<?= esc_attr($enlace) ?>" href="<?= esc_url($recuperar) ?>"><?= esc_html__('¿Olvidaste tu contraseña?', 'angelitozzz') ?></a>

        <?php get_template_part('template-parts/components/boton', null, [
            'texto'  => __('Iniciar sesión', 'angelitozzz'),
            'enviar' => true,
        ]); ?>

        <p class="text-cuerpo text-tinta">
            <?= esc_html__('¿No tienes cuenta?', 'angelitozzz') ?>
            <a class="<?= esc_attr($enlace) ?>" href="<?= esc_url($crear_cuenta) ?>"><?= esc_html__('Crea una aquí', 'angelitozzz') ?></a>
        </p>
    </form>
<?php endif;

get_template_part('template-parts/components/acceso-tarjeta-fin');
