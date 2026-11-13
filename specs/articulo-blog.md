# Página: Artículo del blog

- **Resumen:** Muestra un artículo del blog completo (título, autora, imagen destacada y contenido del editor) y lleva a la lectora a conocer a Bárbara, a otros artículos de la misma categoría o a las soluciones de sueño.
- **Fuente:** `figma:articulo-blog`
- **Plantilla:** `single.php`
- **Tipo:** detalle
- **Contenido:** post
- **Secciones (en orden):** cabecera-del-articulo, cuerpo-del-articulo, escrito-por, te-puede-interesar, cta-final
- **Skills usadas:** wp-helper (enlaces de los botones), wp-acf (página de opciones con los textos comunes), wp-query (te-puede-interesar)
- **Grupo ACF:** Artículo del blog
- **Ubicación:** página de opciones: Artículo del blog
- **Padre:** ninguno
- **Notas de página:**
    - `h1`: el título de la entrada (`the_title()`), en la cabecera. Los títulos del contenido del editor empiezan en `h2`.
    - Título SEO: "<título de la entrada> | Angelitozzz" (lo arma WordPress o el plugin de SEO); meta descripción: el extracto de la entrada. El diseño no trae ninguna de las dos.
    - Todo lo que cambia por artículo es nativo de WordPress: título, categoría, fecha, autor (nombre, avatar e Información biográfica), imagen destacada, contenido del editor y extracto. El tiempo de lectura se calcula del contenido. Por eso la entrada no lleva campos ACF propios.
    - Los textos que son iguales en todos los artículos ("Escrito por", "Conoce a Bárbara", "Ver soluciones de sueño", "También te puede interesar", "Ver todo →" y los del CTA final) son iguales en todos los artículos: "Escrito por", los dos botones, "También te puede interesar" y "Ver todo →" quedan fijos en la plantilla; los del CTA final van en la página de opciones "Artículo del blog" (submenú de Ajustes del sitio) y se leen con `get_field('articulo_blog_cta_final', 'option')`.
    - Fondo crema (`--color-fondo`) en cabecera, cuerpo y escrito-por; blanco en te-puede-interesar.
    - Sin frame móvil en Figma: el móvil propuesto está en el `Comportamiento` de cada sección.
    - `single.php` llama una vez a `get_header()` y `get_footer()` y pone las cinco secciones dentro de `<main id="main-content">` y de un `<article>` con `post_class()` (las tres primeras) dentro del loop de la entrada.
- **Estado:** aplicado

## Sección: cabecera-del-articulo

- **Origen:** `specs/figma/lectura/articulo-blog/cabecera-del-articulo.ref.txt` (nodo 3751:4579)
- **Destino:** `template-parts/pages/articulo-blog/cabecera-del-articulo.php`, `inc/helpers/entradas.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** todo sale de la entrada, sin campos. Migas de pan en `<nav aria-label="Migas de pan">`: "Blog" enlaza a la página de entradas (`page_for_posts`) y la categoría a su archivo (`get_category_link()`), separadas por "/" con `aria-hidden`; texto Caption `--color-tinta-suave`. Debajo, la categoría principal (la primera de la entrada) con el componente `etiqueta` (`estilo: tarjeta`). Título `h1` con `--text-display` `--color-marca`, ancho máximo 1100 px. Fila de autora (el avatar sale de Gravatar o de un plugin de avatar local, ver escrito-por): avatar de WordPress (`get_avatar()`, 44 px, circular, `alt=""` porque el nombre va al lado), "Por <nombre del autor>" (Label `--color-marca`) y "<fecha> · <N> min de lectura" (Caption `--color-tinta-suave`); fecha con `get_the_date('j \d\e F \d\e Y')` en español ("1 de marzo de 2024"). El tiempo de lectura lo calcula el helper propio `angelitozzz_tiempo_lectura()` (palabras del contenido sin etiquetas ÷ 200, redondeado hacia arriba, mínimo 1; devuelve los minutos y la plantilla arma "5 min de lectura"); el mismo helper alimenta el argumento `lectura` de `post-card` en el blog y en te-puede-interesar. Imagen grande: la imagen destacada (`the_post_thumbnail('full')`, `loading="eager"` y `fetchpriority="high"`, alt del adjunto o, si está vacío, el título de la entrada), relación 17:8 (1360 × 640) con `object-cover` y `--radius-tarjeta`; recomendación para la imagen destacada: 2720 × 1280 px (17:8), mínimo 1360 px de ancho. Sin imagen destacada, el bloque de la imagen no se imprime. Contenedor de 1360 px (valor suelto, una sola página) con `pt-18 pb-12` y `gap` 18 px entre filas. Móvil (propuesto): margen lateral de 16 px, título a 36 px (interlineado 1.1), la fila de autora se mantiene en una fila (la línea de fecha puede partirse), imagen en 4:3 con `--radius-media`, `pt-10 pb-8`.
- **Indicaciones del diseño:**
    - La imagen que aparece de quien lo publico (Por Bárbara...) se usara lo que es del avatar del propio wordpress.
    - sobre los minutos de lecturas que sea automatico, y no un campo a parte.
    - Que la imagen grande venga de la imagen destacada.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El `h1` es el título de la entrada y es el único de la página
- [x] El avatar es el de WordPress (`get_avatar()`), sin imagen fija ni campo ACF
- [x] El tiempo de lectura cambia al alargar o acortar el contenido de la entrada (no hay campo para escribirlo)
- [x] La imagen grande es la imagen destacada, carga con `eager` y no se imprime nada si la entrada no tiene una
- [x] "Blog" y la categoría de las migas de pan llevan a la página del blog y al archivo de la categoría

## Sección: cuerpo-del-articulo

- **Origen:** `specs/figma/lectura/articulo-blog/cuerpo-del-articulo.ref.txt` (nodo 3751:4590)
- **Destino:** `template-parts/pages/articulo-blog/cuerpo-del-articulo.php`, `src/css/articulo-contenido.css`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** imprime `the_content()` dentro de `<div class="articulo-contenido">` (columna de 1040 px, valor suelto de una sola página, `pt-6 pb-18`). El texto del diseño es contenido de ejemplo de la entrada, no texto fijo de la plantilla. Los estilos por defecto de WordPress se sobrescriben con los del diseño en `src/css/articulo-contenido.css`: todo cuelga de la clase del contenedor `.articulo-contenido` (no afecta al resto del sitio) y va sobre el HTML normal del contenido, así que no depende del editor (el sitio usa el plugin Classic Editor) ni de clases que ponga el editor; los editores no se modifican. El tema no declara `wp-block-styles`. Estilos: separación vertical de 24 px entre bloques y 16 px entre párrafos seguidos; párrafo `--text-cuerpo-l` `--color-tinta`; el primer párrafo es la entradilla, automática (`--text-cuerpo-xl` `--color-marca`); `h2` con el aspecto de `--text-h3` `--color-marca` (y `h3` con `--text-h4`); lista (`ul`) `--text-cuerpo-l` con viñeta "•" y 10 px entre ítems (`ol` con números, mismo ritmo); imagen a todo el ancho de la columna con `--radius-media`, alto automático (respeta la proporción que sube la autora), y su pie centrado en Caption `--color-tinta-suave`; enlaces en `--color-acento` subrayados; toda cita (`blockquote`) es la "Cita destacada": fondo `--color-fondo-rosa`, borde izquierdo de 6 px `--color-acento`, `--radius-media`, relleno 28 × 32 px, texto Barlow Condensed Medium Italic 26 px / 1.25 `--color-marca` (valor suelto: no hay token de 26 itálica medium), y su firma (el `cite` o, en el editor clásico, el último párrafo cuando la cita tiene más de uno) en Label S `--color-tinta-suave` con "— " antepuesto una sola vez. El consejo no es contenido del editor: después de `the_content()`, dentro de la misma columna, la plantilla imprime la caja "Consejo" si la entrada tiene texto en `entrada_consejo_texto` (ver `entrada.md`): fondo `--color-neutro`, `--radius-media`, relleno 24 × 28 px, ícono bombilla de 32 px fijo en la plantilla (`assets/img/articulo-blog/consejo-icono-bombilla.svg`, decorativo) a la izquierda con 16 px de separación, la etiqueta "Consejo de <primer nombre del autor de la entrada>" (el "Primer nombre" de su perfil de usuario, o su nombre público si está vacío; Label `--color-marca`) y el texto de la entrada en `--text-cuerpo`. Móvil (propuesto): columna a todo el ancho con margen de 16 px; párrafos y listas a 18 px, entradilla a 20 px, `h2` a 26 px, cita a 22 px con relleno 20 × 20 px, consejo con el ícono arriba del texto.
- **Indicaciones del diseño:**
    - Usar la entrada de la interna por defecto de wordpres donde se puede ingresar texto imagen, y etc de cosas para el articulo.
    - ya que wordpress tiene estilos ya definidos por defecto, sobre escribe eso y usa los estilos que se tiene.
    - Sobreescribir los estilos con la clase del contenedor del contenido, sin modificar los editores.
    - El consejo con campos ACF y el ícono fijo, al final del cuerpo; su etiqueta dice "Consejo de" y el nombre del autor, tomado de Usuarios.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El cuerpo es `the_content()`: la plantilla no tiene texto fijo del artículo
- [x] Una entrada con párrafos, `h2`, lista, imagen con pie y cita se ve igual que el diseño con el editor que se use, sin los estilos por defecto de WordPress (márgenes, bordes, tipografía)
- [x] Los estilos solo afectan a `.articulo-contenido`: el resto del sitio no cambia y los editores no se modifican
- [x] El consejo sale al final del cuerpo con la bombilla fija cuando la entrada tiene texto, y no sale si el campo está vacío
- [x] Las imágenes del contenido no se deforman ni se salen de la columna en móvil

## Sección: escrito-por

- **Origen:** `specs/figma/lectura/articulo-blog/escrito-por.ref.txt` (nodo 4038:22779)
- **Destino:** `template-parts/pages/articulo-blog/escrito-por.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** `<aside aria-label="Sobre la autora">` sobre fondo `--color-fondo` (`py-5`), con una tarjeta blanca de 1040 px (`--radius-tarjeta`, relleno 32 px, 28 px entre avatar y texto, sin alto fijo). Avatar: el de WordPress (`get_avatar()` del autor de la entrada, 120 px, circular, `alt=""`). La foto sale de Gravatar o de un plugin de avatar local (los dos se enganchan a `get_avatar()`): la imagen tiene que verse, así que el autor debe tener una configurada. "Escrito por" fijo (Caption `--color-tinta-suave`); nombre del autor (`get_the_author()`) como `h2` con el aspecto de `--text-h4` `--color-marca`; descripción: la "Información biográfica" del usuario (`get_the_author_meta('description')`, `--text-cuerpo-s`), que se oculta si está vacía. Dos botones con el componente `boton` (12 px entre ellos): "Conoce a Bárbara" (`variante: secundario`, `flecha: false`) a la página Conoce a Bárbara (`/conoce-a-barbara/`, enlace a la página por su slug, no URL escrita a mano) y "Ver soluciones de sueño" (`variante: frambuesa`, con flecha) a la tienda de WooCommerce (`wc_get_page_permalink('shop')`); mientras WooCommerce no esté activo el destino es `#` y el componente `boton` lo muestra desactivado. "Escrito por" y los textos de los dos botones son fijos en la plantilla (iguales en todos los artículos); el nombre, el avatar y la biografía salen del perfil del autor. Móvil (propuesto): la tarjeta pasa a una columna con relleno 24 px, avatar de 88 px arriba a la izquierda, y los botones uno debajo del otro a todo el ancho.
- **Indicaciones del diseño:**
    - Usar la misma imagen que usar el avatar de wordpress.
    - Sobre la descripcion usar Información biográfica que viene por defecto en wordpress.
    - Conce a barbara que lleve a la pagina conoce a barbara
    - ver soluciones de sueño que lleve a shop (como se menciono aun quedara pendiente de implementar)
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] El avatar y la biografía cambian al editar el perfil del usuario en WordPress; sin biografía, el párrafo no se imprime
- [x] "Conoce a Bárbara" lleva a `/conoce-a-barbara/`; "Ver soluciones de sueño" queda en `#` (pendiente)
- [x] La tarjeta no tiene alto fijo: una biografía larga la hace crecer sin cortar el texto

## Sección: te-puede-interesar

- **Origen:** `specs/figma/lectura/articulo-blog/te-puede-interesar.ref.txt` (nodo 3751:4628)
- **Destino:** `template-parts/pages/articulo-blog/te-puede-interesar.php`, `template-parts/components/post-card.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** fondo `--color-blanco`, `py-22`, contenedor `--container-sitio` (1110 px). Encabezado en una fila: "También te puede interesar" como `h2` con el aspecto de `--text-h3` `--color-marca`, y a la derecha "Ver todo →" (`--text-cuerpo-s` `--color-marca`) que lleva al archivo de la categoría de la entrada (`get_category_link()`; si no tiene categoría, a la página del blog). Debajo, 3 tarjetas en fila (`gap` 30 px, 28 px bajo el encabezado) con el componente `post-card`, cada una con su título como `h3`. Contenido (wp-query): las 3 entradas más recientes de la misma categoría, sin la entrada actual; si hay menos de 3, se completan con las entradas más recientes del blog en general (tampoco la actual), y si no hay ninguna otra entrada la sección no se imprime. Primero `angelitozzz_query_related()` (misma categoría, sin la actual; después `wp_reset_postdata()`) y, si faltan, `get_posts` con las más recientes del blog sin la actual ni las ya elegidas; es una lista secundaria, no reemplaza la consulta principal. Cada tarjeta recibe `angelitozzz_post_card_args()` (`inc/helpers/entradas.php`). "También te puede interesar" y "Ver todo →" son textos fijos en la plantilla. Móvil (propuesto): una tarjeta por fila (dos en tablet), "Ver todo →" baja debajo del título, `py-14`.
- **Indicaciones del diseño:**
    - Que muestre los 3 articulos mas recientes de la categoria relacionada
    - Si no hay mas articulos relacionados a la categoria, mostrar los articulos en general.
- **Estado:** aplicado
- **ACF:** conectado
- **Query:** conectado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Las 3 tarjetas usan el componente `post-card` con `$args`; la consulta es secundaria y no reemplaza la principal
- [x] "Ver todo →" lleva al archivo de la categoría de la entrada
- [x] Al conectar la consulta (wp-query): misma categoría, sin la entrada actual, completando con las más recientes del blog hasta 3

## Sección: cta-final

- **Origen:** `specs/figma/lectura/articulo-blog/cta-final.ref.txt` (nodo 3751:4670, instancia del componente "CTA final")
- **Destino:** `single.php`, `template-parts/components/cta-final.php`
- **Grupo:** `articulo_blog_cta_final`
- **Skill:** wp-helper
- **Comportamiento:** `single.php` llama al componente compartido `cta-final` con `titulo` "¿Quieres un plan para tu bebé?", `descripcion` "Encuentra la solución de sueño ideal según su edad." y `boton` "Encuentra tu solución" con destino pendiente `#` (nota de Inicio: el enlace de los CTA finales de las demás páginas queda pendiente). Los textos y el botón salen de la página de opciones (`articulo_blog_cta_final`), iguales en todos los artículos. Fondo, destellos y móvil son los del componente.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |
| `articulo_blog_cta_final_titulo` | textarea | Título del bloque de cierre de cada artículo. | ¿Quieres un plan para tu bebé? | sí |
| `articulo_blog_cta_final_descripcion` | textarea | Texto opcional bajo el título. Si se deja vacío no se muestra. | Encuentra la solución de sueño ideal según su edad. | no |
| `articulo_blog_cta_final_boton` | link | Botón del bloque de cierre: texto y destino (la tienda de cursos, pendiente). | Encuentra tu solución | sí |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Usa `template-parts/components/cta-final.php` con `$args`, sin copiar su HTML en la página
- [x] El botón queda en `#` y está anotado como pendiente
