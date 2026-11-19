<?php

/**
 * Correo del pedido pagado (F5-c): los códigos de regalo (specs/cursos-datos.md → regalo, gracias.md
 * → regalo-listo) y el bloque del mes gratis en Mundo (leccion.md, opciones.md → mes-gratis-mundo).
 *
 * Solo en los correos a la clienta de pedido pagado ("Procesando" y "Completado"), antes del detalle
 * del pedido. Los datos salen del plugin angelitozzz-cursos (angelitozzz_cursos_regalos_de_pedido y
 * angelitozzz_cursos_pedido_con_mundo: Mundo solo si hay un curso propio que lo incluye, nunca por un
 * regalo); los textos de Mundo, de Ajustes del sitio, y sin enlace no se imprime. HTML con estilos en
 * línea (los clientes de correo no leen el CSS del tema) y versión de texto plano.
 */

defined('ABSPATH') || exit;

add_action('woocommerce_email_order_details', 'angelitozzz_correo_bloques_pedido', 5, 4);

/**
 * Imprime los regalos y Mundo en el correo del pedido pagado.
 *
 * @param WC_Order $order         Pedido.
 * @param bool     $sent_to_admin Si va al equipo.
 * @param bool     $plain_text    Texto plano.
 * @param WC_Email $email         Correo.
 */
function angelitozzz_correo_bloques_pedido($order, $sent_to_admin, $plain_text, $email = null): void
{
    if ($sent_to_admin || !$order instanceof WC_Order || !$email instanceof WC_Email || !$order->is_paid()) {
        return;
    }
    if (!in_array($email->id, ['customer_processing_order', 'customer_completed_order'], true)) {
        return;
    }

    $regalos = function_exists('angelitozzz_cursos_regalos_de_pedido') ? angelitozzz_cursos_regalos_de_pedido($order) : [];
    $mundo   = function_exists('angelitozzz_cursos_pedido_con_mundo') && angelitozzz_cursos_pedido_con_mundo($order)
        ? angelitozzz_correo_datos_mundo()
        : null;

    if ($plain_text) {
        angelitozzz_correo_texto_plano($regalos, $mundo);
        return;
    }
    angelitozzz_correo_html($regalos, $mundo);
}

/**
 * Textos de Mundo de Ajustes del sitio, o null si falta el enlace o el botón.
 *
 * @return array{enlace: string, titulo: string, texto: string, pie: string, boton: string}|null
 */
function angelitozzz_correo_datos_mundo(): ?array
{
    $opciones = function_exists('get_field') ? (get_field('opciones_mes_gratis_mundo', 'option') ?: []) : [];
    $enlace   = trim((string) ($opciones['enlace'] ?? ''));
    $boton    = trim((string) ($opciones['boton'] ?? ''));

    if ($enlace === '' || $boton === '' || angelitozzz_link_attrs(['url' => $enlace]) === '') {
        return null;
    }

    // El texto es un textarea con marcadores de color: en el correo va sin colores y con sus saltos.
    $texto = (string) ($opciones['texto'] ?? '');
    $texto = trim(wp_strip_all_tags(preg_replace('#<br\s*/?>#i', "\n", angelitozzz_highlight($texto))));

    return [
        'enlace' => $enlace,
        'titulo' => trim((string) ($opciones['titulo'] ?? '')),
        'texto'  => html_entity_decode($texto, ENT_QUOTES, 'UTF-8'),
        'pie'    => trim((string) ($opciones['pie'] ?? '')),
        'boton'  => $boton,
    ];
}

/**
 * URL de la página de canje.
 */
function angelitozzz_correo_url_canje(): string
{
    return function_exists('angelitozzz_cursos_url_pagina') ? angelitozzz_cursos_url_pagina('canjear-regalo') : home_url('/canjear-regalo/');
}

/**
 * Versión HTML.
 *
 * @param array<int, array{titulo: string, codigo: string}> $regalos Regalos.
 * @param array<string, string>|null                       $mundo   Textos de Mundo.
 */
function angelitozzz_correo_html(array $regalos, ?array $mundo): void
{
    $marca = '#234549';
    $nota  = '#6b6b6b';

    foreach ($regalos as $regalo) {
        ?>
        <div style="margin:0 0 24px;padding:24px;border-radius:12px;background:#f8f6f1;text-align:center;">
            <h2 style="margin:0 0 12px;color:<?= esc_attr($marca) ?>;"><?= esc_html__('¡Tu regalo está listo!', 'angelitozzz') ?></h2>
            <p style="margin:0 0 16px;">
                <?php
                /* translators: %s: nombre del curso regalado */
                echo esc_html(sprintf(__('Regalaste %s. Comparte este código con quien quieras y podrá entrar al curso con él.', 'angelitozzz'), $regalo['titulo']));
                ?>
            </p>
            <p style="margin:0 0 16px;padding:14px 20px;border:2px dashed #c4704a;border-radius:12px;background:#ffffff;font-size:28px;font-weight:bold;letter-spacing:2px;color:<?= esc_attr($marca) ?>;">
                <?= esc_html($regalo['codigo']) ?>
            </p>
            <p style="margin:0 0 8px;">
                <?= esc_html__('Se canjea aquí:', 'angelitozzz') ?>
                <a href="<?= esc_url(angelitozzz_correo_url_canje()) ?>" style="color:<?= esc_attr($marca) ?>;"><?= esc_html__('Canjear regalo', 'angelitozzz') ?></a>
            </p>
            <p style="margin:0;font-size:13px;color:<?= esc_attr($nota) ?>;"><?= esc_html__('El código es válido por 6 meses desde la compra. El acceso al curso empieza el día del canje.', 'angelitozzz') ?></p>
        </div>
        <?php
    }

    if ($mundo) {
        ?>
        <div style="margin:0 0 24px;padding:24px;border-radius:12px;background:<?= esc_attr($marca) ?>;color:#f8f6f1;text-align:center;">
            <?php if ($mundo['titulo'] !== '') : ?>
                <h2 style="margin:0 0 12px;color:#f8f6f1;"><?= esc_html($mundo['titulo']) ?></h2>
            <?php endif; ?>
            <?php if ($mundo['texto'] !== '') : ?>
                <p style="margin:0 0 12px;color:#d7d0b5;"><?= nl2br(esc_html($mundo['texto'])) ?></p>
            <?php endif; ?>
            <?php if ($mundo['pie'] !== '') : ?>
                <p style="margin:0 0 16px;font-style:italic;color:#f8f6f1;"><?= esc_html($mundo['pie']) ?></p>
            <?php endif; ?>
            <a href="<?= esc_url($mundo['enlace']) ?>" style="display:inline-block;padding:12px 24px;border-radius:100px;background:#f8f6f1;color:<?= esc_attr($marca) ?>;font-weight:bold;text-decoration:none;"><?= esc_html($mundo['boton']) ?></a>
        </div>
        <?php
    }
}

/**
 * Versión de texto plano.
 *
 * @param array<int, array{titulo: string, codigo: string}> $regalos Regalos.
 * @param array<string, string>|null                       $mundo   Textos de Mundo.
 */
function angelitozzz_correo_texto_plano(array $regalos, ?array $mundo): void
{
    foreach ($regalos as $regalo) {
        echo esc_html(mb_strtoupper(__('¡Tu regalo está listo!', 'angelitozzz'))) . "\n\n";
        /* translators: %s: nombre del curso regalado */
        echo esc_html(sprintf(__('Regalaste %s. Comparte este código con quien quieras y podrá entrar al curso con él.', 'angelitozzz'), $regalo['titulo'])) . "\n\n";
        echo esc_html($regalo['codigo']) . "\n\n";
        echo esc_html__('Se canjea aquí:', 'angelitozzz') . ' ' . esc_url_raw(angelitozzz_correo_url_canje()) . "\n";
        echo esc_html__('El código es válido por 6 meses desde la compra. El acceso al curso empieza el día del canje.', 'angelitozzz') . "\n\n";
        echo "==========\n\n";
    }

    if ($mundo) {
        if ($mundo['titulo'] !== '') {
            echo esc_html(mb_strtoupper($mundo['titulo'])) . "\n\n";
        }
        if ($mundo['texto'] !== '') {
            echo esc_html($mundo['texto']) . "\n\n";
        }
        if ($mundo['pie'] !== '') {
            echo esc_html($mundo['pie']) . "\n\n";
        }
        echo esc_html($mundo['boton']) . ': ' . esc_url_raw($mundo['enlace']) . "\n\n";
        echo "==========\n\n";
    }
}
