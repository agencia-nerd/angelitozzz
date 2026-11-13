# Página: Checkout

- **Resumen:** Checkout de WooCommerce (`/finalizar-compra/`): la alumna inicia sesión o crea su cuenta, completa sus datos de facturación, paga con tarjeta (Niubiz) y aplica un cupón si lo tiene.
- **Fuente:** `figma:checkout`
- **Plantilla:** `woocommerce/checkout/form-checkout.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** hero
- **Skills usadas:** wp-helper (enlaces). Después: wp-woocommerce (funciones y hooks de Woo en los `// woo:`), `inc/woocommerce/` (campos de facturación, F4)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Pieza `checkout` en modo `clásico` (`_shared.md` → WooCommerce). Plantillas sobrescritas: `woocommerce/checkout/form-checkout.php`, `form-login.php`, `form-billing.php`, `review-order.php`, `payment.php`, `terms.php` y `form-coupon.php` (solo las que el diseño necesita), anotadas en `specs/plan/sobrescrituras.md`. Todo el contenido sale de Woo; no hay campos ACF. Textos fijos del tema con el text domain `angelitozzz`.
    - Sin título visible en el diseño: un `h1` solo para lectores de pantalla, "Finalizar compra" (`sr-only`); los bloques ("Iniciar sesión", "Datos de facturación", "Método de pago", "Resumen de compra") son `h2`. Título SEO "Finalizar compra | Angelitozzz", con `noindex` (lo pone WooCommerce).
    - Cuenta (decisión del usuario, 2026-11-13): toda compra necesita cuenta, porque el plugin `angelitozzz-cursos` inscribe a la usuaria cuando el pedido queda pagado. Sin sesión iniciada se ve el bloque "Iniciar sesión" (para quien ya tiene cuenta) y los datos de facturación suman "Correo" y "Contraseña": la cuenta se crea al pagar (Woo: sin compra como invitada, crear cuenta obligatoria, contraseña escrita por la alumna). Con sesión iniciada no se ven ni el bloque de inicio de sesión ni el correo y la contraseña.
    - Facturación (decisión del usuario, 2026-11-13): solo los campos del diseño. Se quitan dirección, ciudad, región, código postal, país y empresa (son cursos virtuales). "Nombres y Apellidos" es un solo campo. "Tipo de documento" (DNI, CE, Pasaporte, RUC) y "Número de documento" son campos propios de facturación. Sin el botón "Guardar información": los datos se guardan en la cuenta al pagar.
    - Por verificar en la prueba de Niubiz (F0): si el plugin de Niubiz exige nombre y apellido por separado o el país, se agregan como campos y se avisa antes de cambiar el diseño.
    - IGV (decisión del usuario, 2026-11-13): la fila sale de los impuestos de Woo y solo se muestra si hay impuesto configurado. Si se cobra IGV, lo define la clienta con su contador.
    - Los enlaces de privacidad y términos van a las páginas que Woo tiene configuradas (Ajustes → Avanzado → Página de términos; Ajustes → Privacidad): no a la URL del diseño (`?page_id=3`).
    - Fondo: crema `--color-fondo`, sin nubes. Cintillo, header y footer salen de `header.php` y `footer.php`.
    - Sin frame móvil en Figma: el comportamiento en móvil es una propuesta en `Comportamiento`.
- **Estado:** aprobado

## Sección: hero

- **Origen:** `specs/figma/lectura/checkout/hero.ref.txt` (nodo 4156:9962: datos y pago 4156:9926, resumen 4156:9961)
- **Destino:** `woocommerce/checkout/form-checkout.php`, `woocommerce/checkout/form-login.php`, `woocommerce/checkout/form-billing.php`, `woocommerce/checkout/payment.php`, `woocommerce/checkout/terms.php`, `woocommerce/checkout/review-order.php`, `woocommerce/checkout/form-coupon.php`, `template-parts/components/resumen-compra.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** dos columnas (969 px y 346 px, separadas 192 px; padding vertical 120 px). **Columna izquierda:**
    1. "Iniciar sesión" (solo sin sesión): "Correo:" y "Contraseña:" en la misma fila, botón beige "Iniciar sesión" y "¿No tienes una cuenta? Regístrate". "Regístrate" no lleva a otra página: lleva el foco al campo "Correo" de "Datos de facturación" (`<a href="#billing_email">`), donde se crea la cuenta. Es el `form-login.php` de Woo siempre visible (no plegado tras "¿Ya eres cliente?").
    2. "Datos de facturación": grilla de 2 columnas con "Nombres y Apellidos", "Celular (con código de país)" (selector de prefijo con +51 por defecto dentro del campo), "Tipo de documento" (`<select>` con chevron: DNI, CE, Pasaporte, RUC) y "Número de documento"; sin sesión, además "Correo" y "Contraseña" (fila nueva, mismo estilo). Los campos son subrayados: la etiqueta encima de una línea negra, sin placeholder; requeridos con `*` y `required`.
    3. "Método de pago": caja con borde `#bcb28a` y radio 12; título, "Tarjetas de crédito / débito aceptadas" y los logos de Visa, Mastercard, American Express y Diners Club (`hero-logos-tarjetas.svg`, `alt` "Visa, Mastercard, American Express y Diners Club"). Debajo, lo que dibuje la pasarela (Niubiz) en `payment.php`; con una sola pasarela no se ve el radio de elegir método.
    4. Texto de privacidad de Woo con el enlace a la política y la casilla "He leído y estoy de acuerdo con los términos y condiciones de la web *" (`terms.php` de Woo; requerida).
  **Columna derecha:** componente `resumen-compra` con los cursos, Subtotal, Descuento (solo con cupón), IGV (solo si hay impuesto) y Total; debajo, centrado, el botón de Woo "Pagar ahora" (`#place_order`, con el aspecto de `boton` frambuesa y flecha). Debajo, "Código de descuento" con botón beige "Aplicar" (el `form-coupon.php` de Woo movido a esta columna; es un `<form>` aparte, fuera del formulario del checkout). Botones beige: fondo `--color-neutro`, radio 100, `--text-rotulo` (valor del diseño). Valores sueltos: `#bcb28a` en bordes y separadores, radios 12 y 100. Los hooks de Woo quedan como comentarios `// woo:` (`woocommerce_before_checkout_form`, `woocommerce_checkout_before_customer_details`, `woocommerce_checkout_billing`, `woocommerce_checkout_after_customer_details`, `woocommerce_checkout_before_order_review`, `woocommerce_checkout_order_review`, `woocommerce_checkout_after_order_review`, `woocommerce_after_checkout_form`, avisos) hasta wp-woocommerce, que los conserva todos. Móvil (propuesta): una columna en este orden: iniciar sesión, datos de facturación, resumen, cupón, método de pago, términos y "Pagar ahora" al final, a todo el ancho; los campos en una columna.
- **Indicaciones del diseño:**
    - nota : te lo divido en partes
    - nota: datos y pago del usuario https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4156-9926&t=lySNWh2dFHTQgiTC-4
    - nota: resumen de compra https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4156-9961&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

Campos del formulario (de Woo, no ACF; se definen en `inc/woocommerce/` con el filtro `woocommerce_checkout_fields` en la F4):

- `billing_first_name`: "Nombres y Apellidos", texto, requerido: sí
- `billing_phone`: "Celular (con código de país)", teléfono con prefijo (+51 por defecto), requerido: sí
- `billing_tipo_documento`: "Tipo de documento", select: DNI, CE, Pasaporte, RUC, requerido: sí
- `billing_numero_documento`: "Número de documento", texto, requerido: sí
- `billing_email`: "Correo", correo (solo sin sesión), requerido: sí
- `account_password`: "Contraseña", contraseña (solo sin sesión), requerido: sí; con las 4 reglas del sitio y la lista `reglas-contrasena` bajo el campo (decisión del usuario, 2026-11-13)

Se quitan: `billing_last_name`, `billing_company`, `billing_country`, `billing_address_1`, `billing_address_2`, `billing_city`, `billing_state`, `billing_postcode`, los de envío y las notas del pedido.

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Cada plantilla sobrescrita conserva todos los `do_action()` del original de Woo, en el mismo orden (anotadas en `specs/plan/sobrescrituras.md`)
- [ ] Sin sesión: se ven el bloque "Iniciar sesión" y los campos Correo y Contraseña; "Regístrate" lleva el foco al Correo
- [ ] Con sesión: no se ven el bloque de inicio de sesión, ni el correo ni la contraseña
- [ ] Cada campo tiene `<label>` asociado; los requeridos llevan `*` y `required`
- [ ] No hay campos de dirección, país ni envío
- [ ] Las filas Descuento e IGV solo aparecen cuando tienen valor
- [ ] El cupón es un formulario aparte que se aplica sin perder lo escrito en los datos de facturación
- [ ] Los enlaces de privacidad y términos van a las páginas configuradas en Woo
- [ ] En móvil "Pagar ahora" queda al final, después de los términos, sin scroll horizontal
