# Página: Testimonios

- **Resumen:** Archivo de testimonios de mamás que trabajaron con Bárbara (Balli), con foto, valoración, categoría y edad del bebé. El visitante filtra por categoría (Soluciones de Sueño o Mundo Angelitozzz), lee historias reales y termina en el llamado a encontrar su solución de sueño.
- **Fuente:** `figma:testimonios`
- **Plantilla:** `archive-testimonio.php`
- **Tipo:** listado
- **Contenido:** testimonio
- **Secciones (en orden):** seccion-testimonios, cta-final
- **Skills usadas:** ninguna en el maquetado. Después: wp-acf (tipo de contenido `testimonio`, taxonomía `categoria_testimonio`, página de opciones del archivo, campos de la página y campos de cada testimonio), wp-query (la lista, el filtro por categoría y la paginación), wp-helper (fotos con `angelitozzz_image()`; ya instalado)
- **Grupo ACF:** Testimonios
- **Ubicación:** página de opciones: archivo de testimonio
- **Padre:** ninguno
- **Notas de página:**
    - incluye las skills necesarias.
    - Esta pagina no deberia de tener internas.
    - `h1`: "Testimonios", en la cabecera de seccion-testimonios (`testimonios_seccion_testimonios_titulo`). El nombre de cada tarjeta es `h2` (con estilo `--text-h4`) y el título del CTA final también `h2`.
    - Título SEO: "Testimonios | Angelitozzz". El diseño no trae meta descripción: se propone usar la bajada ("Mamás que volvieron a dormir y a disfrutar su maternidad.").
    - La nota "incluye las skills necesarias" se aplica así: el maquetado no usa skills de componente (no hay formulario, modal ni acordeón; el filtro es un `<form method="get">` de listado, nunca `wp-form`); después wp-acf registra el tipo de contenido, la taxonomía y los campos, wp-query conecta lista, filtro y paginación, y las fotos pasan por el helper de imágenes de wp-helper.
    - Los testimonios de esta página son el tipo de contenido `testimonio` (`archive-testimonio.php`, URL `/testimonios/`). Son independientes de los sliders de Inicio y Mundo Angelitozzz, que tienen su propio repetidor (`_shared.md`, "Contenido dinámico").
    - Propuesta de registro (lo hace wp-acf): `testimonio` con `has_archive` = `testimonios` y sin ficha individual: cada testimonio no tiene URL propia ni redirección, no es un enlace (solo mostrario en `/testimonios/`); `categoria_testimonio` no jerárquica, sin archivo propio (`taxonomy-categoria_testimonio.php` no se crea): el filtro es `?categoria=<slug>` sobre el mismo archivo.
    - La lista es real (wp-query): `archive-testimonio.php` recorre la consulta principal del archivo y llama a `testimonio-card` con `post_id`; el filtro `?categoria=<slug>` y la paginación salen de `inc/query/testimonio.php`.
    - Los textos de la página (antetítulo, título, bajada, CTA final) viven en la página de opciones "Archivo de testimonios" (submenú de Testimonios en el admin), porque un archivo no tiene pantalla de edición.
- **Al filtrar:** sin recargar
- **Paginación:** números
- **Por página:** 5
- **Filtros en móvil:** igual
- **Estado:** aplicado

`Al filtrar`: al elegir una pastilla la lista y la paginación cambian sin recargar (wp-query intercepta el formulario y actualiza la URL con `?categoria=<slug>`); sin JS el formulario se envía con su botón. `Paginación`: números con flechas anterior/siguiente; solo aparece al superar el límite por página. `Por página`: 5, las tarjetas que muestra el diseño. `Filtros en móvil`: las 3 pastillas se quedan visibles y saltan de línea, sin panel.

**Filtros**

| Filtro      | Tipo      | Fuente                 | Varios | Conteo  | Vista                                                                          |
| ----------- | --------- | ---------------------- | ------ | ------- | ------------------------------------------------------------------------------ |
| `categoria` | taxonomía | `categoria_testimonio` | no     | ninguno | chips/pastillas (radios con aspecto de pastilla; "Todos" primero = sin filtro) |

**Campos de cada testimonio:** están en su propio spec, [testimonio.md](testimonio.md) (grupo ACF "Testimonio", ubicación `tipo de contenido = testimonio`).

## Sección: seccion-testimonios

- **Origen:** `figma:testimonios` nodo 3749:4214 (`specs/figma/lectura/testimonios/seccion-testimonios.ref.txt`)
- **Destino:** `template-parts/pages/testimonios/seccion-testimonios.php`, `template-parts/components/testimonio-card.php`
- **Grupo:** `testimonios_seccion_testimonios`
- **Skill:** ninguna
- **Comportamiento:** Fondo `--color-fondo`, `py-26` (104), contenido centrado en `--container-sitio`, columna con `gap-6` (24). Cabecera centrada: antetítulo `--text-eyebrow` en `--color-marca-suave`; `h1` "Testimonios" con `--text-h1` en `--color-marca`; bajada `--text-cuerpo-l` en `--color-tinta`. Filtros: un `<form method="get" action="<URL del archivo de testimonios>" data-query="filtros">` con un `<fieldset>` y `<legend class="sr-only">` "Filtrar testimonios por categoría"; cada pastilla es un `<label>` con un `<input type="radio" name="categoria">` oculto visualmente (`sr-only peer`): "Todos" (texto fijo de la plantilla, `value=""`, marcada cuando no hay `?categoria`), y una por término de `categoria_testimonio` con su slug como valor (wp-query las toma de `categoria_testimonio`: Soluciones de Sueño y Mundo Angelitozzz). Pastillas en fila centrada con `gap-3` (12): `--text-rotulo-s`, `rounded-full`, `px-4.5 py-2`; la marcada con fondo `--color-marca` y texto `--color-fondo`; las demás fondo `--color-blanco`, borde 1 px `--color-neutro` y texto `--color-marca`; anillo de foco visible (`peer-focus-visible`). El formulario lleva un `<button type="submit">` "Filtrar" `sr-only` que se ve al recibir foco (`focus:not-sr-only`): sin JS es el que envía; con JS wp-query envía al cambiar la pastilla. Lista: `<div data-query="resultados">` con `mt-4` (24 + 16 = 40 px bajo los filtros, como el espaciador del diseño), columna `gap-6` (24), ancho completo del contenedor; cada tarjeta es `template-parts/components/testimonio-card.php` con `foto`, `nombre`, `estrellas`, `categoria`, `edad` y `texto`. La lista es el loop de la consulta principal del archivo (wp-query, 5 por página): cada tarjeta recibe `post_id` y lee título, contenido, imagen destacada, categoría y la ficha ACF del testimonio. Tarjeta (`<article>`): fondo `--color-blanco`, `--radius-tarjeta`, `p-8`, `gap-8`, en fila; foto 160 × 160 (`size-40`, `aspect-square`, `object-cover`, `--radius-media`) a la izquierda con `angelitozzz_image()` y `'alt_fallback'` = nombre, `loading="lazy"`; a la derecha (`gap-2.5`): fila con el `h2` del nombre (`--text-h4`, `--color-marca`) y las estrellas (`gap-3` entre ambos); fila (`gap-2`) con la categoría (componente `etiqueta`, estilo `tarjeta`, sin ícono) y la edad (pastilla `--color-fondo`, texto `--color-marca`, `--text-rotulo-s`, `rounded-full`, `px-3.5 py-1.5`; no se imprime si `edad` está vacía); y el testimonio (`--text-cuerpo`, `--color-tinta`), impreso con `esc_html` (texto plano: wp-query le pasa el contenido sin etiquetas; "<3" se escapa y se ve tal cual). Estrellas: 5 íconos SVG 18 × 18 (`size-4.5`, `gap-0.5`, `testimonio-estrella.svg` con `fill="currentColor"`, `aria-hidden`): las primeras `estrellas` en `--color-estrella` y el resto en `--color-neutro` (propuesta: el diseño solo muestra 5 de 5); el grupo lleva `role="img"` y `aria-label` "5 de 5 estrellas". Paginación: `<nav aria-label="Paginación" data-query="paginacion">` centrada bajo la lista (`gap-6` de la columna), lista de enlaces circulares 40 × 40 (flechas incluidas) (`size-10`, `rounded-full`, `--text-rotulo`, `gap-2`): la página actual con fondo `--color-marca`, texto `--color-fondo` y `aria-current="page"`; las demás fondo `--color-blanco`, borde 1 px `--color-neutro`, texto `--color-marca`. Con flechas anterior/siguiente a los lados (círculos 40 × 40 como los números, con texto accesible; la de un extremo queda inactiva, `aria-disabled`, sin enlace) — decisión del usuario, el diseño no las trae. La paginación solo se imprime si hay más testimonios que el límite por página (5): con 5 testimonios no aparece; con más, wp-query la imprime (`paginate_links`, flechas `←` y `→` solo cuando hay página anterior o siguiente) con los enlaces `/testimonios/page/N/` conservando `?categoria`. Con una sola página no se imprime; con muchas, wp-query muestra puntos suspensivos (`1 2 3 … 8`). Estado vacío (el diseño no lo trae, propuesta): si una categoría no tiene testimonios, dentro de `data-query="resultados"` un párrafo centrado `--text-cuerpo` en `--color-marca-suave`: "Aún no hay testimonios en esta categoría.". Antetítulo y bajada son opcionales: si el campo está vacío la plantilla no imprime esa línea. Móvil (sin frame, propuesta): `px-4 py-16`; el `h1` baja a `--text-h2` (manteniendo peso 600); bajada en varias líneas (sin `whitespace-nowrap`); las pastillas saltan de línea centradas (`flex-wrap justify-center`); la tarjeta pasa a columna con `p-6` y `gap-5`: foto 120 × 120 (`size-30`) arriba a la izquierda, debajo nombre y estrellas (saltan de línea si no caben), pastillas y texto; la paginación queda igual. Sin valores sueltos: el dorado de las estrellas es el token `--color-estrella`.
- **Indicaciones del diseño:**
    - cada testimonio es independiente y diferente de las otras secciones de testimonios, en este apartado se definira testimonios con categorias.
- **Estado:** aplicado
- **ACF:** conectado
- **Query:** conectado

La indicación se aplica así: las tarjetas son elementos del tipo de contenido `testimonio` con la taxonomía `categoria_testimonio` (Soluciones de Sueño, Mundo Angelitozzz), no los repetidores de los sliders de Inicio y Mundo Angelitozzz, y el filtro de pastillas filtra por esa categoría.

**Campos ACF**

| Campo                                        | Tipo     | Descripción                                                                    | Valor actual                                              | Requerido |
| -------------------------------------------- | -------- | ------------------------------------------------------------------------------ | --------------------------------------------------------- | --------- |
| `testimonios_seccion_testimonios_antetitulo` | text     | Texto chico sobre el título de la página.                                      | Historias reales                                          | no        |
| `testimonios_seccion_testimonios_titulo`     | text     | Título principal de la página (h1).                                            | Testimonios                                               | sí        |
| `testimonios_seccion_testimonios_bajada`     | textarea | Frase bajo el título. También se propone como meta descripción.                | Mamás que volvieron a dormir y a disfrutar su maternidad. | no        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Hay un solo `h1` ("Testimonios") y el nombre de cada tarjeta es `h2`
- [x] Las pastillas son radios `name="categoria"` con `<label>` dentro de un `<form method="get" data-query="filtros">` con `<legend>`; se recorren y eligen con teclado y no se usa `wp-form` ni `WP_Query`
- [x] La lista lleva `data-query="resultados"` y cada tarjeta es `template-parts/components/testimonio-card.php` llamada con `$args` (5 en el maquetado)
- [x] La paginación es `<nav aria-label="Paginación" data-query="paginacion">` con enlaces reales, flechas anterior/siguiente y `aria-current="page"` en la actual; solo se imprime si hay más testimonios que el límite por página
- [x] Las estrellas usan `--color-estrella` y el lector de pantalla anuncia "5 de 5 estrellas", no cinco imágenes
- [x] Las fotos pasan por `angelitozzz_image()` con `alt_fallback` = nombre, son cuadradas con `object-cover` y no se deforman
- [x] La pastilla de edad no se imprime si `edad` está vacía
- [x] En móvil (375 px) las pastillas saltan de línea sin desbordar y la tarjeta queda en columna sin scroll horizontal

## Sección: cta-final

- **Origen:** `figma:testimonios` nodo 3749:4344, instancia del componente Figma "CTA final" 3713:17003 (`specs/figma/lectura/testimonios/cta-final.ref.txt`)
- **Destino:** `archive-testimonio.php`, `template-parts/components/cta-final.php`
- **Grupo:** `testimonios_cta_final`
- **Skill:** ninguna
- **Comportamiento:** usa el componente compartido `cta-final` (`_shared.md`), llamado desde `archive-testimonio.php` con `get_template_part('template-parts/components/cta-final', null, $args)` y los argumentos `titulo`, `descripcion` (aquí sí la usa: "Encuentra la solución de sueño ideal para tu bebé.") y `boton`. Fondo `--color-fondo-rosa`, título `h2` centrado (`--text-h2`, `--color-marca`), descripción `--text-cuerpo-l` en `--color-tinta`, botón `boton` variante `frambuesa` con flecha, y los destellos y trazos blancos decorativos del componente (`cta-final-*.svg`, `aria-hidden`; el destello grande sobresale por debajo y se recorta con `overflow-hidden`). El botón "Encuentra tu solución" lleva a la página shop/tienda/cursos, que aún no existe: queda con `#` (pendiente, como en Inicio). Descripción opcional: si queda vacía el componente no la imprime. Móvil: el del componente compartido (`px-4`, título en `--text-h3`, decoraciones reducidas u ocultas). Sin valores sueltos propios.
- **Estado:** aplicado
- **ACF:** conectado

**Campos ACF**

| Campo                               | Tipo     | Descripción                                                                  | Valor actual                                       | Requerido |
| ----------------------------------- | -------- | ---------------------------------------------------------------------------- | -------------------------------------------------- | --------- |
| `testimonios_cta_final_titulo`      | textarea | Título del bloque de cierre de la página.                                    | Tu historia puede ser la próxima.                  | sí        |
| `testimonios_cta_final_descripcion` | textarea | Texto opcional bajo el título. Si se deja vacío no se muestra.               | Encuentra la solución de sueño ideal para tu bebé. | no        |
| `testimonios_cta_final_boton`       | link     | Botón del bloque de cierre: texto y destino (la tienda de cursos, pendiente) | Encuentra tu solución                              | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] La sección es una llamada al componente `cta-final` con `$args`, sin markup propio en la plantilla
- [x] La descripción se muestra y el botón queda con `#` hasta que exista la tienda
