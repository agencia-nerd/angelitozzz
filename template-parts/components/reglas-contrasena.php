<?php

/**
 * Reglas de contraseña (usuario-mi-perfil / Seguridad, 4164:27124): lista de 4 reglas en 2 × 2 que
 * se marcan al escribir. La vigila src/js/modules/reglas-contrasena.js por el id del campo. Qué reglas
 * se muestran sale de angelitozzz_cursos_reglas_contrasena() del plugin, que es el que las valida en el
 * servidor; esta lista solo ayuda a escribirla. Sin el plugin no se muestra.
 * Sin JS se ve la lista con todas las reglas pendientes.
 *
 * $args:
 * - campo (string, requerido: id del campo de contraseña que vigila)
 * - compacto (bool, por defecto false: íconos de 20 px, texto chico y menos espacio; lo usa el checkout)
 */

defined('ABSPATH') || exit;

$campo = sanitize_html_class((string) ($args['campo'] ?? ''));
$compacto = !empty($args['compacto']);
$icono    = $compacto ? 'size-5' : 'size-[30px]';
if ($campo === '' || !function_exists('angelitozzz_cursos_reglas_contrasena')) {
    return;
}

$id_lista = wp_unique_id('reglas-contrasena-');

// Qué reglas hay lo decide el plugin (las mismas que valida el servidor); el tema solo pone el texto.
$claves = array_keys(angelitozzz_cursos_reglas_contrasena(''));

// Regla => texto visible, en el orden del diseño (2 × 2). La clave es la que el JS sabe comprobar.
$textos = [
    'largo'     => __('8 caracteres', 'angelitozzz'),
    'minuscula' => __('1 letra minúscula', 'angelitozzz'),
    'numero'    => __('1 número', 'angelitozzz'),
    'mayuscula' => __('1 letra mayúscula', 'angelitozzz'),
];

$reglas = array_intersect_key($textos, array_flip($claves));

// Una regla nueva del plugin sin texto en el tema se muestra igual (con su clave) para no esconderla.
foreach (array_diff($claves, array_keys($textos)) as $clave) {
    $reglas[$clave] = (string) $clave;
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log("[reglas-contrasena] La regla \"{$clave}\" del plugin no tiene texto en el tema.");
    }
}

$cumplida  = esc_html__('cumplida', 'angelitozzz');
$pendiente = esc_html__('pendiente', 'angelitozzz');
?>
<div class="<?= esc_attr($compacto ? 'flex flex-col gap-2' : 'flex flex-col gap-[35px] max-md:gap-5') ?>" data-reglas-contrasena="<?= esc_attr($campo) ?>">
    <p id="<?= esc_attr($id_lista) ?>-titulo" class="<?= esc_attr($compacto ? 'text-cuerpo-s text-tinta-suave' : 'text-cuerpo-l text-tinta') ?>"><?= esc_html__('Su contraseña debe tener al menos:', 'angelitozzz') ?></p>
    <ul id="<?= esc_attr($id_lista) ?>" class="inline-grid w-fit grid-cols-[repeat(2,fit-content(100%))] <?= esc_attr($compacto ? 'gap-x-8 gap-y-1' : 'gap-x-[65px] gap-y-[5px] max-md:grid-cols-1') ?>" aria-labelledby="<?= esc_attr($id_lista) ?>-titulo" aria-live="polite">
        <?php foreach ($reglas as $regla => $texto) : ?>
            <li class="flex items-center" data-regla="<?= esc_attr($regla) ?>" data-cumplida="false">
                <?php // Ícono de regla cumplida (check sobre #9cb09d) y pendiente (✕ sobre #f5a8a9): decorativos. ?>
                <svg class="hidden <?= esc_attr($icono) ?> shrink-0" data-icono="cumplida" viewBox="0 0 30 30" aria-hidden="true" focusable="false">
                    <rect width="30" height="30" rx="15" fill="#9cb09d" />
                    <path fill="#fff" fill-rule="evenodd" clip-rule="evenodd" d="M19.74 9.1a.68.68 0 0 1 .95.22l.7 1.09a.72.72 0 0 1-.11.92l-.21.2a62 62 0 0 0-2.54 2.69c-1.54 1.74-3.37 4.02-4.6 6.23a1.05 1.05 0 0 1-1.68.22L7.7 15.82a.73.73 0 0 1 .04-1.04l1.37-1.27a.68.68 0 0 1 .89-.04l2.32 1.79c3.63-3.67 5.68-5.08 7.42-6.16Z" />
                </svg>
                <svg class="<?= esc_attr($icono) ?> shrink-0" data-icono="pendiente" viewBox="0 0 30 30" aria-hidden="true" focusable="false">
                    <rect width="30" height="30" rx="15" fill="#f5a8a9" />
                    <path fill="#fff" d="M17.12 15l3.53 3.53-2.12 2.12L15 17.12l-3.54 3.54-2.12-2.12L12.88 15l-3.54-3.54 2.12-2.12L15 12.88l3.54-3.53 2.12 2.12z" />
                </svg>
                <span class="<?= esc_attr($compacto ? 'p-1.5 text-cuerpo-s' : 'p-[10px] text-cuerpo-l') ?> text-tinta whitespace-nowrap">
                    <?= esc_html($texto) ?>
                    <span class="sr-only" data-estado data-texto-cumplida="<?= esc_attr($cumplida) ?>" data-texto-pendiente="<?= esc_attr($pendiente) ?>">(<?= $pendiente // Ya escapado arriba. ?>)</span>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
