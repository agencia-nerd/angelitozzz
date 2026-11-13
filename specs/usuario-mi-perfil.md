# Página: Cuenta: mi perfil

- **Resumen:** La alumna actualiza sus datos personales y cambia su contraseña.
- **Fuente:** `figma:usuario-mi-perfil`
- **Plantilla:** `pages/template-cuenta.php`
- **Tipo:** estática
- **Contenido:** ninguno
- **Secciones (en orden):** seccion-perfil
- **Skills usadas:** ninguna. Los formularios los procesa el plugin `angelitozzz-cursos` (no wp-form)
- **Grupo ACF:** ninguno
- **Ubicación:** ninguna
- **Padre:** ninguno
- **Notas de página:**
    - son dos vistas en una sola pantalla Datos personales y Seguridad
    - Cuenta propia (sin Tutor LMS desde 2026-11-13): página `/cuenta/` con la plantilla `pages/template-cuenta.php`, que comparte el marco y el menú lateral `cuenta-menu` (`_shared.md`) y carga la vista de la URL (`/cuenta/`, `/cuenta/perfil/`, `/cuenta/cursos/`, `/cuenta/pedidos/`, endpoints que registra el plugin `angelitozzz-cursos`). Sin sesión, redirige a `/iniciar-sesion/` con `redirect_to`. "Mi cuenta" de Woo (`/mi-cuenta/`) redirige aquí. Los datos los da el plugin con sus funciones públicas `angelitozzz_cursos_*`, siempre de la usuaria con sesión (nunca de un ID recibido en la URL).
    - Vista `/cuenta/perfil/`. Cada formulario lleva nonce y se envía a una acción del plugin, que sanea y valida en el servidor y guarda solo en la usuaria con sesión: nombre y apellidos con `wp_update_user()`, el teléfono en `billing_phone` (el mismo del checkout), el correo con `wp_update_user()` tras comprobar la contraseña actual (`wp_check_password`) y que el correo no exista, y la contraseña con `wp_set_password()` tras comprobar la actual y las 4 reglas; luego se renueva la sesión.
    - Decisión del usuario (2026-11-13): Datos personales tiene Nombre, Apellidos, Correo electrónico y Número de teléfono; se quitan Nombre de usuario (se arma del correo) y Biografía. El correo se puede cambiar: al cambiarlo se pide la contraseña actual y WordPress envía la confirmación al correo nuevo.
    - Decisión del usuario (2026-11-13): la contraseña tiene en todo el sitio las 4 reglas de Seguridad (8 caracteres, 1 minúscula, 1 mayúscula, 1 número), con la lista que se marca en vivo (componente `reglas-contrasena`).
    - Sin campos ACF. `h1`: el nombre de la alumna en el menú. Título SEO "Mi perfil | Angelitozzz", con `noindex`.
    - Cintillo, header y footer salen de `header.php` y `footer.php`. Sin frame móvil: el móvil es una propuesta.
- **Estado:** aprobado

## Sección: seccion-perfil

- **Origen:** `specs/figma/lectura/usuario-mi-perfil/seccion-perfil.ref.txt` (nodo 4164:26837: menú 4164:26839, datos personales 4164:26871; seguridad 4164:27124 en "PARTE 2", captura `seccion-perfil.seguridad.png`)
- **Destino:** `pages/template-cuenta.php`, `template-parts/pages/cuenta/perfil.php`, `template-parts/components/cuenta-menu.php`, `template-parts/components/cuenta-pestanas.php`, `template-parts/components/reglas-contrasena.php`
- **Grupo:** ninguno
- **Skill:** ninguna
- **Comportamiento:** menú `cuenta-menu` con "Mi perfil" activo. Pestañas `cuenta-pestanas` "Datos personales" y "Seguridad" (tarjetas de 173 × 94 con ícono; la activa con fondo `#fafeff` y borde de 2 px `#492343`), que cambian de vista sin recargar (`cuenta-pestanas` en modo `tabs`, con `role="tablist"`). **Datos personales:** grilla de 2 columnas (1020 px, separación 20 × 35), etiqueta encima (`--text-cuerpo-l`) y campo blanco (borde 0,5 px `#dfdfdf`, radio 15, alto 50): Nombre, Apellidos, Correo electrónico (si cambia, aparece "Contraseña actual" para confirmar) y Número de teléfono (`autocomplete` acorde). Botones `boton` frambuesa "Guardar cambios" y "Cancelar" (vuelve a los datos guardados). **Seguridad:** "Contraseña actual" y "Nueva contraseña" (mismo campo, con el ojo para ver u ocultar; sin "confirmar", como el registro), debajo "Su contraseña debe tener al menos:" y el componente `reglas-contrasena` (2 × 2: check verde `#9cb09d` cumplida, ✕ rosa `#f5a8a9` pendiente). "Guardar contraseña" se activa cuando se cumplen las 4 reglas; "Cancelar" limpia los campos. Mensajes (guardado, contraseña actual incorrecta, correo ya en uso) en un recuadro con `role="status"` o `role="alert"`. Móvil (propuesta): menú arriba (como en el escritorio), pestañas en fila, campos en una columna, botones a todo el ancho.
- **Indicaciones del diseño:**
    - nota: drawer https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4164-26839&t=lySNWh2dFHTQgiTC-4
    - nota: datos personales es https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4164-26871&t=lySNWh2dFHTQgiTC-4
    - seguridad: https://www.figma.com/design/FumWuCPT1PPDg5vBTYoufA/Benchmark-Angelitozzz?node-id=4164-27124&t=lySNWh2dFHTQgiTC-4
- **Estado:** aprobado

**Campos ACF**

| Campo | Tipo | Descripción | Valor actual | Requerido |
| ----- | ---- | ----------- | ------------ | --------- |

**Criterios de aceptación**

- [ ] Cumple los criterios comunes de `_shared.md`
- [ ] Cada formulario lleva nonce y solo cambia los datos de la usuaria con sesión; cambiar el correo o la contraseña pide la contraseña actual
- [ ] Las pestañas son accesibles con teclado (`role="tab"`, `aria-selected`) y cambian sin recargar
- [ ] No se ven Nombre de usuario ni Biografía
- [ ] Cambiar el correo pide la contraseña actual
- [ ] Las 4 reglas se marcan en vivo y "Guardar contraseña" no se activa hasta cumplirlas; el servidor valida lo mismo
- [ ] En móvil los campos van en una columna, sin scroll horizontal
