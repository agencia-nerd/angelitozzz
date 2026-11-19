<?php

/**
 * Canjear regalo · canje (specs/canjear-regalo.md, Figma 3640:1593): formulario del código.
 *
 * Se envía a la acción angelitozzz_canjear_regalo del plugin angelitozzz-cursos (nonce, límite de
 * intentos, código de un solo uso). Sin sesión, el plugin guarda el código y lleva a crear la cuenta.
 *
 * $args (de pages/template-canjear-regalo.php, con angelitozzz_cursos_ultimo_canje()):
 * - error (string, opcional: mensaje del envío anterior; código no válido o demasiados intentos)
 * - valor (string, opcional: lo que escribió, para no perderlo al volver con error)
 */

defined('ABSPATH') || exit;

$error    = trim((string) ($args['error'] ?? ''));
$valor    = (string) ($args['valor'] ?? '');
$id_campo = 'canjear-regalo-codigo';
$id_error = 'canjear-regalo-error';
$plugin   = function_exists('angelitozzz_cursos_campos_ocultos');

// Sin el plugin no hay a dónde enviar el código: se avisa en vez de mostrar un formulario que no funciona.
if (!$plugin && $error === '') {
    $error = __('El canje no está disponible en este momento. Escríbenos por WhatsApp y te ayudamos.', 'angelitozzz');
}

get_template_part('template-parts/components/confirmacion-tarjeta', null, [
    'titulo' => __('¿Te regalaron un curso?', 'angelitozzz'),
    'icono'  => 'regalo',
    'texto'  => __('Ingresa el código que recibiste por correo y empieza cuando quieras.', 'angelitozzz'),
]);
?>
<form class="flex w-full flex-col items-center gap-6" method="post" action="<?= esc_url($plugin ? angelitozzz_cursos_url_envio() : '') ?>" data-enviar-una-vez>
    <?php
    if ($plugin) {
        angelitozzz_cursos_campos_ocultos('angelitozzz_canjear_regalo');
    }
    ?>

    <?php if ($error !== '') : ?>
        <div id="<?= esc_attr($id_error) ?>" class="w-full rounded-xl border border-acento bg-fondo-rosa px-5 py-[14px] text-left text-cuerpo-s text-tinta" role="alert">
            <?= esc_html($error) ?>
        </div>
    <?php endif; ?>

    <div class="w-full">
        <label class="sr-only" for="<?= esc_attr($id_campo) ?>"><?= esc_html__('Código de regalo', 'angelitozzz') ?></label>
        <input
            class="<?= esc_attr('w-full rounded-xl border bg-fondo px-5 py-4 text-cuerpo text-tinta placeholder:text-tinta-suave focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca ' . ($error !== '' ? 'border-acento' : 'border-neutro')) ?>"
            type="text"
            id="<?= esc_attr($id_campo) ?>"
            name="codigo"
            value="<?= esc_attr($valor) ?>"
            placeholder="<?= esc_attr__('Código de regalo (ej. ANGZ-4K7P)', 'angelitozzz') ?>"
            autocomplete="off"
            autocapitalize="characters"
            spellcheck="false"
            required
            <?= $error !== '' ? 'aria-invalid="true" aria-describedby="' . esc_attr($id_error) . '"' : '' ?>>
    </div>

    <?php get_template_part('template-parts/components/boton', null, [
        'texto'  => __('Canjear código', 'angelitozzz'),
        'enviar' => true,
    ]); ?>
</form>
<?php
get_template_part('template-parts/components/confirmacion-tarjeta-fin', null, [
    // Con sesión iniciada no hace falta crear cuenta: la nota no se muestra.
    'nota' => is_user_logged_in() ? '' : __('Si aún no tienes cuenta, la creas en el siguiente paso.', 'angelitozzz'),
]);
