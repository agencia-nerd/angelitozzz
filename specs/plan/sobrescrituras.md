# Sobrescrituras de plugins

Toda plantilla de un plugin que el tema sobrescribe (`woocommerce/`) se anota aquí. Desde 2026-11-13 no hay Tutor LMS: los cursos son del plugin propio `angelitozzz-cursos`, que no sobrescribe plantillas. Antes de actualizar WooCommerce o el plugin de Niubiz en producción: actualizar en el Local, revisar esta tabla y WooCommerce → Estado → Plantillas, y repetir las compras de prueba de `specs/plan/checklist-cursos.md`.

Reglas:

- Orden de preferencia: hook o filtro → CSS → sobrescribir la plantilla. Se sobrescribe solo lo que el diseño no puede lograr de otra forma.
- La copia conserva todos los `do_action()` y `apply_filters()` del original, en el mismo orden.
- En `woocommerce/`, el `@version` de la cabecera es el del original del que se copió (lo controla wp-woocommerce); nunca se sube a mano para silenciar el aviso.

## Versiones instaladas

| Plugin | Versión | Fecha |
| --- | --- | --- |
| WooCommerce | 11.2.2 | 2026-11-10 |

## Plantillas

| Archivo del tema | Original | Versión del original | Motivo | Estado |
| --- | --- | --- | --- | --- |
| `woocommerce/cart/cart.php` | `woocommerce/templates/cart/cart.php` | pendiente (F5) | Carrito del diseño: filas con papelera, sin cantidad, y `resumen-compra` (`carrito.md`) | maquetación (F4) |
| `woocommerce/cart/cart-empty.php` | `woocommerce/templates/cart/cart-empty.php` | pendiente (F5) | Carrito vacío con botón a Soluciones de sueño | maquetación (F4) |
| `woocommerce/checkout/form-checkout.php` | `woocommerce/templates/checkout/form-checkout.php` | pendiente (F5) | Dos columnas del diseño; "Pagar ahora" en la columna derecha (`checkout.md`) | maquetación (F4) |
| `woocommerce/checkout/form-login.php` | `woocommerce/templates/checkout/form-login.php` | pendiente (F5) | "Iniciar sesión" siempre visible, no plegado | maquetación (F4) |
| `woocommerce/checkout/form-billing.php` | `woocommerce/templates/checkout/form-billing.php` | pendiente (F5) | Solo los campos del diseño, subrayados; correo y contraseña sin sesión | maquetación (F4) |
| `woocommerce/checkout/payment.php` | `woocommerce/templates/checkout/payment.php` | pendiente (F5) | Caja "Método de pago" con los logos de tarjetas | maquetación (F4) |
| `woocommerce/checkout/terms.php` | `woocommerce/templates/checkout/terms.php` | pendiente (F5) | Texto de privacidad y casilla de términos del diseño | maquetación (F4) |
| `woocommerce/checkout/review-order.php` | `woocommerce/templates/checkout/review-order.php` | pendiente (F5) | Resumen con el componente `resumen-compra` | maquetación (F4) |
| `woocommerce/checkout/form-coupon.php` | `woocommerce/templates/checkout/form-coupon.php` | pendiente (F5) | Cupón como formulario aparte en la columna derecha | maquetación (F4) |
| `woocommerce/checkout/thankyou.php` | `woocommerce/templates/checkout/thankyou.php` | pendiente (F5) | Gracias: confirmación, regalo listo y banner de Mundo (`gracias.md`) | maquetación (F4) |

Estado `maquetación (F4)`: solo diseño, con datos de muestra (`inc/compra-muestra.php`) y los hooks del original como comentarios `// woo:`. En la F5, wp-woocommerce los conecta, conserva todos los `do_action()` y pone el `@version` del original.
