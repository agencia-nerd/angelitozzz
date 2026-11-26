# Página: Archivos privados (wp-admin)

- **Resumen:** Pantalla de `/wp-admin/` donde la administradora ve, sube y borra los archivos que solo ven las alumnas con acceso: los videos de las lecciones y los materiales (PDF, Word, Excel, PowerPoint, imágenes y audio). Está aparte de la Biblioteca de medios, que es pública.
- **Fuente:** propuesta sin Figma (decisión del usuario, 2026-11-25)
- **Plantilla:** ninguna
- **Destinos:** esta pantalla es del plugin `angelitozzz-cursos`, fuera del tema. Cada `Destino` es una ruta relativa al tema (`../../plugins/angelitozzz-cursos/includes/<archivo>.php`), como en `cursos-datos.md`.
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** pantalla
- **Skills usadas:** ninguna. Es código del plugin (pantalla de administración de WordPress con sus estilos nativos)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Página WP:** ninguna
- **Notas de página:**
    - Pedido del usuario (2026-11-25): un apartado propio en `/wp-admin/`, en vez de Medios, para subir o elegir archivos privados; lo que se sube desde el constructor también cae aquí. Los datos y las reglas (dónde se guardan, tipos, topes, subida por partes, "dónde se usa", migración) están en `cursos-datos.md` → `archivos-privados`.
    - **Quién entra:** la capacidad `angelitozzz_gestionar_cursos`. La administradora (Bárbara) la tiene y entra a `/wp-admin/`. La instructora no entra a `/wp-admin/` (`cursos-datos.md` → `roles`): ve la misma lista desde el constructor (`admin-constructor.md` → `modal-archivos`).
    - **Menú:** "Archivos privados", con un ícono de candado (`dashicons-lock`), debajo de "Medios".
    - Usa los estilos nativos de WordPress (sin Tailwind ni CSS del tema), así se ve como el resto del admin. Textos en el text domain `angelitozzz-cursos`.
    - Todo pasa por la REST `angelitozzz/v1` con nonce `wp_rest` y la capacidad, igual que el constructor. El JS es del plugin y se carga solo en esta pantalla.
- **Estado:** aprobado

## Sección: pantalla

- **Origen:** propuesta sin Figma (2026-11-25)
- **Destino:** `../../plugins/angelitozzz-cursos/includes/admin-archivos.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** una sola pantalla con tres partes (plugin 0.9.0, 2026-11-26). La lista la arma PHP (filtros, buscador y paginación por URL, funciona sin JS); el JS (`assets/js/archivos-admin.js`) suma subir, renombrar y eliminar, sin ventanas nativas del navegador: "Renombrar" pone un campo en la celda (Enter guarda, Escape cancela) y "Eliminar" agrega una fila de confirmación debajo, con el foco en "Cancelar". La subida usa el script compartido `assets/js/subida.js`. Cada pedido de renombrar o eliminar tiene 20 s: si la respuesta no llega, se avisa "No recibimos respuesta del servidor. Actualizamos la lista para ver cómo quedó." y se recarga la lista.
    - **Encabezado:**
        - título "Archivos privados" (`h1` de WordPress);
        - texto: "Solo las alumnas con acceso al curso pueden ver estos archivos. Para fotos o videos públicos de la web usa Medios.";
        - botón "Subir archivo" (`page-title-action`).
    - **Zona de subida** (se muestra con "Subir archivo"):
        - arrastrar y soltar, o "Elegir archivos" (selector del sistema, con `accept` de la lista permitida); se pueden subir varios, uno detrás de otro;
        - una barra de avance por archivo con su nombre, el porcentaje y "Cancelar";
        - la advertencia de peso en rojo (`notice notice-error`) cuando un archivo pasa el tope de su tipo; la subida sigue;
        - en los videos, la recomendación de calidad en ámbar (`notice notice-warning`) si pasan de 1080p o de unos 15 MB por minuto (`cursos-datos.md` → `archivos-privados`); la subida sigue;
        - los errores del tipo ("Ese tipo de archivo no está permitido. Se aceptan MP4, WebM, PDF, Word, Excel, PowerPoint, JPG, PNG, WebP y MP3.") por archivo, sin cortar los demás.
    - **Lista** (tabla con el estilo de las de WordPress, `widefat striped`):
        - columnas: portada o ícono del tipo, nombre, tipo, tamaño, duración (video y audio), fecha de subida y "Dónde se usa" (lecciones y cursos con enlace al constructor, o "Sin usar");
        - filtros encima: "Todos", "Videos" y "Materiales", más un buscador por nombre;
        - 24 por página, con la paginación de WordPress;
        - acciones por fila: "Renombrar" (cambia el nombre que ven las alumnas, no el archivo), "Ver" (abre una vista previa con dirección firmada: el video o el audio en su reproductor, la imagen o el PDF en una pestaña nueva) y "Eliminar";
        - "Eliminar" pide confirmación ("¿Eliminar “<nombre>”? Se borrará del servidor y no se puede recuperar.") y solo aparece si no está en uso; si está en uso, en su lugar dice "En uso: quítalo primero de sus lecciones".
    - **Sin archivos:** "Todavía no subiste archivos privados." y el botón "Subir archivo".
    - **Sin JS:** aviso de que la pantalla necesita JavaScript para subir (la lista se ve igual).
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] (0.9.0) Cumple los criterios comunes de `_shared.md` que aplican al admin (accesibilidad de botones y formularios, textos traducibles y salida escapada)
- [x] (0.9.0, PHPUnit) Solo entra quien tiene `angelitozzz_gestionar_cursos`; el resto no ve el menú ni puede abrir la pantalla
- [ ] Se puede subir un video de 1 GB por partes, con su barra de avance, y varios archivos seguidos (probado en Chrome con un PDF de 21 MB en 3 trozos y un MP4, uno detrás de otro; falta un video real de 1 GB)
- [x] (0.9.0, Chrome) Un archivo que pasa su tope se sube igual con la advertencia en rojo; un SVG o un tipo fuera de la lista no se sube
- [x] Lo subido aquí aparece en "Elegir de mis archivos" del constructor (bloque 4) y no aparece en Medios (esto último probado en Chrome) (4c: "Elegir de mis archivos" lista los archivos privados, que son los mismos de esta pantalla)
- [x] (0.9.0, PHPUnit) "Dónde se usa" lista las lecciones y los cursos correctos; un archivo en uso no se puede eliminar
- [x] (0.9.0, Chrome) "Renombrar" cambia el nombre que ven las alumnas y el de la descarga
- [ ] **A confirmar en producción:** en el Local de Windows, al eliminar un archivo grande (21 MB), a veces (1 de cada 3 o 4) el servidor lo borra pero la respuesta no llega al navegador. Con archivos chicos no pasó (20 de 20). Causa no confirmada, probablemente del Local (Nginx con `php-cgi`). La pantalla tiene un tiempo máximo de 20 s que avisa y recarga la lista; falta ver esa protección actuar en una falla real. Probar en producción (Apache) eliminando un archivo grande.
