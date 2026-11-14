# Página: Gracias por tu compra

- **Resumen:** Confirmación que WooCommerce muestra al terminar de pagar (`/finalizar-compra/order-received/<pedido>/`). La alumna ve qué cursos ya tiene y cómo empezar; si compró para regalar, recibe el código para compartir. Después, la invitación al mes gratis en Mundo Angelitozzz.
- **Fuente:** `figma:gracias`
- **Plantilla:** `woocommerce/checkout/thankyou.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** confirmacion, regalo-listo, mes-gratis-mundo
- **Skills usadas:** wp-helper (enlaces del banner). Después: wp-woocommerce (funciones y hooks de Woo en los `// woo:`)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Sobre lo de gracias tu me lo púedes proponer por favor.
    - Solo `regalo-listo` tiene diseño en Figma (nodo 3649:1702). `confirmacion` y los estados de pago son una propuesta que reutiliza la misma tarjeta (`confirmacion-tarjeta`); `mes-gratis-mundo` reutiliza `banner-mundo`.
    - No es una página de WordPress: es el endpoint `order-received` del checkout de Woo. Plantilla sobrescrita `woocommerce/checkout/thankyou.php` (anotada en `specs/plan/sobrescrituras.md`), que conserva `woocommerce_before_thankyou` y `woocommerce_thankyou` (este último imprime el detalle del pedido de Woo y lo que agregue la pasarela; el tema oculta el detalle con un filtro solo si la pasarela no lo necesita, a revisar con Niubiz en la F0).
    - Qué secciones se ven según el pedido: solo cursos para ella → `confirmacion` y `mes-gratis-mundo`; solo regalos → `regalo-listo` (una tarjeta por regalo) sin `mes-gratis-mundo`; pedido mixto → las tres, en ese orden. Pago pendiente o fallido → solo `confirmacion` en su estado de pago.
    - El primer título visible es el `h1` de la página (el de `confirmacion` o, si no hay, el de la primera tarjeta de regalo); los siguientes son `h2`. Título SEO "Gracias por tu compra | Angelitozzz", con `noindex` (lo pone Woo).
    - Toda compra tiene cuenta (`checkout.md`), así que la alumna llega con su sesión iniciada; Woo además comprueba la clave del pedido en la URL.
    - Sin campos ACF propios: los textos son fijos del tema (text domain `angelitozzz`); el banner usa `opciones.md` → `mes-gratis-mundo`.
    - Fondo crema `--color-fondo`, tarjetas centradas con 32 px entre ellas. Cintillo, header y footer salen de `header.php` y `footer.php`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** pendiente

## Sección: confirmacion

- **Origen:** propuesta sin Figma, con la tarjeta de `specs/figma/lectura/gracias/regalo-listo.ref.txt` (nodo 3649:1702) y la de `specs/figma/lectura/canjear-regalo/canjeado.ref.txt` (nodo 3677:1971)
- **Destino:** `woocommerce/checkout/thankyou.php`, `template-parts/pages/gracias/confirmacion.php`, `template-parts/components/confirmacion-tarjeta.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** componente `confirmacion-tarjeta`, con los cursos del pedido que no son regalo (líneas con `_angelitozzz_curso_id`, datos del plugin):
    - **Pagado** (`processing` o `completed`): ícono check, pastilla verde "✓ Compra exitosa", `h1` "¡Gracias por tu compra, <nombre>!" (nombre de la facturación), texto "Ya tienes acceso a tu curso. Te enviamos el detalle de tu pedido por correo." (en plural si son varios: "a tus cursos"). Una caja crema por curso (igual a la caja de acceso de `canjeado`): "<curso> · Acceso por <duración>" (o "Acceso ilimitado"). Con un curso, `boton` frambuesa "Empezar el curso" a su primera lección; con varios, "Ir a Mis cursos" (`/cuenta/cursos/`). Debajo, el enlace subrayado "Ver mi pedido" al historial de la cuenta (`/cuenta/pedidos/`).
    - **Pago pendiente** (`pending` u `on-hold`, por ejemplo mientras Niubiz confirma): sin ícono, pastilla neutra "Pago en revisión" (fondo `--color-neutro`, texto `--color-marca`), `h1` "Estamos confirmando tu pago", texto "Te avisaremos por correo apenas se confirme y tu curso aparecerá en tu cuenta." y el enlace "Ver mi pedido".
    - **Pago fallido** (`failed`): sin ícono, pastilla neutra "Pago no realizado", `h1` "No pudimos procesar tu pago", texto "No se hizo ningún cobro. Puedes intentarlo de nuevo con otra tarjeta." y `boton` frambuesa "Intentar de nuevo" (`$order->get_checkout_payment_url()`).
    - El acceso real lo da el plugin con el pedido pagado (`cursos-datos.md` → `puente-woo`); la página solo lo muestra. Si la inscripción aún no existe al cargar (la pasarela avisa unos segundos después), se muestra igual el estado pagado: el botón lleva a la lección y la lección comprueba el acceso.
    - Los hooks de Woo quedan como comentarios `// woo:` en su lugar (`woocommerce_before_thankyou`, `woocommerce_thankyou`) hasta wp-woocommerce, que los conserva.
    - Valores sueltos: tarjeta de 720 px, padding 56/64, radio 24, 24 px entre bloques. Móvil (propuesta): la tarjeta ocupa el ancho con 16 px de margen y padding 32/24; el título baja a `--text-h3`.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Con un pedido pagado de un curso, muestra el curso, su duración y "Empezar el curso" a la primera lección
- [ ] Con varios cursos, una caja por curso y el botón "Ir a Mis cursos"
- [ ] Con pago pendiente o fallido no dice "Compra exitosa"; el fallido ofrece "Intentar de nuevo"
- [ ] La plantilla conserva `woocommerce_before_thankyou` y `woocommerce_thankyou`
- [ ] Un pedido de otra persona no se puede ver cambiando el número en la URL (lo controla la clave del pedido de Woo)

## Sección: regalo-listo

- **Origen:** `specs/figma/lectura/gracias/regalo-listo.ref.txt` (nodo 3649:1702)
- **Destino:** `template-parts/pages/gracias/regalo-listo.php`, `template-parts/components/confirmacion-tarjeta.php`, `src/js/modules/copiar-codigo.js`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** una tarjeta `confirmacion-tarjeta` por cada curso comprado como regalo en un pedido pagado (el código lo genera el plugin al pagarse el pedido, `cursos-datos.md` → `regalo`):
    - Ícono de regalo (56 px, decorativo), pastilla verde "✓ Compra exitosa", título "¡Tu regalo está listo!" (`h1` si es la primera tarjeta de la página, `h2` si va después de `confirmacion`).
    - Texto: "Regalaste <curso>. Te enviamos este código por correo: compártelo con quien quieras y podrá entrar al curso con él.".
    - Caja del código: fondo `--color-fondo`, borde punteado de 2 px `#c4704a` (terracota, valor suelto), radio 12, padding 18/24; el código centrado en `2026/Code` (Barlow Condensed 600, 34 px, interlineado 1.2, espaciado 2.72 px; valor suelto de esta sección), `--color-marca`, seleccionable.
    - `boton` frambuesa con flecha "Copiar código" (`<button type="button">`): copia el código al portapapeles y avisa "Código copiado" en una región `aria-live="polite"`; el texto del botón vuelve a "Copiar código" a los 2 segundos. Sin JavaScript o sin permiso de portapapeles, el código se puede seleccionar a mano.
    - Nota (`--text-nota`, `--color-tinta-suave`): "El código es válido por 6 meses desde la compra. El acceso al curso empieza el día del canje.".
    - Pago pendiente o fallido: no se muestra (el código aún no existe).
    - El mismo código llega en el correo del pedido (F5).
    - Móvil (propuesta): como `confirmacion`; el código baja a 28 px si no cabe en una línea, sin partirse.
- **Indicaciones del diseño:**
    - nota: cuando alguien regala el curso.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Un pedido pagado con un regalo muestra su código; con dos regalos, dos tarjetas con códigos distintos
- [ ] "Copiar código" copia el código y lo anuncia a lectores de pantalla
- [ ] Un pedido solo de regalos no muestra el bloque de Mundo
- [ ] El código de la página es el mismo del correo

## Sección: mes-gratis-mundo

- **Origen:** componente `banner-mundo` de `_shared.md` (nodo 3586:1518, en `specs/figma/lectura/leccion/`); propuesta sin Figma para esta página
- **Destino:** `template-parts/components/banner-mundo.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** componente `banner-mundo` con `variante` `confirmacion` y el título "Espera — hay algo más esperándote" (`h2`); el texto, el pie, el botón y el enlace salen de `opciones_mes_gratis_mundo` (`opciones.md`), y el enlace abre en pestaña nueva con `angelitozzz_link`. Se muestra solo si el pedido está pagado y tiene al menos un curso que no es regalo (quien recibe un regalo ve el banner en su primera lección, `leccion.md`). Sin enlace en Ajustes del sitio no se imprime. Ancho igual al de las tarjetas (720 px). Móvil: el del componente.
- **Estado:** pendiente

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Aparece con un curso propio pagado y no aparece con un pedido solo de regalos ni con pago pendiente
- [ ] Sin enlace en Ajustes del sitio no se imprime nada
