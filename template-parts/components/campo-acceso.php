<?php

/**
 * Campo de las pantallas de acceso: etiqueta verde encima y caja crema con ícono a la izquierda.
 * Con tipo password agrega el botón ojo (src/js/modules/ver-contrasena.js) para ver u ocultar.
 *
 * $args:
 * - id           (string, requerido)
 * - name         (string, requerido)
 * - etiqueta     (string, requerido)
 * - tipo         ('text' | 'email' | 'password', por defecto 'text')
 * - placeholder  (string)
 * - icono        ('correo' | 'candado' | 'ninguno', por defecto 'ninguno')
 * - autocomplete (string)
 * - requerido    (bool, por defecto true)
 * - valor        (string: lo que ya escribió, al volver con error; nunca en contraseñas)
 * - error        (bool, por defecto false: borde frambuesa y aria-invalid)
 * - describe     (string, opcional: id del mensaje de error que describe al campo)
 * - asterisco    (bool, por defecto false: agrega " *" a la etiqueta de un campo requerido; crear cuenta lo usa)
 */

defined('ABSPATH') || exit;

$id           = sanitize_html_class((string) ($args['id'] ?? ''));
$name         = (string) ($args['name'] ?? '');
$etiqueta     = trim((string) ($args['etiqueta'] ?? ''));
$tipo         = (string) ($args['tipo'] ?? 'text');
$tipo         = in_array($tipo, ['text', 'email', 'password'], true) ? $tipo : 'text';
$placeholder  = (string) ($args['placeholder'] ?? '');
$icono        = $args['icono'] ?? 'ninguno';
$autocomplete = (string) ($args['autocomplete'] ?? '');
$requerido    = (bool) ($args['requerido'] ?? true);
$valor        = $tipo === 'password' ? '' : (string) ($args['valor'] ?? '');
$error        = (bool) ($args['error'] ?? false);
$describe     = sanitize_html_class((string) ($args['describe'] ?? ''));
$asterisco    = $requerido && !empty($args['asterisco']);

if ($id === '' || $name === '' || $etiqueta === '') {
    return;
}

// Íconos del diseño (18 px, color salvia), decorativos.
$iconos = [
    'correo'  => '<path d="M3 3h12a1.5 1.5 0 0 1 1.5 1.5v9A1.5 1.5 0 0 1 15 15H3a1.5 1.5 0 0 1-1.5-1.5v-9A1.5 1.5 0 0 1 3 3Z" /><path d="M16.5 5.25 9 9 1.5 5.25" />',
    'candado' => '<path d="M3.75 8.25h10.5a1.5 1.5 0 0 1 1.5 1.5V15a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5V9.75a1.5 1.5 0 0 1 1.5-1.5Z" /><path d="M5.25 8.25v-3a3.75 3.75 0 0 1 7.5 0v3" />',
];
$svg = static fn(string $trazos): string => '<svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $trazos . '</svg>';

$caja = 'flex w-full items-center gap-[10px] rounded-xl border bg-fondo px-4 py-[14px] text-etiqueta focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-marca '
    . ($error ? 'border-acento' : 'border-neutro');
?>
<div class="flex w-full flex-col items-start gap-2 text-left">
    <label class="text-rotulo text-marca" for="<?= esc_attr($id) ?>">
        <?= esc_html($etiqueta) ?><?php if ($asterisco) : ?><span aria-hidden="true"> *</span><?php endif; ?>
    </label>
    <div class="<?= esc_attr($caja) ?>"<?= $tipo === 'password' ? ' data-ver-contrasena' : '' ?>>
        <?= isset($iconos[$icono]) ? $svg($iconos[$icono]) : '' // SVG fijo del tema. ?>
        <input
            class="min-w-0 flex-1 bg-transparent text-cuerpo text-tinta outline-none placeholder:text-tinta-suave"
            type="<?= esc_attr($tipo) ?>"
            id="<?= esc_attr($id) ?>"
            name="<?= esc_attr($name) ?>"
            <?= $valor !== '' ? 'value="' . esc_attr($valor) . '"' : '' ?>
            <?= $placeholder !== '' ? 'placeholder="' . esc_attr($placeholder) . '"' : '' ?>
            <?= $autocomplete !== '' ? 'autocomplete="' . esc_attr($autocomplete) . '"' : '' ?>
            <?= $requerido ? 'required' : '' ?>
            <?= $error ? 'aria-invalid="true"' : '' ?>
            <?= $describe !== '' ? 'aria-describedby="' . esc_attr($describe) . '"' : '' ?>>
        <?php if ($tipo === 'password') : ?>
            <button class="flex size-6 shrink-0 cursor-pointer items-center justify-center rounded-full text-etiqueta hover:text-marca focus-visible:outline-2 focus-visible:outline-marca" type="button" aria-pressed="false" aria-controls="<?= esc_attr($id) ?>" hidden data-ver-contrasena-boton>
                <span class="sr-only"><?= esc_html__('Mostrar contraseña', 'angelitozzz') ?></span>
                <svg class="size-[18px]" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M1.5 9S3.75 3.75 9 3.75 16.5 9 16.5 9 14.25 14.25 9 14.25 1.5 9 1.5 9Z" /><circle cx="9" cy="9" r="2.25" />
                    <path class="hidden in-aria-pressed:block" d="M2.25 2.25l13.5 13.5" />
                </svg>
            </button>
        <?php endif; ?>
    </div>
</div>
