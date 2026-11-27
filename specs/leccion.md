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
    - **Cambio (decisión del usuario, 2026-11-25):** el video deja Vimeo. Es un archivo privado del sitio que se reproduce con el reproductor del navegador, con dirección firmada y marca de agua (sección `video`). Los materiales aceptan más tipos que PDF (sección `pestanas`). Detalle en `cursos-datos.md` → `archivos-privados` y `acceso`.
    - **Vimeo vuelve (decisión de la clienta y del usuario, 2026-11-27, F5-e):** el video de una lección puede ser un enlace de Vimeo (lo habitual: la clienta tiene sus videos ahí) o un archivo privado. Los dos van en el mismo marco, con la misma marca de agua y la misma pantalla completa (sección `video`).
    - Mes gratis en Mundo: banner solo en la primera lección del curso (siempre visible), más el bloque en la confirmación de compra y en el correo de pedido. Detalle en la nota “Banner del mes gratis en Mundo”.
    - Nota Banner del mes gratis en Mundo (para desarrollo) a continuacion
    - Aplica a Recién Nacidos Go y a los 3 cursos de Horarios (Angelita Pro no incluye el mes gratis). Versión simple, según lo pedido por Bárbara en la Reunión 3 (reducir costos).
    - Correo de pedido de WooCommerce (el que llega por defecto o como sea lo conveniendo, en el proceso se decide si es woo u otro): se le agrega el bloque del mes gratis con el botón “Activar mi mes gratis en Mundo Angelitozzz”, basado en el “Email 1 — Confirmación inmediata” del Web Copy. Sin secuencia de recordatorios.
    - Decidido en la F5-c (2026-11-19): es el correo "Procesando"/"Completado" de WooCommerce a la clienta, con el bloque que imprime `inc/woocommerce/correos.php` (textos y enlace de `opciones_mes_gratis_mundo`).
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
    - Aplicado en la F4, bloque 5 (2026-11-14): el avance del curso se actualiza en el temario y se anuncia por `aria-live`, sin barra (el diseño no la tiene); "Marcar como completo" funcionaba solo con JS; desde la F5-c (plugin 0.7.1) es un formulario a la acción `angelitozzz_completar_leccion` (mismo permiso que la ruta REST, `angelitozzz_cursos_puede_completar()`) que el JS envía por la REST sin recargar; las pestañas usan el `src/js/modules/pestanas.js` genérico (no un `pestanas-leccion.js`); el video sale de un `<template>` con `angelitozzz_cursos_video_html()` y se carga al pulsar play (`video-leccion.js`), con `<noscript>` sin JS; el título del mensaje de Angelita Pro es `h2` para no saltar del `h1`; el banner de Mundo va entre el video y las pestañas, como en el orden del spec. Para saber si la alumna compró el curso (botón, candados y PDF del curso) se usa `angelitozzz_cursos_puede_ver_curso()`.
    - Verificado en la F5-c (2026-11-19, en el Local): "Marcar como completo" sin JS marca, desmarca y vuelve a la lección; el banner de Mundo no se imprime sin enlace en Ajustes del sitio.
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
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] "Marcar como completo" funciona con JS (ruta REST del plugin con el nonce `wp_rest`), y solo para la usuaria con sesión y acceso
- [x] El estado del botón se anuncia (`aria-pressed`) y el temario se actualiza al marcar
- [x] Sin JS, "Marcar como completo" envía un formulario con nonce a `admin-post.php` y vuelve a la lección (F5-c; una vista previa sin compra no guarda avance)

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
    - **Al final:** los materiales del curso ("PDF Recién Nacidos Go"), con el ícono de su tipo y el de descarga, sin duración ni check. Son direcciones firmadas (`cursos-datos.md` → `acceso`). Desde el 2026-11-25 pueden ser de cualquier tipo de material, no solo PDF. El ícono lo elige `angelitozzz_icono_material()` (`inc/leccion.php`) por la clase y la extensión del material: documento (PDF y Word, el `pdf.svg` del diseño), hoja (Excel), presentación (PowerPoint), imagen o audio; los cuatro últimos son propuesta (`assets/img/leccion/tipo-*.svg`, el mismo círculo del diseño).
    - **Sin compra** (vista previa, propuesta): las lecciones que no son de vista previa se muestran con un candado y sin enlace, y los materiales del curso no se muestran.
    - **Datos:** salen de `angelitozzz_cursos_temario()`.
    - **Móvil (propuesta):** el temario pasa arriba del video, plegado en un acordeón de wp-accordion "Contenido del curso", con la lección actual visible al abrirlo.
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (del plugin, no campos): módulo "Recién Nacidos Go" con 11 lecciones (Bienvenida 3:59, Objetivos del curso 3:20, Sueño seguro 4:10, Las 4 piezas del rompecabezas del sueño 5:20, 5 herramientas para calmar a tu bebé 7:20, Demostración práctica: el swaddle seguro 1:29, Horarios y siestas del recién nacido 9:05, Consejos para practicar el sueño independiente 5:10, Demostración: escalera de sueño 6:49, Las siestas del recién nacido 6:12, Consejos para las noches 3:15) y "PDF Recién Nacidos Go".

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Cada estado (completada, pendiente, actual) se distingue también sin color (ícono y texto para lectores)
- [x] Un título largo se corta con "…" y sigue completo para lectores de pantalla
- [x] El PDF del curso solo se descarga con acceso
- [x] En móvil el temario es un `<details>` de wp-accordion sobre el video

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
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Solo aparece en la primera lección de Angelita Pro (curso de tipo `suscripcion`)
- [x] Los pasos son una lista ordenada y la decoración no la leen los lectores de pantalla

## Sección: video

- **Origen:** `specs/figma/lectura/leccion/seccion-entera.ref.txt` (reproductor 4176:28055)
- **Destino:** `template-parts/pages/leccion/video.php`, `src/js/modules/video-leccion.js`, `src/css/leccion-video.css`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** marco de 1033 × 567 (proporción 16:9, radio 15).
    - **Dos tipos de video (F5-e, 2026-11-27):** `angelitozzz_cursos_video_tipo()` dice si la lección tiene un enlace de **Vimeo** o un **archivo privado** (`cursos-datos.md` → `leccion`). Lo de esta sección vale para los dos, salvo lo marcado como "solo archivo privado" o "solo Vimeo".
    - **Carga diferida:** el video no se pide al abrir la página. Se ve su portada (`angelitozzz_cursos_video_portada()`: con un archivo privado, la imagen que se tomó al subirlo, con su dirección firmada; con Vimeo, la miniatura de Vimeo) con el botón play crema de 88 px (`<button>` "Reproducir video: <lección>"). Al pulsarlo se reemplaza por el reproductor de `angelitozzz_cursos_video_html()` y empieza a reproducir.
    - **Sin portada:** fondo beige del diseño.
    - **Sin video:** la lección no muestra el marco.
    - **Reproductor de Vimeo (solo Vimeo, F5-e):** el `<iframe>` de `player.vimeo.com` que arma el plugin (`dnt=1`, `h=` si es oculto), que al pulsar play se carga con `autoplay=1`. Sin `allowfullscreen`: la pantalla completa de Vimeo no está y se usa la del marco (abajo), así la marca de agua no se pierde. Los controles, la velocidad y adelantar son los de Vimeo. La dirección no vence, así que no hay renovación. El clic derecho dentro del iframe es de Vimeo (no se puede cancelar desde la página).
    - **Reproductor de archivo privado (2026-11-25):**
        - `<video>` del navegador con `controls`, `playsinline`, `preload="metadata"`, `controlsList="nodownload noremoteplayback"`, `disablePictureInPicture` y la dirección firmada del archivo (`cursos-datos.md` → `acceso`). Se puede adelantar porque el servidor entrega por pedazos.
        - Sin menú del clic derecho sobre el video (`contextmenu` cancelado).
        - **Marca de agua (los dos tipos):** el correo de la alumna en texto chico, blanco al 35 % con sombra suave, encima del video (`pointer-events: none`, sin tapar los controles). Cambia de esquina cada 30 segundos (propuesta). Desanima que se grabe la pantalla y se comparta, porque se ve de quién es.
        - **Pantalla completa (los dos tipos):** con el marco entero (video y marca de agua), por un botón propio "Pantalla completa" sobre el reproductor; la pantalla completa propia del `<video>` se quita (`controlsList="nofullscreen"`) para que la marca no desaparezca. En iPhone, Safari solo deja poner en pantalla completa el `<video>` solo, así que ahí la marca no se ve (límite aceptado).
        - (Solo archivo privado.) La dirección vence a las 4 horas: si la alumna deja la pestaña abierta más tiempo y el video falla, el JS pide una nueva a `GET angelitozzz/v1/lecciones/<id>/video` (`cursos-datos.md` → `funciones-publicas`) y sigue en la misma posición y con el mismo estado (propuesta). Como mucho un intento por minuto; si no se puede, un aviso `role="alert"` en el marco: "No pudimos cargar el video. Recarga la página.". Al pulsar play, si la dirección de la página ya venció (o vence en menos de un minuto), se pide otra antes de empezar.
        - Cambiar de proveedor sigue tocando solo `includes/video.php` del plugin.
    - **Cómo está hecho (4b, 2026-11-26):** el plugin entrega el reproductor ya armado (`[data-angelitozzz-video]` con el `<video>`, `[data-angelitozzz-video-marca]` y `[data-angelitozzz-video-pantalla]`); el tema lo deja en un `<template>` y le da forma por esos atributos en `src/css/leccion-video.css`. La marca recorre las esquinas en este orden: arriba izquierda, arriba derecha, abajo derecha y abajo izquierda (`data-esquina`); abajo queda por encima de la barra de controles, y arriba a la derecha baja un poco para no tapar "Pantalla completa". El doble clic en el video también pone el marco en pantalla completa. Firefox no respeta `controlslist="nofullscreen"`: si su botón pone el `<video>` solo en pantalla completa (sin la marca), el JS sale de ella e intenta pasarla al marco entero. Firefox no deja ese segundo intento, así que en Firefox su botón no agranda el video y solo funciona el botón propio (decisión del usuario, 2026-11-26: dejarlo así; prima que el video nunca se vea sin la marca). Sin JS, el reproductor sale en `<noscript>` encima del botón play.
    - **Con Vimeo (F5-e):** el mismo `<template>`, la marca, el botón "Pantalla completa" y el doble clic sobre la marca (dentro del iframe el doble clic es de Vimeo). Sin renovación de dirección ni nonce de la ruta `lecciones/<id>/video`. Sin JS, el iframe sale en `<noscript>` como el `<video>`.
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

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El botón play es un `<button>` con nombre accesible y se usa con teclado
- [x] El video mantiene 16:9 en todos los anchos
- [x] (4b, `cdp-leccion-video.mjs`) El video no se pide hasta pulsar play (al abrir la página solo se pide la portada)
- [x] (4b, en Chrome; falta Safari, ver `cursos-datos.md` → acceso) El reproductor no tiene botón de descarga ni menú del clic derecho, y se puede adelantar
- [x] (4b; en pantalla completa lo comprobó el usuario en Chrome y Firefox con ventana, 2026-11-26) La marca de agua con el correo se ve encima del video, también en la pantalla completa propia
- [x] (4b, `cdp-leccion-video.mjs`) Copiar la dirección del video y abrirla en otro navegador o en una ventana privada no lo muestra (403)
- [x] (4b, `cdp-leccion-video.mjs`) Sin acceso a la lección (o después de cerrar sesión), la dirección del video responde 403
- [ ] (F5-e) Con Vimeo: antes de play no hay iframe ni pedido a `player.vimeo.com`; al pulsar play el iframe carga con `dnt=1` y `autoplay=1`, sin `allowfullscreen`
- [ ] (F5-e) Con Vimeo: la portada es la miniatura de Vimeo, la marca de agua se ve y rota, y "Pantalla completa" pone el marco entero con la marca
- [ ] (F5-e) Con Vimeo: sin acceso a la lección, el HTML no tiene `player.vimeo.com`
- [ ] (F5-e) Una lección con archivo privado sigue cumpliendo todo lo de arriba

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
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Valores del diseño (campos en `opciones.md` → `mes-gratis-mundo`): título "Tu mes gratis en Mundo Angelitozzz", texto "Incluido con tu curso: sesiones grupales en vivo con Bárbara todos los miércoles a las 10:00am (Perú/Colombia) y la videoteca de talleres.", pie "Si ya lo activaste, puedes ignorar este mensaje.", botón "Activar mi mes gratis".

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Solo aparece en la primera lección de los cursos que incluyen el mes gratis
- [x] El enlace sale por `angelitozzz_link()` (pestaña nueva con `rel` y aviso)

## Sección: pestanas

- **Origen:** `specs/figma/lectura/leccion/seccion-entera.ref.txt` (pestañas 4176:28059, texto de la lección, PD 3901:7716, materiales 3899:7674)
- **Destino:** `template-parts/pages/leccion/pestanas.php`, `src/js/modules/pestanas.js`, `inc/leccion.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** pestañas "Resumen" y "Materiales" bajo el video. Con `role="tablist"`, se recorren con las flechas del teclado y cambian sin recargar.
    - **Pestaña activa:** fondo `#fafeff`, borde `#492343`, `--text-h5` verde, ícono documento.
    - **Pestaña inactiva:** blanca, borde `#dbdbdb`, ícono clip.
    - **Sin JS:** se ven las dos secciones una debajo de la otra.
    - **Resumen:**
        - título de la lección (`h2`, `--text-h3`, verde);
        - su texto en `--text-cuerpo-l` con 14 px entre párrafos, con negritas, enlaces y emojis tal como vienen; el texto lo entrega el plugin con `angelitozzz_cursos_texto_leccion()`, ya filtrado con la lista permitida de `cursos-datos.md` y con `rel="noopener noreferrer"` en los enlaces con `target`.
    - **Cita "PD":** un párrafo que empieza con "PD:" se muestra solo como la cita: fondo `--color-fondo-rosa`, borde izquierdo de 4 px frambuesa, radio 12, padding 24/18 y "PD:" en seminegrita. Bárbara no tiene que marcar nada.
    - **Materiales:** una tarjeta por material de la lección (desde el 2026-11-25, de cualquier tipo de material de `cursos-datos.md` → `archivos-privados`: PDF, Word, Excel, PowerPoint, imagen o audio).
        - **Tarjeta:** blanca, borde beige al 45 %, radio 16.
        - **Izquierda:** cuadrado rosa de 48 px con el ícono de su tipo (documento, hoja de cálculo, presentación, imagen o audio; los de documento son el ícono de archivo del diseño). Lo elige `angelitozzz_icono_material()` (`inc/leccion.php`); los cuatro que no son documento son propuesta, con el mismo trazo (`assets/img/leccion/archivo-*.svg`).
        - **Centro:** el nombre ("Horarios 4 a 9 meses", `--text-rotulo`, verde) y "<tipo> · <tamaño>" ("PDF · 2,4 MB", "Word · 120 KB"; `--text-caption`, `--color-tinta-suave`; decisión 2026-11-13: tamaño, no páginas).
        - **Derecha:** "Descargar" en pastilla crema con borde beige. Es la dirección firmada del archivo con nombre accesible "Descargar <nombre>".
        - **Audio (propuesta):** además de "Descargar", un reproductor de audio del navegador debajo del nombre (`<audio controls preload="none" controlsList="nodownload">` con la misma dirección firmada, `aria-label` "Escuchar <nombre>"). El reproductor usa `url` (por pedazos) y "Descargar" usa `url_descarga`, que baja el MP3 entero con su nombre (decisión del usuario, 2026-11-26, plugin 0.10.2: por ejemplo, para tener un ruido blanco sin internet).
    - **Sin materiales:** no se muestra la pestaña "Materiales".
    - **Móvil (propuesta):** pestañas a todo el ancho y tarjetas de material con "Descargar" debajo del nombre.
- **Indicaciones del diseño:**
    - nota: el PD (va dentro de resumen) es asi https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3901-7716&t=lySNWh2dFHTQgiTC-4
    - materiales se vera asi https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=3899-7674&t=lySNWh2dFHTQgiTC-4
- **Estado:** aplicado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (del plugin, no campos): lección "Bienvenida" con 7 párrafos (texto completo en la lectura de Figma) y la cita "PD: ¿Ya activaste tu mes gratis en Mundo Angelitozzz? Revisa tu correo — está ahí esperándote. 🤍"; material "Horarios 4 a 9 meses", "PDF · 13 páginas".

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Las pestañas son accesibles con teclado (`role="tab"`, `aria-selected`, `aria-controls`) y sin JS se ven ambas secciones
- [x] Un párrafo que empieza con "PD:" sale con el estilo de cita, sin marcado especial en el texto guardado
- [x] El texto de la lección no deja pasar HTML fuera de la lista permitida, tampoco lo que otro plugin agregue con `the_content`
- [x] Cada "Descargar" es una descarga firmada (URL con nonce del plugin; el 403 sin acceso lo cubren las pruebas del plugin)
- [x] Sin materiales no aparece la pestaña "Materiales"
- [x] (4b, `cdp-leccion-video.mjs`) Cada material muestra el ícono y el texto de su tipo ("Word · 120 KB") y se descarga con su nombre
- [x] (4b, `cdp-leccion-video.mjs`) Un material de audio se puede escuchar en la tarjeta sin descargarlo
