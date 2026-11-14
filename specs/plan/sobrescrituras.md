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
| `woocommerce/archive-product.php` | `woocommerce/templates/archive-product.php` | — | Listado provisional de Soluciones de sueño (solo maquetación, sin hooks). Se reemplaza por `archive-solucion.php` en la F4 | provisional |
| `woocommerce/content-product.php` | `woocommerce/templates/content-product.php` | — | Tarjeta provisional del listado. Se reemplaza en la F4 | provisional |
