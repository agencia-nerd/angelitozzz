# Página: Cuenta: cursos inscritos

- **Resumen:** La alumna ve todos sus cursos, los que lleva en curso y los que terminó, con su avance, y entra a seguir aprendiendo.
- **Fuente:** `figma:usuario-cursos`
- **Plantilla:** `pages/template-cuenta.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** seccion-entera
- **Skills usadas:** wp-helper (imágenes). Los datos son del plugin `angelitozzz-cursos`
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Cuenta propia (sin Tutor LMS desde 2026-11-13): página `/cuenta/` con la plantilla `pages/template-cuenta.php`, que comparte el marco y el menú lateral `cuenta-menu` (`_shared.md`) y carga la vista de la URL (`/cuenta/`, `/cuenta/perfil/`, `/cuenta/cursos/`, `/cuenta/pedidos/`, endpoints que registra el plugin `angelitozzz-cursos`). Sin sesión, redirige a `/iniciar-sesion/` con `redirect_to`. "Mi cuenta" de Woo (`/mi-cuenta/`) redirige aquí. Los datos los da el plugin con sus funciones públicas `angelitozzz_cursos_*`, siempre de la usuaria con sesión (nunca de un ID recibido en la URL).
    - Vista `/cuenta/cursos/` con 3 pestañas: Inscritos (todos), Activos y Completados (`?pestana=inscritos|activos|completados`, valor de una lista permitida).
    - Decisión del usuario (2026-11-13): las vistas "Cursos inscritos" y "Completados" que faltan en el diseño son propuesta con la misma tarjeta; cambia el pie (ver `Comportamiento`). Cada pestaña muestra su número.
    - El ítem del menú "Cursos inscritos" abre la pestaña "Cursos activos", como en el diseño.
    - Sin campos ACF. `h1`: el nombre de la alumna en el menú. Título SEO "Mis cursos | Angelitozzz", con `noindex`.
    - Cintillo, header y footer salen de `header.php` y `footer.php`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: seccion-entera

- **Origen:** `specs/figma/lectura/usuario-cursos/seccion-entera.ref.txt` (nodo 4164:25446: menú 4164:25448, cursos activos 4164:25480)
- **Destino:** `pages/template-cuenta.php`, `template-parts/pages/cuenta/cursos.php`, `template-parts/components/cuenta-menu.php`, `template-parts/components/cuenta-pestanas.php`, `template-parts/components/curso-progreso-card.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** menú `cuenta-menu` con "Cursos inscritos" activo. Pestañas `cuenta-pestanas` "Cursos inscritos", "Cursos activos" y "Completados" (ícono arriba, número al lado del texto: propuesta), que son enlaces (`?pestana=`), no JS. Grilla de 3 columnas (separación 20) de `curso-progreso-card` variante `vertical` (355 px, borde beige, radio 20): foto 353 × 190, título (`h3`), ícono libro con "N de M lecciones" (`--text-caption`, `--color-tinta-suave`), extracto, "Progreso" con porcentaje, barra y botón al pie. **Pie por estado:** en progreso, `boton` "Continuar curso" (a la última lección vista); en 0 %, "Empezar curso" (a la primera lección); completado (propuesta), barra al 100 %, `etiqueta` "Completado" junto al título y `boton` secundario "Volver a ver". **Inscritos** muestra todos con su pie; **Activos**, los que están entre 1 % y 99 % (y los en 0 %); **Completados**, los terminados. Sin cursos en una pestaña (propuesta): "Aún no tienes cursos aquí" y, en Inscritos, `boton` "Ver soluciones de sueño". Paginación (números) solo con más de 9 cursos. La grilla del diseño desborda 14 px: la altura la da el contenido. Móvil (propuesta): menú arriba, pestañas en fila con scroll propio, tarjetas en 1 columna (2 en tablet).
- **Indicaciones del diseño:**
    - nota: drawer https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4164-25448&t=lySNWh2dFHTQgiTC-4
    - cursos activos es : https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4164-25480&t=lySNWh2dFHTQgiTC-4
    - nota: falta vista para Cursos inscritos y Completados, tu diseñalo y proponmelo
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (del plugin, no campos): 5 cursos (Horarios 9 a 15 meses 10/12 80 %, Recién Nacidos Go 1/11 10 %, Angelita Pro 13/14 90 %, Horarios 4 a 9 meses 7/14 50 %, Horarios 15 a 36 meses 3/13 20 %).

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Solo se listan los cursos de la usuaria con sesión; una pestaña desconocida en la URL vuelve a Inscritos
- [ ] Las 3 pestañas muestran su número y la activa lleva `aria-current="page"`
- [ ] El botón de cada tarjeta cambia según el avance (Empezar, Continuar, Volver a ver)
- [ ] La barra es accesible (`role="progressbar"`) y su ancho es el porcentaje real
- [ ] Cada pestaña sin cursos muestra su estado vacío
- [ ] En móvil las tarjetas van en 1 columna, sin scroll horizontal
