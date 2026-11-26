<?php

defined('ABSPATH') || exit;

/*
 * Respuestas con datos de una visitante que ninguna caché de páginas debe guardar (auditoría del tema, 2026-11-26).
 * - Las páginas privadas (acceso, cuenta, canje y administrador) y las lecciones: imprimen el mensaje de un solo uso
 *   (correo, nombre, código de regalo) y la clave de reseteo de contraseña. WordPress solo manda no-cache con sesión iniciada.
 * - Cualquier página con carrito de WooCommerce: el mini carrito del pie y el contador del encabezado salen con el
 *   carrito de esa sesión. WooCommerce solo marca carrito, checkout y Mi cuenta.
 * Las páginas públicas sin carrito siguen cacheables: ahí el mini carrito sale vacío.
 */

/**
 * ¿La respuesta actual lleva datos de la visitante?
 */
function angelitozzz_sin_cache_necesario(): bool
{
    if (is_singular('leccion')) {
        return true;
    }

    foreach (angelitozzz_plantillas_privadas() as $plantilla) {
        if (is_page_template($plantilla)) {
            return true;
        }
    }

    if (!empty($_COOKIE['woocommerce_items_in_cart'])) {
        return true;
    }

    return function_exists('WC') && WC()->cart && !WC()->cart->is_empty();
}

add_action('template_redirect', 'angelitozzz_sin_cache', 1);

/**
 * Manda los encabezados no-cache y avisa a los plugins de caché.
 */
function angelitozzz_sin_cache(): void
{
    if (!angelitozzz_sin_cache_necesario()) {
        return;
    }

    nocache_headers();

    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE', true);
    }
}
