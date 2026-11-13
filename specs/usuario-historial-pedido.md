# Página: Cuenta: historial de pedidos

- **Resumen:** La alumna ve sus compras: qué compró, cuándo, cuánto pagó y si su acceso sigue activo.
- **Fuente:** `figma:usuario-historial-pedido`
- **Plantilla:** `pages/template-cuenta.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** seccion-entera
- **Skills usadas:** ninguna. Los datos son de los pedidos de WooCommerce y del acceso del plugin `angelitozzz-cursos`
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Cuenta propia (sin Tutor LMS desde 2026-11-13): página `/cuenta/` con la plantilla `pages/template-cuenta.php`, que comparte el marco y el menú lateral `cuenta-menu` (`_shared.md`) y carga la vista de la URL (`/cuenta/`, `/cuenta/perfil/`, `/cuenta/cursos/`, `/cuenta/pedidos/`, endpoints que registra el plugin `angelitozzz-cursos`). Sin sesión, redirige a `/iniciar-sesion/` con `redirect_to`. "Mi cuenta" de Woo (`/mi-cuenta/`) redirige aquí. Los datos los da el plugin con sus funciones públicas `angelitozzz_cursos_*`, siempre de la usuaria con sesión (nunca de un ID recibido en la URL).
    - Vista `/cuenta/pedidos/`. Los pedidos se leen con `wc_get_orders()` filtrados por la usuaria con sesión (`customer_id`), solo con `WC_Order` (compatible con HPOS); el estado de cada fila sale de la inscripción del plugin.
    - Decisión del usuario (2026-11-13): "Estado" es el del acceso al curso, no el del pedido: Activo, Por expirar (15 días o menos para que venza el acceso), Vencido, Cancelado (pedido cancelado o reembolsado) y Pendiente (pedido sin pagar). Cada estado tiene su color (propuesta: Activo salvia `--color-etiqueta`, Por expirar `--color-estrella`, Vencido y Cancelado `--color-tinta-suave`, Pendiente `--color-neutro`); en el diseño todos son salvia.
    - Una fila por curso del pedido: un pedido con 2 etapas de Horarios Ideales son 2 filas con el mismo número de pedido. "Llamada con Bárbara" del diseño es un ejemplo de producto que no es curso: se muestra igual, con el estado del pedido.
    - La pestaña única "Historial" del diseño (con ícono de corazón) no cambia nada: se deja como título de la vista con el ícono de historial.
    - Sin campos ACF. `h1`: el nombre de la alumna en el menú. Título SEO "Historial de pedidos | Angelitozzz", con `noindex`.
    - Cintillo, header y footer salen de `header.php` y `footer.php`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: seccion-entera

- **Origen:** `specs/figma/lectura/usuario-historial-pedido/seccion-entera.ref.txt` (nodo 4164:25908: menú 4164:25910, historial 4164:25942)
- **Destino:** `pages/template-cuenta.php`, `template-parts/pages/cuenta/pedidos.php`, `template-parts/components/cuenta-menu.php`, `template-parts/components/cuenta-pestanas.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** menú `cuenta-menu` con "Historial de pedidos" activo. Encima, la tarjeta "Historial" con el estilo de `cuenta-pestanas` (activa). Tabla real (`<table>` con `<caption>` "Historial de pedidos" solo para lectores, `<th scope="col">`): columnas "ID del pedido", "Nombre", "Fecha de compra", "Precio" y "Estado"; cabecera blanca con radio 15 arriba, filas blancas de 60 px con borde inferior `#c1c1c1` de 1,5 px, la última con radio abajo. ID con el número real de Woo ("#1234"), fecha "5 de noviembre, 2025", precio formateado por Woo, estado con el componente `etiqueta` estilo `tarjeta` y el color de su estado. Más recientes primero; paginación solo con más de 10 filas. Sin pedidos (propuesta): "Aún no tienes compras" y `boton` "Ver soluciones de sueño". Sin acciones (ver detalle o factura) en el diseño. Móvil (propuesta): cada fila pasa a una tarjeta con las 5 etiquetas y sus valores, sin scroll horizontal.
- **Indicaciones del diseño:**
    - nota: drawer https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4164-25910&t=lySNWh2dFHTQgiTC-4
    - nota: historial https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4164-25942&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (de Woo y del plugin, no campos): 5 filas de ejemplo (Horarios 9 a 15 meses $37 Activo, Recién Nacidos Go $97 Cancelado, Horarios 4 a 9 meses $37 Por expirar, Angelita Pro $67 Activo, Llamada con Bárbara $30 Activo); todas con ID "#0001".

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Solo se ven los pedidos de la usuaria con sesión
- [ ] Es una `<table>` con encabezados de columna y `caption`
- [ ] El estado es el del acceso (Activo, Por expirar, Vencido, Cancelado, Pendiente), cada uno con su color y su texto
- [ ] Un pedido con varios cursos muestra una fila por curso
- [ ] Sin pedidos se ve el estado vacío
- [ ] En móvil las filas pasan a tarjetas, sin scroll horizontal
