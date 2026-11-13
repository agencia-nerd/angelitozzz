# Página: Administrador: escritorio

- **Resumen:** Primera pantalla del administrador de cursos de Bárbara: cuántas estudiantes y cursos tiene, cuánto lleva ganado y cuántas inscritas tiene cada curso. Desde aquí crea un curso nuevo.
- **Fuente:** `figma:admin-escritorio`
- **Plantilla:** `pages/template-administrador.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** seccion
- **Skills usadas:** ninguna. Los datos son del plugin `angelitozzz-cursos` (`cursos-datos.md` → `funciones-publicas`)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - las dos notas son lo que va dentro de seccion
    - **Página propia `/administrador/`:**
        - Plantilla `pages/template-administrador.php`, con las vistas `/administrador/` (escritorio), `/administrador/cursos/` (`admin-cursos.md`) y `/administrador/cursos/nuevo/` y `/administrador/cursos/<id>/editar/` (`admin-constructor.md`).
        - Los endpoints los registra el plugin.
        - Comparte el marco de la cuenta: menú lateral a la izquierda y contenido a la derecha.
    - **Acceso:**
        - Solo con la capacidad `angelitozzz_gestionar_cursos` (rol `instructora` o administradora, `cursos-datos.md` → `roles`).
        - Sin sesión → `/iniciar-sesion/` con `redirect_to`.
        - Con sesión y sin la capacidad → `/cuenta/`.
        - Se comprueba antes de imprimir la página.
    - Por ahora solo Bárbara (decisión del usuario, 2026-11-13). Los datos son de todos los cursos del sitio, no "de cada instructora".
    - Sin campos ACF: textos fijos del tema y datos del plugin. `h1`: el nombre en el menú ("Hola, / Bárbara"). Título SEO "Administrador | Angelitozzz", con `noindex`.
    - Cintillo, header y footer salen de `header.php` y `footer.php`, como en el diseño. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: seccion

- **Origen:** `specs/figma/lectura/admin-escritorio/seccion.ref.txt` (nodo 4184:29056: menú 4184:29058, contenido 4184:29180)
- **Destino:** `pages/template-administrador.php`, `template-parts/pages/administrador/escritorio.php`, `template-parts/components/cuenta-menu.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** fondo crema, padding 120/135.
    - **Menú:** `cuenta-menu` con `menu` `instructora` (458 px de ancho): "Hola," y el nombre, los ítems "Escritorio" (activo) y "Cursos", y "Cerrar sesión".
    - **"Crear nuevo curso":** arriba a la derecha, tarjeta verde de 173 × 94 (radio 15, ícono + en círculo blanco, texto blanco `--text-cuerpo-l`). Es un enlace a `/administrador/cursos/nuevo/`. Mismo estilo que `cuenta-pestanas`, en verde.
    - **"Resumen":** `h2`, `--text-cuerpo-xl`, verde. 3 tarjetas blancas (355 × 147, radio 15), cada una con número grande (`--text-h1`) y etiqueta:
        - "Estudiantes en total": usuarias distintas con al menos una inscripción, de cualquier estado;
        - "Total de cursos": publicados;
        - "Ganancias totales": suma de los pedidos pagados de productos de cursos, menos reembolsos, en USD con el formato de Woo. El diseño no trae el número: se agrega.
        - Ícono: el diseño solo tiene el de estudiantes (`#492343`). Se usa ese, y en las otras dos se deja el hueco sin ícono (propuesta: se piden los íconos a diseño).
    - **"Cursos":** `h2`. Tabla real (`<table>` con `<caption>` solo para lectores, `<th scope="col">`) de 1108 px:
        - cabecera "Curso" | "Estudiantes inscritos" (`--text-h5`, verde);
        - filas de 60 px con borde inferior `#c1c1c1` de 1,5 px y radio 15 arriba y abajo;
        - una fila por curso publicado, con su número de inscripciones activas;
        - el nombre del curso enlaza a su edición (propuesta; en el diseño no es enlace);
        - ordenada por nombre.
    - **Sin cursos** (propuesta): "Aún no tienes cursos" y el enlace a crear uno.
    - **Datos:** de `angelitozzz_cursos_admin_resumen()` y `angelitozzz_cursos_admin_lista()`.
    - **Móvil (propuesta):** menú arriba como en la cuenta, "Crear nuevo curso" a todo el ancho, tarjetas de resumen en una columna y la tabla pasa a tarjetas, sin scroll horizontal.
- **Indicaciones del diseño:**
    - nota: drawer https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4184-29058&t=lySNWh2dFHTQgiTC-4
    - nota: contenido https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4184-29180&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (del plugin, no campos): 683 estudiantes, 6 cursos, ganancias sin número; tabla con Angelita Pro 19, Recién Nacidos Go 82, Horarios · 4 a 9 meses 5, Horarios · 9 a 15 meses 96, Horarios · 15 a 36 meses 36, Semanas extra 51.

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Sin sesión lleva a iniciar sesión; una alumna con sesión va a `/cuenta/` y nunca ve los datos
- [ ] Las ganancias descuentan los reembolsos y salen con el formato de moneda de Woo
- [ ] La tabla es una `<table>` con encabezados y `caption`
- [ ] En móvil no hay scroll horizontal
