<?php

/**
 * Crear cuenta · formulario (specs/crear-cuenta.md, Figma 4156:23743).
 * Se envía a la acción angelitozzz_crear_cuenta del plugin angelitozzz-cursos: valida todo en el
 * servidor (las 4 reglas de contraseña, correo repetido, términos), crea la usuaria con el rol
 * customer (usuario armado del correo) y abre su sesión. Lo escrito vuelve si hay error (nunca la contraseña).
 */

defined('ABSPATH') || exit;

$plugin  = function_exists('angelitozzz_cursos_campos_ocultos');
$mensaje = $plugin ? angelitozzz_cursos_mensaje() : null;
$campos  = (array) ($mensaje['campos'] ?? []);
$error   = ($mensaje['tipo'] ?? '') === 'error';

// Vuelta tras crear la cuenta: la página de origen (solo del propio sitio y nunca otra página de
// acceso). Sin origen va vacía y el plugin manda a la cuenta.
$pedida = isset($_GET['redirect_to']) ? wp_unslash($_GET['redirect_to']) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- lo valida angelitozzz_cursos_redirect_seguro().
$volver = $plugin ? angelitozzz_cursos_redirect_seguro($pedida, '') : '';
if ($volver !== '' && function_exists('angelitozzz_cursos_es_pagina_acceso') && angelitozzz_cursos_es_pagina_acceso($volver)) {
    $volver = '';
}

$iniciar_sesion = $plugin ? angelitozzz_cursos_url_pagina('iniciar-sesion') : home_url('/iniciar-sesion/');
if ($plugin && $volver !== '') {
    $iniciar_sesion = add_query_arg('redirect_to', rawurlencode($volver), $iniciar_sesion);
}

// Términos (página configurada en WooCommerce) y privacidad (Ajustes → Privacidad): sin página, sin enlace.
$terminos   = function_exists('wc_terms_and_conditions_page_id') && wc_terms_and_conditions_page_id() > 0
    ? (string) get_permalink(wc_terms_and_conditions_page_id())
    : '';
$privacidad = (string) get_privacy_policy_url();
$enlace     = 'text-rotulo text-marca underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
$enlace_s   = 'text-marca underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';
$ligar      = static fn(string $texto, string $url): string => $url !== ''
    ? '<a class="' . esc_attr($enlace_s) . '" href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html($texto) . '<span class="sr-only"> (' . esc_html__('se abre en una pestaña nueva', 'angelitozzz') . ')</span></a>'
    : esc_html($texto);

$comun = static fn(array $campo): array => $campo + [
    'error'     => $error,
    'describe'  => $error ? 'crear-cuenta-mensaje' : '',
    'asterisco' => true,
];

get_template_part('template-parts/components/acceso-tarjeta', null, [
    'titulo' => __('Crea tu cuenta', 'angelitozzz'),
    'bajada' => __('Con tu cuenta accedes a tus cursos desde cualquier dispositivo.', 'angelitozzz'),
]);

if (!$plugin) : ?>
    <p class="text-cuerpo text-tinta"><?= esc_html__('El registro no está disponible por ahora. Vuelve a intentarlo más tarde.', 'angelitozzz') ?></p>
<?php else : ?>
    <form class="flex w-full flex-col items-center gap-8" method="post" action="<?= esc_url(angelitozzz_cursos_url_envio()) ?>" data-enviar-una-vez>
        <?php angelitozzz_cursos_campos_ocultos('angelitozzz_crear_cuenta', $volver); ?>

        <?php get_template_part('template-parts/components/aviso-formulario', null, [
            'texto' => (string) ($mensaje['texto'] ?? ''),
            'tipo'  => $error ? 'error' : 'aviso',
            'id'    => 'crear-cuenta-mensaje',
        ]); ?>

        <div class="flex w-full flex-col gap-6">
            <div class="grid grid-cols-2 gap-4 max-md:grid-cols-1 max-md:gap-6">
                <?php
                get_template_part('template-parts/components/campo-acceso', null, $comun([
                    'id'           => 'crear-cuenta-nombre',
                    'name'         => 'nombre',
                    'etiqueta'     => __('Nombre', 'angelitozzz'),
                    'placeholder'  => __('Ej. Ana Lucía', 'angelitozzz'),
                    'autocomplete' => 'given-name',
                    'valor'        => (string) ($campos['nombre'] ?? ''),
                ]));
                get_template_part('template-parts/components/campo-acceso', null, $comun([
                    'id'           => 'crear-cuenta-apellidos',
                    'name'         => 'apellidos',
                    'etiqueta'     => __('Apellidos', 'angelitozzz'),
                    'placeholder'  => __('Ej. León', 'angelitozzz'),
                    'autocomplete' => 'family-name',
                    'valor'        => (string) ($campos['apellidos'] ?? ''),
                ]));
                ?>
            </div>
            <?php
            get_template_part('template-parts/components/campo-acceso', null, $comun([
                'id'           => 'crear-cuenta-correo',
                'name'         => 'correo',
                'etiqueta'     => __('Correo electrónico', 'angelitozzz'),
                'tipo'         => 'email',
                'placeholder'  => 'tucorreo@ejemplo.com',
                'icono'        => 'correo',
                'autocomplete' => 'email',
                'valor'        => (string) ($campos['correo'] ?? ''),
            ]));
            get_template_part('template-parts/components/campo-acceso', null, $comun([
                'id'           => 'crear-cuenta-clave',
                'name'         => 'clave',
                'etiqueta'     => __('Contraseña', 'angelitozzz'),
                'tipo'         => 'password',
                'placeholder'  => __('Mínimo 8 caracteres', 'angelitozzz'),
                'icono'        => 'candado',
                'autocomplete' => 'new-password',
            ]));
            echo '<div class="text-left">';
            get_template_part('template-parts/components/reglas-contrasena', null, ['campo' => 'crear-cuenta-clave']);
            echo '</div>';
            ?>

            <div class="flex items-start gap-3 text-left">
                <input class="mt-[3px] size-[22px] shrink-0 cursor-pointer rounded-[4px] accent-marca" type="checkbox" id="crear-cuenta-terminos" name="terminos" value="1" required>
                <label class="text-cuerpo-s text-tinta" for="crear-cuenta-terminos">
                    <?php
                    printf(
                        /* translators: 1: enlace "términos y condiciones", 2: enlace "política de privacidad" */
                        esc_html__('Acepto los %1$s y la %2$s', 'angelitozzz') . '<span aria-hidden="true"> *</span>',
                        $ligar(__('términos y condiciones', 'angelitozzz'), $terminos),   // phpcs:ignore WordPress.Security.EscapeOutput -- ya escapado en $ligar.
                        $ligar(__('política de privacidad', 'angelitozzz'), $privacidad)  // phpcs:ignore WordPress.Security.EscapeOutput -- ya escapado en $ligar.
                    );
                    ?>
                </label>
            </div>
        </div>

        <?php get_template_part('template-parts/components/boton', null, [
            'texto'  => __('Crear cuenta', 'angelitozzz'),
            'enviar' => true,
        ]); ?>

        <p class="text-cuerpo text-tinta">
            <?= esc_html__('¿Ya tienes cuenta?', 'angelitozzz') ?>
            <a class="<?= esc_attr($enlace) ?>" href="<?= esc_url($iniciar_sesion) ?>"><?= esc_html__('Inicia sesión', 'angelitozzz') ?></a>
        </p>
    </form>
<?php endif;

get_template_part('template-parts/components/acceso-tarjeta-fin');
