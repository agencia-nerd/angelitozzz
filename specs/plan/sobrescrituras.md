# Sobrescrituras de plugins

Toda plantilla de un plugin que el tema sobrescribe (`woocommerce/`) se anota aquí. Desde 2026-11-13 no hay Tutor LMS: los cursos son del plugin propio `angelitozzz-cursos`, que no sobrescribe plantillas. Antes de actualizar WooCommerce o el plugin de Openpay en producción: actualizar en el Local, revisar esta tabla y WooCommerce → Estado → Plantillas, y repetir las compras de prueba de `specs/plan/checklist-cursos.md` (y `specs/pruebas/cdp-compra.mjs`).

Reglas:

- Orden de preferencia: hook o filtro → CSS → sobrescribir la plantilla. Se sobrescribe solo lo que el diseño no puede lograr de otra forma.
- La copia conserva todos los `do_action()` y `apply_filters()` del original, en el mismo orden.
- En `woocommerce/`, el `@version` de la cabecera es el del original del que se copió (lo controla wp-woocommerce, `check.mjs`); nunca se sube a mano para silenciar el aviso.
- Lo que Woo imprime por defecto en esos ganchos y el diseño reemplaza se quita en `inc/woocommerce/plantillas.php` (no en las plantillas).

## Versiones instaladas

| Plugin | Versión | Fecha |
| --- | --- | --- |
| WooCommerce | 11.3.0 | 2026-11-18 |
| Openpay Cards (pasarela, oficial de Openpay) | 3.2.0 | 2026-11-18 |

## Plantillas

| Archivo del tema | Original | Versión del original | Motivo | Estado |
| --- | --- | --- | --- | --- |
| `woocommerce/cart/cart.php` | `woocommerce/templates/cart/cart.php` | 11.3.0 | Carrito del diseño: filas con papelera, sin cantidad, y `resumen-compra` (`carrito.md`) | conectada (F5-b) |
| `woocommerce/cart/cart-empty.php` | `woocommerce/templates/cart/cart-empty.php` | 7.0.1 | Carrito vacío con botón a Soluciones de sueño | conectada (F5-b) |
| `woocommerce/checkout/form-checkout.php` | `woocommerce/templates/checkout/form-checkout.php` | 9.4.0 | Dos columnas del diseño; "Pagar ahora" en la columna derecha con `form=` (`checkout.md`) | conectada (F5-b) |
| `woocommerce/checkout/form-login.php` | `woocommerce/templates/checkout/form-login.php` | 10.0.0 | "Iniciar sesión" siempre visible, no plegado | conectada (F5-b) |
| `woocommerce/checkout/form-billing.php` | `woocommerce/templates/checkout/form-billing.php` | 3.6.0 | Solo los campos del diseño, subrayados (los define el plugin `angelitozzz-cursos`); correo y contraseña sin sesión | conectada (F5-b) |
| `woocommerce/checkout/payment.php` | `woocommerce/templates/checkout/payment.php` | 10.9.0 | Caja "Método de pago" con los logos de tarjetas; términos, nonce y ganchos del botón dentro de `#payment` | conectada (F5-b) |
| `woocommerce/checkout/terms.php` | `woocommerce/templates/checkout/terms.php` | 3.4.0 | Texto de privacidad y casilla de términos con el estilo del diseño (los textos son los de Woo) | conectada (F5-b) |
| `woocommerce/checkout/review-order.php` | `woocommerce/templates/checkout/review-order.php` | 11.2.2 | Resumen con el componente `resumen-compra` | conectada (F5-b) |
| `woocommerce/checkout/form-coupon.php` | `woocommerce/templates/checkout/form-coupon.php` | 9.8.0 | Cupón como formulario aparte en la columna derecha, siempre visible | conectada (F5-b) |
| `woocommerce/checkout/thankyou.php` | `woocommerce/templates/checkout/thankyou.php` | 11.2.0 | Gracias: confirmación, regalo listo y banner de Mundo (`gracias.md`) | conectada (F5-b) |

## Ajustes a plugins de terceros sin tocar sus archivos

- **Openpay Cards:** carga sus scripts (también el antifraude de `js.openpay.pe`) en todo el sitio; el plugin `angelitozzz-cursos` los saca fuera del checkout (`includes/pagos.php`). Su CSS en línea del checkout (`.form-row` con `!important`, `.hidden { visibility: hidden }`) se neutraliza para lo nuestro en `src/css/pasarela.css`.
- **WooCommerce:** `checkout.js` cambia el texto de `#place_order` y borra la flecha del diseño; `src/js/modules/checkout-boton.js` la repone.
