<?php

/**
 * Cuenta · Historial de pedidos (specs/usuario-historial-pedido.md, Figma 4164:25942).
 * Pedidos de WooCommerce de la usuaria de la sesión (wc_get_orders con customer_id, solo WC_Order,
 * compatible con HPOS), una fila por línea, los más recientes primero. El estado es el de cada línea
 * (angelitozzz_cursos_estado_linea() del plugin: el acceso al curso, o el código si es un regalo); en una
 * línea que no es curso, el del pedido de Woo. El color se elige por la clave, no por el texto traducido.
 * En móvil cada fila pasa a una tarjeta con sus etiquetas.
 */

defined('ABSPATH') || exit;

$por_pagina = 10;
$filas      = [];

if (function_exists('wc_get_orders') && is_user_logged_in()) {
    $pedidos = wc_get_orders([
        'customer_id' => get_current_user_id(),
        'orderby'     => 'date',
        'order'       => 'DESC',
        'limit'       => -1,
        'type'        => 'shop_order',
    ]);

    foreach ($pedidos as $pedido) {
        if (!$pedido instanceof WC_Order) {
            continue;
        }
        $fecha = $pedido->get_date_created();
        foreach ($pedido->get_items() as $item_id => $item) {
            if (!$item instanceof WC_Order_Item_Product) {
                continue;
            }
            $linea  = function_exists('angelitozzz_cursos_estado_linea')
                ? angelitozzz_cursos_estado_linea($pedido->get_id(), (int) $item_id)
                : [];
            $estado = (string) ($linea['texto'] ?? '');
            $clave  = (string) ($linea['clave'] ?? '');
            if ($estado === '') {
                $estado = wc_get_order_status_name($pedido->get_status());
            }
            $filas[] = [
                'numero' => $pedido->get_order_number(),
                'nombre' => $item->get_name(),
                'fecha'  => $fecha ? date_i18n('j \d\e F, Y', $fecha->getTimestamp()) : '',
                'precio' => wc_price((float) $pedido->get_line_total($item, true), ['currency' => $pedido->get_currency()]),
                'estado'  => $estado,
                'clave'   => $clave,
                'renovar' => (string) ($linea['renovar_url'] ?? ''), // F6: suscripción por vencer o vencida.
            ];
        }
    }
}

// Paginación propia de la vista (?pagina=), solo con más de 10 filas.
$total   = count($filas);
$paginas = (int) ceil($total / $por_pagina);
$actual  = isset($_GET['pagina']) ? max(1, absint($_GET['pagina'])) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- solo elige la página del listado.
$actual  = $paginas > 0 ? min($actual, $paginas) : 1;
$visibles = array_slice($filas, ($actual - 1) * $por_pagina, $por_pagina);

// Color del estado (propuesta del spec; en el diseño todos son salvia): argumento `color` de etiqueta.
$colores = [
    'por_expirar'        => 'estrella',
    'vencido'            => 'suave',
    'cancelado'          => 'suave',
    'pendiente'          => 'neutro',
    'regalo_sin_canjear' => 'estrella', // Falta compartir el código.
    'regalo_vencido'     => 'suave',
];
$columnas = [
    'numero' => __('ID del pedido', 'angelitozzz'),
    'nombre' => __('Nombre', 'angelitozzz'),
    'fecha'  => __('Fecha de compra', 'angelitozzz'),
    'precio' => __('Precio', 'angelitozzz'),
    'estado' => __('Estado', 'angelitozzz'),
];
?>
<div class="flex flex-col gap-[50px] max-md:gap-8">
    <?php get_template_part('template-parts/components/cuenta-pestanas', null, [
        'activa'   => 'historial',
        'pestanas' => [['id' => 'historial', 'texto' => __('Historial', 'angelitozzz'), 'icono' => 'pestana-historial']],
    ]); ?>

    <?php if (!$filas) : ?>
        <div class="flex flex-col items-start gap-6 rounded-[15px] bg-blanco p-8">
            <h2 class="text-h5 text-marca"><?= esc_html__('Aún no tienes compras', 'angelitozzz') ?></h2>
            <?php get_template_part('template-parts/components/boton', null, [
                'enlace' => ['url' => home_url('/soluciones-de-sueno/'), 'title' => __('Ver soluciones de sueño', 'angelitozzz')],
            ]); ?>
        </div>
    <?php else : ?>
        <table class="w-full max-w-[1121px] border-separate border-spacing-0 text-left max-md:block">
            <caption class="sr-only"><?= esc_html__('Historial de pedidos', 'angelitozzz') ?></caption>
            <thead class="max-md:sr-only">
                <tr>
                    <?php foreach ($columnas as $clave => $titulo) : ?>
                        <th scope="col" class="<?= esc_attr('border-b-[1.5px] border-[#c1c1c1] bg-blanco px-6 py-4 text-h5 text-marca first:rounded-tl-[15px] last:rounded-tr-[15px]' . ($clave === 'estado' ? ' text-center' : '')) ?>"><?= esc_html($titulo) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody class="max-md:flex max-md:flex-col max-md:gap-4">
                <?php foreach ($visibles as $i => $fila) :
                    $ultima = $i === count($visibles) - 1;
                    ?>
                    <tr class="max-md:flex max-md:flex-col max-md:gap-2 max-md:rounded-[15px] max-md:bg-blanco max-md:p-5">
                        <?php foreach ($columnas as $clave => $titulo) :
                            $celda = 'bg-blanco px-6 text-cuerpo text-tinta md:h-[60px] max-md:flex max-md:items-center max-md:justify-between max-md:gap-4 max-md:p-0 max-md:text-right'
                                . ($ultima ? ' md:first:rounded-bl-[15px] md:last:rounded-br-[15px]' : ' md:border-b-[1.5px] md:border-[#c1c1c1]')
                                . ($clave === 'estado' ? ' md:text-center' : '');
                            ?>
                            <td class="<?= esc_attr($celda) ?>">
                                <span class="text-rotulo text-marca md:hidden" aria-hidden="true"><?= esc_html($titulo) ?></span>
                                <?php if ($clave === 'numero') : ?>
                                    <span>#<?= esc_html($fila['numero']) ?></span>
                                <?php elseif ($clave === 'precio') : ?>
                                    <span><?= wp_kses_post($fila['precio']) ?></span>
                                <?php elseif ($clave === 'estado') : ?>
                                    <span class="inline-flex flex-col items-center gap-1 max-md:items-end">
                                        <?php get_template_part('template-parts/components/etiqueta', null, [
                                            'texto'  => $fila['estado'],
                                            'estilo' => 'tarjeta',
                                            'color'  => $colores[$fila['clave']] ?? 'salvia',
                                        ]); ?>
                                        <?php if ($fila['renovar'] !== '') : ?>
                                            <a href="<?= esc_url($fila['renovar']) ?>" class="text-rotulo-s text-marca underline underline-offset-2 hover:text-acento">
                                                <?= esc_html__('Renovar', 'angelitozzz') ?><span class="sr-only"> <?= esc_html($fila['nombre']) ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </span>
                                <?php else : ?>
                                    <span><?= esc_html($fila[$clave]) ?></span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($paginas > 1) : ?>
            <nav class="paginacion" aria-label="<?= esc_attr__('Páginas del historial', 'angelitozzz') ?>">
                <?= wp_kses_post(paginate_links([
                    'base'      => esc_url_raw(add_query_arg('pagina', '%#%')),
                    'format'    => '',
                    'current'   => $actual,
                    'total'     => $paginas,
                    'prev_text' => __('Anterior', 'angelitozzz'),
                    'next_text' => __('Siguiente', 'angelitozzz'),
                ])) ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
