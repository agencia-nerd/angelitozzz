# Página: Mundo Angelitozzz

- **Resumen:** Presenta Mundo Angelitozzz, la comunidad de suscripción mensual donde las mamás resuelven cada semana sus dudas de sueño con Bárbara; el visitante entiende para quién es y qué incluye, lee testimonios y se une con un enlace externo (el pago se hace fuera del sitio).
- **Fuente:** `figma:mundo-angelitozzz`
- **Plantilla:** `pages/template-mundo-angelitozzz.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** hero, promocinar-marca, que-incluye-mundo, testimonios, cta-mundo
- **Skills usadas:** wp-helper
- **Grupo ACF:** Mundo Angelitozzz
- **Ubicación:** plantilla pages/template-mundo-angelitozzz.php
- **Padre:** ninguno
- **Notas de página:**
    - `h1` en el hero ("El sueño de tu bebé no para de cambiar. …")
    - título SEO "Mundo Angelitozzz | Angelitozzz"
    - sin meta descripción en el diseño
    - los tres botones de suscripción ("Quiero unirme" del hero, "Unirme a la comunidad" y "Quiero unirme a Mundo Angelitozzz") van al mismo enlace externo, un solo campo URL de la página (`mundo_angelitozzz_hero_enlace_suscripcion`), y abren en una pestaña nueva con el helper de enlaces de `wp-helper`; cada botón tiene su propio texto
    - el WhatsApp del hero ("Escríbeme por WhatsApp.") usa `opciones_contacto_whatsapp` y `opciones_contacto_whatsapp_mensaje` de Ajustes del sitio, no un campo de la página
    - en el hero del diseño hay un precio oculto ("$ 14 / x mes"): está escondido en Figma, no se documenta ni se maqueta
    - cada sección tiene su propio fondo (crema, blanco, teal, beige, rosa): la página no usa el fondo de nubes de Inicio
    - testimonios: la página tiene su propio repetidor, independiente de Inicio y de la página Testimonios (nota de página de Inicio: "cada pagina que tiene testimonios es independiente")
    - sin frame móvil en Figma: el móvil sigue lo propuesto en el `Comportamiento` de cada sección
- **Estado:** aplicado

## Sección: hero

- **Origen:** `specs/figma/lectura/mundo-angelitozzz/hero.ref.txt` (nodo 4017:20213)
- **Destino:** `template-parts/pages/mundo-angelitozzz/hero.php`
- **Grupo:** `mundo_angelitozzz_hero`
- **Skill:** wp-helper
- **Comportamiento:** fondo `--color-fondo`, alto mínimo de 808 px en escritorio con el contenido centrado en vertical. Dos columnas centradas con `gap-[129px]` (valor suelto): texto a la izquierda (507 px, `gap-[58px]` suelto entre bloques) y foto a la derecha. Columna de texto: logo de Mundo Angelitozzz (75 × 75, campo de imagen, `'alt_fallback'` "Mundo Angelitozzz"), a 10 px el título `h1` (estilo `--text-h2`, `--color-marca`, ancho máximo 600 px; en el diseño la caja de 600 px desborda la columna y se deja así) con su salto de línea, a `gap-[17px]` (suelto) la bajada (`--text-body-s`, `--color-tinta`), a `gap-6` el botón compartido `boton` variante `frambuesa` con flecha y texto "Quiero unirme"; debajo, la nota de precio (`--text-body-s`, `--color-tinta`) y la línea de regalo: ícono de regalo (`hero-icono-regalo.svg`, 18 px, decorativo, fijo del tema) con `gap-2`, el texto "¿Quieres regalar Mundo Angelitozzz?" (`--text-body-s`, `--color-tinta`) y el enlace subrayado "Escríbeme por WhatsApp." (`--text-label-s`, `--color-marca`; los `#000000` y `#234549` sueltos del diseño usan esos tokens). Foto a la derecha: 477 × 390, `aspect-[477/390]`, `object-cover`, `--radius-media`; es la imagen principal de la página (`'loading' => 'eager'`, `'alt_fallback'` con el título). **Botón "Quiero unirme":** lleva a la URL externa de `mundo_angelitozzz_hero_enlace_suscripcion` (la misma de "Unirme a la comunidad" y "Quiero unirme a Mundo Angelitozzz"); el componente la recibe como string y pasa por `angelitozzz_link_attrs()` con `'blank' => true` (pestaña nueva, `rel` seguro y aviso `sr-only`); el pago no ocurre en este sitio. Mientras el cliente no entregue la URL real se usa la URL genérica `https://example.com/` (para que no haya confusiones), anotada como pendiente. **"Escríbeme por WhatsApp.":** `<a>` a `https://wa.me/<opciones_contacto_whatsapp>?text=<opciones_contacto_whatsapp_mensaje codificado>`, en pestaña nueva con el helper de enlaces; el número y el mensaje salen de Ajustes del sitio (`get_field(..., 'option')`), ningún número queda escrito en la plantilla; si el número está vacío se muestra solo el texto de regalo sin enlace. Si la nota de precio o el texto de regalo vienen vacíos se ocultan. **Decoraciones** (`alt=""`, `aria-hidden`, posición absoluta respecto a la sección, fijas del tema): nube con ojos cerrados arriba a la derecha (`hero-nube-derecha*.svg`, 328 × 196, a 125 px del borde superior y 8.5 % del borde derecho) y nube grande con ojos abajo a la izquierda que se sale del borde (`hero-nube-izquierda*.svg`, 524 × 315, `left` −4.27 %, a 459 px del borde superior); cada nube se exporta como un solo SVG con sus ojos o se arma con sus tres piezas. Móvil (propuesto): `px-4 py-12` sin alto mínimo; una sola columna con el texto arriba y la foto debajo a todo el ancho (misma proporción); logo a 56 px, título en `--text-h3`, `gap-8` entre bloques en lugar de 58 px; el botón mantiene su ancho; la línea de regalo puede pasar a dos líneas; la nube de la derecha se oculta y la de la izquierda se reduce a la mitad detrás del texto.
- **Indicaciones del diseño:**
    - El boton/link Quiero unirme llevara a un enlace externo, ese proceso/pago no se realizara en esta pagina
    - El boton/link Escríbeme por WhatsApp, ese numero sumalo un campo en configuracion del sitio
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                                       | Tipo     | Descripción                                                                                                                                                                                                                                | Valor actual                                                                                                                               | Requerido |
| ------------------------------------------- | -------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------ | --------- |
| `mundo_angelitozzz_hero_logo`               | image    | Logo de Mundo Angelitozzz sobre el título. Se muestra en un círculo chico. Recomendado: hasta 2000 px en el lado más largo, cuadrada. | —                                                                                                                                          | no        |
| `mundo_angelitozzz_hero_titulo`             | textarea | Título principal de la página (h1), en dos líneas. Usa Enter para pasar a otra línea.                                                                                                                         | El sueño de tu bebé no para de cambiar.<br>Cada etapa trae nuevas preguntas. Aquí siempre hay respuestas.                                  | sí        |
| `mundo_angelitozzz_hero_texto`              | textarea | Texto bajo el título que explica qué es Mundo Angelitozzz.                                                                                                                                                    | Mundo Angelitozzz es la comunidad donde resuelves tus dudas de sueño con Bárbara cada semana — para que dejes de adivinar con cada cambio. | sí        |
| `mundo_angelitozzz_hero_enlace_suscripcion` | url      | Enlace externo para suscribirse a Mundo Angelitozzz (el pago se hace fuera del sitio). Lo usan los tres botones de la página: "Quiero unirme", "Unirme a la comunidad" y "Quiero unirme a Mundo Angelitozzz". Se abre en una pestaña nueva | https://example.com/                                                                                                                       | sí        |
| `mundo_angelitozzz_hero_boton`              | text     | Texto del botón principal del hero. Lleva al enlace de suscripción.                                                                                                                                            | Quiero unirme                                                                                                                              | sí        |
| `mundo_angelitozzz_hero_precio`             | text     | Nota de precio bajo el botón. Si se deja vacía no se muestra.                                                                                                                                                  | Desde $14/mes · Cancela cuando quieras                                                                                                     | no        |
| `mundo_angelitozzz_hero_regalo_texto`       | text     | Texto de la línea de regalo, junto al ícono de regalo. Si se deja vacío la línea no se muestra.                                                                                                                | ¿Quieres regalar Mundo Angelitozzz?                                                                                                        | no        |
| `mundo_angelitozzz_hero_regalo_enlace`      | text     | Texto del enlace a WhatsApp de la línea de regalo. El número y el mensaje salen de Ajustes del sitio.                                                                                                          | Escríbeme por WhatsApp.                                                                                                                    | no        |
| `mundo_angelitozzz_hero_imagen`             | image    | Foto principal de la página, a la derecha del texto. Se recorta para llenar el marco. Recomendado: hasta 2000 px en el lado más largo, horizontal. | —                                                                                                                                          | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El título es el único `h1` de la página y respeta su salto de línea
- [x] La foto carga con `loading="eager"` y mantiene la proporción 477:390 en escritorio y móvil
- [x] "Quiero unirme" usa el componente `boton` (variante `frambuesa`), lee la URL de `mundo_angelitozzz_hero_enlace_suscripcion` y abre en pestaña nueva con `rel` seguro y aviso `sr-only` (por ahora con la URL genérica `https://example.com/`, pendiente de la real)
- [x] "Escríbeme por WhatsApp." arma `https://wa.me/…` con `opciones_contacto_whatsapp` y el mensaje de Ajustes del sitio; no hay ningún número escrito en la plantilla
- [x] El precio oculto del diseño ("$ 14 / x mes") no aparece en la página
- [x] Las dos nubes son decorativas y no tapan el texto en ninguna pantalla

## Sección: promocinar-marca

- **Origen:** `specs/figma/lectura/mundo-angelitozzz/promocinar-marca.ref.txt` (nodo 4017:20254)
- **Destino:** `template-parts/pages/mundo-angelitozzz/promocinar-marca.php`
- **Grupo:** `mundo_angelitozzz_promocinar_marca`
- **Skill:** ninguna
- **Comportamiento:** el nombre de la sección es el del usuario en Figma; su contenido es la comparación "¿Te suena alguna de estas? / Para quién es". Fondo `--color-blanco`, `py-22`. Tarjeta centrada `--color-fondo` de 1191 px de ancho máximo (`max-w-[1191px]`, valor suelto: más ancha que `--container-sitio`), radio 30 px (`rounded-[30px]`, valor suelto) y padding `pt-14 pb-14 px-18`. Dentro, dos columnas iguales de 470 px separadas por un divisor vertical de 1 px `--color-neutro` (borde izquierdo de la segunda columna, no un elemento aparte), con el divisor alineado arriba con los títulos. Cada columna: título `h2` (estilo `--text-h3`, `--color-marca`), a `gap-6` la lista de viñetas (`--text-body`, `--color-tinta`) como `<ul>` con un `<li>` por fila del repetidor y la viñeta "•" por CSS (`list-disc` o `marker`), nunca escrita en el texto. Bajo la lista izquierda, a `gap-6`, la nota en itálica (`--text-nota`, color `#c4704a` `color/terracota` como valor suelto `text-[#c4704a]`: se usa una sola vez en el sitio). La lista izquierda tiene 5 ítems y la derecha 6; el alto de la tarjeta lo da el contenido (sin los 470 px fijos del diseño). **Viñetas como repetidor y no textarea:** cada lista es un `repeater` con un subcampo `texto`, porque así el cliente agrega, quita y reordena ítems sin escribir "•" a mano ni depender de saltos de línea, y cada columna puede tener un número distinto de ítems. No se maquetan los 2 textos ocultos que quedaron de otra página (bonos del taller de horarios) ni el grupo vacío de la capa (4017:20259). Si la nota viene vacía se oculta. Móvil (propuesto): `px-4 py-12`; la tarjeta ocupa el ancho con `p-6` y radio `--radius-tarjeta`; las columnas pasan a una sola, una debajo de la otra, y el divisor pasa a horizontal (borde superior de la segunda columna con `pt-6 mt-6`); títulos en `--text-h4`.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                                                   | Tipo     | Descripción                                                                                                 | Valor actual                                                                                                            | Requerido |
| ------------------------------------------------------- | -------- | ----------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------- | --------- |
| `mundo_angelitozzz_promocinar_marca_situaciones_titulo` | text     | Título de la columna izquierda (situaciones con las que se identifica la mamá). | ¿Te suena alguna de estas?                                                                                              | sí        |
| `mundo_angelitozzz_promocinar_marca_situaciones`        | repeater | Lista de situaciones de la columna izquierda, una por fila. La viñeta se agrega sola. En el diseño son 5    | —                                                                                                                       | sí        |
| `mundo_angelitozzz_promocinar_marca_situaciones.texto`  | text     | Texto de una situación.                                                        | Tu bebé dormía bien y de repente todo cambió — y no sabes cómo volver.                                                  | sí        |
| `mundo_angelitozzz_promocinar_marca_situaciones_nota`   | textarea | Frase en itálica bajo la lista izquierda. Si se deja vacía no se muestra.      | El sueño infantil cambia constantemente. No es que lo estés haciendo mal — es que nadie te está guiando en tiempo real. | no        |
| `mundo_angelitozzz_promocinar_marca_perfiles_titulo`    | text     | Título de la columna derecha (para quién es Mundo Angelitozzz).                 | Mundo Angelitozzz es para ti si...                                                                                      | sí        |
| `mundo_angelitozzz_promocinar_marca_perfiles`           | repeater | Lista de la columna derecha, una razón por fila. La viñeta se agrega sola. En el diseño son 6               | —                                                                                                                       | sí        |
| `mundo_angelitozzz_promocinar_marca_perfiles.texto`     | text     | Texto de una razón.                                                            | Eres mamá primeriza y el sueño de tu bebé te tiene confundida — necesitas apoyarte en alguien que realmente sepa.       | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Las dos listas son `<ul>` con un `<li>` por ítem y la viñeta sale del CSS, no del texto
- [x] La columna izquierda muestra 5 ítems y la derecha 6, separadas por el divisor de 1 px
- [x] La nota usa `#c4704a` como único valor suelto de color, listado en este `Comportamiento`
- [x] No aparecen los textos ocultos de bonos ni elementos vacíos del diseño
- [x] En móvil las columnas se apilan y el divisor pasa a horizontal, sin scroll horizontal

## Sección: que-incluye-mundo

- **Origen:** `specs/figma/lectura/mundo-angelitozzz/que-incluye-mundo.ref.txt` (nodo 4017:20281)
- **Destino:** `template-parts/pages/mundo-angelitozzz/que-incluye-mundo.php`
- **Grupo:** `mundo_angelitozzz_que_incluye_mundo`
- **Skill:** wp-helper
- **Comportamiento:** fondo `--color-etiqueta`, contenedor `--container-sitio`, `py-26`, encabezado centrado con `gap-5`: antetítulo (`--text-eyebrow`, `--color-marca`), título `h2` (estilo `--text-h1`, `--color-marca`, ancho máximo 900 px) y bajada (`--text-body-l`, `--color-marca`, ancho máximo 760 px). A `mt-11` (20 + 24 px del diseño) una fila de 3 tarjetas iguales con `gap-8`: fondo `--color-blanco`, `--radius-tarjeta`, `overflow-hidden`; imagen arriba a todo el ancho con `aspect-[349/220]`, `object-cover`, `loading="lazy"` y `'alt_fallback'` con el título de la tarjeta; debajo, `p-7` y `gap-2.5`: título `h3` (`--text-h4`, `--color-marca`), texto (`--text-body`, `--color-tinta`) y una pastilla `--color-fondo` `rounded-full` `px-3.5 py-1.5` con su etiqueta (`--text-label-s`, `--color-marca`). Las tarjetas no tienen el alto fijo de 414 px del diseño: se igualan con la fila (`items-stretch`). La primera imagen del diseño está recortada a mano para encuadrar a Bárbara; en el tema todas usan `object-cover` (con `object-top` si hace falta) y el cliente sube la imagen ya encuadrada. A `mt-11` el botón compartido `boton` variante `verde` con flecha y el texto de `mundo_angelitozzz_que_incluye_mundo_boton`, que lleva a la misma URL externa del hero (`mundo_angelitozzz_hero_enlace_suscripcion`, con `'blank' => true`); mientras no exista usa la URL genérica `https://example.com/`, anotada como pendiente. **Decoraciones** (`alt=""`, `aria-hidden`, posición absoluta, fijas del tema): destello blanco arriba a la izquierda (`que-incluye-destello-izquierda.svg`, 60 × 63), destello crema arriba a la derecha (`que-incluye-destello-derecha.svg`, 46 × 47) y nube abajo a la derecha que se sale del borde derecho e inferior de la sección (`que-incluye-nube.svg`, 300 × 131, `right` −3.13 %); la sección no recorta la nube (`overflow-visible`) para que asome sobre la siguiente como en la captura. Móvil (propuesto): `px-4 py-16`; título en `--text-h3`, bajada en `--text-body`; tarjetas en una columna (tres desde `lg`, una debajo de otra antes) con el mismo `gap-8`; destellos reducidos a la mitad y la nube a 160 px.
- **Indicaciones del diseño:**
    - el boton Unirme a la comunidad deberia llevar de igual manera que el hero a un enlace externo.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                                                     | Tipo     | Descripción                                                                                                                             | Valor actual                                                                   | Requerido |
| --------------------------------------------------------- | -------- | --------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------ | --------- |
| `mundo_angelitozzz_que_incluye_mundo_antetitulo`          | text     | Texto chico sobre el título de la sección.                                                                  | Qué incluye tu suscripción                                                     | no        |
| `mundo_angelitozzz_que_incluye_mundo_titulo`              | text     | Título de la sección.                                                                                       | Todo lo que necesitas para nunca quedarte sin respuesta.                       | sí        |
| `mundo_angelitozzz_que_incluye_mundo_bajada`              | textarea | Frase bajo el título.                                                                                      | Respuestas en vivo, contenido para cada etapa y una comunidad que te acompaña. | no        |
| `mundo_angelitozzz_que_incluye_mundo_beneficios`          | repeater | Tarjetas de lo que incluye la suscripción. En el diseño son 3                                                                           | —                                                                              | sí        |
| `mundo_angelitozzz_que_incluye_mundo_beneficios.imagen`   | image    | Imagen de la parte superior de la tarjeta. Se recorta para llenar el marco. Recomendado: hasta 2000 px en el lado más largo, horizontal. | —                                                                              | sí        |
| `mundo_angelitozzz_que_incluye_mundo_beneficios.titulo`   | text     | Título de la tarjeta.                                                                                       | Sesiones en vivo con Bárbara                                                   | sí        |
| `mundo_angelitozzz_que_incluye_mundo_beneficios.texto`    | textarea | Texto de la tarjeta.                                                                                       | Cada semana resuelves tus dudas en vivo.                                       | sí        |
| `mundo_angelitozzz_que_incluye_mundo_beneficios.etiqueta` | text     | Etiqueta chica al pie de la tarjeta (horario, disponibilidad). Si se deja vacía no se muestra.              | Miércoles · 10am Perú/Colombia                                                 | no        |
| `mundo_angelitozzz_que_incluye_mundo_boton`               | text     | Texto del botón bajo las tarjetas. Lleva al enlace de suscripción del hero.                                 | Unirme a la comunidad                                                          | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Las 3 tarjetas salen de un solo bloque de markup repetido (bucle sobre el repetidor), con el título como `h3`
- [x] Las imágenes mantienen la proporción 349:220 con `object-cover` y cargan con `lazy`
- [x] "Unirme a la comunidad" usa el componente `boton` (variante `verde`) y lleva a la misma URL que "Quiero unirme" del hero, en pestaña nueva
- [x] Una tarjeta sin etiqueta no deja una pastilla vacía
- [x] En móvil las tarjetas pasan a una columna sin scroll horizontal y la nube no tapa contenido

## Sección: testimonios

- **Origen:** `specs/figma/lectura/mundo-angelitozzz/testimonios.ref.txt` (nodo 4017:20317, instancia del componente Figma "Testimonios · Tarjetas" 3810:27)
- **Destino:** `pages/template-mundo-angelitozzz.php`, `template-parts/components/testimonios-slider.php`, `src/js/modules/testimonios-slider.js`
- **Grupo:** `mundo_angelitozzz_testimonios`
- **Skill:** wp-helper
- **Comportamiento:** usa el componente compartido `testimonios-slider` (`_shared.md`), llamado desde la plantilla con `get_template_part('template-parts/components/testimonios-slider', null, $args)` y los argumentos `titulo`, `testimonios` (filas del repetidor propio de esta página, con `autora`, `detalle`, `estrellas` y `texto`) y `boton`. "Usar lo que se pide" son las reglas de testimonios de Inicio: slider con Swiper, **sin avance automático**, y "Ver más testimonios" lleva a la página Testimonios (`/testimonios/`, el archivo `archive-testimonio.php`). Los testimonios son independientes: este repetidor no comparte datos con Inicio ni con el tipo de contenido `testimonio`. Lo que dibuja el componente: fondo `--color-neutro`, `pt-26 pb-[181px]` (suelto, deja sitio a la fila de "Z"), `gap-14`; encabezado con el título `h2` (`--text-h2`, `--color-marca`) a la izquierda y las flechas anterior/siguiente a la derecha (círculos `--color-blanco` de 48 px, `<button>` con texto accesible); 3 tarjetas visibles de 354 px con `gap-6` y una tarjeta asomada a cada lado al 45 % de opacidad, tapada por un degradado de 220 px de `--color-neutro` a transparente; cada tarjeta `--color-blanco`, `--radius-media`, 5 estrellas `--color-estrella`, la cita (`--text-body`, `--color-tinta`), comillas decorativas arriba a la derecha, divisor `--color-neutro`, avatar `--color-fondo-rosa` con corazón (decorativo, no hay foto), autora (`--text-label`, `--color-marca`) y detalle (`--text-caption`, `--color-tinta-suave`); puntos de paginación debajo; botón `boton` variante `secundario` sin flecha. **Decoraciones del componente** (`alt=""`, `aria-hidden`): destello crema junto al título, destello amarillo pequeño junto a la tarjeta asomada derecha, nube arriba a la derecha (260 × 113), nube con ojos abajo a la izquierda (300 × 180) y la fila "Detalle ZZZ" de 15 letras "Z" crema rotadas al 40 % de opacidad (texto decorativo con `aria-hidden`, no se lee). Móvil: el del componente compartido (propuesto: 1 tarjeta visible con la siguiente asomando, sin degradados, flechas bajo el título, título en `--text-h3`, `px-4 py-16`, fila de "Z" reducida).
- **Indicaciones del diseño:**
    - testimonios independientes.
    - usar lo que se pide.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                                                 | Tipo     | Descripción                                                                                                                  | Valor actual                                                                                                                       | Requerido |
| ----------------------------------------------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | --------- |
| `mundo_angelitozzz_testimonios_titulo`                | text     | Título del slider de testimonios de esta página.                                                 | Lo que dicen las mamás que ya son parte de Mundo Angelitozzz.                                                                      | sí        |
| `mundo_angelitozzz_testimonios_testimonios`           | repeater | Testimonios del slider, propios de esta página (no se comparten con Inicio ni con la página Testimonios). En el diseño son 5 | —                                                                                                                                  | sí        |
| `mundo_angelitozzz_testimonios_testimonios.texto`     | textarea | Testimonio, con sus comillas.                                                                   | “Bárbara, te quiero agradecer por tu guía durante todo este proceso. ¡Noah está durmiendo mejor que nunca y yo no lo puedo creer!” | sí        |
| `mundo_angelitozzz_testimonios_testimonios.autora`    | text     | Nombre de quien da el testimonio.                                                                | Raisa                                                                                                                              | sí        |
| `mundo_angelitozzz_testimonios_testimonios.detalle`   | text     | Edad del bebé o detalle bajo el nombre.                                                          | Bebé de 5 meses                                                                                                                    | no        |
| `mundo_angelitozzz_testimonios_testimonios.estrellas` | number   | Cantidad de estrellas, de 1 a 5. Por defecto 5                                                                               | 5                                                                                                                                  | no        |
| `mundo_angelitozzz_testimonios_boton`                 | link     | Botón bajo el slider: texto y destino (la página Testimonios)                                                                | Ver más testimonios                                                                                                                | no        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] La sección es una llamada al componente `testimonios-slider` con `$args`, sin markup propio en la plantilla
- [x] El slider usa Swiper, no avanza solo y se mueve con las flechas y con teclado
- [x] Los testimonios salen del repetidor de esta página, distintos de los de Inicio
- [x] "Ver más testimonios" lleva a `/testimonios/`
- [x] En móvil se ve una tarjeta con la siguiente asomando, sin scroll horizontal de la página

## Sección: cta-mundo

- **Origen:** `specs/figma/lectura/mundo-angelitozzz/cta-mundo.ref.txt` (nodo 4017:20487)
- **Destino:** `template-parts/pages/mundo-angelitozzz/cta-mundo.php`
- **Grupo:** `mundo_angelitozzz_cta_mundo`
- **Skill:** wp-helper
- **Comportamiento:** no es el componente `cta-final`: tiene lista de beneficios, precio y nota, y solo existe en esta página. Fondo `--color-fondo-rosa`, contenedor `--container-sitio`, `py-26`, todo centrado con `gap-6`. Título `h2` (estilo `--text-display`, `--color-marca`). Fila de 3 beneficios con `gap-7` como `<ul>`: cada `<li>` con un círculo `--color-marca` de 24 px y el check blanco (`cta-check.svg`, 14 px, decorativo, fijo del tema) a `gap-2` del texto (`--text-body-l`, `--color-marca`). Precio (`--text-label`, `--color-marca-suave`). A 8 px más (`mt-2`) el botón compartido `boton` variante `frambuesa` con flecha y el texto de `mundo_angelitozzz_cta_mundo_boton`, que lleva a la misma URL externa del hero (`mundo_angelitozzz_hero_enlace_suscripcion`, con `'blank' => true`); mientras no exista usa la URL genérica `https://example.com/`, anotada como pendiente. Debajo la nota (`--text-body-s`, `--color-tinta-suave`). Si el precio o la nota vienen vacíos se ocultan. **Decoraciones** (`alt=""`, `aria-hidden`, posición absoluta, fijas del tema): destello blanco grande con rayos pegado arriba a la izquierda (`cta-destello-grande.svg`, 248 × 262, a 146 px del borde izquierdo), destello mediano a la derecha (`cta-destello-mediano.svg`, 120 × 125, x 1408, y 208) y pequeño arriba a la derecha (`cta-destello-pequeno.svg`, 68 × 71, x 1639, y 98); se posicionan respecto al centro de la sección para no separarse del contenido en pantallas más angostas que 1920. Móvil (propuesto): `px-4 py-16`; título en `--text-h3`; los 3 beneficios en columna alineados a la izquierda dentro de un bloque centrado, con `gap-3`; botón a todo el ancho si no entra su texto; el destello grande se reduce a 120 px y el mediano se oculta.
- **Indicaciones del diseño:**
    - el boton enlace Quiero unirme a Mundo Angelitozzz deberia de ir igualmente al enlace externo.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                                          | Tipo     | Descripción                                                                                                    | Valor actual                                            | Requerido |
| ---------------------------------------------- | -------- | -------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------- | --------- |
| `mundo_angelitozzz_cta_mundo_titulo`           | text     | Título del bloque de cierre de la página.                                          | Todo esto, desde $14 al mes.                            | sí        |
| `mundo_angelitozzz_cta_mundo_beneficios`       | repeater | Beneficios en fila bajo el título, cada uno con un check. En el diseño son 3                                   | —                                                       | sí        |
| `mundo_angelitozzz_cta_mundo_beneficios.texto` | text     | Texto de un beneficio.                                                             | Sesiones grupales en vivo con Bárbara                   | sí        |
| `mundo_angelitozzz_cta_mundo_precio`           | text     | Línea de precio y condiciones bajo los beneficios. Si se deja vacía no se muestra. | Desde $14/mes · Sin contratos · Cancela cuando quieras  | no        |
| `mundo_angelitozzz_cta_mundo_boton`            | text     | Texto del botón. Lleva al enlace de suscripción del hero.                          | Quiero unirme a Mundo Angelitozzz                       | sí        |
| `mundo_angelitozzz_cta_mundo_nota`             | text     | Nota chica bajo el botón. Si se deja vacía no se muestra.                          | Sin compromisos. Cancela cuando quieras, sin penalidad. | no        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Los beneficios son un `<ul>` con un `<li>` por fila del repetidor y el check es decorativo
- [x] "Quiero unirme a Mundo Angelitozzz" usa el componente `boton` (variante `frambuesa`) y lleva a la misma URL que los otros dos botones de suscripción, en pestaña nueva
- [x] Sin precio o sin nota no quedan párrafos vacíos
- [x] Los destellos no tapan el título ni el botón en ningún ancho
