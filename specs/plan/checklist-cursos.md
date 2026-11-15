# Checklist: cursos, compra y cuentas

Avance del plan de cursos: LMS a medida en el plugin `angelitozzz-cursos`, con cobro por WooCommerce + Niubiz y contenido administrable con ACF. Una rama y un PR por fase. Se marca `- [x]` al terminar y verificar cada punto. Es documentación del tema: no se sube al servidor.

**Cambio de rumbo (2026-11-13):** se deja Tutor LMS (gratis y Pro). Los cursos, la cuenta de la alumna, la lección y el administrador de Bárbara se construyen a medida, iguales a Figma y a los specs. Lo hecho con Tutor en la F0 anterior era de prueba y se limpia en la nueva F0.

## Decisiones tomadas

| Tema | Decisión |
| --- | --- |
| Cursos y lecciones | A medida, en el plugin propio `angelitozzz-cursos` (tipos `curso` y `leccion`, tablas de inscripciones y de avance). Sin Tutor LMS |
| Alcance de la lección | Video, texto (con cita "PD"), materiales descargables, avance y banner de Mundo. Sin cuestionarios, certificados ni comentarios |
| Cobro | WooCommerce es el motor (carrito, checkout, cupones, pedidos, correos, reembolsos). Pasarela Niubiz con su plugin oficial para WooCommerce, en USD. El plugin propio solo hace el puente pedido → inscripción |
| Videos | Vimeo gratis, videos ocultos (no listados), sin restricción de dominio: riesgo aceptado por el usuario (quien tenga el enlace puede verlo). El código no se ata a Vimeo: una sola función arma el reproductor y se cambia de proveedor ahí |
| Landing de venta | Tipo de contenido `solucion` con secciones ACF flexibles, unida a 1 o más cursos |
| URL del listado | `/soluciones-de-sueno/` (y cada solución en `/soluciones-de-sueno/<slug>/`) |
| Angelita Pro | Suscripción cada 4 meses con 7 días de prueba: WooCommerce Subscriptions (licencia oficial) si Niubiz permite cobros con tarjeta guardada; si no, renovación manual con enlace de pago |
| Duración del acceso | 4, 6, 12 meses o ilimitado, por curso (lo elige Bárbara en el constructor). En el regalo, el acceso empieza el día del canje |
| Garantía de 7 días | Devolución: se paga al momento; si se reembolsa el pedido, se quita el acceso |
| Regalo | Código de regalo (cupón de Woo de 100 %, un solo uso, 6 meses) que se canjea en la web |
| Administrador de cursos | A medida, como Figma: escritorio, lista de cursos y constructor de 3 pasos (Básico, Contenido, Adicional). Por ahora solo Bárbara (rol `instructora`) |
| Contenido relacionado | Automático: 3 soluciones distintas de la actual |
| Plugins de terceros | Solo oficiales y con actualizaciones. Nada de GPLVault para pagos, cuentas ni seguridad |

## Arquitectura

- **Plugin `angelitozzz-cursos`** (carpeta `wp-content/plugins/angelitozzz-cursos/`, sin repo por ahora): toda la lógica de negocio.
    - Tipos `curso` y `leccion` (con curso, módulo, orden, video, duración, texto y materiales).
    - Tablas `wp_angelitozzz_inscripciones` (alumna, curso, pedido, inicio, vencimiento, estado) y `wp_angelitozzz_progreso` (alumna, lección, completada, última vista), creadas con `dbDelta` y una versión de esquema guardada en una opción.
    - Acceso a lecciones y descargas protegidas.
    - Avance, vencimiento (tarea diaria), puente con Woo, cuentas, roles y capacidades.
    - API REST `angelitozzz/v1` del constructor.
    - Canje de regalos.
- **Tema `angelitozzz`:** solo presentación (plantillas de los specs, componentes, CSS y JS). Lee el plugin por sus funciones públicas `angelitozzz_cursos_*`; nunca hace consultas propias a las tablas del plugin.
- **Ajustes a WooCommerce** que son del tema (plantillas de carrito, checkout y gracias; bloque de Mundo en el correo): `woocommerce/` e `inc/woocommerce/`. Los campos de facturación del checkout son lógica: van en el plugin `angelitozzz-cursos` (regla de wp-woocommerce, 2026-11-14).

## Seguridad y buenas prácticas (todas las fases)

- **Entrada y salida:**
    - Se sanea todo lo que entra: `sanitize_*`, `absint` y listas permitidas.
    - Se escapa todo lo que sale: `esc_html`, `esc_attr`, `esc_url`, `wp_kses`.
    - El SQL va solo con `$wpdb->prepare`.
- **Formularios y peticiones:** cada formulario y cada petición AJAX o REST lleva nonce y comprobación de capacidad. Cada lección, descarga o inscripción comprueba que pertenece a la usuaria, para que nadie acceda a lo de otra cambiando un ID en la URL o en la petición.
- **Cuentas:**
    - Límite de intentos de inicio de sesión por IP y por correo.
    - Los mensajes no revelan si un correo está registrado.
    - Las 4 reglas de contraseña se validan en el servidor.
    - Cambiar el correo pide la contraseña actual.
    - `wp-admin` queda cerrado para las alumnas.
- **Subidas de archivos:** se comprueba el tipo real del archivo (`wp_check_filetype_and_ext`) y su tamaño máximo. Los PDF de materiales quedan fuera del acceso público y se entregan por una descarga con permiso.
- **Pagos:**
    - La tarjeta solo la maneja el plugin de Niubiz.
    - La inscripción se crea una sola vez aunque Woo avise dos veces.
    - Cada alta o baja de acceso queda en el registro de Woo (`wc_get_logger`).
- **Claves:** no se guardan en el repo. Las credenciales de Niubiz se cargan en sus ajustes, distintas para prueba y producción.
- **Calidad del código:**
    - En el plugin: `php -l`, PHPCS con WordPress Coding Standards y PHPUnit (con las pruebas de WordPress) para inscripción, vencimiento, acceso y puente con Woo.
    - `/security-review` y `/code-review` al cerrar cada fase.

## Reglas de mantenimiento

- Nunca editar archivos de plugins de terceros. Orden de preferencia: hooks y filtros → CSS → sobrescribir una plantilla.
- Cada plantilla sobrescrita de `woocommerce/` conserva todos sus `do_action()` y se anota en `specs/plan/sobrescrituras.md`.
- Con Woo, solo `WC_Order` y `WC_Product` (compatibles con HPOS).
- El plugin propio versiona su esquema: cada cambio de tablas es una migración, y la desinstalación (`uninstall.php`) borra lo suyo.
- Antes de actualizar Woo o Niubiz en producción: actualizar en el Local, revisar Woo → Estado → plantillas y `specs/plan/sobrescrituras.md`, y repetir las compras de prueba.

## F0 · Limpieza y base

- [x] Pasarela elegida por la clienta: **Niubiz**
- [x] Precio de A Dormir Por Fin confirmado: **$399**
- [x] `specs/_shared.md`: "Contenido dinámico" y tabla WooCommerce (carrito, checkout y mini-carrito clásicos)
- [x] Crear `specs/plan/sobrescrituras.md`
- [x] Desinstalar Tutor LMS en el Local (2026-11-13, con respaldo previo del usuario):
    - [x] borrar sus tablas `wp_tutor_*`, sus opciones, sus metadatos, el rol `tutor_instructor` y sus capacidades;
    - [x] borrar las páginas "Escritorio" y "Registro de Estudiante";
    - [x] borrar los 6 cursos `courses` de prueba;
    - [x] borrar las páginas duplicadas "Cart" y "Checkout".
- [x] Borrar los 6 productos de prueba (`_angelitozzz_prueba`); se rehacen ligados a los cursos propios.
- [x] Borrar `inc/tutor/estilos.php` y su `require_once` de `functions.php`.
- [x] Esqueleto del plugin `angelitozzz-cursos` en `wp-content/plugins/` (2026-11-13): cabecera, activación, migraciones versionadas, desinstalación, compatibilidad con HPOS, config de PHPCS y PHPUnit. Sin activar.
- [x] Repo del plugin: no se crea por decisión del usuario (2026-11-13). El plugin queda solo en `wp-content/plugins/angelitozzz-cursos/`; su respaldo son los del sitio. Se puede hacer `git init` local o subirlo a GitHub más adelante
- [x] `composer install` en el plugin y suite de pruebas de WordPress con una base de datos de pruebas (2026-11-14): PHP 8.4 de Local, suite de WP 7.1.4 en `~/Local Sites/angelitozzz/wp-tests/` y base `local_tests`; `composer test` en verde (detalle en el README del plugin)
- [ ] Ocultar `/tienda/` de Woo y redirigirla a `/soluciones-de-sueno/` (cuando exista `archive-solucion.php`).
- [x] Mensaje a la clienta enviado (respuesta pendiente): afiliación de Niubiz en USD, credenciales de prueba y consulta por cobros recurrentes.
- [ ] Credenciales de comercio de Niubiz: código de comercio, usuario y contraseña de **prueba (Sandbox)** y de producción.
- [ ] Respuesta de Niubiz: ¿la cuenta puede tener cobros automáticos con tarjeta guardada en USD ("Pago programado" / tokenización)? Define cómo se renueva Angelita Pro.
- [ ] Plugin **oficial** de Niubiz para WooCommerce (portal de desarrolladores: `desarrolladores.niubiz.com.pe/docs/plugins`) instalado y probado en Sandbox:
    - [ ] Cobra en USD
    - [ ] Pago de prueba aprobado y rechazado
    - [ ] Funciona con el checkout clásico
    - [ ] Compatible con HPOS
    - [ ] Verificar si exige nombre y apellido por separado o el país (afecta a `checkout.md`)
- [ ] Vimeo: confirmar con la clienta que su cuenta gratis permite videos ocultos y que el espacio le alcanza.

## F1 · Specs

- [x] Soluciones de sueño (`soluciones-de-sueno.md`) y las 4 landings con sus layouts, modales y drawer (`solucion.md`)
- [x] Carrito y checkout (`carrito.md`, `checkout.md`)
- [x] Iniciar sesión, crear cuenta y recuperar contraseña
- [x] Cuenta de la alumna: escritorio, mi perfil, cursos inscritos (con las vistas propuestas) e historial de pedidos (`usuario-*.md`)
- [x] Lecturas de Figma de la lección, del escritorio del administrador y de la lista de cursos con las capturas del constructor
- [x] Specs aprobados ajustados al LMS propio (sin Tutor): plantillas y `Destino`, reglas de acceso y de seguridad
- [x] `cursos-datos.md`: modelo de datos (curso, lección, inscripción, avance, vencimiento, roles, reglas de acceso, puente con Woo) → aprobado
- [x] `leccion.md`: temario, video, Resumen, Materiales, banner de Mundo (con la línea "Si ya lo activaste…"), mensaje de Angelita Pro, cita "PD", "Marcar como completo" → aprobado (con `opciones.md` → `mes-gratis-mundo`)
- [x] `admin-escritorio.md` y `admin-cursos.md` (lista, menú "Más opciones", aviso de publicado) → aprobados
- [x] `admin-constructor.md`: pasos Básico, Contenido y Publicar (el paso 3 deja de tener los textos de venta), y los modales de editar y eliminar → aprobado (2026-11-14)
- [x] Página de gracias (`gracias.md`, 2026-11-14): propuesta sin Figma (confirmación, estados de pago y bloque del mes gratis en Mundo) más la tarjeta "¡Tu regalo está listo!" de Figma → aprobado (2026-11-14)
- [x] Regalo (2026-11-14): `canjear-regalo.md` (página `/canjear-regalo/` con "¿Te regalaron un curso?" y "Regalo canjeado"), la compra exitosa con código en `gracias.md` y la sección `regalo` de `cursos-datos.md` (código, canje y reembolso) → aprobados (2026-11-14)
- [x] Comportamiento en móvil propuesto en cada spec (no hay frames móviles); el 2026-11-14 se completaron las 5 secciones del constructor que no lo tenían
- [x] Todos los specs aprobados en `specs/_index.md` (2026-11-14): la F1 queda cerrada

## F2 · Plugin: datos, acceso y puente con Woo

- [x] Tipos `curso` y `leccion`, con su relación, módulos y orden
- [x] Tablas de inscripciones y de avance con migraciones
- [x] Acceso: lección solo con inscripción vigente; curso sin comprar → su solución
- [x] Materiales: carpeta protegida y descarga con permiso
- [x] Avance: completar lección, última vista, porcentaje y conteos (inscritos, activos, completados)
- [x] Vencimiento: tarea diaria y estado "Por expirar" (15 días o menos)
- [x] Puente con Woo: pedido pagado → inscribe (sin duplicar); reembolsado o cancelado → quita el acceso; registro en `wc_get_logger`
- [x] Reproductor de video en una sola función (Vimeo hoy)
- [x] Pruebas PHPUnit de inscripción, vencimiento, acceso y puente (2026-11-14: plugin 0.2.0)
- [x] Revisión de seguridad y correctitud de la fase (2026-11-14): como el plugin no tiene repo, `/security-review` y `/code-review` no aplican (revisan un diff de git); se hizo una revisión independiente del plugin completo. Sus 10 hallazgos se corrigieron con su prueba (57 pruebas en verde)

## F3 · Cuentas

- [x] Iniciar sesión con límite de intentos y vuelta a la página de origen
- [x] Crear cuenta: usuario armado del correo y las 4 reglas de contraseña
- [x] Recuperar contraseña en 3 pasos, sin revelar si el correo existe
- [x] Mi perfil: datos personales (cambiar el correo pide la contraseña) y seguridad
- [x] Rol `instructora` y capacidades; `wp-admin` cerrado para alumnas; `/mi-cuenta/` → escritorio (2026-11-14: la instructora conserva `wp-admin` hasta que exista el constructor, F5)
- [x] Cuenta creada al pagar en el checkout (Woo) con las mismas reglas (plugin 0.3.0, 78 pruebas en verde; las pantallas son de la F4)
- [ ] Auditoría de seguridad al cerrar la F3 (decisión del usuario, 2026-11-14): plugin completo (F2 + F3) y plantillas de cuentas, con la skill `cloudflare/security-audit-skill` (https://github.com/cloudflare/security-audit-skill; leer su código antes de instalarla). Si no sirve para PHP/WordPress, otra equivalente. Antes de correrla, preguntar al usuario el perfil y el tope de agentes (usa muchos subagentes). Cada hallazgo se corrige con su prueba

## F4 · Maquetación

Una rama por bloque (decisión del usuario, 2026-11-14): 1) soluciones, 2) carrito, checkout y gracias, 3) regalo, 4) acceso, 5) cuenta y lección, 6) administrador. Solo maquetación: los datos de muestra viven en `inc/solucion-muestra.php` y `inc/solucion-muestra/` hasta la F5. El tipo `solucion` lo registra temporalmente el mu-plugin de vista previa.

- [x] Listado de Soluciones de sueño maquetado (era provisional en `woocommerce/archive-product.php`)
- [x] Mover el listado a `archive-solucion.php` reutilizando `shop.php` y `producto-card` (bloque 1, 2026-11-14)
- [x] `single-solucion.php` + un archivo por layout en `template-parts/pages/solucion/` (bloque 1)
- [x] Modales con wp-modal (drawer del carrito, etapas, selector de edad) y preguntas frecuentes con wp-accordion (bloque 1)
- [x] Carrito, checkout y gracias en `woocommerce/` (bloque 2, 2026-11-14; vista previa con `?vista_previa=carrito|carrito-vacio|checkout|gracias&estado=…`)
- [x] Regalo: pantallas de compra, canje y canjeado (bloque 3, 2026-11-14: compra en los bloques 1 y 2; canje y canjeado en `/canjear-regalo/`, vista previa `?vista_previa=canjear-regalo&estado=…`)
- [ ] Iniciar sesión, crear cuenta y recuperar contraseña
- [ ] Cuenta de la alumna (4 vistas) con `cuenta-menu`, `cuenta-pestanas` y `curso-progreso-card`
- [ ] Lección
- [ ] Escritorio, lista de cursos y constructor de Bárbara
- [ ] Imágenes pesadas de Figma a WebP (img-webp) antes de las plantillas (soluciones hechas en el bloque 1; falta en cada bloque siguiente)

## F5 · Administrable y conexión

- [ ] wp-acf: `solucion` (tarjeta, compra, flexible de secciones) y ajustes del sitio (mes gratis en Mundo, textos del regalo); contenido inicial de las 4 soluciones
- [ ] wp-query: listado de soluciones (12 por página, "Ver más") y relacionados automáticos
- [ ] wp-woocommerce: soporte del tema, sobrescrituras con `@version`, CSS clásico de Woo quitado, mini-carrito que se actualiza solo
- [ ] Borrar `wp-content/mu-plugins/angelitozzz-preview-woo.php` (también registra el tipo `solucion` de vista previa)
- [ ] Borrar la muestra `inc/solucion-muestra.php` e `inc/solucion-muestra/` (y su `require_once`): las vistas pasan a `get_field()`, precios de Woo y wp-query
- [ ] Borrar la muestra `inc/compra-muestra.php` (y su `require_once`) y la ruta `?vista_previa=` del mu-plugin: carrito, checkout y gracias leen `WC()->cart` y `WC_Order`
- [ ] Páginas Carrito y Finalizar compra con el shortcode clásico (hoy tienen los bloques de Woo) y productos de prueba ligados a los cursos
- [ ] Campos de facturación del checkout (`checkout.md`, con `billing_phone_prefijo`) en el plugin `angelitozzz-cursos`, y el login del checkout con el límite de intentos de la F3
- [ ] Constructor: API REST con permisos, nonce y validación; al publicar crea o actualiza el producto de Woo del curso
- [ ] Cerrar `wp-admin` para la instructora cuando el constructor funcione (va al administrador de cursos; `cursos-datos.md` → `roles`)
- [ ] Precio de la landing leído del producto de Woo (precio de lanzamiento = precio rebajado)
- [ ] "Agregar al carrito" sin recargar y abre el drawer; selector de etapas; descuento por cantidad leído de ACF; cupón en el hero
- [ ] Regalo: comprar como regalo genera el cupón; canje con sesión o creando la cuenta; el acceso empieza ese día
- [ ] Acción de canje en el plugin (`canjear-regalo.md`): nonce, límite de intentos, código de un solo uso marcado en la misma operación que inscribe, dato de un solo uso para la confirmación y flujo sin sesión por crear cuenta; la página `/canjear-regalo/` en `inc/acf/paginas.php`
- [ ] Bloque de Mundo en gracias y en el correo de pedido, excepto en regalos
- [ ] Banner de Mundo solo en la primera lección de cada curso (no en Angelita Pro); mensaje inicial de Angelita Pro
- [ ] `specs/plan/sobrescrituras.md` al día
- [ ] `/security-review` y `/code-review` de la fase

## F6 · Pagos y Angelita Pro

- [ ] Niubiz en modo prueba con las compras de la verificación
- [ ] Angelita Pro: WooCommerce Subscriptions (licencia oficial) cada 4 meses con 7 días de prueba, o renovación manual según la respuesta de Niubiz
- [ ] Niubiz en producción

## F7 · Cierre

- [ ] QA en escritorio, tablet y móvil
- [ ] Revisión de seguridad final (`/security-review` de todo el plugin y las plantillas)
- [ ] Manual de la clienta con wp-manual (incluye el administrador de cursos)
- [ ] Deploy:
    - [ ] Tema: `pnpm build` y subir el tema con `dist/`, sin `hot`, `node_modules/`, `src/`, `specs/` ni configs.
    - [ ] Plugin `angelitozzz-cursos`: subirlo sin tests ni configs de desarrollo.

## Verificación (repetir antes de cada deploy)

- [ ] `php -l`, PHPCS y PHPUnit del plugin, `pnpm build` y los `check.mjs` de design-import y wp-acf en 0
- [ ] Compra de 1 curso → inscripción con su vencimiento
- [ ] Horarios Ideales con 2 y con 3 etapas, con el descuento correcto
- [ ] Compra con cupón
- [ ] Regalo: genera el código, el canje inscribe y el acceso empieza ese día
- [ ] Angelita Pro: prueba de 7 días y renovación
- [ ] Reembolso: el pedido reembolsado quita el acceso
- [ ] Vencimiento: un acceso vencido no abre la lección y figura "Vencido" en el historial
- [ ] Sin acceso: la lección y el PDF de materiales no se abren (ni cambiando el ID en la URL)
- [ ] Inicio de sesión: se bloquea tras varios intentos y no revela si el correo existe
- [ ] Constructor: una alumna no puede usar la API ni ver el administrador
- [ ] Avance y materiales en la lección; banner de Mundo solo en la primera lección
- [ ] Correo de pedido con el bloque de Mundo y sin él (regalo)
- [ ] Prueba de actualización: Woo y Niubiz actualizados en el Local, plantillas al día en Woo → Estado y en `specs/plan/sobrescrituras.md`
