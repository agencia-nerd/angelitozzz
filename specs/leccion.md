# Página: Lección

- **Resumen:** Donde la alumna toma el curso: ve el temario con su avance, el video de la lección, el texto (Resumen) y los descargables (Materiales), y marca la lección como completa. Es la plantilla de todas las lecciones de todos los cursos.
- **Fuente:** `figma:leccion`
- **Plantilla:** `single-leccion.php`
- **Tipo:** detalle
- **Contenido:** leccion
- **Secciones (en orden):** cabecera, temario, mensaje-angelita-pro, video, banner-mundo, pestanas
- **Skills usadas:** wp-helper (imágenes y enlaces), wp-accordion (temario en móvil). Datos y acceso del plugin `angelitozzz-cursos` (`cursos-datos.md`)
- **Grupo ACF:** ninguno
- **Ubicación:** tipo de contenido = leccion
- **Padre:** ninguno
- **Notas de página:**
    - Nota Plantilla de lección (para desarrollo)
    - Esta pantalla es la plantilla para todas las lecciones de todos los cursos. Aquí se muestra el Video 1 de Recién Nacidos Go con su texto real, tal como viene en el documento de Bárbara.
    - Un solo tema (módulo) con el nombre del curso y las lecciones en el orden del documento, con los títulos tal como los escribió Bárbara. Al final de la lista va el ítem “PDF Recién Nacidos Go” como material del curso: sin check de “completado”, con ícono de descarga (en Tutor puede ser una lección con el PDF adjunto y se oculta su check con CSS, o el material del curso; ambos son simples).
    - El texto de cada lección se carga desde el documento “Captions de los videos”, en el campo “Descripción de la lección” de Tutor.
    - Formato: párrafos, negritas, enlaces y emojis tal como vienen en el documento.
    - Cuando un texto tenga “PD:” al final, va con estilo de cita (fondo rosa con borde frambuesa).
    - Solo se agregan descargables en las lecciones donde el documento menciona un PDF o archivo (pestaña “Materiales” (Lesson attachments de Tutor; la pestaña “Exercise files” se renombra con Loco Translate)).
    - El video es el reproductor de Vimeo de cada lección (en Figma, placeholder).
    - Mes gratis en Mundo: banner solo en la primera lección del curso (siempre visible), más el bloque en la confirmación de compra y en el correo de pedido. Detalle en la nota “Banner del mes gratis en Mundo”.
    - Nota Banner del mes gratis en Mundo (para desarrollo) a continuacion
    - Aplica a Recién Nacidos Go y a los 3 cursos de Horarios (Angelita Pro no incluye el mes gratis). Versión simple, según lo pedido por Bárbara en la Reunión 3 (reducir costos).
    - Correo de pedido de WooCommerce (el que llega por defecto o como sea lo conveniendo, en el proceso se decide si es woo u otro): se le agrega el bloque del mes gratis con el botón “Activar mi mes gratis en Mundo Angelitozzz”, basado en el “Email 1 — Confirmación inmediata” del Web Copy. Sin secuencia de recordatorios.
    - Página de confirmación de compra: bloque verde “Espera — hay algo más esperándote” con el mismo botón.
    - Dentro del curso: el banner de Mundo va solo en la primera lección de cada curso, siempre visible. Sin estados, sin “Ya lo activé” y sin guardar clics. En el resto de lecciones, el recordatorio es el texto de Bárbara (“Revisa tu correo…”).
    - No va en “Mi cuenta”.
    - Mismo enlace en los tres lugares: la prueba gratuita de nas.io (pendiente que Bárbara lo envíe), editable desde el panel.
    - Compras para regalo: la confirmación y el correo de quien compra no muestran el bloque; quien recibe el curso lo ve en la primera lección.
    - **Cómo se aplican las notas sin Tutor (2026-11-13):**
        - Las menciones a Tutor y a Loco Translate de las notas anteriores son del plan viejo.
        - El texto de la lección es su "Descripción de la lección" del constructor (`admin-constructor.md`).
        - La pestaña se llama "Materiales" en el tema, sin traducir nada.
        - El PDF del curso es un material del curso (`_angelitozzz_materiales` del curso, `cursos-datos.md`): va al final del temario, sin check y con descarga.
    - Qué cursos llevan el banner de Mundo: el dato "Incluye mes gratis en Mundo" de cada curso (`_angelitozzz_mes_mundo`, `cursos-datos.md`), que Bárbara marca en el constructor. El diseño dice Recién Nacidos Go y Horarios; A Dormir Por Fin también lo promete en su landing ("1 mes en Mundo Angelitozzz"); Angelita Pro no. El enlace y los textos del banner son de Ajustes del sitio (`opciones.md`, sección `mes-gratis-mundo`): el mismo enlace sirve para la confirmación de compra y el correo.
    - El banner conserva la línea "Si ya lo activaste, puedes ignorar este mensaje." (decisión del usuario, 2026-11-13): es solo texto, no un estado.
    - Acceso (`cursos-datos.md` → `acceso`): sin sesión → iniciar sesión y volver; sin inscripción vigente → la solución del curso; las lecciones de vista previa se ven sin comprar. La comprobación ocurre antes de imprimir la página.
    - `h1`: el nombre del curso (cabecera); el título de la lección es `h2`. Título SEO "<lección> · <curso> | Angelitozzz", con `noindex` (contenido de pago).
    - Cintillo, header y footer salen de `header.php` y `footer.php`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: cabecera

- **Origen:** `specs/figma/lectura/leccion/seccion-entera.ref.txt` (contenido 4176:27889, cabecera 4176:28045)
- **Destino:** `single-leccion.php`, `template-parts/pages/leccion/cabecera.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** sobre la columna principal (1033 px):
    - **Izquierda:** botón circular de 43 px para volver, enlace a `/cuenta/cursos/` con nombre accesible "Volver a mis cursos". Al lado, el nombre del curso como `h1` (`--text-h2`, negro).
    - **Derecha:** botón "Marcar como completo" (260 × 50, blanco, borde `#9ec0c0`, ícono de check en círculo). Es un `<button>` con `aria-pressed`. Al pulsarlo llama a `POST angelitozzz/v1/lecciones/<id>/completar` (nonce `wp_rest`), pasa a "Completada" con el check relleno `#9cb09d` y actualiza el temario y el avance sin recargar; pulsarlo de nuevo la desmarca.
    - **Errores:** si la petición falla, vuelve al estado anterior y muestra un aviso con `role="alert"`.
    - **Sin JS:** el botón envía un formulario normal (`admin-post.php`, con nonce) y vuelve a la lección.
    - **Vista previa sin comprar:** el botón no se muestra.
    - **Diseño:** no tiene barra de progreso ni anterior/siguiente; no se agregan.
    - **Móvil (propuesta):** el título pasa a `--text-h3` y el botón queda debajo, a todo el ancho.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] "Marcar como completo" funciona con y sin JS, con nonce, y solo para la usuaria con sesión y acceso
- [ ] El estado del botón se anuncia (`aria-pressed`) y el temario se actualiza al marcar

## Sección: temario

- **Origen:** `specs/figma/lectura/leccion/seccion-entera.ref.txt` (temario 4176:27857)
- **Destino:** `template-parts/pages/leccion/temario.php`, `template-parts/accordions/leccion-temario.php`
- **Grupo:** ninguno
- **Skill:** wp-accordion
- **Comportamiento:** columna izquierda de 458 px (60 px de separación con el contenido).
    - **Encabezado:** ícono de libros y "Contenido del curso" (`h2`, verde).
    - **Módulos:** cada uno con su cabecera verde (título en blanco, `--text-h5`, 50 px, radio 15) y debajo sus lecciones en orden. El diseño tiene un solo módulo con el nombre del curso.
    - **Fila de lección** (50 px, enlace a la lección): ícono de video, título cortado con "…" (el título completo va en `title` y es el texto del enlace), duración (`m:ss`, de `_angelitozzz_duracion`) y círculo de estado de 24 px:
        - completada: check relleno `#9cb09d`;
        - pendiente: círculo vacío con borde `#9ec0c0`;
        - actual: fondo `#fafeff`, título verde y `aria-current="page"`.
    - **Al final:** los PDF del curso ("PDF Recién Nacidos Go"), con ícono de documento y de descarga, sin duración ni check. Son descargas firmadas (`cursos-datos.md` → `acceso`).
    - **Sin compra** (vista previa, propuesta): las lecciones que no son de vista previa se muestran con un candado y sin enlace, y el PDF del curso no se muestra.
    - **Datos:** salen de `angelitozzz_cursos_temario()`.
    - **Móvil (propuesta):** el temario pasa arriba del video, plegado en un acordeón de wp-accordion "Contenido del curso", con la lección actual visible al abrirlo.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (del plugin, no campos): módulo "Recién Nacidos Go" con 11 lecciones (Bienvenida 3:59, Objetivos del curso 3:20, Sueño seguro 4:10, Las 4 piezas del rompecabezas del sueño 5:20, 5 herramientas para calmar a tu bebé 7:20, Demostración práctica: el swaddle seguro 1:29, Horarios y siestas del recién nacido 9:05, Consejos para practicar el sueño independiente 5:10, Demostración: escalera de sueño 6:49, Las siestas del recién nacido 6:12, Consejos para las noches 3:15) y "PDF Recién Nacidos Go".

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Cada estado (completada, pendiente, actual) se distingue también sin color (ícono y texto para lectores)
- [ ] Un título largo se corta con "…" y sigue completo para lectores de pantalla
- [ ] El PDF del curso solo se descarga con acceso
- [ ] En móvil el temario es un `<details>` de wp-accordion sobre el video

## Sección: mensaje-angelita-pro

- **Origen:** `specs/figma/lectura/leccion/seccion-entera.ref.txt` (bloque 4, nodo 4205:98; captura `seccion-entera.angelita-pro.png`)
- **Destino:** `template-parts/pages/leccion/mensaje-angelita-pro.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** solo en la primera lección de un curso de tipo `suscripcion` (Angelita Pro), arriba del video, como un mensaje.
    - **Tarjeta:** horizontal, fondo `--color-fondo-rosa`, radio 24, padding 28/32, sombra `0 8px 24px #23454914`.
    - **Izquierda:** círculo blanco de 88 px con la luna y la estrella (SVG decorativo).
    - **Derecha:**
        - antetítulo "ANTES DE EMPEZAR" (14 px, frambuesa, espaciado);
        - título "Bienvenida a Angelita Pro." (`h3`, 28 px, verde);
        - texto "Tómate unos minutos para ver el video de introducción y descargar tu Plan de Sueño.";
        - 3 pasos en pastillas blancas con número en círculo verde: "Mira el video", "Descarga tu plan", "Activa a Angelita". Son una lista ordenada (`<ol>`), no botones;
        - nota en cursiva "Tus 2 semanas con Angelita empiezan cuando tú la actives, no hoy." (`--color-marca-suave`).
    - **Decoración:** nubes dormidas y destellos a la derecha, recortados por la tarjeta (`aria-hidden`).
    - **Textos (propuesta):** fijos del tema, porque solo hay un curso de suscripción.
    - **Valores sueltos:** 14/28/18/16/15 px, sin estilo de texto propio en Figma.
    - **Móvil (propuesta):** el círculo arriba, el texto debajo y los pasos uno por fila.
- **Indicaciones del diseño:**
    - nota: para angelita pro sera asi al inicio : https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4205-98&t=1Oj88szSOsHwbY8K-4 (va arriba del la seccion del video, es como un mensaje)
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Solo aparece en la primera lección de Angelita Pro (curso de tipo `suscripcion`)
- [ ] Los pasos son una lista ordenada y la decoración no la leen los lectores de pantalla

## Sección: video

- **Origen:** `specs/figma/lectura/leccion/seccion-entera.ref.txt` (reproductor 4176:28055)
- **Destino:** `template-parts/pages/leccion/video.php`, `src/js/modules/video-leccion.js`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** marco de 1033 × 567 (proporción 16:9, radio 15).
    - **Carga diferida:** el iframe del proveedor no se carga al abrir la página. Se ve la portada del video (miniatura de Vimeo, guardada al leer la duración) con el botón play crema de 88 px (`<button>` "Reproducir video: <lección>"). Al pulsarlo se reemplaza por el iframe de `angelitozzz_cursos_video_html()` con `autoplay`.
    - **Sin portada:** fondo beige del diseño.
    - **Sin video:** la lección no muestra el marco.
    - **Seguridad del iframe:** `allow="autoplay; fullscreen; picture-in-picture"`, `title` con el nombre de la lección, `referrerpolicy` estricta y sin `allow-same-origin` de más. Cambiar de proveedor solo toca `includes/video.php` del plugin.
    - **Móvil:** ocupa el ancho y mantiene 16:9.
- **Indicaciones del diseño:**
    - nota: seccion especifica del curso https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4176-27857&t=lySNWh2dFHTQgiTC-4
    - nota: contenido https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4176-27889&t=lySNWh2dFHTQgiTC-4
    - (Las dos notas son de la sección entera: el temario y el contenido.)
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El iframe del video no se carga hasta pulsar play (sin peticiones a Vimeo al abrir la página)
- [ ] El botón play es un `<button>` con nombre accesible y se usa con teclado
- [ ] El video mantiene 16:9 en todos los anchos

## Sección: banner-mundo

- **Origen:** `specs/figma/lectura/leccion/seccion-entera.ref.txt` (componente "Banner Mundo" 3586:1518)
- **Destino:** `template-parts/components/banner-mundo.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** solo en la primera lección de un curso con "Incluye mes gratis en Mundo" (`_angelitozzz_mes_mundo`), debajo del video y siempre visible, sin estados.
    - **Tarjeta:** degradado verde (`#1c3a3e` → `#234549` → `#3e6b6d`), radio 24 y sombra.
    - **Logo:** de Mundo Angelitozzz, 96 px, con borde dorado `--color-estrella` (asset del tema).
    - **Textos:** título (`h3`, crema), texto (beige) y la línea en cursiva.
    - **Botón:** `boton` claro, sin flecha, con el enlace de la prueba gratuita en pestaña nueva (el helper de enlaces pone `rel` y el aviso).
    - **Origen de los textos y el enlace:** Ajustes del sitio (`opciones.md` → `mes-gratis-mundo`). Sin enlace cargado, el banner no se muestra.
    - **Decoración:** vector en la esquina superior derecha.
    - **Componente compartido:** la misma pieza sirve para la confirmación de compra con otro título (spec de gracias, pendiente).
    - **Móvil (propuesta):** el logo arriba, centrado, y el botón a todo el ancho.
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Valores del diseño (campos en `opciones.md` → `mes-gratis-mundo`): título "Tu mes gratis en Mundo Angelitozzz", texto "Incluido con tu curso: sesiones grupales en vivo con Bárbara todos los miércoles a las 10:00am (Perú/Colombia) y la videoteca de talleres.", pie "Si ya lo activaste, puedes ignorar este mensaje.", botón "Activar mi mes gratis".

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Solo aparece en la primera lección de los cursos que incluyen el mes gratis
- [ ] Sin enlace en Ajustes del sitio no se imprime
- [ ] El enlace sale por `angelitozzz_link()` (pestaña nueva con `rel` y aviso)

## Sección: pestanas

- **Origen:** `specs/figma/lectura/leccion/seccion-entera.ref.txt` (pestañas 4176:28059, texto de la lección, PD 3901:7716, materiales 3899:7674)
- **Destino:** `template-parts/pages/leccion/pestanas.php`, `src/js/modules/pestanas-leccion.js`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** pestañas "Resumen" y "Materiales" bajo el video. Con `role="tablist"`, se recorren con las flechas del teclado y cambian sin recargar.
    - **Pestaña activa:** fondo `#fafeff`, borde `#492343`, `--text-h5` verde, ícono documento.
    - **Pestaña inactiva:** blanca, borde `#dbdbdb`, ícono clip.
    - **Sin JS:** se ven las dos secciones una debajo de la otra.
    - **Resumen:**
        - título de la lección (`h2`, `--text-h3`, verde);
        - su texto en `--text-cuerpo-l` con 14 px entre párrafos, con negritas, enlaces y emojis tal como vienen; el texto pasa por `wp_kses` con la lista permitida de `cursos-datos.md`.
    - **Cita "PD":** un párrafo que empieza con "PD:" se muestra solo como la cita: fondo `--color-fondo-rosa`, borde izquierdo de 4 px frambuesa, radio 12, padding 24/18 y "PD:" en seminegrita. Bárbara no tiene que marcar nada.
    - **Materiales:** una tarjeta por PDF de la lección.
        - **Tarjeta:** blanca, borde beige al 45 %, radio 16.
        - **Izquierda:** cuadrado rosa de 48 px con ícono de archivo.
        - **Centro:** el nombre ("Horarios 4 a 9 meses", `--text-rotulo`, verde) y "PDF · <tamaño>" (`--text-caption`, `--color-tinta-suave`; decisión 2026-11-13: tamaño, no páginas).
        - **Derecha:** "Descargar" en pastilla crema con borde beige. Es un enlace de descarga firmada con nombre accesible "Descargar <nombre>".
    - **Sin materiales:** no se muestra la pestaña "Materiales".
    - **Móvil (propuesta):** pestañas a todo el ancho y tarjetas de material con "Descargar" debajo del nombre.
- **Indicaciones del diseño:**
    - nota: el PD (va dentro de resumen) es asi https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3901-7716&t=lySNWh2dFHTQgiTC-4
    - materiales se vera asi https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3899-7674&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (del plugin, no campos): lección "Bienvenida" con 7 párrafos (texto completo en la lectura de Figma) y la cita "PD: ¿Ya activaste tu mes gratis en Mundo Angelitozzz? Revisa tu correo — está ahí esperándote. 🤍"; material "Horarios 4 a 9 meses", "PDF · 13 páginas".

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Las pestañas son accesibles con teclado (`role="tab"`, `aria-selected`, `aria-controls`) y sin JS se ven ambas secciones
- [ ] Un párrafo que empieza con "PD:" sale con el estilo de cita, sin marcado especial en el texto guardado
- [ ] El texto de la lección no deja pasar HTML fuera de la lista permitida
- [ ] Cada "Descargar" es una descarga firmada que falla (403) sin acceso
- [ ] Sin materiales no aparece la pestaña "Materiales"
