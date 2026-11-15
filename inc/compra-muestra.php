<?php

/**
 * TEMPORAL (F4, solo maquetación): datos de muestra del carrito, el checkout y la página de gracias
 * (specs/carrito.md, checkout.md, gracias.md). Imitan lo que en la F5 darán WooCommerce
 * (WC()->cart, WC_Order), el plugin angelitozzz-cursos (duración, primera lección, códigos de regalo)
 * y ACF (opciones_mes_gratis_mundo). Se borra en la F5 junto con la vista previa del mu-plugin.
 */

defined('ABSPATH') || exit;

/**
 * Carrito de muestra, en el formato que reciben cart.php y review-order.php.
 * Los ítems son los del drawer (inc/solucion-muestra/carrito.php), para que todo coincida.
 */
function angelitozzz_compra_muestra_carrito(): array
{
    $drawer = function_exists('angelitozzz_solucion_muestra_parte') ? angelitozzz_solucion_muestra_parte('carrito') : [];
    $items  = [];

    foreach ((array) ($drawer['items'] ?? []) as $item) {
        $items[] = [
            'clave'  => (string) ($item['clave'] ?? ''),          // woo: $cart_item_key
            'imagen' => $item['imagen'] ?? '',                      // woo: imagen destacada de la solución del curso
            'tipo'   => (string) ($item['tipo'] ?? 'Curso'),
            'nombre' => (string) ($item['nombre'] ?? ''),           // woo: $_product->get_name()
            'acceso' => (string) ($item['acceso'] ?? ''),           // f5: duración del curso (plugin)
            'precio' => (string) ($item['precio'] ?? ''),           // woo: WC()->cart->get_product_subtotal()
            'regalo' => false,                                      // f5: ítem marcado como regalo
            'quitar' => '#',                                        // woo: wc_get_cart_remove_url($cart_item_key)
        ];
    }

    return [
        'items'     => $items,
        'subtotal'  => (string) ($drawer['subtotal'] ?? ''),        // woo: WC()->cart->get_cart_subtotal()
        'descuento' => '',                                          // woo: solo con cupón aplicado
        'impuesto'  => [],                                          // woo: solo con impuestos configurados (IGV)
        'total'     => (string) ($drawer['subtotal'] ?? ''),        // woo: WC()->cart->get_total()
    ];
}

/**
 * Pedido de muestra para la página de gracias según el estado de la vista previa:
 * pagado (1 curso), varios (2 cursos), pendiente, fallido, regalo (2 regalos) y mixto (1 curso + 1 regalo).
 */
function angelitozzz_compra_muestra_pedido(string $estado): array
{
    $curso = static fn(string $nombre, string $acceso, string $slug): array => [
        'nombre'          => $nombre,
        'acceso'          => $acceso,                                           // f5: angelitozzz_cursos_* (duración)
        'primera_leccion' => home_url('/soluciones-de-sueno/' . $slug . '/'),   // f5: URL de la primera lección
    ];
    $regalo = static fn(string $nombre, string $codigo): array => [
        'curso'  => $nombre,
        'codigo' => $codigo, // f5: código de regalo que genera el plugin al pagarse el pedido
    ];

    $pedido = [
        'numero'   => '1024',                 // woo: $order->get_order_number()
        'nombre'   => 'María',                // woo: $order->get_billing_first_name()
        'estado'   => 'pagado',               // woo: processing|completed → pagado, pending|on-hold → pendiente, failed → fallido
        'cursos'   => [],                     // f5: líneas del pedido con curso que no son regalo
        'regalos'  => [],                     // f5: líneas compradas como regalo
        'pago_url' => home_url('/finalizar-compra/'), // woo: $order->get_checkout_payment_url()
        'pedidos'  => home_url('/cuenta/pedidos/'),
        'cursos_url' => home_url('/cuenta/cursos/'),
    ];

    switch ($estado) {
        case 'varios':
            $pedido['cursos'] = [
                $curso('Recién Nacidos Go', 'Acceso por 4 meses', 'recien-nacidos-go'),
                $curso('Curso Para bebés de 4 a 9 meses', 'Acceso por 6 meses', 'horarios-ideales'),
            ];
            break;
        case 'pendiente':
            $pedido['estado'] = 'pendiente';
            $pedido['cursos'] = [$curso('Recién Nacidos Go', 'Acceso por 4 meses', 'recien-nacidos-go')];
            break;
        case 'fallido':
            $pedido['estado'] = 'fallido';
            $pedido['cursos'] = [$curso('Recién Nacidos Go', 'Acceso por 4 meses', 'recien-nacidos-go')];
            break;
        case 'regalo':
            $pedido['regalos'] = [
                $regalo('Recién Nacidos Go', 'ANGEL-7K2Q-94XM'),
                $regalo('A Dormir Por Fin', 'ANGEL-3PZD-61RC'),
            ];
            break;
        case 'mixto':
            $pedido['cursos']  = [$curso('Recién Nacidos Go', 'Acceso por 4 meses', 'recien-nacidos-go')];
            $pedido['regalos'] = [$regalo('Recién Nacidos Go', 'ANGEL-7K2Q-94XM')];
            break;
        default: // pagado
            $pedido['cursos'] = [$curso('Recién Nacidos Go', 'Acceso por 4 meses', 'recien-nacidos-go')];
    }

    return $pedido;
}

/**
 * Grupo opciones_mes_gratis_mundo de muestra (specs/opciones.md), mientras el campo no exista en ACF.
 * El enlace de muestra lleva a la página de Mundo Angelitozzz del sitio.
 */
function angelitozzz_compra_muestra_mes_gratis(): array
{
    return [
        'enlace' => home_url('/mundo-angelitozzz/'),
        'titulo' => 'Tu mes gratis en Mundo Angelitozzz',
        'texto'  => 'Incluido con tu curso: sesiones grupales en vivo con Bárbara todos los miércoles a las 10:00am (Perú/Colombia) y la videoteca de talleres.',
        'pie'    => 'Si ya lo activaste, puedes ignorar este mensaje.',
        'boton'  => 'Activar mi mes gratis',
    ];
}
