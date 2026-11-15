<?php

/**
 * Gracias · confirmacion (specs/gracias.md): estado del pago y cursos propios del pedido.
 * Propuesta sin Figma con la tarjeta confirmacion-tarjeta y la caja de acceso de "Canje exitoso".
 *
 * $args:
 * - pedido (array: estado pagado|pendiente|fallido, nombre, cursos[nombre, acceso, primera_leccion],
 *           pago_url, pedidos, cursos_url). F4: muestra de inc/compra-muestra.php (woo: $order).
 */

defined('ABSPATH') || exit;

$pedido = (array) ($args['pedido'] ?? []);
$estado = (string) ($pedido['estado'] ?? 'pagado');
$cursos = array_values(array_filter((array) ($pedido['cursos'] ?? []), 'is_array'));
$nombre = trim((string) ($pedido['nombre'] ?? '')); // woo: $order->get_billing_first_name()

$ver_pedido = [
    'url'   => (string) ($pedido['pedidos'] ?? home_url('/cuenta/pedidos/')),
    'title' => __('Ver mi pedido', 'angelitozzz'),
];
$clase_enlace = 'text-rotulo text-marca underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-marca';

if ($estado === 'pendiente') {
    get_template_part('template-parts/components/confirmacion-tarjeta', null, [
        'titulo'          => __('Estamos confirmando tu pago', 'angelitozzz'),
        'pastilla'        => __('Pago en revisión', 'angelitozzz'),
        'pastilla_estilo' => 'neutro',
        'texto'           => __('Te avisaremos por correo apenas se confirme y tu curso aparecerá en tu cuenta.', 'angelitozzz'),
    ]);
    echo angelitozzz_link($ver_pedido, ['class' => $clase_enlace]);
    get_template_part('template-parts/components/confirmacion-tarjeta-fin');
    return;
}

if ($estado === 'fallido') {
    get_template_part('template-parts/components/confirmacion-tarjeta', null, [
        'titulo'          => __('No pudimos procesar tu pago', 'angelitozzz'),
        'pastilla'        => __('Pago no realizado', 'angelitozzz'),
        'pastilla_estilo' => 'neutro',
        'texto'           => __('No se hizo ningún cobro. Puedes intentarlo de nuevo con otra tarjeta.', 'angelitozzz'),
    ]);
    get_template_part('template-parts/components/boton', null, [
        // woo: $order->get_checkout_payment_url()
        'enlace' => ['url' => (string) ($pedido['pago_url'] ?? ''), 'title' => __('Intentar de nuevo', 'angelitozzz')],
    ]);
    get_template_part('template-parts/components/confirmacion-tarjeta-fin');
    return;
}

// Pagado: solo con cursos propios (un pedido solo de regalos no imprime esta tarjeta).
if (!$cursos) {
    return;
}

$varios = count($cursos) > 1;
$titulo = $nombre !== ''
    /* translators: %s: nombre de la alumna */
    ? sprintf(__('¡Gracias por tu compra, %s!', 'angelitozzz'), $nombre)
    : __('¡Gracias por tu compra!', 'angelitozzz');

get_template_part('template-parts/components/confirmacion-tarjeta', null, [
    'titulo'   => $titulo,
    'icono'    => 'check',
    'pastilla' => __('✓ Compra exitosa', 'angelitozzz'),
    'texto'    => $varios
        ? __('Ya tienes acceso a tus cursos. Te enviamos el detalle de tu pedido por correo.', 'angelitozzz')
        : __('Ya tienes acceso a tu curso. Te enviamos el detalle de tu pedido por correo.', 'angelitozzz'),
]);
?>
<ul class="flex w-full flex-col gap-3">
    <?php foreach ($cursos as $curso) : ?>
        <li class="rounded-xl bg-fondo px-5 py-[14px] text-cuerpo text-marca">
            <?= esc_html(trim((string) ($curso['nombre'] ?? '') . ' · ' . (string) ($curso['acceso'] ?? ''), ' ·')) ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php
get_template_part('template-parts/components/boton', null, $varios
    ? ['enlace' => ['url' => (string) ($pedido['cursos_url'] ?? home_url('/cuenta/cursos/')), 'title' => __('Ir a Mis cursos', 'angelitozzz')]]
    : ['enlace' => ['url' => (string) ($cursos[0]['primera_leccion'] ?? ''), 'title' => __('Empezar el curso', 'angelitozzz')]]
);
echo angelitozzz_link($ver_pedido, ['class' => $clase_enlace]);
get_template_part('template-parts/components/confirmacion-tarjeta-fin');
