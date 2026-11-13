# Página: Carrito

- **Resumen:** Carrito de WooCommerce (`/carrito/`): la alumna revisa los cursos que eligió, puede quitar alguno y pasa a pagar.
- **Fuente:** `figma:carrito`
- **Plantilla:** `woocommerce/cart/cart.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** carrito-de-compras
- **Skills usadas:** wp-helper (imágenes). Después: wp-woocommerce (funciones y hooks de Woo en los `// woo:`)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Pieza `carrito` en modo `clásico` (`_shared.md` → WooCommerce): plantilla PHP sobrescrita en `woocommerce/cart/cart.php` (y `cart-empty.php` para el vacío), que se anota en `specs/plan/sobrescrituras.md`. Todo el contenido sale del carrito de Woo; no hay campos ACF. Los textos fijos ("Carrito de compras", "Resumen de compra", "Ir a pagar") son del tema y pasan por el text domain `angelitozzz`.
    - `h1`: "Carrito de compras". Título SEO "Carrito | Angelitozzz" (página de Woo). Con `noindex` (lo pone WooCommerce).
    - Decisiones del usuario (2026-11-13): cada fila lleva una papelera para quitar el curso, como en el drawer; sin selector de cantidad (cada curso se compra una vez: los productos se marcan "Vender individualmente" en Woo); el carrito vacío es una propuesta (el diseño no lo trae).
    - El cupón no está en el carrito: se aplica en la landing (hero) y en el checkout. La fila "Descuento" solo se muestra si hay un cupón aplicado.
    - Textos unificados con el drawer: el botón dice "Ir a pagar" (el diseño dice "Ir a Pagar") y la duración "Acceso por 4 meses".
    - Fondo: crema `--color-fondo`, sin nubes.
    - Cintillo, header y footer salen de `header.php` y `footer.php`.
    - Sin frame móvil en Figma: el comportamiento en móvil es una propuesta en `Comportamiento`.
- **Estado:** aprobado

## Sección: carrito-de-compras

- **Origen:** `specs/figma/lectura/carrito/carrito-de-compras.ref.txt` (nodo 4151:10148: lista 4151:10179, resumen 4151:10157)
- **Destino:** `woocommerce/cart/cart.php`, `woocommerce/cart/cart-empty.php`, `template-parts/components/resumen-compra.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** título `h1` "Carrito de compras" (`--text-h1`, `--color-marca`) y dos columnas (lista de 607 px y resumen de 345 px, separadas 145 px, dentro de `--container-sitio`). **Lista:** un `<form>` de Woo con una fila por curso, sin encabezados de tabla: miniatura 68 × 68 (radio 12, `object-cover`, imagen destacada de la solución del curso), ícono de regalo + "Curso · Acceso por <duración>" (`--text-cuerpo-s`, `--color-marca`; la duración sale del curso, en el plugin `angelitozzz-cursos`), nombre del curso y precio de la fila a la derecha, papelera con nombre accesible "Quitar <curso>" (enlace de Woo `wc_get_cart_remove_url`), y una línea `#bcb28a` bajo cada fila. El precio no se repite bajo el nombre (en el diseño aparece dos veces). Sin cantidad ni botón "Actualizar carrito". **Resumen:** componente `resumen-compra` (`_shared.md`) con los cursos, Subtotal, Descuento (solo con cupón) y Total; debajo, centrado, `boton` frambuesa "Ir a pagar" al checkout. **Vacío** (propuesta): "Tu carrito está vacío" y `boton` "Ver soluciones de sueño" a `/soluciones-de-sueno/`. Los hooks de Woo quedan como comentarios `// woo:` en su lugar (`woocommerce_before_cart`, `woocommerce_before_cart_table`, `woocommerce_cart_contents`, `woocommerce_after_cart_table`, `woocommerce_cart_collaterals`, `woocommerce_after_cart`, avisos `woocommerce_output_all_notices`) hasta el paso wp-woocommerce, que los conserva todos. Valores sueltos: color de bordes `#bcb28a`, radio 12, padding vertical 120 px. Móvil (propuesta): una columna, el resumen bajo la lista y el botón a todo el ancho; padding 56 px vertical y 16 px lateral.
- **Indicaciones del diseño:**
    - nota: Esta es la seccion donde se alojara todo lo seleccionado https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4151-10179&t=lySNWh2dFHTQgiTC-4
    - nota: resumen es https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4151-10157&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Contenido del diseño (de Woo, no campos): un curso "Recién Nacidos Go (0 a 12 semanas)", "Curso · acceso por 4 meses", $97; resumen con Subtotal $97, Descuento - $0, Total $97.

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] `cart.php` conserva todos los `do_action()` del original de Woo, en el mismo orden (anotado en `specs/plan/sobrescrituras.md`)
- [ ] Cada fila tiene la papelera con nombre accesible y quita el curso; no hay selector de cantidad
- [ ] La fila "Descuento" solo aparece con un cupón aplicado
- [ ] El resumen usa el componente `resumen-compra`, el mismo del checkout
- [ ] Con el carrito vacío se ve el mensaje y el botón a Soluciones de sueño
- [ ] En móvil el resumen queda bajo la lista, sin scroll horizontal
