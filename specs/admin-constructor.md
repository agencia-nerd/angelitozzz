# Página: Administrador: constructor de cursos

- **Resumen:** Donde Bárbara crea y edita un curso en 3 pasos: Básico (datos, precio, acceso y portada), Contenido (módulos, lecciones con su video, texto y descargables) y Publicar. Al publicar, el curso y su producto de WooCommerce quedan listos para venderse.
- **Fuente:** `figma:admin-cursos`
- **Plantilla:** `pages/template-administrador.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** pasos, paso-basico, paso-contenido, modal-leccion, modal-archivos, modal-eliminar, paso-publicar
- **Skills usadas:** wp-modal (editar lección, editar módulo, eliminar), wp-helper (imágenes). Guardado por la API REST del plugin `angelitozzz-cursos` (`cursos-datos.md`)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Página WP:** ninguna
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
    - Capturas: `specs/figma/lectura/admin-cursos/constructor-*.png`. El constructor se lee de capturas, sin código de Figma: el usuario decidió hacerlo a medida (2026-11-13) y los valores salen de la captura y de los tokens del tema.
    - Decisión del usuario (2026-11-13): el paso 3 queda como **"Publicar"**. Se quitan "Qué aprenderás", "Requisitos", "Para quién es" y "Duración total": los textos de venta se editan en la solución, sin duplicarlos. El paso conserva "Visibilidad" y suma un resumen del curso.
    - **URLs:**
        - `/administrador/cursos/nuevo/` crea el curso en el paso 1.
        - `/administrador/cursos/<id>/editar/?paso=1|2|3` lo edita.
        - Un `paso` fuera de la lista vuelve al 1; un `<id>` que no es un curso, 404.
    - **Acceso y seguridad:**
        - Solo la capacidad `angelitozzz_gestionar_cursos`, como en `admin-escritorio.md`.
        - Todo se guarda por la API REST `angelitozzz/v1`, con nonce `wp_rest`, `permission_callback` con la capacidad y validación de cada campo en el servidor; la validación del navegador es solo ayuda.
        - **Rutas** (plugin `includes/rest-constructor.php`, lógica en `includes/constructor.php`): `POST /cursos` y `POST /cursos/<id>` (paso 1), `POST|DELETE /cursos/<id>/portada`, `POST /cursos/<id>/modulos`, `POST|DELETE /cursos/<id>/modulos/<modulo>`, `POST /cursos/<id>/lecciones`, `POST|DELETE /lecciones/<id>`, `POST /cursos/<id>/materiales` (descargable de un módulo, con `archivo_id` y `modulo`), `DELETE /cursos/<id>/materiales/<archivo>`, `POST /lecciones/<id>/materiales` (con `archivo_id`), `DELETE /lecciones/<id>/materiales/<archivo>` y `POST /cursos/<id>/orden`. Publicar y pasar a borrador usan `POST /cursos/<id>/estado` de `admin-cursos.md`. Los errores de validación responden 400 con el mensaje de cada campo (`data.campos`).
        - Cada lección, módulo y descargable se comprueba que sea del curso: un ID de otro curso responde 400 o 404 y no cambia nada.
        - **Archivos privados (2026-11-25; en el plugin desde la 0.10.0):** `GET /video?url=` se quitó (era la vista previa de Vimeo; hoy responde 404). Se suman las rutas de subida por partes y de la lista (`cursos-datos.md` → `archivos-privados`: `POST /archivos/subidas`, `PUT /archivos/subidas/<id>`, `POST /archivos/subidas/<id>/fin`, `GET /archivos`), y las de materiales pasan a recibir el ID de un archivo privado ya subido en vez del PDF. Un archivo que no existe o que no es del tipo que pide el campo responde 400, y también el mismo archivo agregado dos veces a la misma lección o al mismo curso. Quitar un descargable (o eliminar su módulo) solo lo desvincula. Lo que devuelve cada lección y cada descargable (`video`, `video_nombre`, `miniatura` firmada, `clase`, `tipo_texto`) está en `cursos-datos.md` → `funciones-publicas` (`angelitozzz_cursos_admin_curso()`).
        - Las respuestas devuelven solo lo que la pantalla necesita.
    - **Cambios sin guardar:** si quedan cambios sin guardar al salir, el navegador pregunta antes (`beforeunload`).
    - **Página:**
        - El marco y el menú son los de `admin-escritorio.md` (`cuenta-menu` con `menu` `instructora`, "Cursos" activo y `saludo` `p`: el nombre no es el `h1`).
        - `h1`: el título del paso ("Crear nuevo curso" en un curso nuevo y "Editar curso" al editar uno que ya existe, "Contenido del curso", "Publicar curso"). Lo da `angelitozzz_titulo_paso_constructor()` (`inc/administrador.php`), que también arma el título SEO.
        - El JS se divide por paso (`constructor-basico.js`, `constructor-contenido.js`, `constructor-publicar.js`, con `editor-texto.js` para Tiptap) y comparte `admin-comun.js` con la lista de cursos (cliente de la API, `pedirConfirmacion()` y el aviso de cambios sin guardar), en vez de un solo `constructor.js`.
        - Las URL de la API no se escriben en el JS: PHP pasa la base con `rest_url( 'angelitozzz/v1/' )` en `data-api` y `urlApi()` de `admin-comun.js` le agrega cada ruta y sus parámetros. Así funciona con enlaces permanentes "bonitos" (`/wp-json/…`) y con los simples (`?rest_route=…`).
        - Desde la F5-c (2026-11-19) no hay ventanas nativas del navegador: el enlace del editor y "¿Descartar los cambios?" son paneles del mismo modal (sin modales apilados, regla de wp-modal; decisión del usuario). Ver `modal-leccion`.
        - Sin JavaScript, el constructor muestra un aviso (`<noscript>`): guarda todo por la API.
        - Título SEO del paso, con `noindex`.
        - Sin frame móvil: el móvil es una propuesta.
    - **Cambio (decisión del usuario, 2026-11-25):** el video de la lección deja de ser un enlace de Vimeo; se sube un archivo o se elige uno ya subido ("Archivos privados"), y los descargables aceptan más tipos que PDF. Cambian `paso-contenido`, `modal-leccion` y `modal-eliminar`, y se suma `modal-archivos`.
- **Estado:** aplicado

## Sección: pasos

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-basico-pagina.png` (4185:30575), `constructor-contenido-pagina.png` (4186:31074)
- **Destino:** `pages/template-administrador.php`, `template-parts/pages/administrador/constructor.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** encabezado común a los 3 pasos.
    - **Migas:** "Cursos / Nuevo curso" o "Cursos / <nombre del curso>", en un `<nav aria-label="Migas">`. "Cursos" enlaza a `/administrador/cursos/`.
    - **Título:** el `h1` del paso.
    - **Pastillas de los pasos:** "1 · Básico", "2 · Contenido", "3 · Publicar" separadas por ">". La activa es salvia `--color-etiqueta` con texto blanco y `aria-current="step"`; las otras son blancas. Son enlaces a cada paso; en un curso nuevo, los pasos 2 y 3 están desactivados hasta guardar el paso 1. El diseño dice "3 · Adicional": pasa a "3 · Publicar" por la decisión del paso 3.
    - **Tarjetas:** cada paso va en tarjetas blancas de radio 20 con título `h2` (`--text-h5`, verde).
    - **Campos:** fondo crema, borde beige y radio 12, con la etiqueta verde encima y un texto de ayuda chico debajo de la etiqueta (`--text-caption`).
    - **Botones al pie, a la derecha:**
        - secundario, contorno verde (`boton` `secundario`);
        - principal, frambuesa con flecha.
    - **Móvil (propuesta):** las pastillas con scroll propio, las tarjetas a todo el ancho y los botones apilados (el principal primero).
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El paso activo lleva `aria-current="step"` y los pasos no disponibles no son enlaces
- [x] Sin la capacidad, la página y la API no responden (redirección o 403)

## Sección: paso-basico

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-basico-formulario.png` (4185:30609)
- **Destino:** `template-parts/pages/administrador/constructor-basico.php`, `src/js/modules/constructor-basico.js`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** dos tarjetas.
    - **"Información del curso":**
        - "Título del curso" (placeholder "Ej. Recién Nacidos Go", requerido).
        - "Descripción corta" (área de texto, placeholder "Ej. Todo lo que necesitas saber para que tu recién nacido duerma mejor — desde los primeros días.").
        - En dos columnas:
            - "Categoría (edad)" (placeholder "Ej. 0 a 12 semanas") y "Tipo" (select: Curso online, Asesoría personalizada, Suscripción);
            - "Precio (USD)" (número con 2 decimales, mayor o igual a 0, requerido) y "Precio de lanzamiento (opcional)" (menor que el precio, o vacío);
            - "Duración del acceso" (select: 4 meses, 6 meses, 12 meses, Acceso ilimitado; nota "Duracion del accesos son de 4,6,12 meses y acceso ilimitado").
        - Interruptor "Incluye mes gratis en Mundo Angelitozzz" (propuesta: el dato de `cursos-datos.md` → `_angelitozzz_mes_mundo`; muestra el banner en la primera lección). Debajo, la ayuda (unida con `aria-describedby`): "Muestra el banner del mes gratis en la primera lección del curso y el bloque en la página de gracias y en el correo de la compra." Con Tipo = Suscripción y el interruptor encendido aparece un aviso en rojo (`--color-acento`) con un ícono "!" (`role="status"`): "Este curso es de suscripción. Las suscripciones normalmente no incluyen el mes gratis en Mundo: te recomendamos no activarlo." No se bloquea: lo decide Bárbara (F5-c, decisión del usuario, 2026-11-19).
        - **Sin "Estado":** el diseño lo tiene, pero se quita porque es el mismo dato que la "Visibilidad" del paso 3. Mientras no se publique, el curso queda en borrador.
    - **"Imagen de portada":**
        - Zona para soltar o elegir la imagen (borde punteado terracota, ícono de subir, "Arrastra una imagen o haz clic para subirla"). Es un `<input type="file">` real con su `<label>`, usable con teclado.
        - Ayuda: "JPG, PNG o WebP · Recomendado: hasta 2000 px en el lado más largo, horizontal." Se cambia el "1200 × 800 px · bebé en cuna, sin mantas ni peluches" del diseño, porque la regla del proyecto es no fijar medidas.
        - Al subir, muestra la vista previa con "Cambiar" y "Quitar".
        - El servidor valida el tipo real (`wp_check_filetype_and_ext`: jpg, png, webp) y el tamaño (propuesta: 8 MB), y la guarda en la biblioteca de medios como imagen destacada.
    - **Botones:**
        - "Guardar borrador": guarda y se queda, con el aviso "Guardado" (`role="status"`).
        - "Continuar: agregar contenido": guarda y va al paso 2.
    - **Al guardar:** el plugin crea o actualiza el curso (borrador) y su producto de Woo (nombre, precio normal y rebajado, oculto) con `WC_Product`.
    - **Errores:** van junto a cada campo (`aria-describedby`) y en un resumen con `role="alert"`; no se pierde lo escrito.
- **Indicaciones del diseño:**
    - (Las notas de "paso uno" de la página aplican aquí: la duración del acceso y el formulario.)
    - **Móvil (propuesta):** las dos tarjetas en una columna (información arriba, portada debajo); los campos que van en fila se apilan; la portada ocupa el ancho con su proporción; botones apilados como en `pasos`.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Datos que guarda (del plugin, no ACF; `cursos-datos.md` → `curso`): título, extracto, imagen destacada, `_angelitozzz_edad`, `_angelitozzz_tipo`, `_angelitozzz_duracion_acceso`, `_angelitozzz_mes_mundo`, y precio normal y rebajado de su producto de Woo.

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El servidor rechaza título vacío, precios negativos, un precio de lanzamiento mayor o igual al precio, y valores de select fuera de la lista
- [x] Una portada que no es jpg, png o webp (por su contenido, no por la extensión) o que supera el tamaño no se sube
- [x] Guardar crea o actualiza el producto de Woo oculto con los precios
- [x] Los errores se anuncian y quedan asociados a su campo

## Sección: paso-contenido

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-contenido-carga.png` (4186:31108)
- **Destino:** `template-parts/pages/administrador/constructor-contenido.php`, `src/js/modules/constructor-contenido.js`, `template-parts/modals/editar-modulo.php`, `template-parts/modals/elegir-archivo.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** tarjeta "Módulos y lecciones" con la ayuda "Arrastra para ordenar. Cada lección tiene su video, un texto corto y sus descargables." (2026-11-25: sin "de Vimeo").
    - **Módulos:**
        - Cada módulo es una fila plegable: fondo crema, borde beige, radio 16 y chevron.
        - Cabecera "Módulo N · <título>". Cerrado, suma " · N lecciones".
        - Botones de editar (lápiz, "Editar módulo <título>") y eliminar (papelera, "Eliminar módulo <título>").
        - Editar abre un modal chico con "Título del módulo" (propuesta, con el estilo de `modal-leccion`).
        - Eliminar abre `modal-eliminar`.
    - **Ítems de un módulo:** cada uno con:
        - asa de arrastre;
        - ícono terracota: video para una lección, documento para un descargable;
        - título;
        - línea gris: "Video · 3:59" o "<tipo> · 2,4 MB" ("PDF · 2,4 MB", "Word · 120 KB"; 2026-11-25: antes "Video de Vimeo" y "Descargable");
        - "Descripción: …" (el comienzo del texto);
        - editar y eliminar.
    - **Agregar:**
        - "+ Agregar lección" (terracota) al pie de cada módulo abre `modal-leccion` vacío.
        - "+ Agregar descargable" al lado (propuesta) agrega un material del curso al módulo, como el ítem "Guía del curso (PDF)" del diseño. Ese material va al final del temario de la lección, sin check. Desde el 2026-11-25 abre un menú con "Subir archivo" (desde la computadora, a "Archivos privados") y "Elegir de mis archivos" (`modal-archivos`), con cualquier tipo de material. Es un botón con `aria-expanded` que muestra las dos opciones debajo; Escape o un clic afuera las cierran. Mientras sube, el módulo muestra la barra ("Subiendo <archivo>… N %") con "Cancelar", y la advertencia de peso o el error en rojo (`role="alert"`). Al terminar, o al elegir, se vincula al módulo (`POST /cursos/<id>/materiales` con `archivo_id` y `modulo`) y la fila nueva recibe el foco.
    - **"+ Agregar módulo":** botón de borde punteado; crea "Módulo N" y abre su edición de título.
    - **Ordenar:** módulos e ítems se ordenan arrastrando. Para teclado y lectores de pantalla, cada ítem tiene también "Subir" y "Bajar" (botones visibles al tener foco). Al soltar se guarda el orden (`menu_order` y el orden de módulos) y se anuncia "Orden guardado" (`aria-live`).
    - **Botones:**
        - "Volver a información": paso 1.
        - "Continuar: publicar": paso 3.
    - **Guardado:** cada cambio se guarda al momento por la API (crear, editar, ordenar, eliminar), sin un "guardar todo". Si una petición falla, el cambio se revierte en pantalla y se avisa.
- **Indicaciones del diseño:**
    - (Las notas de "paso dos" de la página aplican aquí: la carga de contenido y el modal de eliminar.)
    - **Móvil (propuesta):** módulos e ítems a todo el ancho; en cada ítem, editar, subir, bajar y eliminar quedan visibles como botones de ícono de 44 px, así se ordena sin arrastrar; el título largo se corta con puntos suspensivos.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (ejemplo): "Módulo 1 · Bienvenida" con "Conoce a Bárbara" (Video de Vimeo · 3:59), "Objetivos del curso" (3:20) y "Guía del curso (PDF)" (Descargable · 2,4 MB); "Módulo 2 · Crea rutinas que funcionan · 4 lecciones"; "Módulo 3 · Despertar nocturno · 3 lecciones".

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Módulos e ítems se ordenan arrastrando y también con "Subir" y "Bajar" desde el teclado; el orden se guarda y se anuncia
- [x] Cada acción usa la API con nonce y capacidad; un fallo revierte el cambio en pantalla
- [x] Los módulos son plegables y sus botones tienen nombre accesible con el título del módulo
- [x] La línea gris de cada ítem dice "Video · <duración>" o "<tipo> · <tamaño>", sin "Vimeo"
- [x] "+ Agregar descargable" sube un archivo nuevo o elige uno de "Archivos privados", de cualquier tipo de material

## Sección: modal-leccion

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-modal-editar-modulo.png` (3773:5409)
- **Destino:** `template-parts/modals/editar-leccion.php`, `src/js/modules/constructor-contenido.js`, `src/js/modules/editor-texto.js`, `src/js/modules/elegir-archivo.js`, `inc/constructor-subida.php`
- **Grupo:** ninguno
- **Skill:** wp-modal
- **Comportamiento:** modal de 860 px.
    - **Cabecera:** "Módulo N · Lección N" chico y título "Editar lección" ("Nueva lección" al agregar). Cerrar ✕.
    - **Campos:**
        - **"Título de la lección":** ayuda "Se muestra arriba del video.", requerido.
        - **"Video de la lección"** (2026-11-25, reemplaza a "Video (enlace de Vimeo)"): ayuda "Sube el video en MP4 desde tu computadora o elige uno que ya subiste. Solo lo ven las alumnas con acceso al curso.".
            - **Sin video:** un recuadro de 180 × 100 (fondo `--color-neutro`, radio 12, ícono de video) y a su derecha los botones "Elegir de mis archivos" (secundario, abre `modal-archivos` solo con videos) y "Subir video" (terracota, abre el selector de archivos del sistema con `accept="video/mp4,video/webm"`).
            - **Subiendo:** barra de avance con el nombre del archivo y el porcentaje ("Subiendo mi-video.mp4… 62 %", un `<progress>` nativo, que ya es `progressbar` con su valor) y "Cancelar". El modal no se puede guardar hasta que termine (el botón "Guardar lección" queda desactivado con el texto de ayuda "Espera a que termine de subir el video.").
            - **Archivos:** la lista y la cola de subidas están en `elegir-archivo.js`; `inc/constructor-subida.php` encola la subida del plugin (`angelitozzz-cursos-subida`) solo en el paso 2.
            - **De a uno por vez** (2026-11-25, por los procesos del hosting): en toda la página sube un archivo a la vez; los demás muestran "En espera: <nombre>" hasta su turno. Cerrar o descartar la lección cancela lo que se estaba subiendo; lo que ya terminó queda sin usar en "Archivos privados".
            - **Con video:** la portada (cuadro tomado del video) en el recuadro, "Video listo" y "<nombre> · Duración: 08:24 · se detecta sola", más "Cambiar video" (vuelve a mostrar los dos botones) y "Quitar video". Quitarlo solo lo desvincula: sigue en "Archivos privados".
            - **Advertencia de peso:** si el video pasa el tope (500 MB), la advertencia en rojo de `cursos-datos.md` → `archivos-privados`, con `role="alert"`, debajo de la barra; la subida sigue.
            - **Recomendación de calidad:** si el video pasa de 1080p o de unos 15 MB por minuto, el aviso en ámbar de `cursos-datos.md` → `archivos-privados` (720p o 1080p con HandBrake), con `role="status"`; la subida sigue.
            - **Errores** (`role="alert"`): tipo no permitido "Ese archivo no es un video MP4 o WebM."; corte de la subida tras 3 reintentos "No pudimos terminar de subir el video. Revisa tu conexión e inténtalo de nuevo.".
        - **"Descripción de la lección":** ayuda "Es el texto que la mamá ve debajo del video. Se edita como un documento.".
            - Editor liviano con solo Negrita, Cursiva, Lista y Enlace (propuesta: Tiptap con esas 4 extensiones, instalado con pnpm), con barra crema.
            - Enlace (F5-c): el botón abre una fila bajo la barra (`aria-expanded`) con la etiqueta "Enlace", un campo URL, la ayuda "Selecciona el texto y pega el enlace. Tiene que empezar con https://, http:// o mailto:." y los botones "Aplicar", "Quitar enlace" (solo si el texto ya tenía enlace) y "Cancelar". Un enlace que no empieza con http, https o mailto muestra "Ese enlace no es válido: tiene que empezar con https://, http:// o mailto:." (`role="alert"`, campo con `aria-invalid`). Enter aplica (no envía el formulario); Escape cierra solo la fila. Al aplicar, el foco vuelve al editor.
            - Al guardar, el servidor filtra el HTML con `wp_kses` y la lista permitida de `cursos-datos.md`.
            - Un párrafo que empieza con "PD:" se verá como cita en la lección.
        - **"Descargables (opcional)":** ayuda "Archivos que la mamá puede descargar desde esta lección: PDF, Word, Excel, PowerPoint, imágenes o audio." (2026-11-25: antes solo PDF).
            - Chips con el nombre del archivo, el ícono de su tipo y un botón para quitar ("Quitar <archivo>"; quitar no lo borra de "Archivos privados"), más "Subir archivo" y "Elegir de mis archivos" (`modal-archivos` solo con materiales). Mientras sube, el chip dice "Subiendo <archivo>… N %" y su botón es "Cancelar la subida de <archivo>".
            - Un tipo no permitido: "<archivo>: Ese tipo de archivo no está permitido. Se aceptan PDF, Word, Excel, PowerPoint, JPG, PNG, WebP y MP3." (`role="alert"`); el mismo archivo dos veces: "Ese archivo ya está en esta lección.".
            - Lo subido o elegido se vincula al pulsar "Guardar lección" (`POST /lecciones/<id>/materiales` con `archivo_id`), y lo quitado se desvincula (`DELETE`).
            - El servidor revisa el tipo por la extensión y el contenido (lista de `cursos-datos.md` → `archivos-privados`, sin SVG ni video) y lo guarda en el almacén privado. Si pasa el tope de su tipo, se sube igual con la advertencia en rojo.
        - **Interruptor frambuesa "Lección de vista previa gratuita (se puede ver sin comprar)":** un `<input type="checkbox" role="switch">`.
    - **Pie:**
        - "Cancelar": cierra sin guardar, y pregunta si hay cambios. La pregunta es una vista del mismo modal (F5-c) con el estilo del modal `eliminar`: ícono "!" terracota, título "¿Descartar los cambios?", texto "Tienes cambios sin guardar en esta lección. Si los descartas, se pierden." y los botones "Seguir editando" (secundario, recibe el foco) y "Descartar cambios" (frambuesa con flecha). Esc y la ✕ también la muestran; con la vista abierta, Esc o la ✕ vuelven a editar.
        - "Guardar lección": guarda por la API, cierra y actualiza la fila.
- **Indicaciones del diseño:**
    - (La nota de la página "Para editar un modulo, curso es un modal asi" muestra este modal de lección; editar un módulo usa un modal chico con el mismo estilo.)
    - **Móvil (propuesta):** ocupa la pantalla con 16 px de margen y scroll interno; la cabecera y el pie quedan fijos; los campos en una columna. En "Video de la lección", el recuadro va arriba y los botones debajo, a todo el ancho.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Datos que guarda (del plugin, no ACF; `cursos-datos.md` → `leccion`): título, contenido, `_angelitozzz_video` (ID del archivo privado desde el 2026-11-25), `_angelitozzz_duracion`, `_angelitozzz_materiales`, `_angelitozzz_vista_previa`, `_angelitozzz_modulo` y el orden.

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Es un modal de wp-modal con `title`, foco atrapado y vuelta del foco al cerrar
- [x] El HTML del editor se filtra en el servidor: `<script>`, atributos `on*` y estilos no se guardan
- [x] "Subir video" sube un MP4 o WebM por partes con su barra de avance y, al terminar, muestra la portada, el nombre y la duración (la duración, con un video que la tiene; que el servidor la lea de un MP4 real es `cursos-datos.md` → `leccion`)
- [x] "Elegir de mis archivos" abre `modal-archivos` solo con videos y el elegido queda en la lección
- [x] Un archivo que no es video en "Video de la lección", o un SVG o un video en "Descargables", no se guarda y muestra el error
- [x] Un archivo que pasa su tope muestra la advertencia en rojo y se sube igual
- [x] "Guardar lección" queda desactivado mientras sube un archivo
- [x] Quitar el video o un descargable no lo borra de "Archivos privados"

## Sección: modal-archivos

- **Origen:** propuesta sin Figma (decisión del usuario, 2026-11-25: "Elegir de mis archivos"), con el estilo de `modal-leccion`
- **Destino:** `template-parts/modals/elegir-archivo.php`, `template-parts/pages/administrador/lista-archivos.php`, `src/js/modules/elegir-archivo.js`, `src/js/modules/constructor-contenido.js`
- **Grupo:** ninguno
- **Skill:** wp-modal
- **Comportamiento:** la lista de "Archivos privados" para elegir uno sin salir del constructor (la instructora no entra a `/wp-admin/`). Los datos salen de `GET /archivos` (`angelitozzz_cursos_admin_archivos()`).
    - **Dónde se abre:**
        - desde "+ Agregar descargable" del paso 2: es su propio modal de wp-modal (720 px), con `title` "Elegir de mis archivos";
        - desde "Editar lección" (video o descargables): es una vista del mismo modal de la lección, con "← Volver a la lección" en la cabecera, como "¿Descartar los cambios?". Sin modales apilados (regla de la F5-c).
    - **Filtro fijo:** al elegir el video solo se listan videos; al elegir un descargable, solo materiales, sin las portadas de los videos (plugin 0.10.3).
    - **Buscador:** campo "Buscar por nombre" (`<input type="search">` con su `<label>`), que filtra sin recargar.
    - **Lista:** una fila por archivo, con la portada o el ícono de su tipo (48 px), el nombre, "<tipo> · <tamaño>" (y la duración en los videos) y "Usado en N lecciones" en gris si está en uso. Cada fila es un `<button>` "Elegir <nombre>". Al elegir, vuelve a la lección (o cierra el modal) con el archivo puesto y el foco en su chip o en "Cambiar video".
    - **Paginación:** "Cargar más" al pie (24 por vez).
    - **Vacío:** "Todavía no subiste archivos de este tipo." y el botón "Subir archivo".
    - **Errores:** si la lista no carga, "No pudimos cargar tus archivos. Inténtalo de nuevo." (`role="alert"`) con "Reintentar".
    - **Móvil (propuesta):** ocupa la pantalla con 16 px de margen y scroll interno; las filas a todo el ancho.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Desde "Editar lección" es una vista del mismo modal (no se apila otro) y "Volver a la lección" no pierde lo escrito
- [x] Al elegir el video solo aparecen videos; al elegir un descargable, solo materiales
- [x] El buscador filtra por nombre y cada fila es un botón con nombre accesible
- [x] Una alumna no puede pedir la lista (403)

## Sección: modal-eliminar

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-modal-eliminar-modulo.png` (3687:4560)
- **Destino:** `template-parts/modals/eliminar.php`, `src/js/modules/admin-comun.js`
- **Grupo:** ninguno
- **Skill:** wp-modal
- **Comportamiento:** un solo modal de confirmación para lecciones, descargables y módulos (y para cursos, `admin-cursos.md`).
    - **Diseño:** tarjeta blanca centrada, ícono "!" terracota en círculo, título y texto que cambian según qué se elimina, y los botones "Cancelar" (secundario) y "Sí, eliminar" (frambuesa con flecha).
    - **Textos:**
        - **Lección:** "¿Eliminar esta lección?" / "Se eliminará “<título>” y su texto. El video seguirá guardado en Archivos privados." (2026-11-25: antes "en Vimeo").
        - **Módulo** (propuesta): "¿Eliminar este módulo?" / "Se eliminarán “<título>” y sus N lecciones. Los videos seguirán guardados en Archivos privados.".
        - **Descargable** (propuesta): "¿Quitar este descargable?" / "Se quitará “<archivo>” de este curso. Seguirá guardado en Archivos privados.".
    - **Si el curso tiene alumnas inscritas** (propuesta): se agrega "Las alumnas inscritas dejarán de ver este contenido.".
    - **Qué pasa al eliminar:** lecciones y módulos van a la papelera de WordPress (se pueden recuperar 30 días). Desde el 2026-11-25, sus videos y descargables no se borran: siguen en "Archivos privados" y solo se borran desde esa pantalla, si no están en uso (`archivos-privados.md`). Antes, los PDF se borraban de la carpeta privada.
    - **Foco:** al abrirse, el foco va a "Cancelar".
- **Indicaciones del diseño:**
    - (Nota de la página: "eliminar un modulo y lecciones modal". El diseño solo dibuja el de lección; los demás textos son propuesta.)
    - **Móvil (propuesta):** ancho completo con 16 px de margen; los dos botones a todo el ancho, uno debajo del otro ("Eliminar" arriba, "Cancelar" abajo).
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Es un modal de wp-modal; el foco empieza en "Cancelar"
- [x] Eliminar va a la papelera y no borra el video de Vimeo
- [x] Eliminar una lección o un módulo, o quitar un descargable, no borra sus archivos de "Archivos privados"
- [x] El texto nombra exactamente lo que se elimina

## Sección: paso-publicar

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-adicional-listo.png` (4186:31920), con la decisión del usuario sobre el paso 3
- **Destino:** `template-parts/pages/administrador/constructor-publicar.php`, `src/js/modules/constructor-publicar.js`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** paso 3 "Publicar curso".
    - **"Resumen del curso"** (propuesta, en lugar de "Lo que verá la mamá en la página del curso"):
        - portada chica, título, categoría (edad), tipo;
        - precio (y el de lanzamiento);
        - duración del acceso;
        - "Incluye mes gratis en Mundo" sí/no;
        - N módulos, N lecciones y la duración total (suma de las lecciones, calculada).
        - Cada dato con "Editar" que lleva a su paso.
    - **"Antes de publicar"** (propuesta): lista de lo que falta, con un aviso por punto:
        - título;
        - portada;
        - precio;
        - al menos una lección con video;
        - el curso agregado a una solución, para que se vea en Soluciones de Sueño. Esto se avisa pero no bloquea.
    - **"Visibilidad":**
        - Radios verdes: "Publicado — visible en Soluciones de Sueño y a la venta" y "Borrador — solo visible para administradores". Son un `<fieldset>` con `<legend>`.
        - Con Publicado, el botón dice "Publicar curso"; con Borrador, "Guardar como borrador".
    - **Botones:** "Volver a contenido" (paso 2) y el principal.
    - **Al publicar:**
        - Curso, lecciones y producto pasan a publicados.
        - Vuelve a `/administrador/cursos/` con el aviso "¡Curso publicado!" (`admin-cursos.md`).
        - No se puede publicar sin los 4 primeros puntos de "Antes de publicar": el servidor también lo comprueba.
- **Indicaciones del diseño:**
    - (Las notas de "paso tres" de la página aplican aquí; sus 4 campos de texto se quitan por decisión del usuario.)
    - **Móvil (propuesta):** tarjetas en una columna, a todo el ancho; la lista "Antes de publicar" debajo del resumen; botones apilados como en `pasos`.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El servidor no publica un curso sin título, portada, precio o al menos una lección con video
- [x] Publicar deja el curso, sus lecciones y su producto de Woo publicados, y el producto sigue oculto del catálogo
- [x] La visibilidad es un `<fieldset>` con `<legend>` y el botón cambia de texto según la opción
