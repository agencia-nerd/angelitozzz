# Página: Recuperar contraseña

- **Resumen:** La alumna que olvidó su contraseña escribe su correo, recibe un enlace y crea una nueva.
- **Fuente:** `figma:recuperar-contrasena`
- **Plantilla:** `pages/template-recuperar-contrasena.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** formulario
- **Skills usadas:** wp-helper (imagen del logo). El formulario lo procesa el plugin `angelitozzz-cursos` (no wp-form)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - Página propia `/recuperar-contrasena/` con los 3 pasos (sin Tutor LMS desde 2026-11-13). El plugin `angelitozzz-cursos` usa las funciones de WordPress: `retrieve_password()` envía el correo con la clave (el enlace apunta a esta página, filtro `lostpassword_url`), `check_password_reset_key()` valida el enlace y `reset_password()` guarda la nueva. Nonce y límite de pedidos por IP y por correo.
    - El diseño solo dibuja el primer paso. Los otros dos son propuesta con la misma tarjeta: "Revisa tu correo" (tras enviar) y "Crea una nueva contraseña" (al abrir el enlace).
    - Sin campos ACF: textos fijos del tema (text domain `angelitozzz`).
    - `h1`: el título de cada paso. Título SEO "Recuperar contraseña | Angelitozzz", con `noindex`.
    - Cintillo, header y footer salen de `header.php` y `footer.php`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: formulario

- **Origen:** `specs/figma/lectura/recuperar-contrasena/formulario.ref.txt` (nodo 4156:24277; tarjeta 4156:24625)
- **Destino:** `pages/template-recuperar-contrasena.php`, `template-parts/pages/recuperar-contrasena/formulario.php`, `template-parts/components/acceso-tarjeta.php`, `template-parts/components/campo-acceso.php`
- **Grupo:** ninguno
- **Skill:** wp-helper
- **Comportamiento:** mismo fondo, decoración y tarjeta que iniciar sesión (`acceso-tarjeta`). **Paso 1 (diseño):** logo, `h1` "Recupera tu contraseña", texto "Escribe tu correo y te enviaremos un enlace para crear una nueva.", campo "Correo electrónico" (`campo-acceso`, ícono sobre, "tucorreo@ejemplo.com", `name="user_login"`, `autocomplete="email"`, requerido), `boton` frambuesa "Enviar enlace" (submit) y "Volver a iniciar sesión" (enlace subrayado verde). **Paso 2 (propuesta):** tras enviar, la misma tarjeta con `h1` "Revisa tu correo", el texto "Si el correo está registrado, te enviamos un enlace para crear una nueva contraseña. Revisa también la carpeta de spam." y "Volver a iniciar sesión". El mensaje es el mismo exista o no la cuenta (no revela qué correos están registrados). **Paso 3 (propuesta, al abrir el enlace del correo):** `h1` "Crea una nueva contraseña", campos "Nueva contraseña" y "Repite la contraseña" (`campo-acceso` con candado y ojo, `autocomplete="new-password"`; deben coincidir) y la lista `reglas-contrasena` bajo la nueva (las 4 reglas del sitio, decisión del usuario, 2026-11-13), `boton` "Guardar contraseña"; al guardar, va a iniciar sesión con el aviso "Tu contraseña se cambió. Ya puedes iniciar sesión.". Errores (enlace vencido o ya usado, contraseñas distintas, reglas sin cumplir) en un recuadro `role="alert"`. Móvil (propuesta): tarjeta con 16 px de margen y padding 32 px.
- **Indicaciones del diseño:**
    - nota: te paso link de la secccion especifica: https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4156-24625&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Los formularios llevan nonce y hay límite de pedidos de enlace por IP y por correo
- [ ] El enlace del correo vence y sirve una sola vez (clave de WordPress); la nueva contraseña cumple las 4 reglas en el servidor
- [ ] Tras enviar se ve "Revisa tu correo" con el mismo mensaje exista o no la cuenta
- [ ] El enlace del correo abre "Crea una nueva contraseña" y al guardar lleva a iniciar sesión con el aviso
- [ ] Un enlace vencido muestra el error con `role="alert"` y un enlace para pedir otro
- [ ] En móvil la tarjeta no tiene scroll horizontal
