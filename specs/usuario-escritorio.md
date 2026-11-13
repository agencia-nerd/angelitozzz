# Página: Cuenta: escritorio

- **Resumen:** Primera pantalla de la cuenta de la alumna: ve cuántos cursos tiene, cuántos terminó y cuáles lleva en progreso, y sigue el curso donde lo dejó.
- **Fuente:** `figma:usuario-escritorio`
- **Plantilla:** `pages/template-cuenta.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** escritorio-del-alumno
- **Skills usadas:** wp-helper (imágenes). Los datos son del plugin `angelitozzz-cursos`
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Cuenta propia (sin Tutor LMS desde 2026-11-13): página `/cuenta/` con la plantilla `pages/template-cuenta.php`, que comparte el marco y el menú lateral `cuenta-menu` (`_shared.md`) y carga la vista de la URL (`/cuenta/`, `/cuenta/perfil/`, `/cuenta/cursos/`, `/cuenta/pedidos/`, endpoints que registra el plugin `angelitozzz-cursos`). Sin sesión, redirige a `/iniciar-sesion/` con `redirect_to`. "Mi cuenta" de Woo (`/mi-cuenta/`) redirige aquí. Los datos los da el plugin con sus funciones públicas `angelitozzz_cursos_*`, siempre de la usuaria con sesión (nunca de un ID recibido en la URL).
    - El menú tiene los 4 ítems del diseño (Escritorio, Mi perfil, Cursos inscritos, Historial de pedidos). "Favoritos" está oculto en Figma: no se hace.
    - Sin campos ACF: los textos son fijos del tema y los datos, del plugin.
    - `h1`: el nombre de la alumna en el menú ("Hola, / Ana Lucía"). Título SEO "Mi cuenta | Angelitozzz", con `noindex`.
    - Cintillo, header y footer salen de `header.php` y `footer.php`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: escritorio-del-alumno

- **Origen:** `specs/figma/lectura/usuario-escritorio/escritorio-del-alumno.ref.txt` (nodo 4164:24914: menú 4164:25044, resumen y cursos 4164:25045)
- **Destino:** `pages/template-cuenta.php`, `template-parts/pages/cuenta/escritorio.php`, `template-parts/components/cuenta-menu.php`, `template-parts/components/curso-progreso-card.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** fondo crema, padding 120/135. A la izquierda el menú `cuenta-menu` con "Escritorio" activo. A la derecha: "Resumen" (`h2`, `--text-cuerpo-xl`, verde) con 3 tarjetas blancas (radio 15): número grande (`--text-h1`) y etiqueta "Cursos inscritos", "Cursos completados", "Cursos en progreso" (los conteos del plugin). El diseño deja un hueco de 55 px para un ícono que no dibuja: se quita. "Mis cursos" (`h2`) con los cursos en progreso más recientes (máximo 3) en el componente `curso-progreso-card` variante `horizontal`: foto 371 × 198, título (`h3`), extracto, "Progreso" con el porcentaje, barra y `boton` frambuesa "Continuar curso" (lleva a la última lección vista). Sin cursos (propuesta): "Aún no tienes cursos" y `boton` "Ver soluciones de sueño". Valores sueltos: `#859898` en "Progreso", borde negro de 1 px del menú. Móvil (propuesta): el menú pasa arriba como una fila de pestañas con scroll horizontal propio (no de la página); las 3 tarjetas de resumen en una columna (3 en tablet); la tarjeta de curso apila foto y texto.
- **Indicaciones del diseño:**
    - nota: drawer https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4164-25044&t=lySNWh2dFHTQgiTC-4
    - nota: resumen y cursos https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4164-25045&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (del plugin, no campos): resumen 5 inscritos, 2 completados, 3 en progreso; cursos "Cursos de Horarios · 9 a 15 meses" 80 %, "Recién Nacidos Go" 60 %, "Angelita Pro" 10 %.

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Sin sesión redirige a `/iniciar-sesion/`; solo se ven los datos de la usuaria con sesión
- [ ] El menú tiene solo los 4 ítems del diseño y marca el activo con `aria-current="page"`
- [ ] Los números del resumen son los del plugin para la alumna
- [ ] La barra de progreso es accesible (`role="progressbar"` con su valor) y su ancho es el porcentaje real
- [ ] Sin cursos se ve el estado vacío con el botón a Soluciones de sueño
- [ ] En móvil el menú no causa scroll horizontal de la página
