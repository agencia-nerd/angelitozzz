# Página: Administrador: cursos

- **Resumen:** Lista de los cursos de Bárbara con sus estudiantes, fecha y precio. Desde cada tarjeta los edita, los ve en el sitio, los publica o los pasa a borrador; arriba crea uno nuevo.
- **Fuente:** `figma:admin-cursos`
- **Plantilla:** `pages/template-administrador.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** seccion
- **Skills usadas:** wp-helper (imágenes), wp-modal (confirmación de eliminar). Datos y acciones del plugin `angelitozzz-cursos`
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - las dos notas son lo que va dentro de seccion
    - al momento de agregar un nuevo curso es asi (paso uno: 1 Básico)
    - (paso uno: 1 Básico) pagina entera https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4185-30575&t=lySNWh2dFHTQgiTC-4
    - (paso uno: 1 Básico) seccion especifica https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4185-30578&t=lySNWh2dFHTQgiTC-4
    - (paso uno: 1 Básico) drawer (barra izquierda) https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4185-30580&t=lySNWh2dFHTQgiTC-4
    - (paso uno: 1 Básico) formulario para agregar curso https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4185-30609&t=lySNWh2dFHTQgiTC-4
    - (paso uno: 1 Básico) Duracion del accesos son de 4,6,12 meses y acceso ilimitado
    - (paso dos: 2 Contenido) pagina entera : https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4186-31074&t=lySNWh2dFHTQgiTC-4
    - (paso dos: 2 Contenido) seccion especifica https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4186-31077&t=lySNWh2dFHTQgiTC-4
    - (paso dos: 2 Contenido) drewer (barra izquierda) https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4186-31079&t=lySNWh2dFHTQgiTC-4
    - (paso dos: 2 Contenido) carga de contenido https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4186-31108&t=lySNWh2dFHTQgiTC-4
    - (paso dos: 2 Contenido) eliminar un modulo y lecciones modal https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3687-4560&t=lySNWh2dFHTQgiTC-4
    - (paso tres: 3 Adicional) pagina entera https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4186-31886&t=lySNWh2dFHTQgiTC-4
    - (paso tres: 3 Adicional) seccion especifica https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4186-31889&t=lySNWh2dFHTQgiTC-4
    - (paso tres: 3 Adicional) drawer (barra izquierda) https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4186-31891&t=lySNWh2dFHTQgiTC-4
    - (paso tres: 3 Adicional) carga de contenido listo para publicar https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4186-31920&t=lySNWh2dFHTQgiTC-4
    - Para editar un modulo, curso es un modal asi : https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3773-5409&t=lySNWh2dFHTQgiTC-4
    - Las notas de "paso uno / dos / tres" y los modales de editar y eliminar son del constructor: se aplican en `admin-constructor.md` (capturas en `specs/figma/lectura/admin-cursos/constructor-*.png`), no en esta lista.
    - Vista `/administrador/cursos/` de `pages/template-administrador.php`, con el mismo acceso que `admin-escritorio.md` (capacidad `angelitozzz_gestionar_cursos`; si no, `/iniciar-sesion/` o `/cuenta/`).
    - Sin campos ACF. `h1`: el nombre en el menú. Título SEO "Cursos | Administrador | Angelitozzz", con `noindex`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: seccion

- **Origen:** `specs/figma/lectura/admin-cursos/seccion.ref.txt` (nodo 4184:29688: menú 4184:29690, contenido 4184:29718, aviso 3687:4671 en "PARTE" final; capturas `seccion.png` y `seccion.notificacion.png`)
- **Destino:** `template-parts/pages/administrador/cursos.php`, `template-parts/components/curso-admin-card.php`, `template-parts/modals/eliminar-curso.php`, `template-parts/components/cuenta-menu.php`, `src/js/modules/admin-cursos.js`
- **Grupo:** ninguno
- **Skill:** wp-modal
- **Comportamiento:** lista de cursos con sus acciones, en el marco del administrador.
    - **Estructura:**
        - **Menú:** `cuenta-menu` con `menu` `instructora`, "Cursos" activo.
        - **"Crear nuevo curso":** igual que en el escritorio, arriba a la derecha.
        - **Grilla:** 3 columnas de 355 px (separación 20) del componente `curso-admin-card`. Todos los cursos, publicados y borradores; los borradores llevan la `etiqueta` "Borrador" sobre la foto (propuesta: el diseño no muestra estados). Más recientes primero; paginación (números) con más de 12.
    - **Tarjeta (`curso-admin-card`):**
        - **Contenido:**
            - portada 190 px (`object-cover`, imagen destacada; sin imagen, la "not found");
            - título (`h3`, `--text-h5`);
            - ícono de estudiantes con "N estudiantes" (inscripciones activas);
            - ícono de calendario con la fecha de publicación (`d/m/Y`; en borrador, la de creación);
            - "Precio" y el precio de su producto de Woo (`--text-h4`, verde). Con precio rebajado se ve el rebajado (propuesta).
        - **Botón "Más opciones":** circular de 40 px (⋯), con nombre accesible "Más opciones de <curso>". Abre un menú (propuesta, el diseño no lo dibuja; `aria-expanded`; se cierra con Esc o al hacer clic fuera) con:
            - "Editar" → constructor, paso 1;
            - "Ver en el sitio" → la solución del curso, si existe;
            - "Publicar" o "Pasar a borrador";
            - "Eliminar".
    - **Eliminar** (propuesta):
        - **Confirmación:** abre un modal de wp-modal, con el mismo estilo que "¿Eliminar esta lección?" del constructor, "¿Eliminar este curso?".
        - **Con estudiantes inscritas:** el curso no se elimina. El modal explica que se puede pasar a borrador y solo ofrece esa acción, para no dejar sin acceso a quien pagó.
        - **Sin estudiantes:** se envía a la papelera de WordPress con sus lecciones; su producto de Woo pasa a borrador.
    - **Acciones** (publicar, borrador, eliminar): `POST` a la API REST `angelitozzz/v1/cursos/<id>` con nonce `wp_rest` y la capacidad. Si fallan, el aviso va con `role="alert"`.
    - **Aviso "¡Curso publicado! Ya está visible en Soluciones de Sueño.":**
        - **Cuándo:** al volver del constructor tras publicar, o al publicar desde el menú.
        - **Aspecto:** tarjeta verde arriba a la derecha (radio 14, sombra `0 12px 32px #00000024`) con check, texto crema y botón ✕ "Cerrar aviso". Es `role="status"`, se cierra solo a los 6 s (propuesta) o con ✕, y no tapa el foco.
        - **Texto:** si el curso no está en ninguna solución, dice "¡Curso publicado! Agrégalo a una solución para que se vea en Soluciones de Sueño." (propuesta).
    - **Móvil (propuesta):** menú arriba, "Crear nuevo curso" a todo el ancho, tarjetas en 1 columna (2 en tablet) y el aviso a todo el ancho abajo.
- **Indicaciones del diseño:**
    - nota: drawer https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4184-29690&t=lySNWh2dFHTQgiTC-4
    - nota: contenido https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4184-29718&t=lySNWh2dFHTQgiTC-4
    - nota: la notificacion al publicar un curso es asi https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3687-4671&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (del plugin y de Woo, no campos): 4 tarjetas (Angelita Pro $67, Recién Nacidos Go $97, Horarios · 4 a 9 meses $37, Horarios · 9 a 15 meses $37), todas "60 estudiantes" y "10/05/2025".

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Solo quien tiene `angelitozzz_gestionar_cursos` ve la lista y usa sus acciones; la API responde 403 a los demás
- [ ] El menú "Más opciones" se usa con teclado y se cierra con Esc
- [ ] Un curso con estudiantes no se puede eliminar, solo pasar a borrador
- [ ] El aviso de publicado se anuncia (`role="status"`) y se puede cerrar
- [ ] En móvil no hay scroll horizontal
