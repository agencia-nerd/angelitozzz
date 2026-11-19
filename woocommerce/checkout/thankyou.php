<?php

/**
 * Gracias por tu compra (specs/gracias.md): endpoint order-received del checkout de WooCommerce.
 *
 * Sobrescribe woocommerce/templates/checkout/thankyou.php. Conserva la estructura y los hooks del original
 * (woocommerce_before_thankyou, woocommerce_thankyou_<método>, woocommerce_thankyou y el filtro
 * woocommerce_thankyou_order_failed_text); el aviso de pago fallido, order-received.php y el resumen del
 * original se reemplazan por la tarjeta del diseño. La tabla de detalle que Woo engancha en
 * woocommerce_thankyou la quita inc/woocommerce/plantillas.php; lo que agregue la pasarela sí se muestra.
 *
 * Datos: el pedido de WooCommerce y el plugin angelitozzz-cursos (solo sus funciones públicas):
 * - angelitozzz_cursos_pedido_cursos()  cursos propios del pedido (sin regalos, solo si es de la usuaria).
 * - angelitozzz_cursos_pedido_regalos() códigos de regalo del pedido (F5-c; sin ella, ningún regalo).
 * - angelitozzz_cursos_url_pagina()     "Ver mi pedido" (cuenta/pedidos) y "Mis cursos" (cuenta/cursos).
 *
 * Qué se ve:
 * - pagado, solo cursos propios → confirmacion + mes-gratis-mundo
 * - pagado, solo regalos        → una tarjeta regalo-listo por regalo (sin Mundo)
 * - pagado, mixto               → las tres, en ese orden
 * - pendiente o fallido         → solo confirmacion en su estado
 * - cancelado o reembolsado     → solo confirmacion: pedido sin efecto, con WhatsApp y "Ver mi pedido"
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 11.2.0
 *
 * @var WC_Order|false $order
 */

defined('ABSPATH') || exit;
?>
<div class="woocommerce-order flex flex-col gap-8 px-4 py-30 max-md:py-14">
    <?php
    if ($order) :

        do_action('woocommerce_before_thankyou', $order->get_id());

        // Estado del pago (specs/gracias.md → confirmacion).
        if ($order->has_status('failed')) {
            $estado = 'fallido';
        } elseif ($order->has_status(['cancelled', 'refunded'])) {
            $estado = $order->has_status('refunded') ? 'reembolsado' : 'cancelado'; // Pedido sin efecto.
        } elseif ($order->has_status(['processing', 'completed'])) {
            $estado = 'pagado';
        } else {
            $estado = 'pendiente'; // pending, on-hold y cualquier otro estado sin pago confirmado.
        }
        $pagado = $estado === 'pagado';

        // El campo de facturación es "Nombres y Apellidos": el saludo usa solo el primer nombre.
        $nombre_completo = trim((string) $order->get_billing_first_name());
        $nombre          = $nombre_completo !== '' ? (string) preg_split('/\s+/u', $nombre_completo)[0] : '';

        // Cursos propios del pedido. Si el pedido está pagado y la inscripción aún no existe (la pasarela avisa
        // unos segundos después), igual se muestra pagado: la lección comprueba el acceso.
        $cursos = [];
        if (function_exists('angelitozzz_cursos_pedido_cursos')) {
            foreach ((array) angelitozzz_cursos_pedido_cursos((int) $order->get_id()) as $curso) {
                if (!is_array($curso)) {
                    continue;
                }
                $meses    = (int) ($curso['duracion_mes'] ?? 0);
                $cursos[] = [
                    'nombre'          => (string) ($curso['titulo'] ?? ''),
                    'acceso'          => $meses > 0
                        /* translators: %d: meses de acceso al curso */
                        ? sprintf(_n('Acceso por %d mes', 'Acceso por %d meses', $meses, 'angelitozzz'), $meses)
                        : __('Acceso ilimitado', 'angelitozzz'),
                    'primera_leccion' => (string) ($curso['primera_leccion_url'] ?? ''),
                ];
            }
        }

        // Regalos: el código existe solo con el pedido pagado (F5-c del plugin).
        $regalos = [];
        if ($pagado && function_exists('angelitozzz_cursos_pedido_regalos')) {
            foreach ((array) angelitozzz_cursos_pedido_regalos((int) $order->get_id()) as $regalo) {
                if (!is_array($regalo)) {
                    continue;
                }
                $regalos[] = [
                    'curso'  => (string) ($regalo['titulo'] ?? $regalo['curso'] ?? ''),
                    'codigo' => (string) ($regalo['codigo'] ?? ''),
                ];
            }
            $regalos = array_values(array_filter($regalos, static fn(array $r): bool => $r['codigo'] !== ''));
        }

        $hay_cuenta = function_exists('angelitozzz_cursos_url_pagina');

        $texto_fallido_defecto = __('No se hizo ningún cobro. Puedes intentarlo de nuevo con otra tarjeta.', 'angelitozzz');
        /** Filtro del original (WooCommerce 11.2.0): mensaje del pago fallido. */
        $texto_fallido = apply_filters('woocommerce_thankyou_order_failed_text', $texto_fallido_defecto, $order);

        $pedido = [
            'estado'        => $estado,
            'nombre'        => $nombre,
            'cursos'        => $cursos,
            'pago_url'      => $order->get_checkout_payment_url(),
            'texto_fallido' => is_string($texto_fallido) ? $texto_fallido : $texto_fallido_defecto,
            'pedidos'       => $hay_cuenta ? angelitozzz_cursos_url_pagina('cuenta', 'pedidos') : $order->get_view_order_url(),
            'cursos_url'    => $hay_cuenta ? angelitozzz_cursos_url_pagina('cuenta', 'cursos') : home_url('/'),
        ];

        $hay_confirmacion = !$pagado || $cursos;

        if ($hay_confirmacion) {
            get_template_part('template-parts/pages/gracias/confirmacion', null, ['pedido' => $pedido]);
        }

        foreach ($regalos as $i => $regalo) {
            get_template_part('template-parts/pages/gracias/regalo-listo', null, [
                'regalo' => $regalo,
                // El primer título visible de la página es el h1.
                'nivel'  => (!$hay_confirmacion && $i === 0) ? 'h1' : 'h2',
            ]);
        }

        // Pagado sin cursos ni regalos que mostrar (p. ej. el pedido no es de la sesión o el plugin está
        // inactivo): el mensaje de pedido recibido del original, para que la página no quede vacía.
        if ($pagado && !$cursos && !$regalos) {
            wc_get_template('checkout/order-received.php', ['order' => $order]);
        }

        // Mes gratis en Mundo: pedido pagado con al menos un curso propio que lo incluye ("Mes gratis en
        // Mundo" del constructor; Angelita Pro no). Los regalos no lo llevan: quien lo recibe lo ve en su
        // primera lección.
        if ($pagado && $cursos && function_exists('angelitozzz_cursos_pedido_con_mundo') && angelitozzz_cursos_pedido_con_mundo($order)) {
            get_template_part('template-parts/components/banner-mundo', null, [
                'titulo'   => __('Espera — hay algo más esperándote', 'angelitozzz'),
                'variante' => 'confirmacion',
            ]);
        }

        do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id());
        do_action('woocommerce_thankyou', $order->get_id());

    else :

        wc_get_template('checkout/order-received.php', ['order' => false]);

    endif;
    ?>
</div>
