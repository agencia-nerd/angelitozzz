# Página: Iniciar sesión

- **Resumen:** Pantalla de acceso de la alumna: entra con su correo y contraseña para ver sus cursos, su historial y sus regalos. Desde aquí recupera la contraseña o va a crear su cuenta.
- **Fuente:** `figma:iniciar-sesion`
- **Plantilla:** `pages/template-iniciar-sesion.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** formulario
- **Skills usadas:** wp-helper (imagen del logo). El formulario lo procesa el plugin `angelitozzz-cursos` (no wp-form)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Página propia `/iniciar-sesion/` (sin Tutor LMS desde 2026-11-13; `_shared.md` → WooCommerce: `cuenta` = `no aplica` porque la cuenta es propia). El formulario se envía a una acción del plugin `angelitozzz-cursos`, que usa `wp_signon()` con nonce, límite de intentos por IP y por correo, y un mensaje que no revela si el correo existe. "Mi cuenta" de Woo (`/mi-cuenta/`), `wp-login.php` para alumnas y cualquier página de la cuenta sin sesión redirigen aquí, con `redirect_to` validado con `wp_validate_redirect` (solo URL del propio sitio).
    - Sin campos ACF: los textos son fijos del tema (text domain `angelitozzz`).
    - Decisiones del usuario (2026-11-13): "Iniciar sesión" del header lleva a esta página (sin modal). Al entrar bien, vuelve a la página de la que venía (checkout, landing, lección; `redirect_to`), y si entró directo, va al escritorio de la alumna.
    - Los campos de estas pantallas son cajas (estilo de `campo-acceso`), distintos de los subrayados del checkout: se respeta cada diseño (decisión del usuario, 2026-11-13).
    - `h1`: "Inicia sesión". Título SEO "Iniciar sesión | Angelitozzz", con `noindex`.
    - El checkout dice "Iniciar sesión" y "Regístrate"; esta pantalla, "Inicia sesión" y "Crea una aquí": se respeta cada diseño.
    - Cintillo, header y footer salen de `header.php` y `footer.php`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: formulario

- **Origen:** `specs/figma/lectura/iniciar-sesion/formulario.ref.txt` (nodo 4156:23795; tarjeta 4156:23796)
- **Destino:** `pages/template-iniciar-sesion.php`, `template-parts/pages/iniciar-sesion/formulario.php`, `template-parts/components/acceso-tarjeta.php`, `template-parts/components/campo-acceso.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** fondo crema `--color-fondo` con la decoración de acceso (nube dormida abajo a la izquierda, destellos rosa y beige, luna salvia; decorativa, `aria-hidden`) y la tarjeta blanca centrada del componente `acceso-tarjeta`: logo de Mundo Angelitozzz, `h1` "Inicia sesión" y bajada "Entra para ver tus cursos, tu historial y tus regalos.". Formulario (`<form method="post">` con nonce) con `campo-acceso`: "Correo electrónico" (ícono sobre, placeholder "tucorreo@ejemplo.com", `type="email"`, `autocomplete="username"`) y "Contraseña" (ícono candado, botón ojo para ver u ocultar con `aria-pressed` y nombre accesible, `autocomplete="current-password"`). Sin "Recordarme" en el diseño: se envía marcado por defecto (campo oculto `rememberme`). Debajo, "¿Olvidaste tu contraseña?" (enlace subrayado verde a `/recuperar-contrasena/`), el `boton` frambuesa "Iniciar sesión" (un `<button type="submit">` con el aspecto del componente) y "¿No tienes cuenta? Crea una aquí" (enlace a `/crear-cuenta/`). **Errores** (propuesta): el mensaje "Correo o contraseña incorrectos" (el mismo en los dos casos) o "Demasiados intentos. Prueba de nuevo en unos minutos." arriba del formulario, en un recuadro con `role="alert"`, y el campo con borde frambuesa. Mientras envía, el botón se desactiva. Valores sueltos: borde de campo `#d7d0b5` (= `--color-neutro`), tarjeta 560 px, padding 56, radio 28, sombra `0 12px 40px #24454a14`, separación 32 px. Móvil (propuesta): la tarjeta ocupa el ancho con 16 px de margen y padding 32 px; la decoración se reduce y no tapa la tarjeta.
- **Indicaciones del diseño:**
    - nota: te paso link de la secccion especifica: https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4156-23796&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El formulario lleva nonce; tras varios intentos fallidos se bloquea un rato y el mensaje no revela si el correo existe
- [ ] `redirect_to` solo acepta URL del propio sitio
- [ ] Cada campo tiene `<label>` asociado y el `autocomplete` correcto
- [ ] El ojo muestra y oculta la contraseña, con nombre accesible y `aria-pressed`
- [ ] Al entrar vuelve a la página de origen; sin origen, al escritorio de la alumna
- [ ] Un error de acceso se muestra con `role="alert"` sin perder el correo escrito
- [ ] `/mi-cuenta/` de Woo lleva a esta pantalla sin sesión (o al escritorio con sesión)
- [ ] En móvil la tarjeta no tiene scroll horizontal y la decoración no tapa los campos
