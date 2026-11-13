# Página: Administrador: constructor de cursos

- **Resumen:** Donde Bárbara crea y edita un curso en 3 pasos: Básico (datos, precio, acceso y portada), Contenido (módulos, lecciones con su video, texto y descargables) y Publicar. Al publicar, el curso y su producto de WooCommerce quedan listos para venderse.
- **Fuente:** `figma:admin-cursos`
- **Plantilla:** `pages/template-administrador.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** pasos, paso-basico, paso-contenido, modal-leccion, modal-eliminar, paso-publicar
- **Skills usadas:** wp-modal (editar lección, editar módulo, eliminar), wp-helper (imágenes). Guardado por la API REST del plugin `angelitozzz-cursos` (`cursos-datos.md`)
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
    - Capturas: `specs/figma/lectura/admin-cursos/constructor-*.png`. El constructor se lee de capturas, sin código de Figma: el usuario decidió hacerlo a medida (2026-11-13) y los valores salen de la captura y de los tokens del tema.
    - Decisión del usuario (2026-11-13): el paso 3 queda como **"Publicar"**. Se quitan "Qué aprenderás", "Requisitos", "Para quién es" y "Duración total": los textos de venta se editan en la solución, sin duplicarlos. El paso conserva "Visibilidad" y suma un resumen del curso.
    - **URLs:**
        - `/administrador/cursos/nuevo/` crea el curso en el paso 1.
        - `/administrador/cursos/<id>/editar/?paso=1|2|3` lo edita.
        - Un `paso` fuera de la lista vuelve al 1; un `<id>` que no es un curso, 404.
    - **Acceso y seguridad:**
        - Solo la capacidad `angelitozzz_gestionar_cursos`, como en `admin-escritorio.md`.
        - Todo se guarda por la API REST `angelitozzz/v1`, con nonce `wp_rest`, `permission_callback` con la capacidad y validación de cada campo en el servidor; la validación del navegador es solo ayuda.
        - Las respuestas devuelven solo lo que la pantalla necesita.
    - **Cambios sin guardar:** si quedan cambios sin guardar al salir, el navegador pregunta antes (`beforeunload`).
    - **Página:**
        - El marco y el menú son los de `admin-escritorio.md` (`cuenta-menu` con `menu` `instructora`, "Cursos" activo).
        - `h1`: el título del paso ("Crear nuevo curso", "Contenido del curso", "Publicar curso").
        - Título SEO del paso, con `noindex`.
        - Sin frame móvil: el móvil es una propuesta.
- **Estado:** pendiente

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
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El paso activo lleva `aria-current="step"` y los pasos no disponibles no son enlaces
- [ ] Sin la capacidad, la página y la API no responden (redirección o 403)

## Sección: paso-basico

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-basico-formulario.png` (4185:30609)
- **Destino:** `template-parts/pages/administrador/constructor-basico.php`, `src/js/modules/constructor.js`, plugin `includes/rest-constructor.php`
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
        - Interruptor "Incluye mes gratis en Mundo Angelitozzz" (propuesta: el dato de `cursos-datos.md` → `_angelitozzz_mes_mundo`; muestra el banner en la primera lección).
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
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Datos que guarda (del plugin, no ACF; `cursos-datos.md` → `curso`): título, extracto, imagen destacada, `_angelitozzz_edad`, `_angelitozzz_tipo`, `_angelitozzz_duracion_acceso`, `_angelitozzz_mes_mundo`, y precio normal y rebajado de su producto de Woo.

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El servidor rechaza título vacío, precios negativos, un precio de lanzamiento mayor o igual al precio, y valores de select fuera de la lista
- [ ] Una portada que no es jpg, png o webp (por su contenido, no por la extensión) o que supera el tamaño no se sube
- [ ] Guardar crea o actualiza el producto de Woo oculto con los precios
- [ ] Los errores se anuncian y quedan asociados a su campo

## Sección: paso-contenido

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-contenido-carga.png` (4186:31108)
- **Destino:** `template-parts/pages/administrador/constructor-contenido.php`, `src/js/modules/constructor.js`, plugin `includes/rest-constructor.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** tarjeta "Módulos y lecciones" con la ayuda "Arrastra para ordenar. Cada lección tiene su video de Vimeo, un texto corto y sus descargables.".
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
        - línea gris: "Video de Vimeo · 3:59" o "Descargable · 2,4 MB";
        - "Descripción: …" (el comienzo del texto);
        - editar y eliminar.
    - **Agregar:**
        - "+ Agregar lección" (terracota) al pie de cada módulo abre `modal-leccion` vacío.
        - "+ Agregar descargable" al lado (propuesta) sube un PDF del curso al módulo, como el ítem "Guía del curso (PDF)" del diseño. Ese PDF va al final del temario de la lección, sin check.
    - **"+ Agregar módulo":** botón de borde punteado; crea "Módulo N" y abre su edición de título.
    - **Ordenar:** módulos e ítems se ordenan arrastrando. Para teclado y lectores de pantalla, cada ítem tiene también "Subir" y "Bajar" (botones visibles al tener foco). Al soltar se guarda el orden (`menu_order` y el orden de módulos) y se anuncia "Orden guardado" (`aria-live`).
    - **Botones:**
        - "Volver a información": paso 1.
        - "Continuar: publicar": paso 3.
    - **Guardado:** cada cambio se guarda al momento por la API (crear, editar, ordenar, eliminar), sin un "guardar todo". Si una petición falla, el cambio se revierte en pantalla y se avisa.
- **Indicaciones del diseño:**
    - (Las notas de "paso dos" de la página aplican aquí: la carga de contenido y el modal de eliminar.)
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (ejemplo): "Módulo 1 · Bienvenida" con "Conoce a Bárbara" (Video de Vimeo · 3:59), "Objetivos del curso" (3:20) y "Guía del curso (PDF)" (Descargable · 2,4 MB); "Módulo 2 · Crea rutinas que funcionan · 4 lecciones"; "Módulo 3 · Despertar nocturno · 3 lecciones".

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Módulos e ítems se ordenan arrastrando y también con "Subir" y "Bajar" desde el teclado; el orden se guarda y se anuncia
- [ ] Cada acción usa la API con nonce y capacidad; un fallo revierte el cambio en pantalla
- [ ] Los módulos son plegables y sus botones tienen nombre accesible con el título del módulo

## Sección: modal-leccion

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-modal-editar-modulo.png` (3773:5409)
- **Destino:** `template-parts/modals/editar-leccion.php`, `src/js/modules/constructor.js`, plugin `includes/rest-constructor.php`, `includes/video.php`
- **Grupo:** ninguno
- **Skill:** wp-modal
- **Comportamiento:** modal de 860 px.
    - **Cabecera:** "Módulo N · Lección N" chico y título "Editar lección" ("Nueva lección" al agregar). Cerrar ✕.
    - **Campos:**
        - **"Título de la lección":** ayuda "Se muestra arriba del video.", requerido.
        - **"Video (enlace de Vimeo)":** ayuda "Bárbara sube el video a Vimeo y pega aquí el enlace.".
            - Al pegar, el servidor consulta oEmbed de Vimeo y muestra la miniatura con "Video conectado" y "Duración: 08:24 · se detecta sola".
            - Si no es de Vimeo o no se puede leer: "No pudimos leer ese video. Revisa que el enlace sea de Vimeo y que el video permita insertarse en otros sitios." (`role="alert"`).
            - Con videos ocultos de Vimeo vale el enlace completo, con su código privado.
        - **"Descripción de la lección":** ayuda "Es el texto que la mamá ve debajo del video. Se edita como un documento.".
            - Editor liviano con solo Negrita, Cursiva, Lista y Enlace (propuesta: Tiptap con esas 4 extensiones, instalado con pnpm), con barra crema.
            - Al guardar, el servidor filtra el HTML con `wp_kses` y la lista permitida de `cursos-datos.md`.
            - Un párrafo que empieza con "PD:" se verá como cita en la lección.
        - **"Descargables (opcional)":** ayuda "PDFs que la mamá puede descargar desde esta lección.".
            - Chips con el nombre del PDF, ícono y botón para quitar ("Quitar <archivo>"), más "Agregar PDF".
            - El servidor valida que sea PDF por su contenido y que no pase de 20 MB, y lo guarda en la carpeta privada.
        - **Interruptor frambuesa "Lección de vista previa gratuita (se puede ver sin comprar)":** un `<input type="checkbox" role="switch">`.
    - **Pie:**
        - "Cancelar": cierra sin guardar, y pregunta si hay cambios.
        - "Guardar lección": guarda por la API, cierra y actualiza la fila.
- **Indicaciones del diseño:**
    - (La nota de la página "Para editar un modulo, curso es un modal asi" muestra este modal de lección; editar un módulo usa un modal chico con el mismo estilo.)
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Datos que guarda (del plugin, no ACF; `cursos-datos.md` → `leccion`): título, contenido, `_angelitozzz_video`, `_angelitozzz_duracion` (y la miniatura), `_angelitozzz_materiales`, `_angelitozzz_vista_previa`, `_angelitozzz_modulo` y el orden.

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Es un modal de wp-modal con `title`, foco atrapado y vuelta del foco al cerrar
- [ ] Un enlace que no es de Vimeo no se guarda y muestra el error; uno válido muestra la miniatura y la duración
- [ ] El HTML del editor se filtra en el servidor: `<script>`, atributos `on*` y estilos no se guardan
- [ ] Un archivo que no es PDF, o de más de 20 MB, no se sube; los PDF no quedan en una URL pública

## Sección: modal-eliminar

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-modal-eliminar-modulo.png` (3687:4560)
- **Destino:** `template-parts/modals/eliminar.php`
- **Grupo:** ninguno
- **Skill:** wp-modal
- **Comportamiento:** un solo modal de confirmación para lecciones, descargables y módulos (y para cursos, `admin-cursos.md`).
    - **Diseño:** tarjeta blanca centrada, ícono "!" terracota en círculo, título y texto que cambian según qué se elimina, y los botones "Cancelar" (secundario) y "Sí, eliminar" (frambuesa con flecha).
    - **Textos:**
        - **Lección:** "¿Eliminar esta lección?" / "Se eliminará “<título>” y su texto. El video seguirá guardado en Vimeo.".
        - **Módulo** (propuesta): "¿Eliminar este módulo?" / "Se eliminarán “<título>” y sus N lecciones. Los videos seguirán guardados en Vimeo.".
        - **Descargable** (propuesta): "¿Eliminar este descargable?" / "Se eliminará “<archivo>”.".
    - **Si el curso tiene alumnas inscritas** (propuesta): se agrega "Las alumnas inscritas dejarán de ver este contenido.".
    - **Qué pasa al eliminar:** lecciones y módulos van a la papelera de WordPress (se pueden recuperar 30 días). Los PDF eliminados se borran de la carpeta privada.
    - **Foco:** al abrirse, el foco va a "Cancelar".
- **Indicaciones del diseño:**
    - (Nota de la página: "eliminar un modulo y lecciones modal". El diseño solo dibuja el de lección; los demás textos son propuesta.)
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Es un modal de wp-modal; el foco empieza en "Cancelar"
- [ ] Eliminar va a la papelera y no borra el video de Vimeo
- [ ] El texto nombra exactamente lo que se elimina

## Sección: paso-publicar

- **Origen:** `specs/figma/lectura/admin-cursos/constructor-adicional-listo.png` (4186:31920), con la decisión del usuario sobre el paso 3
- **Destino:** `template-parts/pages/administrador/constructor-publicar.php`, plugin `includes/rest-constructor.php`
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
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El servidor no publica un curso sin título, portada, precio o al menos una lección con video
- [ ] Publicar deja el curso, sus lecciones y su producto de Woo publicados, y el producto sigue oculto del catálogo
- [ ] La visibilidad es un `<fieldset>` con `<legend>` y el botón cambia de texto según la opción
