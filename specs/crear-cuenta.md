# Página: Crear cuenta

- **Resumen:** Registro de la alumna fuera de la compra: crea su cuenta con nombre, apellidos, correo y contraseña para acceder a sus cursos desde cualquier dispositivo (o canjear un regalo).
- **Fuente:** `figma:crear-cuenta`
- **Plantilla:** `pages/template-crear-cuenta.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** formulario
- **Skills usadas:** wp-helper (imagen del logo). El formulario lo procesa el plugin `angelitozzz-cursos` (no wp-form)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Página propia `/crear-cuenta/` (sin Tutor LMS desde 2026-11-13). El formulario se envía a una acción del plugin `angelitozzz-cursos`, que valida todo en el servidor y crea la usuaria con `wp_insert_user()` y el rol `customer` de Woo, con nonce y límite de intentos. En el checkout la cuenta se crea al pagar (spec `checkout.md`); esta pantalla es para quien se registra sin comprar.
    - Decisiones del usuario (2026-11-13): solo los campos del diseño. El nombre de usuario de WordPress lo arma el plugin a partir del correo (sin campo visible) y no hay "Confirmar contraseña": el ojo deja revisarla. Contraseña con las 4 reglas del sitio (8 caracteres, 1 minúscula, 1 mayúscula, 1 número; decisión del usuario, 2026-11-13, en `usuario-mi-perfil.md`), con la lista `reglas-contrasena` que se marca en vivo bajo el campo. Al crear la cuenta, vuelve a la página de origen (`redirect_to`); si entró directo, va al escritorio de la alumna.
    - Los términos y la privacidad del texto de la casilla llevan enlaces a las páginas configuradas en Woo (el diseño no los dibuja). La casilla empieza sin marcar (en el diseño está marcada como ejemplo).
    - Sin campos ACF: textos fijos del tema (text domain `angelitozzz`).
    - `h1`: "Crea tu cuenta". Título SEO "Crear cuenta | Angelitozzz", con `noindex`.
    - Cintillo, header y footer salen de `header.php` y `footer.php`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: formulario

- **Origen:** `specs/figma/lectura/crear-cuenta/formulario.ref.txt` (nodo 4156:23937; tarjeta 4156:23743)
- **Destino:** `pages/template-crear-cuenta.php`, `template-parts/pages/crear-cuenta/formulario.php`, `template-parts/components/acceso-tarjeta.php`, `template-parts/components/campo-acceso.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** mismo fondo, decoración y tarjeta que iniciar sesión (`acceso-tarjeta`): logo, `h1` "Crea tu cuenta" y bajada "Con tu cuenta accedes a tus cursos desde cualquier dispositivo.". Campos con `campo-acceso`: "Nombre" (placeholder "Ej. Ana Lucía", `first_name`, `autocomplete="given-name"`) y "Apellidos" ("Ej. León", `last_name`, `autocomplete="family-name"`) en dos columnas; "Correo electrónico" (ícono sobre, "tucorreo@ejemplo.com", `email`, `autocomplete="email"`); "Contraseña" (ícono candado, "Mínimo 8 caracteres", ojo para ver u ocultar, `password`, `autocomplete="new-password"`; debajo, la lista `reglas-contrasena`; las reglas se validan también en el servidor (plugin, F3) y el error se avisa con su mensaje). Todos requeridos (`required` y `*` en la etiqueta, que el diseño no dibuja). Casilla verde "Acepto los términos y condiciones y la política de privacidad" (requerida, con los dos enlaces). `boton` frambuesa "Crear cuenta" (submit) y "¿Ya tienes cuenta? Inicia sesión" (enlace a iniciar sesión). El formulario lleva nonce y `redirect_to` (validado como URL del propio sitio). **Errores** (propuesta): correo ya registrado ("Ya existe una cuenta con este correo. Inicia sesión o recupera tu contraseña.") y contraseña que no cumple las reglas en un recuadro `role="alert"` arriba del formulario, conservando lo escrito. Móvil (propuesta): Nombre y Apellidos en una columna; tarjeta con 16 px de margen y padding 32 px.
- **Indicaciones del diseño:**
    - nota: te paso link de la secccion especifica: https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4156-23743&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] El formulario lleva nonce; el servidor valida todos los campos (no confía en el navegador) y crea la usuaria con el rol `customer`
- [ ] Solo se ven Nombre, Apellidos, Correo, Contraseña y la casilla; el usuario se arma del correo
- [ ] La casilla empieza sin marcar, es requerida y enlaza a términos y privacidad
- [ ] La contraseña muestra las 4 reglas en vivo y el servidor rechaza una que no las cumpla
- [ ] Con un correo ya registrado se ve el error y no se pierde lo escrito
- [ ] Al crear la cuenta vuelve a la página de origen; sin origen, al escritorio de la alumna
- [ ] En móvil los campos van en una columna, sin scroll horizontal
