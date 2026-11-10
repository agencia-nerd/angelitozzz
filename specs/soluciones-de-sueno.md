# Página: Soluciones de sueño

- **Resumen:** Página de la tienda (WooCommerce) con todos los cursos y servicios de sueño de Angelitozzz. El visitante compara las opciones según la edad de su bebé y entra a la ficha del curso que le interesa, o escribe por WhatsApp si no sabe por dónde empezar.
- **Fuente:** `figma:soluciones-de-sueno`
- **Plantilla:** `woocommerce/archive-product.php`
- **Tipo:** listado
- **Contenido:** producto
- **Secciones (en orden):** shop, cta-final
- **Skills usadas:** wp-helper (enlaces e imágenes). Después: wp-acf (campos de la página), wp-woocommerce (hooks de Woo en los `// woo:`), wp-query (el bucle real y el "Ver más")
- **Grupo ACF:** Soluciones de sueño
- **Ubicación:** página "Soluciones de sueño" (página de la tienda en WooCommerce → Ajustes → Productos)
- **Padre:** ninguno
- **Notas de página:**
    - Aun no se realizaran las internas de cada producto. eso quedara pendiente
    - `h1`: el título de la sección shop ("Soluciones de sueño", `soluciones_de_sueno_shop_titulo`). El título del CTA final es `h2`; los nombres de las tarjetas son `h3` (dentro de un `h2` solo para lectores de pantalla, ver la sección shop).
    - Título SEO "Soluciones de sueño | Angelitozzz" (lo arma WordPress con el título de la página de la tienda). El diseño no trae meta descripción: se propone usar la bajada del shop.
    - Fondo de la página: color `--color-fondo` (crema) con la imagen de nubes `pagina-fondo-nubes.webp` (`specs/figma/lectura/soluciones-de-sueno/assets/`, la misma de Inicio) fija en el tema, no editable, y se queda quieta al hacer scroll (`background-attachment: fixed`; en móvil, donde iOS no lo respeta, una capa `position: fixed` detrás del contenido). Se ve detrás del shop; el cta-final la tapa con su fondo rosa. Se aplica solo en esta página (clase en `<main>` de `woocommerce/archive-product.php`), no en todo el sitio. Móvil: `background-size` reducido para que las nubes no tapen las tarjetas.
    - Fichas de producto (`single-product.php` / `content-single-product.php`): pendientes, fuera de esta etapa. Los botones de las tarjetas quedan con `#` hasta entonces.
    - Contenido de Woo: los productos (nombre, imagen, descripción corta, enlace) son nativos de WooCommerce y no son campos ACF. La etiqueta de esquina, el rango de edad y la lista de beneficios de cada tarjeta son datos del producto que se definen con la ficha del curso (pendiente), no campos de esta página.
    - Sin filtros, orden ni contador de resultados (nota del usuario en shop y `Filtros de tienda: no` en `_shared.md`): los hooks de Woo que los imprimen (`woocommerce_result_count`, `woocommerce_catalog_ordering`) se quitan en el paso wp-woocommerce.
    - Los archivos de categoría de producto (`product_cat`) reutilizan esta plantilla y no tienen spec propio; el diseño no los muestra.
    - Cintillo, header y footer salen de `header.php` y `footer.php` (spec `opciones.md`); la plantilla no los repite.
    - Sin frame móvil en Figma: el comportamiento en móvil de cada sección es una propuesta en su `Comportamiento`.
- **Al filtrar:** sin recargar
- **Paginación:** cargar más
- **Por página:** 12
- **Filtros en móvil:** igual
- **Estado:** aplicado

**Filtros**

| Filtro | Tipo | Fuente | Varios | Conteo | Vista |
| ------ | ---- | ------ | ------ | ------ | ----- |

Sin filtros: nota del usuario en la sección shop ("No llevara filtros, ni nada.") y `Filtros de tienda: no` en `_shared.md`. La tabla queda vacía a propósito; `Al filtrar` y `Filtros en móvil` no se usan.

## Sección: shop

- **Origen:** `specs/figma/lectura/soluciones-de-sueno/shop.ref.txt` (nodo 4011:11093)
- **Destino:** `woocommerce/archive-product.php`, `template-parts/pages/soluciones-de-sueno/shop.php`, `woocommerce/content-product.php`, `template-parts/components/producto-card.php`
- **Grupo:** `soluciones_de_sueno_shop`
- **Skill:** wp-helper
- **Comportamiento:** por ahora solo diseño: `archive-product.php` llama a `shop.php`, que imprime el encabezado y la grilla con las 4 tarjetas del diseño como arrays fijos; cada tarjeta pasa por `woocommerce/content-product.php` (con `$args`), que llama al componente `producto-card`. Conectar WooCommerce después es cambiar los arrays por el bucle de productos. Los hooks de Woo quedan fuera del marcado, marcados con comentarios PHP en su lugar: en `archive-product.php` `// woo: woocommerce_before_main_content`, `// woo: woocommerce_after_main_content`; en `shop.php` `// woo: woocommerce_before_shop_loop` (sin contador ni orden), `// woo: woocommerce_product_loop_start`, `// woo: bucle de productos (wc_get_template_part content product)`, `// woo: woocommerce_product_loop_end`, `// woo: woocommerce_after_shop_loop` (el "Ver más") y `// woo: woocommerce_no_products_found`; en `content-product.php` `// woo: woocommerce_before_shop_loop_item` y `// woo: woocommerce_after_shop_loop_item`. Sin consultas ni lógica de Woo. **Encabezado:** título `h1` con `--text-h1` en `--color-marca` y bajada `--text-body-l` en `--color-tinta`, centrados en una columna de 451 px, separados 24 px. **Grilla:** `data-query="resultados"`, 3 columnas de 346 px con separación de 32 px entre columnas y 80 px entre filas, ancho total 1103 px (dentro de `--container-sitio`); las tarjetas de una fila tienen la misma altura y el botón queda abajo (`mt-auto`, el "Empuje" del diseño). Antes de la grilla, un `h2` solo para lectores de pantalla (`sr-only`, "Todos los cursos") para que los nombres de las tarjetas (`h3` en `producto-card`, igual que en Inicio) no salten de nivel. **Tarjeta:** componente `producto-card` con `imagen`, `etiqueta`, `etiqueta_icono`, `titulo`, `edad`, `descripcion`, `beneficios` y `boton` (aquí sí lleva `beneficios`, a diferencia de home/como-ayudarte): imagen 346 × 374 con `--radius-tarjeta` y `object-cover`; etiqueta de esquina con el componente `etiqueta` estilo `esquina` (fondo `--color-etiqueta`, `--radius-etiqueta` abajo a la derecha, `--text-label-s` blanco); nombre y edad centrados en `--text-body`; descripción `--text-body-s` centrada (314 px); beneficios como `<ul>` en `--text-body-s` alineada a la izquierda (314 px), con la viñeta "•" por CSS, no escrita en el texto; botón `boton` variante `frambuesa` con flecha. Los íconos de etiqueta: Angelita Pro `destellos`, A Dormir Por Fin `estrella`, las otras dos `ninguno`. Nombre, imagen y descripción corta son nativos de Woo (sin ACF); etiqueta, edad y beneficios son datos del producto pendientes de definir con la ficha del curso (no son campos de esta página). La imagen de la primera tarjeta (Angelita Pro) va vacía a propósito: el componente muestra la imagen por defecto "not found" (`ayudarte-imagen-not-found.webp`, el mismo archivo que `shop-angelita-pro-placeholder.png` del diseño; no se duplica). Las otras tres son imágenes de muestra del diseño (`shop-a-dormir-por-fin.webp`, `shop-recien-nacidos-go.webp`, `shop-horarios-ideales.webp`) guardadas como assets del tema hasta que vengan de Woo; `alt` = nombre del curso. Las tres primeras con `loading="eager"` (primera pantalla), la cuarta `lazy`. Cada botón es un enlace a la ficha de su curso: queda con `#` (fichas pendientes). **Ver más (nota del usuario, no dibujado en el diseño):** debajo de la grilla, centrado, a 80 px, un `<nav aria-label="Paginación" data-query="paginacion">` con un solo enlace real a la página siguiente (`/page/2/`, ahora `#`) dibujado con el componente `boton` variante `secundario`, texto "Ver más", sin flecha. Se imprime solo cuando hay más de 12 cursos (`Por página: 12`); con las 4 tarjetas de muestra no se ve. wp-query lo convierte en "cargar más" (agrega las tarjetas siguientes sin recargar). Sin estado vacío en el diseño: se deja el lugar del hook `woocommerce_no_products_found` (mensaje de Woo). Valores sueltos: padding de sección 88 px, separación encabezado-grilla 72 px, ancho del encabezado 451 px, ancho de la grilla 1103 px, columnas de 346 px, textos de 314 px, separaciones 80, 32, 24, 20 y 16 px, tarjeta de 756 px de alto en el diseño (no se fija: la altura la da el contenido). Móvil (propuesta): 2 columnas en tablet (`md`) y 1 columna en móvil con la tarjeta a todo el ancho (máximo 346 px, centrada); padding 56 px vertical y 16 px lateral; el título baja a `--text-h3` y el encabezado ocupa todo el ancho; separación entre filas 56 px.
- **Indicaciones del diseño:**
    - Esta pagina sera donde se muestren todos los cursos, por el momento sera diseño, luego se pasara con woocomerce.
    - No llevara filtros, ni nada.
    - Cuando sobre pase 12 cursos que muestre un boton de ver mas.
    - cada boton es un enlace a la interna de cada curso.
- **Estado:** aplicado

**Campos ACF**

| Campo                             | Tipo     | Descripción                                                                                                  | Valor actual                                                                              | Requerido |
| --------------------------------- | -------- | ------------------------------------------------------------------------------------------------------------ | ----------------------------------------------------------------------------------------- | --------- |
| `soluciones_de_sueno_shop_titulo` | text     | Título principal de la página de cursos. Se muestra centrado sobre las tarjetas. | Soluciones de sueño                                                                       | sí        |
| `soluciones_de_sueno_shop_bajada` | textarea | Texto bajo el título.                                                           | Encuentra la solución perfecta para ti — según la edad de tu bebé y lo que necesitas hoy. | no        |

Tarjetas del diseño (productos de Woo, no campos; textos completos en `shop.ref.txt`): Angelita Pro (sin imagen, edad "(4 a 24 meses)", etiqueta "Nuevo · Desde $97" con destellos, botón "Quiero conocer a Angelita"), A Dormir Por Fin ("(4 meses a 4 años)", "Asesoría con Bárbara · $399" con estrella, "Quiero mi asesoría personal"), Recién Nacidos Go ("(0 a 12 semanas)", "Curso Online · $97" sin ícono, "Ver curso"), Cursos de Horarios Ideales ("(4 meses a 3 años)", "Curso Online · Desde $37" sin ícono, "Ver cursos por edad").

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] Las 4 tarjetas salen de `woocommerce/content-product.php` llamando a `producto-card`, sin marcado propio de tarjeta
- [x] La tarjeta sin imagen muestra la imagen por defecto "not found" con la misma proporción que las demás
- [x] No hay filtros, orden ni contador de resultados en la página
- [x] Ningún hook ni función de WooCommerce en el marcado: cada lugar está marcado con un comentario `// woo:`
- [x] El "Ver más" es un `<nav aria-label="Paginación">` con un enlace real, usa `boton` variante `secundario` centrado y solo se imprime con más de 12 cursos
- [x] Los botones de las tarjetas quedan con `#` hasta que existan las fichas de los cursos
- [x] En móvil las tarjetas van en 1 columna y en tablet en 2, sin scroll horizontal

## Sección: cta-final

- **Origen:** `specs/figma/lectura/soluciones-de-sueno/cta-final.ref.txt` (nodo 4011:11140, instancia del componente Figma "CTA final" 3713:17003)
- **Destino:** `woocommerce/archive-product.php`, `template-parts/components/cta-final.php`
- **Grupo:** `soluciones_de_sueno_cta_final`
- **Skill:** wp-helper
- **Comportamiento:** usa el componente compartido `cta-final` (`_shared.md`), llamado desde la plantilla con `get_template_part('template-parts/components/cta-final', null, $args)` y los argumentos `titulo`, `descripcion` (visible en esta página) y `boton`. Fondo `--color-fondo-rosa`, título `h2` centrado (`--text-h2`, `--color-marca`), descripción `--text-body-l` en `--color-tinta`, botón `boton` variante `frambuesa` con flecha, y los destellos y trazos blancos decorativos del componente (`cta-final-*.svg`, el destello grande sobresale por debajo y se recorta con `overflow-hidden`). **Botón de WhatsApp (nota del usuario):** el número no es un campo de esta página: sale de `opciones_contacto_whatsapp` de Ajustes del sitio (`get_field(..., 'option')`). La plantilla arma el array link que recibe `boton`: `url` = `https://wa.me/<número>` y, si `opciones_contacto_whatsapp_mensaje` tiene texto, `?text=` con el mensaje codificado (`rawurlencode`); `title` = el texto de `soluciones_de_sueno_cta_final_boton_texto`; `target` = `_blank` (el helper de enlaces pone `rel` y el aviso `sr-only` de pestaña nueva). Si el número está vacío, el botón no se muestra. Valores sueltos: padding 104 px vertical y 405 px lateral (`--container-sitio`), separación de 20 px más un espacio de 8 px antes del botón. Móvil: el del componente compartido (título en `--text-h3`, `px-4`, decoraciones reducidas u ocultas).
- **Indicaciones del diseño:**
    - es ese apartado especifico, pide que se redireccione al whatsapp, o sea usar el campo de configuracion del sitio correspondiente.
- **Estado:** aplicado

**Campos ACF**

| Campo                                       | Tipo     | Descripción                                                                                                           | Valor actual                                                           | Requerido |
| ------------------------------------------- | -------- | --------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------- | --------- |
| `soluciones_de_sueno_cta_final_titulo`      | text     | Título del bloque de cierre de la página.                                                 | ¿No sabes por dónde empezar?                                           | sí        |
| `soluciones_de_sueno_cta_final_descripcion` | textarea | Texto opcional bajo el título. Si se deja vacío no se muestra.                           | Cuéntame la edad de tu bebé y te ayudo a encontrar la opción correcta. | no        |
| `soluciones_de_sueno_cta_final_boton_texto` | text     | Texto del botón que abre WhatsApp. El número y el mensaje se editan en Ajustes del sitio. | Escríbeme por WhatsApp                                                 | sí        |

**Criterios de aceptación**

- [x] Cumple los criterios comunes de `_shared.md`
- [x] La sección es una llamada al componente `cta-final` con `$args`, sin markup propio en la plantilla
- [x] El botón abre `https://wa.me/<opciones_contacto_whatsapp>` (con `?text=` si hay mensaje) en una pestaña nueva, pasando por el helper de enlaces
- [x] Sin número en Ajustes del sitio el botón no se muestra y no queda un enlace vacío
