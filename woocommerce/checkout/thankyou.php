<?php

/**
 * Gracias por tu compra (specs/gracias.md): endpoint order-received del checkout de WooCommerce.
 *
 * Original: woocommerce/templates/checkout/thankyou.php. F4, solo maquetación: el pedido es la muestra
 * de inc/compra-muestra.php (?vista_previa=gracias&estado=…); wp-woocommerce (F5) cambia cada `// woo:`
 * por WooCommerce, conserva los hooks del original en su lugar y pone su @version.
 *
 * Qué se ve:
 * - pagado, solo cursos propios → confirmacion + mes-gratis-mundo
 * - pagado, solo regalos        → una tarjeta regalo-listo por regalo (sin Mundo)
 * - pagado, mixto               → las tres, en ese orden
 * - pendiente o fallido         → solo confirmacion en su estado
 *
 * @var WC_Order|false $order
 */

defined('ABSPATH') || exit;

// woo: $order (WC_Order); estado: processing|completed → pagado, pending|on-hold → pendiente, failed → fallido.
$pedido = function_exists('angelitozzz_compra_muestra_pedido')
    ? angelitozzz_compra_muestra_pedido(isset($estado_muestra) ? (string) $estado_muestra : 'pagado')
    : [];

$estado  = (string) ($pedido['estado'] ?? 'pagado');
$pagado  = $estado === 'pagado';
$cursos  = array_values(array_filter((array) ($pedido['cursos'] ?? []), 'is_array'));
$regalos = $pagado ? array_values(array_filter((array) ($pedido['regalos'] ?? []), 'is_array')) : [];
$hay_confirmacion = !$pagado || $cursos;
?>
<div class="woocommerce-order flex flex-col gap-8 px-4 py-30 max-md:py-14">
    <?php
    // woo: if ( $order ) :
    // woo: do_action( 'woocommerce_before_thankyou', $order->get_id() );

    // woo: el original imprime aquí el aviso de pago fallido u order-received.php y el resumen del pedido;
    // el diseño los reemplaza por la tarjeta de confirmación.
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

    // Mes gratis en Mundo: pedido pagado con al menos un curso propio.
    if ($pagado && $cursos) {
        get_template_part('template-parts/components/banner-mundo', null, [
            'titulo'   => __('Espera — hay algo más esperándote', 'angelitozzz'),
            'variante' => 'confirmacion',
        ]);
    }

    // woo: do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
    // woo: do_action( 'woocommerce_thankyou', $order->get_id() );
    // woo: else : wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); endif;
    ?>
</div>
