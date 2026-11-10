# Sobrescrituras de plugins

Toda plantilla de un plugin que el tema sobrescribe (`woocommerce/`, `tutor/`) se anota aquí. Antes de actualizar WooCommerce o Tutor LMS en producción: actualizar en el Local, revisar esta tabla y WooCommerce → Estado → Plantillas, y repetir las compras de prueba de `specs/plan/checklist-cursos.md`.

Reglas:

- Orden de preferencia: hook o filtro → CSS → sobrescribir la plantilla. Se sobrescribe solo lo que el diseño no puede lograr de otra forma.
- La copia conserva todos los `do_action()` y `apply_filters()` del original, en el mismo orden.
- En `woocommerce/`, el `@version` de la cabecera es el del original del que se copió (lo controla wp-woocommerce); nunca se sube a mano para silenciar el aviso.
- En `tutor/`, se anota la versión de Tutor LMS de la que se copió el archivo (Tutor no avisa de plantillas obsoletas).

## Versiones instaladas

| Plugin | Versión | Fecha |
| --- | --- | --- |
| WooCommerce | 11.2.2 | 2026-11-10 |
| Tutor LMS | 4.2.1 | 2026-11-10 |

## Plantillas

| Archivo del tema | Original | Versión del original | Motivo | Estado |
| --- | --- | --- | --- | --- |
| `woocommerce/archive-product.php` | `woocommerce/templates/archive-product.php` | — | Listado provisional de Soluciones de sueño (solo maquetación, sin hooks). Se reemplaza por `archive-solucion.php` en la F2 | provisional |
| `woocommerce/content-product.php` | `woocommerce/templates/content-product.php` | — | Tarjeta provisional del listado. Se reemplaza en la F2 | provisional |
