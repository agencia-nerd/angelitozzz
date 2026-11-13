# Página: Inicio

- **Resumen:** Presenta a Angelitozzz y a Bárbara (coach de sueño pediátrico), muestra las formas en que puede ayudar (cursos, asesoría, Mundo Angelitozzz) y lleva a la visitante a encontrar su curso ideal.
- **Fuente:** `figma:home`
- **Plantilla:** `front-page.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** hero, como-ayudarte, quien-soy, seccion-cta, beneficios, seccion-instagram, testimonios, cta-final
- **Skills usadas:** wp-helper (imágenes, enlaces y texto con saltos de línea `angelitozzz_highlight()`)
- **Grupo ACF:** Inicio
- **Ubicación:** página de inicio (front page)
- **Padre:** ninguno
- **Notas de página:**
    - La pagina tiene una imagen de fondo (quedara fija donde se usa), pero no en todas / otras paginas se repiten.
    - El cintillo-promociones, header, footer va en todas las paginas obligatoriamente.
    - la seccion de testimonios no todos comparten los mismos testimonios, pero cada pagina que tiene testimonios es independiente, incluyendo la pagina de testimonios, pero si usar lo que se pide para las secciones testimonios (swiper, no automatico, redifreccionar a su pagina)
    - varias paginas compartes la estructura o similar a de cta-final, como se menciona en la nota de esa seccion, eso aun quedara pendiente para las demas cta-final de otras paginas.
    - `h1`: el título del hero ("La maternidad tiene muchos sacrificios…"). Los títulos de las demás secciones son `h2` aunque usen el estilo `--text-h1` o `--text-h3`; los nombres de las tarjetas son `h3`.
    - Fondo de la página: color `--color-fondo` (crema) con la imagen de nubes `pagina-fondo-nubes.webp` (la misma de Soluciones de sueño, `specs/figma/lectura/soluciones-de-sueno/assets/`) fija en el tema, no editable, y se queda quieta al hacer scroll (`background-attachment: fixed`; en móvil, donde iOS no lo respeta, una capa `position: fixed` detrás del contenido). Se ve detrás de las secciones sin fondo propio (como-ayudarte, beneficios, seccion-instagram); hero, quien-soy, seccion-cta, testimonios y cta-final tapan el fondo con su color o imagen. Se aplica solo en `front-page.php` (clase en `<main>`), no en todo el sitio.
    - Cintillo, header y footer salen de `header.php` y `footer.php` (spec `opciones.md`); `front-page.php` no los repite.
    - Título SEO "Inicio | Angelitozzz"; el diseño no trae meta descripción.
    - Sin frame móvil en Figma: el comportamiento en móvil de cada sección es una propuesta en su `Comportamiento`.
    - Enlaces a páginas que aún no existen (página de cursos, tienda/shop, cursos de la plataforma externa) quedan con `#` y se anotan como pendientes en su sección.
- **Estado:** aplicado

## Sección: hero

- **Origen:** `specs/figma/lectura/home/hero.ref.txt` (nodo 4011:9021)
- **Destino:** `template-parts/pages/home/hero.php`
- **Grupo:** `home_hero`
- **Skill:** wp-helper
- **Comportamiento:** fondo `--color-neutro`, dos columnas a todo el ancho: texto a la izquierda y la foto a la derecha (caja 940 × 626, `object-cover`, relación 3:2, `loading="eager"` por ser la imagen principal; si el cliente no escribe el alt, `alt_fallback` = el título). Antetítulo con `--text-eyebrow` en `--color-marca-suave`; título `h1` con `--text-display` en `--color-marca`, en dos líneas con `angelitozzz_highlight()`; bajada `--text-cuerpo-l`; credenciales en `--text-rotulo` `--color-marca-suave`, como `<ul>` en fila con un punto "•" entre ítems dibujado por CSS (no es texto). Botón con el componente `boton` (`enlace` = `home_hero_boton`, `variante` `frambuesa`, `flecha` true); lleva a la página de cursos, que aún no existe: queda con `#` (pendiente). Decoración (SVG con `alt=""` y `aria-hidden`): móvil colgante a la izquierda (3 hilos de 150/250/110 px con 2 lunas y una estrella, unidos en un solo SVG) y 3 estrellas de contorno sobre la foto (rotadas −9,9° y 21,26°, unidas en uno o dos SVG). Valores sueltos: padding del texto 180 px a la izquierda y 72 px a la derecha, separaciones 22, 32 y 16 px, caja de foto 940 × 626. Móvil (propuesta): una columna, primero el texto (padding lateral 16 px) y debajo la foto a todo el ancho con relación 3:2; el título baja a `--text-h2`; las credenciales pasan a varias líneas sin los puntos; el móvil colgante se achica y las estrellas se ocultan si tapan el texto.
- **Indicaciones del diseño:**
    - Encuentra tu curso ideal llevara a la pagina de cursos que aun esta en solo diseño.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                          | Tipo     | Descripción                                                                                                              | Valor actual                                                                                                      | Requerido |
| ------------------------------ | -------- | ------------------------------------------------------------------------------------------------------------------------ | ----------------------------------------------------------------------------------------------------------------- | --------- |
| `home_hero_eyebrow`            | text     | Antetítulo sobre el título principal.                                                        | Coach de Sueño Pediátrico · Desde 2016                                                                            | no        |
| `home_hero_titulo`             | textarea | Título principal de la portada, en dos líneas. Usa Enter para pasar a otra línea.           | La maternidad tiene muchos sacrificios.<br>El sueño no debería ser uno de ellos.                                  | sí        |
| `home_hero_bajada`             | textarea | Texto bajo el título.                                                                       | Cursos, asesorías y acompañamiento para que tu bebé aprenda a dormir bien — desde recién nacido hasta los 4 años. | no        |
| `home_hero_credenciales`       | repeater | Datos cortos bajo la bajada, separados por un punto. El diseño tiene 3                                                   | —                                                                                                                 | no        |
| `home_hero_credenciales.texto` | text     | Un dato (por ejemplo, familias acompañadas).                                                 | 8.000+ familias acompañadas                                                                                       | sí        |
| `home_hero_boton`              | link     | Botón principal del hero: texto y destino                                                                                | Encuentra tu curso ideal                                                                                          | sí        |
| `home_hero_imagen`             | image    | Foto del hero, a la derecha. Se recorta para llenar el bloque. Recomendado: hasta 2000 px en el lado más largo, horizontal. | —                                                                                                                 | sí        |

Credenciales del diseño, en orden: "8.000+ familias acompañadas", "10 años de experiencia", "12+ países".

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El título del hero es el único `h1` de la página
- [x] La foto carga con `loading="eager"` y mantiene la relación 3:2 sin deformarse
- [x] El botón usa el componente `boton` y queda con `#` hasta que exista la página de cursos
- [x] Si las credenciales están vacías, la fila no se muestra

## Sección: como-ayudarte

- **Origen:** `specs/figma/lectura/home/como-ayudarte.ref.txt` (nodo 4011:9045)
- **Destino:** `template-parts/pages/home/como-ayudarte.php`, `template-parts/components/producto-card.php`
- **Grupo:** `home_como_ayudarte`
- **Skill:** wp-helper
- **Comportamiento:** por ahora solo diseño: las 4 tarjetas son fijas en la plantilla (los cursos de la plataforma externa aún no se conectan), pero los campos quedan documentados para el paso ACF. Título `h2` con `--text-h1` en `--color-marca` y un "zzz" decorativo (SVG) arriba a la derecha; bajada `--text-cuerpo-s`. Cada tarjeta es el componente `producto-card` con `imagen`, `etiqueta`, `etiqueta_icono`, `titulo`, `edad`, `descripcion` y `boton` (sin `beneficios`): imagen 346 × 374 con `--radius-tarjeta`, etiqueta de esquina (`etiqueta` estilo `esquina`), nombre y edad centrados en `--text-cuerpo`, descripción `--text-cuerpo-s` en `--color-tinta-suave`, y botón `boton` variante `frambuesa` con flecha. La primera tarjeta (Angelita Pro) no tiene imagen a propósito: el componente muestra la imagen por defecto "not found" (`ayudarte-imagen-not-found.webp`, asset del tema). La descripción son dos párrafos en un solo texto (línea en blanco entre ambos) impreso con `angelitozzz_highlight()`. Todos los botones quedan con `#` (cursos de la plataforma externa, pendiente). Las flechas circulares a los lados (54 px, borde `--color-contorno`) indican un carrusel: Swiper como en testimonios (`src/js/modules/carrusel-tarjetas.js`) (no automático, `<button>` con texto accesible "Anterior"/"Siguiente"), 4 tarjetas por vista en escritorio; las flechas se ocultan si todas las tarjetas caben. Valores sueltos: padding vertical 88 px, separaciones 54, 32, 24, 20, 16 y 15 px, ancho del encabezado 485 px, radio 32 px del primer recuadro (se usa `--radius-tarjeta`). Móvil (propuesta): 1,15 tarjetas por vista (asoma la siguiente) con deslizamiento táctil, 2 en tablet, flechas ocultas en móvil; padding lateral 16 px.
- **Indicaciones del diseño:**
    - Este apartado sera por el momento solo diseño, ya que aun no se implementara lo que es los cursos de una plataforma externa.
    - Se esta pondiendo un curso sin alguna imagen, para que cuando no tenga imagen sea la imagen por defecto not found.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                                     | Tipo     | Descripción                                                                                                                          | Valor actual                                                                                                                          | Requerido |
| ----------------------------------------- | -------- | ------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------- | --------- |
| `home_como_ayudarte_titulo`               | text     | Título de la sección de formas de ayuda.                                                                 | ¿Cómo puedo ayudarte?                                                                                                                 | sí        |
| `home_como_ayudarte_bajada`               | text     | Texto bajo el título.                                                                                    | Distintas formas de acompañarte, según lo que necesitas hoy.                                                                          | no        |
| `home_como_ayudarte_items`                | repeater | Tarjetas de cursos y servicios, en el orden en que se muestran. El diseño tiene 4                                                    | —                                                                                                                                     | sí        |
| `home_como_ayudarte_items.imagen`         | image    | Imagen de la tarjeta. Si se deja vacía se muestra la imagen por defecto. Recomendado: hasta 2000 px en el lado más largo, vertical. | —                                                                                                                                     | no        |
| `home_como_ayudarte_items.etiqueta`       | text     | Etiqueta de la esquina de la imagen (Nuevo, Curso Online…). Si se deja vacía no se muestra.              | Nuevo                                                                                                                                 | no        |
| `home_como_ayudarte_items.etiqueta_icono` | select   | Ícono de la etiqueta. Opciones: ninguno, destellos, estrella                                                                                 | destellos                                                                                                                             | no        |
| `home_como_ayudarte_items.titulo`         | text     | Nombre del curso o servicio.                                                                             | Angelita Pro                                                                                                                          | sí        |
| `home_como_ayudarte_items.edad`           | text     | Rango de edad, bajo el nombre.                                                                           | (4 a 24 meses)                                                                                                                        | no        |
| `home_como_ayudarte_items.descripcion`    | textarea | Descripción de la tarjeta. Deja una línea en blanco para separar párrafos.                              | Tu coach de sueño con IA, disponible 24/7 por WhatsApp.<br><br>Plan integral de sueño, respuestas inmediatas, sin turnos ni horarios. | sí        |
| `home_como_ayudarte_items.boton`          | link     | Botón de la tarjeta: texto y destino                                                                                                 | Quiero conocer a Angelita                                                                                                             | sí        |

Tarjetas del diseño (textos completos en `como-ayudarte.ref.txt`): Angelita Pro (sin imagen, etiqueta "Nuevo" con destellos, botón "Quiero conocer a Angelita"), A Dormir Por Fin (etiqueta "Asesoría con Bárbara" con estrella, botón "Quiero mi asesoría personal"), Recién Nacidos Go (etiqueta "Curso Online", botón "Ver curso"), Cursos de Horarios Ideales (etiqueta "Curso Online", botón "Ver cursos").

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Las 4 tarjetas usan el componente `producto-card`, sin marcado propio de tarjeta
- [x] La tarjeta sin imagen muestra la imagen por defecto "not found" con la misma proporción que las demás
- [x] El carrusel no avanza solo, se mueve con las flechas, con teclado y deslizando en móvil
- [x] Los botones quedan con `#` hasta que se conecten los cursos

## Sección: quien-soy

- **Origen:** `specs/figma/lectura/home/quien-soy.ref.txt` (nodo 4011:10706)
- **Destino:** `template-parts/pages/home/quien-soy.php`
- **Grupo:** `home_quien_soy`
- **Skill:** wp-helper
- **Comportamiento:** fondo `--color-neutro`, contenido en una columna de 1143 px (la del diseño; `--container-sitio` dejaría 39 px entre texto y foto en vez de 72): texto a la izquierda (columna de 589 px, centrado vertical) y foto a la derecha (482 × 487, `--radius-media`, `object-cover`, `loading="lazy"`; `alt_fallback` = el título). Título `h2` con `--text-h1` en `--color-marca` y un corazón con degradado (SVG decorativo, 46 px) arriba a la derecha del título. Texto `--text-cuerpo-s` en párrafos de una línea cada uno, como un solo texto con saltos impreso con `angelitozzz_highlight()`. Botón `boton` variante `frambuesa` con flecha, lleva a la página Conoce a Bárbara (`/conoce-a-barbara/`). Valores sueltos: padding vertical 88 px, separaciones 24 y 32 px, ancho de la columna de texto 589 px, ancho del contenido 1143 px. Móvil (propuesta): una columna, primero la foto (ancho completo, relación casi cuadrada 482:487) y debajo el texto y el botón; padding lateral 16 px; el título baja a `--text-h2`.
- **Indicaciones del diseño:**
    - EL boton llevara a la pagina conoce a barbara
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                   | Tipo     | Descripción                                                                                                                             | Valor actual                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | Requerido |
| ----------------------- | -------- | --------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------- |
| `home_quien_soy_titulo` | text     | Título de la presentación de Bárbara.                                                                       | ¡Hola, soy Bárbara!                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | sí        |
| `home_quien_soy_texto`  | textarea | Texto de presentación. Usa Enter para pasar a otra línea.                                                  | Soy Coach de Sueño Pediátrico certificada, mami de dos y fundadora de Angelitozzz desde 2016.<br>En 2012, cuando nació mi hija mayor, conocí de primera mano la desesperación de no dormir y la culpa de no saber cómo resolverlo.<br>En 10 años he acompañado a más de 8.000 familias en 12 países a lograr algo que muchas creían imposible: que su bebé durmiera de corrido — noches y siestas.<br>Para que no te pase lo que me pasó a mí.<br>Para que tu bebé, tu familia y tú puedan florecer. | sí        |
| `home_quien_soy_boton`  | link     | Botón que lleva a la página Conoce a Bárbara: texto y destino                                                                           | Conoce mi historia                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | sí        |
| `home_quien_soy_imagen` | image    | Foto de Bárbara, a la derecha del texto. Se recorta para llenar el bloque. Recomendado: hasta 2000 px en el lado más largo, cuadrada. | —                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El botón lleva a la página Conoce a Bárbara
- [x] Los saltos de línea del texto se imprimen con `angelitozzz_highlight()`

## Sección: seccion-cta

- **Origen:** `specs/figma/lectura/home/seccion-cta.ref.txt` (nodo 4011:10704)
- **Destino:** `template-parts/pages/home/seccion-cta.php`
- **Grupo:** `home_seccion_cta`
- **Skill:** wp-helper
- **Comportamiento:** presenta Mundo Angelitozzz. Fondo: la imagen de lunas sobre teal `cta-fondo-lunas.webp` (asset decorativo del tema, no editable) con `object-cover` detrás del contenido. Todo centrado en una columna de 1070 px: logo de Mundo Angelitozzz (86 × 86, círculo), título `h2` con `--text-h3` en `--color-marca` en tres líneas (un solo texto con saltos, `angelitozzz_highlight()`), párrafo `--text-cuerpo`, lista `<ul>` de 3 puntos en `--text-cuerpo` (la viñeta "•" la dibuja CSS, no es parte del texto), precio en `--text-h5` `--color-marca` y botón `boton` variante `verde` (texto y flecha crema) que lleva a la página Mundo Angelitozzz (`/mundo-angelitozzz/`). Si el precio o el logo están vacíos no se muestran. Valores sueltos: padding 88 px vertical y 425 px lateral (columna de 1070 px), separaciones de 32 px. Móvil (propuesta): padding lateral 16 px, el título baja a `--text-h4` y los saltos se mantienen; la lista se alinea a la izquierda para que las viñetas no queden desordenadas; la imagen de fondo sigue con `object-cover`.
- **Indicaciones del diseño:**
    - Quiero tener siempre una respuesta, llevara a la pagina de mundo-angelitozzz
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                           | Tipo     | Descripción                                                                                                                 | Valor actual                                                                                                                                                                                                           | Requerido |
| ------------------------------- | -------- | --------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------- |
| `home_seccion_cta_logo`         | image    | Logo de Mundo Angelitozzz sobre el título. Se muestra en un círculo. Recomendado: hasta 2000 px en el lado más largo, cuadrada. | —                                                                                                                                                                                                                      | no        |
| `home_seccion_cta_titulo`       | textarea | Título de la sección, en tres líneas. Usa Enter para pasar a otra línea.                       | El sueño de tu bebé cambia todo el tiempo.<br>Y cada vez que cambia, aparecen nuevas preguntas.<br>Mundo Angelitozzz es el lugar donde siempre hay respuestas.                                                         | sí        |
| `home_seccion_cta_texto`        | textarea | Párrafo bajo el título.                                                                        | Cada semana, una sesión en vivo conmigo para resolver lo que está pasando ahora mismo con el sueño de tu bebé. Más talleres grabados, recursos al instante y una comunidad de mamás que ya pasaron por donde tú estás. | no        |
| `home_seccion_cta_puntos`       | repeater | Lista de lo que incluye Mundo Angelitozzz. El diseño tiene 3                                                                | —                                                                                                                                                                                                                      | no        |
| `home_seccion_cta_puntos.texto` | text     | Un punto de la lista.                                                                          | Sesiones en vivo semanales con Bárbara — resuelves dudas en tiempo real                                                                                                                                                | sí        |
| `home_seccion_cta_precio`       | text     | Precio y condición, sobre el botón.                                                             | Desde $14/mes · Cancela cuando quieras                                                                                                                                                                                 | no        |
| `home_seccion_cta_boton`        | link     | Botón que lleva a la página Mundo Angelitozzz: texto y destino                                                              | Quiero tener siempre una respuesta                                                                                                                                                                                     | sí        |

Puntos del diseño, en orden: "Sesiones en vivo semanales con Bárbara — resuelves dudas en tiempo real", "Talleres y recursos para cada etapa, disponibles al instante", "Chat privado con mamás que entienden lo que estás viviendo".

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El botón usa la variante `verde` del componente `boton` y lleva a la página Mundo Angelitozzz
- [x] La lista es un `<ul>` y la viñeta sale de CSS, no del texto
- [x] La imagen de fondo cubre la sección sin deformarse en escritorio y en móvil

## Sección: beneficios

- **Origen:** `specs/figma/lectura/home/beneficios.ref.txt` (nodo 4011:9188)
- **Destino:** `template-parts/pages/home/beneficios.php`
- **Grupo:** `home_beneficios`
- **Skill:** ninguna
- **Comportamiento:** composición tipo móvil de cuna sobre el fondo de nubes de la página: título `h2` con `--text-h3` en `--color-marca` dentro de una nube central (SVG decorativo 486 × 291) y 5 beneficios, cada uno con su colgante (hilo + ícono en un SVG decorativo: cubo, carita, corazón, luna, chupete, de izquierda a derecha) y su texto `--text-cuerpo` centrado debajo. Los íconos y sus posiciones son fijos en la plantilla y siguen el orden del repetidor (el ítem 1 va bajo el cubo, el 2 bajo la carita…); el diseño está pensado para 5 beneficios. Los textos se maquetan como `<ul>`. Se corrige el error del diseño "más más feliz" → "más feliz" (decisión del usuario). Los colores de hilos e íconos (`#9ec0c0` green 20 y `#492343`) quedan dentro de los SVG, no como clases. Valores sueltos: padding inferior 88 px, posiciones de la composición (colgantes de 267 a 558 px de alto, textos de 198 a 280 px de ancho); en escritorio se arma con una grilla de 5 columnas de 1485 px (el ancho de la composición del diseño) y cada columna baja según el largo de su colgante, sin posiciones absolutas; la nube con el título comparte la celda de la grilla. Móvil y tablet (propuesta, por debajo de `lg`): el título dentro de la nube arriba y los 5 beneficios en una lista de una columna (dos en tablet), cada uno con su ícono sin el hilo largo encima del texto; padding lateral 16 px.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                         | Tipo     | Descripción                                                                                                                        | Valor actual                                                 | Requerido |
| ----------------------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------ | --------- |
| `home_beneficios_titulo`      | text     | Título dentro de la nube central.                                                                      | Beneficios del sueño saludable                               | sí        |
| `home_beneficios_items`       | repeater | Beneficios, de izquierda a derecha. Cada uno se muestra bajo su colgante (cubo, carita, corazón, luna, chupete). Máximo: 5. | —                                                            | sí        |
| `home_beneficios_items.texto` | text     | Texto de un beneficio.                                                                                | Dormir promueve el sano crecimiento y desarrollo de tu bebé. | sí        |

Beneficios del diseño, en orden: "Dormir promueve el sano crecimiento y desarrollo de tu bebé.", "Un bebé descansado es más feliz y menos estresado." (corregido), "Mejora la salud mental materna y fortalece el vínculo madre-bebé.", "Dormir bien fomenta su aprendizaje y desarrollo del lenguaje", "Mejora la tolerancia a la frustración.".

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El segundo beneficio dice "Un bebé descansado es más feliz y menos estresado." (sin "más más")
- [x] En escritorio la composición se arma con grilla, sin posiciones absolutas para los textos
- [x] En móvil los 5 beneficios se leen en orden, cada uno con su ícono, sin solaparse

## Sección: seccion-instagram

- **Origen:** `specs/figma/lectura/home/seccion-instagram.ref.txt` (nodo 4011:10557)
- **Destino:** `template-parts/pages/home/seccion-instagram.php`
- **Grupo:** `home_seccion_instagram`
- **Skill:** wp-helper
- **Comportamiento:** por ahora solo diseño: las 3 imágenes son fijas en la plantilla (assets `instagram-post-1.webp` a `instagram-post-3.webp`), a la espera de conectar el Instagram con un plugin; los campos quedan documentados. Sobre el fondo de nubes de la página: título `h2` con `--text-h1` en `--color-marca` alineado a la izquierda, 3 imágenes en fila con `justify-between` (333 × 466, relación 5:7, `--radius-media`, `object-cover`, `loading="lazy"`, `alt_fallback` = el título) y el botón `boton` variante `frambuesa` centrado. El botón "Síguenos" lleva al perfil de Instagram: mientras sea solo diseño usa la URL de Instagram de `opciones_redes_items` y abre en pestaña nueva con el helper de enlaces; si no hay, queda con `#`. Valores sueltos: padding 88 px vertical y 388 px lateral (se usa `--container-sitio`), separaciones de 57 y 49 px. Móvil (propuesta): las imágenes pasan a una fila con desplazamiento horizontal (`scroll-snap`, 1,2 imágenes por vista, sin JS), 3 columnas más chicas desde tablet; padding lateral 16 px.
- **Indicaciones del diseño:**
    - Este apartado es referencias, o sea tambien solo diseño ya que se buscara la forma de conectarlo con su IG, quizas con algun plugin.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                             | Tipo    | Descripción                                                                                                                 | Valor actual            | Requerido |
| --------------------------------- | ------- | --------------------------------------------------------------------------------------------------------------------------- | ----------------------- | --------- |
| `home_seccion_instagram_titulo`   | text    | Título de la sección de Instagram.                                                              | ¡Síguenos en Instagram! | sí        |
| `home_seccion_instagram_imagenes` | gallery | Imágenes de publicaciones de Instagram, en fila. El diseño tiene 3. Recomendado: hasta 2000 px en el lado más largo, vertical. | —                       | no        |
| `home_seccion_instagram_boton`    | link    | Botón al perfil de Instagram: texto y destino. Se abre en una pestaña nueva                                                 | Síguenos                | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Las 3 imágenes mantienen la relación 5:7 y cargan con `lazy`
- [x] El botón abre Instagram en una pestaña nueva con `rel` seguro y aviso accesible (helper de enlaces)
- [x] En móvil la fila se desliza horizontalmente sin scroll horizontal de la página

## Sección: testimonios

- **Origen:** `specs/figma/lectura/home/testimonios.ref.txt` (nodo 4011:9243)
- **Destino:** `template-parts/pages/home/testimonios.php`, `template-parts/components/testimonios-slider.php`
- **Grupo:** `home_testimonios`
- **Skill:** wp-helper
- **Comportamiento:** usa el componente `testimonios-slider` con `titulo` = `home_testimonios_titulo`, `testimonios` = el repetidor `home_testimonios_items` (`autora`, `detalle`, `estrellas`, `texto`) y `boton` = `home_testimonios_boton`. Repetidor propio de Inicio, independiente del tipo `testimonio` y de los testimonios de otras páginas. Slider con Swiper (`src/js/modules/testimonios-slider.js`), **no automático**: flechas circulares blancas arriba a la derecha (`<button>` con texto accesible "Anterior"/"Siguiente"), puntos de paginación debajo, se mueve con teclado y deslizando; respeta `prefers-reduced-motion` (sin animación de transición). Fondo `--color-neutro`; título `h2` con `--text-h2` en `--color-marca` con un destello blanco decorativo a la izquierda. Tarjetas blancas de 320 px de alto, `--radius-media`, estrellas en `--color-estrella`, comillas decorativas, cita `--text-cuerpo`, separador `--color-neutro`, autora `--text-rotulo` en `--color-marca` con avatar circular `--color-fondo-rosa` y corazón, detalle `--text-caption` en `--color-tinta-suave`. En escritorio 3 tarjetas por vista y las de los lados asoman al 45 % de opacidad con degradado `--color-neutro`. Botón "Ver más testimonios" con `boton` variante `secundario` sin flecha, lleva a la página Testimonios (`/testimonios/`). Decoración: nubes y "ZZZ" (15 letras Z crema al 40 % de opacidad) al pie, SVG con `aria-hidden`, estáticas. Valores sueltos: padding 104 px arriba, 181 px abajo y 405 px lateral (`--container-sitio`), separaciones 56, 24, 14 y 12 px, padding de tarjeta 28 px, alto de tarjeta 320 px, opacidad 45 % y 40 %. Móvil (propuesta): 1,1 tarjetas por vista (asoma la siguiente), 2 en tablet; las flechas bajan junto a los puntos; alto de tarjeta automático (mínimo 320 px) para que el texto no se corte; la "ZZZ" se reduce.
- **Indicaciones del diseño:**
    - usa swiper para este slider, que no sea automatico.
    - testimonios independientes.
    - ver mas testimonios llevara a la pagina de testimonios.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                              | Tipo     | Descripción                                                                           | Valor actual                                                                                        | Requerido |
| ---------------------------------- | -------- | ------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------- | --------- |
| `home_testimonios_titulo`          | text     | Título del slider de testimonios.                         | SHHH... Bebés durmiendo felices                                                                     | sí        |
| `home_testimonios_items`           | repeater | Testimonios del slider de Inicio (independientes de otras páginas). El diseño tiene 5 | —                                                                                                   | sí        |
| `home_testimonios_items.texto`     | textarea | Texto del testimonio, con sus comillas.                  | “Probé de todo y nada funcionaba. Con el plan de Bárbara, en una semana mi bebé dormía en su cuna.” | sí        |
| `home_testimonios_items.autora`    | text     | Nombre de quien da el testimonio.                         | Carolina, mamá de Tomás                                                                             | sí        |
| `home_testimonios_items.detalle`   | text     | Edad del bebé, bajo el nombre.                            | Bebé de 6 meses                                                                                     | no        |
| `home_testimonios_items.estrellas` | number   | Cantidad de estrellas, de 1 a 5                                                       | 5                                                                                                   | no        |
| `home_testimonios_boton`           | link     | Botón bajo el slider que lleva a la página Testimonios: texto y destino               | Ver más testimonios                                                                                 | sí        |

Testimonios del diseño (textos completos en `testimonios.ref.txt`), en orden: Carolina, mamá de Tomás (Bebé de 6 meses); Daniela, mamá de Mateo (Bebé de 8 meses); Lucía, mamá de Emilia (Bebé de 2 años); Valeria, mamá de Joaquín (Bebé de 12 días); Andrea, mamá de Lucas (Bebé de 7 meses). Todos con 5 estrellas.

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Usa el componente `testimonios-slider` con Swiper y no avanza solo
- [x] Flechas y puntos funcionan con teclado y el slider se desliza en móvil
- [x] "Ver más testimonios" lleva a `/testimonios/`
- [x] Los testimonios salen del repetidor de Inicio, no del tipo `testimonio`

## Sección: cta-final

- **Origen:** `specs/figma/lectura/home/cta-final.ref.txt` (nodo 4011:9244)
- **Destino:** `template-parts/pages/home/cta-final.php`, `template-parts/components/cta-final.php`
- **Grupo:** `home_cta_final`
- **Skill:** wp-helper
- **Comportamiento:** usa el componente `cta-final` con `titulo` = `home_cta_final_titulo`, `descripcion` vacía (el diseño de Inicio no la tiene, el componente la oculta) y `boton` = `home_cta_final_boton`. Fondo `--color-fondo-rosa`, título `h2` con `--text-h2` en `--color-marca` centrado, botón `boton` variante `frambuesa` con flecha. El botón lleva a la página shop/tienda/cursos, que aún no tiene nombre ni existe: queda con `#` (pendiente). Los CTA finales de las demás páginas usan el mismo componente pero sus destinos quedan pendientes en sus specs. Decoración: destellos blancos a izquierda y derecha (7 piezas en 4 SVG con `aria-hidden`: grupo de arriba a la izquierda, grupo de abajo a la izquierda, el destello grande y el de la derecha; el grande se sale por abajo y se recorta con `overflow-hidden`). Valores sueltos: padding 104 px vertical y 405 px lateral (`--container-sitio`), separación de 20 px más un espacio de 8 px antes del botón. Móvil (propuesta): padding lateral 16 px, el título baja a `--text-h3`, los destellos se achican y el grande se oculta.
- **Indicaciones del diseño:**
    - Eso llevara a la pagina shop/tienda/cursos (aun falta definir el nombre de la pagina), pero aun estara pendiente.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                   | Tipo     | Descripción                                                  | Valor actual                                                                            | Requerido |
| ----------------------- | -------- | ------------------------------------------------------------ | --------------------------------------------------------------------------------------- | --------- |
| `home_cta_final_titulo` | textarea | Título del cierre de la página. | La maternidad ya tiene suficientes sacrificios. El sueño no tiene que ser uno de ellos. | sí        |
| `home_cta_final_boton`  | link     | Botón del cierre: texto y destino                            | Encuentra tu solución                                                                   | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Usa el componente `cta-final` sin marcado propio y sin descripción
- [x] El botón queda con `#` hasta que exista la página de la tienda o cursos
