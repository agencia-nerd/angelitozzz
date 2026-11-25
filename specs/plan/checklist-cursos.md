# Checklist: cursos, compra y cuentas

Avance del plan de cursos: LMS a medida en el plugin `angelitozzz-cursos`, con cobro por WooCommerce + Openpay (Niubiz hasta el 2026-11-18) y contenido administrable con ACF. Una rama y un PR por fase. Se marca `- [x]` al terminar y verificar cada punto. Es documentación del tema: no se sube al servidor.

**Cambio de rumbo (2026-11-13):** se deja Tutor LMS (gratis y Pro). Los cursos, la cuenta de la alumna, la lección y el administrador de Bárbara se construyen a medida, iguales a Figma y a los specs. Lo hecho con Tutor en la F0 anterior era de prueba y se limpia en la nueva F0.

## Decisiones tomadas

| Tema | Decisión |
| --- | --- |
| Cursos y lecciones | A medida, en el plugin propio `angelitozzz-cursos` (tipos `curso` y `leccion`, tablas de inscripciones y de avance). Sin Tutor LMS |
| Alcance de la lección | Video, texto (con cita "PD"), materiales descargables, avance y banner de Mundo. Sin cuestionarios, certificados ni comentarios |
| Cobro | WooCommerce es el motor (carrito, checkout, cupones, pedidos, correos, reembolsos). Pasarela Openpay (Perú) con su plugin oficial para WooCommerce, "Openpay Cards", en USD (decisión de la clienta, 2026-11-18; antes Niubiz). El plugin propio solo hace el puente pedido → inscripción |
| Videos | Vimeo gratis, videos ocultos (no listados), sin restricción de dominio: riesgo aceptado por el usuario (quien tenga el enlace puede verlo). El código no se ata a Vimeo: una sola función arma el reproductor y se cambia de proveedor ahí |
| Landing de venta | Tipo de contenido `solucion` con secciones ACF flexibles, unida a 1 o más cursos |
| URL del listado | `/soluciones-de-sueno/` (y cada solución en `/soluciones-de-sueno/<slug>/`) |
| Angelita Pro | Suscripción cada 4 meses con 7 días de prueba: por definir en la F6. El plugin de Openpay guarda tarjetas pero no muestra soporte de WooCommerce Subscriptions (revisión de su código, 2026-11-18): renovación manual con enlace de pago, o un desarrollo propio con la tarjeta guardada o las suscripciones de la API de Openpay. Mientras tanto se vende como un curso directo |
| Duración del acceso | 4, 6, 12 meses o ilimitado, por curso (lo elige Bárbara en el constructor). En el regalo, el acceso empieza el día del canje |
| Garantía de 7 días | Devolución: se paga al momento; si se reembolsa el pedido, se quita el acceso |
| Regalo | Código de regalo (cupón de Woo de 100 %, un solo uso, 6 meses) que se canjea en la web |
| Administrador de cursos | A medida, como Figma: escritorio, lista de cursos y constructor de 3 pasos (Básico, Contenido, Publicar). Bárbara es la dueña: cuenta propia de rol administradora, que edita el contenido en `/wp-admin/` y los cursos en `/administrador/` (decisión del usuario, 2026-11-15). El rol `instructora` queda para quien solo maneje cursos |
| Contenido relacionado | Automático: 3 soluciones distintas de la actual |
| Plugins de terceros | Solo oficiales y con actualizaciones. Nada de GPLVault para pagos, cuentas ni seguridad |

## Arquitectura

- **Plugin `angelitozzz-cursos`** (carpeta `wp-content/plugins/angelitozzz-cursos/`, con repo propio en GitHub, `agencia-nerd/angelitozzz-cursos`, desde el 2026-11-17): toda la lógica de negocio.
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
    - Los mensajes no revelan si un correo está registrado. Excepción aceptada (usuario, 2026-11-19, opción A): "Crear cuenta" y el checkout avisan si el correo ya tiene cuenta, con límite de 10 por hora por IP (`crear-cuenta.md`, `checkout.md`).
    - Las 4 reglas de contraseña se validan en el servidor.
    - Cambiar el correo pide la contraseña actual.
    - `wp-admin` queda cerrado para las alumnas.
- **Subidas de archivos:** se comprueba el tipo real del archivo (`wp_check_filetype_and_ext`) y su tamaño máximo. Los PDF de materiales quedan fuera del acceso público y se entregan por una descarga con permiso.
- **Pagos:**
    - La tarjeta solo la maneja el plugin de Openpay (se tokeniza en el navegador con su JS).
    - La inscripción se crea una sola vez aunque Woo avise dos veces.
    - Cada alta o baja de acceso queda en el registro de Woo (`wc_get_logger`).
- **Claves:** no se guardan en el repo. Las credenciales de Openpay (ID de comercio, llave privada `sk_` y pública `pk_`) se cargan en sus ajustes de WooCommerce, distintas para prueba (sandbox) y producción; nunca en el chat ni en el repo.
- **Calidad del código:**
    - En el plugin: `php -l`, PHPCS con WordPress Coding Standards y PHPUnit (con las pruebas de WordPress) para inscripción, vencimiento, acceso y puente con Woo.
    - `/security-review` y `/code-review` al cerrar cada fase.

## Reglas de mantenimiento

- Nunca editar archivos de plugins de terceros. Orden de preferencia: hooks y filtros → CSS → sobrescribir una plantilla.
- Cada plantilla sobrescrita de `woocommerce/` conserva todos sus `do_action()` y se anota en `specs/plan/sobrescrituras.md`.
- Con Woo, solo `WC_Order` y `WC_Product` (compatibles con HPOS).
- El plugin propio versiona su esquema: cada cambio de tablas es una migración, y la desinstalación (`uninstall.php`) borra lo suyo.
- Antes de actualizar Woo u Openpay en producción: actualizar en el Local, revisar Woo → Estado → plantillas y `specs/plan/sobrescrituras.md`, y repetir las compras de prueba.

## F0 · Limpieza y base

- [x] Pasarela elegida por la clienta: **Openpay** (2026-11-18; antes Niubiz, que no llegó a mandar credenciales)
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
- [x] Esqueleto del plugin `angelitozzz-cursos` en `wp-content/plugins/` (2026-11-13): cabecera, activación, migraciones versionadas, desinstalación, compatibilidad con HPOS, config de PHPCS y PHPUnit. Se activó en la F2.
- [x] Repo del plugin: en GitHub, `agencia-nerd/angelitozzz-cursos` (2026-11-17). Al principio no se creó (decisión del usuario, 2026-11-13); el 2026-11-14 se hizo un `git init` local, y su historial se perdió al importar el sitio a otra PC, porque All-in-One no lleva `.git`.
- [x] `composer install` en el plugin y suite de pruebas de WordPress con una base de datos de pruebas (2026-11-14): PHP 8.4 de Local, suite de WP 7.1.4 en `~/Local Sites/angelitozzz/wp-tests/` y base `local_tests`; `composer test` en verde (detalle en el README del plugin)
- [x] Ocultar `/tienda/` de Woo y redirigirla a `/soluciones-de-sueno/` (2026-11-17, `inc/tienda.php`).
- [x] Credenciales de comercio de Openpay de **prueba (sandbox)** y de producción, en USD (comercio "Angelitozzz SAC"; las tiene el usuario, 2026-11-18).
- [x] Plugin **oficial** de Openpay para WooCommerce, "Openpay Cards" 3.2.0 (wordpress.org/plugins/openpay-cards), revisado antes de instalar (2026-11-18): acepta PEN y USD en Perú, declara compatibilidad con HPOS y con el checkout clásico y de bloques; la captura pide capacidad y nonce; el webhook (`/wc-api/openpay_cards`) usa autenticación básica y confirma cada cobro contra la API; probado por el autor hasta WooCommerce 11.0.1. Instalado y activo en el Local.
- [x] Openpay probado en sandbox (F5-b, 2026-11-18, compra a mano del usuario en Chrome):
    - [x] Credenciales de sandbox cargadas por el usuario en WooCommerce → Ajustes → Pagos → Openpay (país Perú, cargo Directo). Ojo: el asistente de bienvenida de Woo había cambiado la moneda a PEN; se volvió a USD
    - [x] Cobra en USD
    - [x] Pago aprobado (tarjeta 4111 1111 1111 1111): pedido pagado ("Procesando"), cuenta creada al pagar, campos propios guardados en el pedido y la cuenta, inscripción de 4 meses y página de gracias
    - [x] Rechazo del banco (2026-11-18, tarjetas de prueba de Openpay Perú en `specs/pruebas/LEEME.md`): 4444 4444 4444 4448 y 4222 2222 2222 2220 (pedidos 696 y 697): pedido "Fallido", aviso de error en el checkout, cuenta creada y sin inscripción. El error 1001 de antes era de datos (p. ej. Amex con CVV de 3 dígitos), no un rechazo
    - [x] Reintento sin recargar tras un rechazo: Openpay respondía 1003 (token ya usado; pedidos 696 y 697) porque nuestro "Pagar ahora" vive fuera del `<form>` y su `openpay.js` no borraba el token viejo. Corregido en `src/js/modules/checkout-boton.js` (2026-11-18) y probado: rechazo con 4222… y, sin recargar, 4111… → POST /tokens 201 con token nuevo, cargo 200 y el pedido 697 pasó a "Procesando" con su inscripción
    - [x] La prueba automática (`cdp-pago.mjs`) no puede pagar: el sandbox de Openpay responde 403 sin CORS al Chrome sin ventana. El pago se prueba a mano en un navegador normal
    - [x] Funciona con el checkout clásico (la pasarela aparece en el checkout del tema)
    - [x] Compatible con HPOS (lo declara)
    - [x] No exige apellido ni país: cobró con "Nombres y Apellidos" en un solo campo y sin dirección; `checkout.md` queda como está
- [ ] Preguntar a Openpay si la cuenta exige 3D Secure. Es la confirmación extra que pide el banco de la tarjeta (código por SMS o por su app): el pago sale al banco y vuelve, y el pedido queda en "Pago en revisión" (`gracias.md`) hasta que Openpay avisa por el webhook. Se pregunta para saber si ese camino se prueba antes de producción. El 3D Secure y el webhook necesitan una URL pública: en el Local, un Live Link de Local; si no, se prueban en la F6.
- [ ] Proveedor de video por decidir: Vimeo u otra fuente (el usuario lo analiza, 2026-11-25). Cambiarlo toca solo `includes/video.php` del plugin (el tema pide la dirección con `angelitozzz_cursos_video_url()`). Si sigue Vimeo: confirmar con la clienta que su cuenta gratis permite videos ocultos y que el espacio le alcanza.

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
- [x] Revisión de seguridad y correctitud de la fase (2026-11-14): como en ese momento el plugin no tenía repo, `/security-review` y `/code-review` no aplicaban (revisan un diff de git); se hizo una revisión independiente del plugin completo. Desde el 2026-11-17 el plugin está en GitHub y ya se pueden usar. Sus 10 hallazgos se corrigieron con su prueba (57 pruebas en verde)

## F3 · Cuentas

- [x] Iniciar sesión con límite de intentos y vuelta a la página de origen
- [x] Crear cuenta: usuario armado del correo y las 4 reglas de contraseña
- [x] Recuperar contraseña en 3 pasos, sin revelar si el correo existe
- [x] Mi perfil: datos personales (cambiar el correo pide la contraseña) y seguridad
- [x] Rol `instructora` y capacidades; `wp-admin` cerrado para alumnas; `/mi-cuenta/` → escritorio (2026-11-14: la instructora conservó `wp-admin` hasta que existió el constructor; desde la F4, bloque 6b, va a `/administrador/`)
- [x] Cuenta creada al pagar en el checkout (Woo) con las mismas reglas (plugin 0.3.0, 78 pruebas en verde; las pantallas son de la F4)
- [ ] ~~Auditoría de seguridad al cerrar la F3~~ → se mueve a la F7 (decisión del usuario, 2026-11-19): se corre una sola vez, con todo integrado (regalo, canje, Angelita Pro). Ver F7 → "Auditoría de seguridad completa"

## F4 · Maquetación

Una rama por bloque (decisión del usuario, 2026-11-14): 1) soluciones, 2) carrito, checkout y gracias, 3) regalo, 4) acceso, 5) cuenta y lección, 6) administrador. Solo maquetación: los datos de muestra viven en `inc/solucion-muestra.php` y `inc/solucion-muestra/` hasta la F5. El tipo `solucion` lo registraba temporalmente el mu-plugin de vista previa; desde la F5-a lo registra ACF (`acf-json/post_type_*.json`).

- [x] Listado de Soluciones de sueño maquetado (era provisional en `woocommerce/archive-product.php`)
- [x] Mover el listado a `archive-solucion.php` reutilizando `shop.php` y `producto-card` (bloque 1, 2026-11-14)
- [x] `single-solucion.php` + un archivo por layout en `template-parts/pages/solucion/` (bloque 1)
- [x] Modales con wp-modal (drawer del carrito, etapas, selector de edad) y preguntas frecuentes con wp-accordion (bloque 1)
- [x] Carrito, checkout y gracias en `woocommerce/` (bloque 2, 2026-11-14; vista previa con `?vista_previa=carrito|carrito-vacio|checkout|gracias&estado=…`, quitada en la F5-b junto con el mu-plugin)
- [x] Regalo: pantallas de compra, canje y canjeado (bloque 3, 2026-11-14: compra en los bloques 1 y 2; canje y canjeado en `/canjear-regalo/`, vista previa `?vista_previa=canjear-regalo&estado=…`, quitada en la F5-b)
- [x] Iniciar sesión, crear cuenta y recuperar contraseña (bloque 4, 2026-11-14): conectadas de verdad al plugin (sin muestra) y probadas de punta a punta en el Local. Las 3 páginas existen en el Local (`/cuenta/`, a donde lleva entrar sin origen, llegó en el bloque 5)
- [x] Cuenta de la alumna (4 vistas) con `cuenta-menu`, `cuenta-pestanas` y `curso-progreso-card` (bloque 5, 2026-11-14): conectada al plugin y a WooCommerce, probada con datos de prueba (borrados al terminar). La página `/cuenta/` existe en el Local
- [x] Lección (bloque 5, 2026-11-14): conectada al plugin (acceso, temario, avance por REST, video, materiales, banner de Mundo y mensaje de Angelita Pro)
- [x] Administrador de Bárbara, bloque 6a (2026-11-14): escritorio (resumen y tabla) y lista de cursos (tarjetas, menú "Más opciones", publicar, pasar a borrador, eliminar y aviso de publicado), conectados al plugin 0.4.0 (`includes/admin.php`, `includes/rest-admin.php`, 90 pruebas en verde; un curso en borrador conserva el acceso de sus inscritas) y probados en el Local con una instructora de prueba. La página `/administrador/` existe en el Local (creada a mano); el token `--color-terracota` entra en `_shared.md`
- [x] Administrador de Bárbara, bloque 6b (2026-11-14): constructor de 3 pasos (Básico, Contenido y Publicar) conectado al plugin 0.5.0 (`includes/constructor.php`, `includes/rest-constructor.php`, 101 pruebas en verde), con Tiptap 3.34.0 para la descripción de la lección. Probado en el Local de punta a punta: crear un curso con portada, módulo, lecciones con video de Vimeo, texto y PDF, ordenar (arrastre y teclado), eliminar y publicar (producto de Woo publicado y oculto del catálogo). Adelanta tareas de la F5 (abajo, marcadas)
- [x] Imágenes pesadas de Figma a WebP (img-webp) antes de las plantillas: hechas en el bloque 1; los bloques 2 a 6 solo usan SVG (no queda ningún PNG ni JPG en `assets/`)

## F5 · Administrable y conexión

**Entorno de pruebas en cada PC:** hay dos PC, la de casa (MySQL de Local en el `10090`) y la del trabajo (en el `10222`). El 2026-11-17 el sitio se importó a la del trabajo y el entorno se rearmó a mano. All-in-One no lleva `~/bin/`, `wp-tests/`, `conf/nginx/` ni `.git`, y el `phpunit.xml` que sí lleva queda con las rutas de la otra PC.

Desde el 2026-11-17 el entorno ya no se arma a mano:
- El plugin calcula sus rutas y lee el puerto del `sites.json` de Local (`tests/bootstrap.php` y `tests/wp-tests-config.php`), sin `phpunit.xml`.
- El resto lo revisa o lo arma `node specs/pruebas/entorno/instalar.mjs [--instalar]`.
- `composer test`: 106 pruebas en verde.

El 2026-11-19, en la PC del trabajo (tras un `git clone` del tema y del plugin), `instalar.mjs --instalar` volvió a armar la suite, la base `local_tests`, la regla de Nginx y `vendor/`: `composer test` con 119 pruebas en verde y `composer lint` en 0.

En otra PC, seguir `specs/pruebas/LEEME.md` → "Entorno en otra PC" antes de tocar el plugin.

**Orden de la F5 (decisión del usuario, 2026-11-17, tras la auditoría completa del proyecto; ajustado el 2026-11-19):** F5-a (ACF y wp-query) → F5-b (WooCommerce) → arreglos previos a la F5-c (lo que el canje reutiliza) → F5-c (plugin: regalo, Mundo, lección) → cierre (con el resto de la deuda). La auditoría de seguridad completa pasa a la F7. Una rama por subfase y el plan aprobado antes de cada una.

### F5-a · Soluciones administrables (hecha)

- [x] wp-acf: `solucion` (tarjeta, compra, flexible de secciones), "Archivo de solucion" y mes gratis en Mundo; contenido inicial de las 4 soluciones y los 5 menús (F5-a, 2026-11-17). El tipo lo registra ACF con sus URL (`URL:` en los specs, wp-acf 1.13.0). Los botones de WhatsApp de las landings usan el número de Ajustes del sitio. Los textos del regalo quedan para la F5-c, con el regalo
- [x] wp-query: listado de soluciones (12 por página, "Ver más" sin recargar) y relacionados automáticos (F5-a, 2026-11-17)
- [x] Páginas `/iniciar-sesion/`, `/crear-cuenta/`, `/recuperar-contrasena/`, `/cuenta/`, `/administrador/` y `/canjear-regalo/` en `inc/acf/paginas.php` con su plantilla (2026-11-17, wp-acf 1.13.0: `Página WP` en los specs de cuenta y administrador, `Origen: plugin` para `curso` y `leccion`). En el Local se conservaron las que ya existían y se creó `/canjear-regalo/`
- [x] Constructor: API REST con permisos, nonce y validación; al guardar el paso 1 crea o actualiza el producto de Woo del curso y al publicar lo publica oculto del catálogo (adelantado en la F4, bloque 6b)
- [x] Cerrar `wp-admin` para la instructora cuando el constructor funcione (va al administrador de cursos; `cursos-datos.md` → `roles`; adelantado en la F4, bloque 6b)

### F5-b · WooCommerce

Plan aprobado el 2026-11-18. Tema y plugin 0.6.0 (119 pruebas en verde, PHPCS en 0). Probado en Chrome con `specs/pruebas/cdp-compra.mjs` y los datos de `datos-f5.php tienda`.

- [x] wp-woocommerce 1.41.0 (motor 1.1.0): `inc/woocommerce/base.php` (soporte, CSS clásico de Woo quitado), `fragments.php` y bloque en `CLAUDE.md`; `check.mjs` en `ok`
- [x] `page.php`: Privacidad, Términos y cualquier página sin plantilla con el estilo de los artículos; Carrito y Finalizar compra sin envoltorio (sus plantillas traen el diseño)
- [x] Plantillas de `woocommerce/` conectadas (subagentes por grupo): todos los `do_action()` y filtros del original, nonces del carrito, del pago y del login, bucle de pasarelas, campos con los args del plugin (y `woocommerce_form_field()` para los que el diseño no conoce). Lo que Woo imprime por defecto y el diseño reemplaza se quita en `inc/woocommerce/plantillas.php`
- [x] `@version` de cada sobrescritura igual al original de WooCommerce 11.3.0 (`sobrescrituras.md`)
- [x] `#order_review` y `#place_order` fuera del `<form name="checkout">` con `form=`: probado (el resumen se actualiza por AJAX y el envío dispara el submit del checkout). El JS de Woo borra la flecha del botón al cambiar su texto: `src/js/modules/checkout-boton.js` la repone. Ese módulo también hace lo que el clic de `openpay.js` no ve con el botón fuera del form: quita el token del intento anterior y valida el nombre del titular (2026-11-18; las dos probadas a mano por el usuario). Queda la API `wc.customPlaceOrderButton` de Woo, que busca el botón dentro del form: Openpay no la usa
- [x] Contador del carrito del header y contenido del drawer por fragmentos de Woo (`template-parts/components/carrito-cuenta.php` y `carrito-contenido.php`, `inc/woocommerce/carrito.php`)
- [x] Borrados el mu-plugin `angelitozzz-preview-woo.php` (copia en el scratchpad de la sesión), `inc/solucion-muestra.php`, `inc/solucion-muestra/` e `inc/compra-muestra.php` con sus `require_once`, después de conectar las vistas que los usaban. El modo "próximamente" de Woo se apagó en el Local (`woocommerce_coming_soon` = no): en producción se apaga al publicar
- [x] Páginas Carrito y Finalizar compra con el shortcode clásico, una sola vez y solo si tenían el bloque de Woo (`inc/woocommerce/paginas.php`); productos de prueba ligados a los cursos (`datos-f5.php tienda`)
- [x] Campos de facturación en el plugin (`includes/checkout.php`: definición, validación del prefijo, celular y documento, correo de la cuenta con sesión) y prueba de que el límite de intentos frena el login del checkout (`wp_signon`)
- [x] Precio de la landing, de cada etapa y de los relacionados leído del producto de Woo (`inc/woocommerce/precios.php`; rebajado = lanzamiento, "antes" = normal; sin ".00" en todo el HTML de precios de Woo)
- [x] "Agregar al carrito" sin recargar y abre el drawer (`src/js/modules/carrito.js`, `?wc-ajax=add_to_cart`); selector de etapas con los productos; descuento por cantidad de ACF como cargo negativo (`inc/woocommerce/descuentos.php`: 2 etapas $67, 3 $97); cupón del hero (`?wc-ajax=apply_coupon`, sin JS va al carrito); quitar y la sugerida en el drawer
- [x] Marca de regalo ("Regalar este curso") del carrito al pedido en el plugin; el código y el canje son de la F5-c
- [x] Producto del curso virtual y "Vender individualmente" en cada guardado, y migración 2 para los que ya existían (era un hallazgo de "Antes de la F5-c")
- [x] Scripts de Openpay solo en el checkout (`includes/pagos.php` del plugin) y su CSS en línea neutralizado para lo nuestro (`src/css/pasarela.css`)
- [x] `specs/plan/sobrescrituras.md` al día
- [x] Pago con Openpay en sandbox de punta a punta (ver F0): aprobado → gracias pagado e inscripción; rechazado → pedido fallido sin inscripción (2026-11-18)
- [x] Páginas `/terminos-y-condiciones/` y `/politicas-de-privacidad/` (las del menú "Pie: legal") creadas en el Local con texto de relleno (decisión del usuario, 2026-11-18) y elegidas en Woo (página de términos) y en Ajustes → Privacidad: el checkout ya muestra la casilla de términos con su enlace. El texto real lo pone el usuario (ver F7)
- [x] Datos de prueba de la F5-b borrados (2026-11-18, con respaldo de la base en el scratchpad): `datos-f5.php tienda-borrar` (cursos, lecciones, productos, inscripciones y pedidos 692, 696 y 697) y las usuarias de prueba 21, 22 y 23
- [x] Gracias con un pedido `cancelled` o `refunded`: tarjeta "Pedido sin efecto" (decisión del usuario, 2026-11-18; `gracias.md` → confirmacion): "Este pedido fue cancelado/reembolsado", acceso ya no activo, WhatsApp y "Ver mi pedido", sin Mundo ni regalos. Probado con pedidos temporales
- [x] (Aceptado, no se cambia) Quitar un curso desde la página `/carrito/` recarga la página (desde el drawer no recarga). Aceptado (usuario, 2026-11-18): en el diseño no hay enlace a `/carrito/`, que queda de respaldo (el carrito del header sin JS, el cupón del hero sin JS y los avisos o redirecciones de Woo)

### Antes de la F5-c · Arreglos que el canje reutiliza

Decisión del usuario (2026-11-19): la auditoría completa pasa a la F7. Antes de la F5-c solo se arregla lo que el canje usa (límite de intentos, cuentas e inscripciones), para no copiar el problema al regalo. Una rama corta, cada arreglo con su prueba:

- [x] Seguridad: mensajes que revelan si un correo está registrado (plugin 0.6.1, 2026-11-19, opción A). Login de `wp-login.php`, de Woo y XML-RPC con un solo mensaje ("Correo o contraseña incorrectos."); recuperar en `wp-login.php` con un correo inexistente lleva a la misma confirmación; el perfil responde igual si el correo nuevo ya es de otra cuenta y no envía nada. "Crear cuenta" y el checkout conservan su aviso (excepción de la regla de "Cuentas"), y el checkout suma al límite de 10 por hora por IP cada vez que sale. Probado también en vivo en `wp-login.php`
- [x] Seguridad: límite de intentos atómico (plugin 0.6.1): tabla `angelitozzz_intentos` (migración 3, borra los transitorios viejos), suma en una sola consulta, ventana fija desde el primer intento, un intento bloqueado no suma y la tarea diaria borra los contadores vencidos
- [x] Plugin: una inscripción `cancelada` que se reactiva (pedido pagado de nuevo) empieza hoy, o al terminar el acceso vigente, con sus propios meses (plugin 0.6.1)
- [x] Plugin: sin `GET_LOCK` no inscribe (error `angelitozzz_bloqueo`; espera con el filtro `angelitozzz_cursos_espera_bloqueo`); la búsqueda de la línea ya inscrita va dentro del bloqueo; el puente deja nota en el pedido y reintenta una vez a los 5 minutos; el registro del cambio de estado lleva la usuaria y el curso (plugin 0.6.1)

Plugin 0.6.1: 133 pruebas en verde (14 nuevas) y PHPCS en 0. La migración 3 corrió en el Local.

### Deuda de la F2 y del tema (va en el cierre de la F5)

Hallazgos de la auditoría del 2026-11-17 y de la revisión del 2026-11-18 que no bloquean la F5-c. Cada uno con su prueba:

- [x] Seguridad: los enlaces del texto de la lección admiten `target` sin forzar `rel` (`tipos.php:158-163`); el tema imprime la lección con `the_content` sin la lista permitida de `wp_kses` (`template-parts/pages/leccion/pestanas.php`). Hecho (plugin 0.7.2, 2026-11-20): `angelitozzz_cursos_limpiar_texto()` fuerza `target="_blank"` y `rel="noopener noreferrer"`, y el tema imprime con `angelitozzz_cursos_texto_leccion()` (filtra después de `the_content`). 166 pruebas en verde, PHPCS en 0
- [x] Plugin: producto del curso con "Vender individualmente" y virtual, como pide `cursos-datos.md` → `puente-woo` (hecho en la F5-b, plugin 0.6.0: en cada guardado y con la migración 2)
- [x] Plugin: exportador y borrador de datos personales de WordPress (`wp_privacy_personal_data_*`; nota de página de `cursos-datos.md`) Hecho (plugin 0.7.3, 2026-11-20). `includes/privacidad.php`, con su prueba.
- [x] Plugin: `angelitozzz_cursos_puede_ver()` exige `$usuario_id` aunque el spec lo da como opcional (`acceso.php:43`); `angelitozzz_cursos_pendientes_publicar()` no exige la capacidad (`admin.php:425`); `_angelitozzz_producto_id` no comprueba que el producto exista (`tipos.php:204`) Hecho (plugin 0.7.3, 2026-11-20). `puede_ver()` acepta la usuaria opcional (sin ella, la de la sesión); `pendientes_publicar()` y `aviso_publicado()` exigen la capacidad (publicar usa la regla interna `faltan_para_publicar()`, así que sin sesión sigue bloqueando); `_angelitozzz_producto_id` solo guarda un producto que existe.
- [x] Plugin: `uninstall.php` no borra la meta `_angelitozzz_correo_pendiente`, los transitorios `angelitozzz_lim_*` y `angelitozzz_msg_*` ni la meta `_angelitozzz_curso_id` de los productos. Con `ANGELITOZZZ_CURSOS_BORRAR_TODO` tampoco borra los productos de Woo ni las portadas que crea el constructor (`constructor.php:183-199`, `:241-253`); su cabecera dice "borra todo lo que el plugin creó" (revisión del 2026-11-18) Hecho (plugin 0.7.3, 2026-11-20). La desinstalación vive en `includes/desinstalar.php` (la llama `uninstall.php`) y tiene prueba. Sin `BORRAR_TODO`, la meta `_angelitozzz_curso_id` de los productos se conserva a propósito: los cursos también se quedan y el puente la necesita al reinstalar.
- [x] (F5-c, bloque 2) Plugin: el PDF descargado sale con el nombre "Privado-…". `descargas.php:231` arma el nombre con `get_the_title()`, que antepone "Privado:" a los adjuntos privados. Hay que leer el título crudo, como ya hace `angelitozzz_cursos_datos_material()` (`:257`). Con su prueba (revisión del 2026-11-18)
- [x] Plugin: alta manual de una inscripción (existe el origen `manual`, sin pantalla ni ruta). Decidir si entra o se saca del spec Decidido (usuario, 2026-11-20): se saca. Sin origen `manual` en el código ni en el spec; para dar un curso sin cobrar, un cupón de 100 %.
- [x] Tema y plugin: el tema lee el plugin solo por sus funciones públicas (regla de `CLAUDE.md`). La lectura de `_angelitozzz_tipo` y `_angelitozzz_mes_mundo` con `get_post_meta` en `single-leccion.php` ya se cambió por `angelitozzz_cursos_datos_curso()` (F5-c, bloque 1). Queda que el tema llama a funciones que no están en la lista pública del spec: `angelitozzz_cursos_puede_gestionar()`, `angelitozzz_cursos_vimeo_partes()` y `angelitozzz_cursos_redirect_seguro()` Hecho (plugin 0.7.3, 2026-11-20). `puede_gestionar()` y `redirect_seguro()` entran en la lista pública de `cursos-datos.md`; `vimeo_partes()` el tema ya no la usa.
- [x] Tema y plugin: más diferencias entre la lista pública de `cursos-datos.md` → `funciones-publicas` y el código (revisión del 2026-11-18). Se corrige el spec o el código, uno por uno: Hecho (plugin 0.7.3, 2026-11-20). Corregido en el spec: `temario()` con `$actual_id`, `datos_tarjeta()` en la lista, `puede_ver_curso()` e `inicio_de()` marcadas como que piden la usuaria; y en el código: `aviso_publicado()` exige la capacidad.
    - `angelitozzz_cursos_temario()` recibe un segundo argumento, `$actual_id`, que el spec no nombra.
    - `angelitozzz_cursos_datos_tarjeta()` está en `api.php` pero no en el spec.
    - `puede_ver_curso()` e `inicio_de()` piden `$usuario_id`, aunque el spec dice que todas trabajan con la usuaria de la sesión.
    - `angelitozzz_cursos_aviso_publicado()` no exige la capacidad, aunque el spec dice que las del administrador sí la exigen.
- [x] (F5-c, 2026-11-19: el plugin da `angelitozzz_cursos_estado_linea()` con clave y texto, y `pedidos.php` elige el color por la clave) Tema: `template-parts/pages/cuenta/pedidos.php:59-64` elige el color de cada estado comparando con el texto traducido que devuelve el plugin ("Vencido", "Por expirar"…). Si cambia la traducción, se rompe. El plugin tiene que devolver también una clave del estado (revisión del 2026-11-18)
- [x] Tema: las 4 reglas de contraseña están repetidas en el tema (`template-parts/components/reglas-contrasena.php:23-28` y `src/js/modules/reglas-contrasena.js`) en vez de leerse de `angelitozzz_cursos_reglas_contrasena()`. Solo ayudan al escribir (el servidor valida), pero si cambian en el plugin quedan distintas (revisión del 2026-11-18) Hecho (2026-11-24, rama `fix/cierre-f5-detalles`). Qué reglas se muestran sale de `angelitozzz_cursos_reglas_contrasena()`; el tema solo pone el texto y la comprobación en vivo. Una regla nueva del plugin sin texto en el tema sale con su clave (y un `error_log` con `WP_DEBUG`). El largo en el JS cuenta caracteres como `mb_strlen`.

### F5-c · Plugin: regalo, Mundo y lección

Plan aprobado el 2026-11-19, en 2 bloques. Decisiones del usuario: avisos del constructor como panel dentro del modal (no modales apilados), textos del regalo fijos en el tema (no en Ajustes del sitio), el código llega solo a quien compra y Mundo según el `mes_mundo` de cada curso.

**Bloque 1 (plugin 0.7.0, 152 pruebas en verde, PHPCS en 0; probado en el Local el 2026-11-19):**

- [x] Regalo: comprar como regalo genera el cupón (`includes/regalos.php`); canje con sesión o creando la cuenta; el acceso empieza ese día (o se encadena al vigente). El código no sirve en el carrito ni en el checkout. Reembolsar o cancelar anula el código o la inscripción del canje
- [x] Acción de canje en el plugin (`angelitozzz_canjear_regalo`): nonce, límite de intentos (5 cada 15 minutos por IP y por usuaria), código de un solo uso marcado en la misma operación que inscribe (bloqueo por cupón), dato de un solo uso para la confirmación y flujo sin sesión por crear cuenta (cookie `angelitozzz_canje`)
- [x] Funciones públicas del regalo: `angelitozzz_cursos_pedido_regalos()`, `angelitozzz_cursos_regalos_de_pedido()` y `angelitozzz_cursos_ultimo_canje()`. Los textos del regalo quedan fijos en el tema (decisión del usuario, 2026-11-19)
- [x] Bloque de Mundo en gracias y en el correo de pedido (`inc/woocommerce/correos.php`), excepto en regalos y solo si algún curso propio lo incluye (`angelitozzz_cursos_pedido_con_mundo()`). El correo lleva también el código de cada regalo
- [x] Banner de Mundo solo en la primera lección de cada curso con `mes_mundo`; mensaje inicial de Angelita Pro. `single-leccion.php` lee el tipo y `mes_mundo` con `angelitozzz_cursos_datos_curso()` (ya sin `get_post_meta`)

**Prueba a mano del bloque 1 (usuario, 2026-11-19): todo bien.** Ajustes que salieron de ella, hechos en las mismas ramas:

- [x] Código de regalo en el cupón: cualquier error de Woo con ese código dice que se canjea en "Canjear regalo" (antes, con otro curso en el carrito, salía el error de productos de Woo)
- [x] "Regalar este curso" en Horarios Ideales (etapas): abre el selector de edad en modo regalo, sin descuento por cantidad
- [x] "Copiar código" sin https (el Local): método clásico del navegador
- [x] "Mis pedidos": una línea de regalo muestra el estado de su código ("Regalo · sin canjear", "canjeado", "vencido") en vez del estado del pedido de Woo ("Procesando"). Los pedidos de Woo conservan su estado nativo (decisión del usuario): "Procesando" ya quiere decir pagado
- [x] Constructor: ayuda genérica bajo "Incluye mes gratis en Mundo" y, con Tipo = Suscripción y el interruptor encendido, un aviso en rojo que recomienda no activarlo. Sin bloqueo: el interruptor decide en todos los cursos (decisión del usuario, 2026-11-19)

**Bloque 2 (plugin 0.7.1, 162 pruebas en verde, PHPCS en 0; probado en el Local y en Chrome el 2026-11-19):**

- [x] "Marcar como completo" sin JS: acción `angelitozzz_completar_leccion` con el mismo permiso que la ruta REST (`angelitozzz_cursos_puede_completar()`); con JS sigue sin recargar
- [x] Nombre del PDF descargado sin "Privado" (`angelitozzz_cursos_nombre_descarga()`)
- [x] Aviso "Este curso no está en tu cuenta" en la landing y en `/soluciones-de-sueno/` (`?aviso=sin-acceso` también desde un curso; `angelitozzz_cursos_aviso_acceso()`)
- [x] `video-portada` pide la dirección del reproductor al plugin (`angelitozzz_cursos_video_url()`): cambiar de proveedor de video toca solo `includes/video.php`
- [x] Constructor sin ventanas nativas: el enlace del editor es una fila bajo la barra (campo URL, validación, "Quitar enlace", Enter y Escape) y "¿Descartar los cambios?" es una vista del mismo modal ("Seguir editando" / "Descartar cambios"), sin modales apilados. `cdp-constructor.mjs` falla si aparece un diálogo nativo
- [x] Hallazgos de la verificación de `cursos-datos.md` (2026-11-17):
    - [x] El producto del curso (oculto del catálogo) ya no sale en el mapa del sitio (ni `product_cat` ni `product_tag`) ni en las consultas de productos por la REST API, como la búsqueda de la Store API (plugin 0.7.0, bloque 1)
    - [x] Aviso "Este curso no está en tu cuenta" (`cursos-datos.md` → `acceso`): la landing y `/soluciones-de-sueno/` lo muestran (bloque 2)
    - [x] Criterio de `leccion` sobre el proveedor de video: se reescribe (la lógica está en `includes/video.php`; los textos pueden nombrar a Vimeo). `video-portada.php` ya pide la dirección al plugin (bloque 2)
    - [x] Rol al desactivar (decisión del usuario): la desactivación quita el rol `instructora` y la capacidad, y la activación los vuelve a crear (plugin 0.5.1, `angelitozzz-cursos.php`, con su prueba; 106 pruebas en verde)
- [x] Por decidir (idea del usuario, 2026-11-19): cuando una solución no tiene curso, mostrar "Comprar ahora" desactivado con un texto como "Curso en preparación", en vez de llevar a un carrito vacío. Falta el texto y si "Regalar este curso" también cambia Decidido (usuario, 2026-11-20), va en la rama del tema del cierre: botón "Próximamente" desactivado, sin precio ni "Regalar este curso"; en Horarios Ideales la etapa sin curso queda desactivada con "En preparación"; las tarjetas del listado y los relacionados llevan la etiqueta "En preparación". Textos fijos en el tema. Hecho (2026-11-24, rama `feat/solucion-proximamente`): `angelitozzz_solucion_a_la_venta()` (`inc/woocommerce/precios.php`) decide si hay algún curso a la venta. Sin él, el hero y la galería dicen "Próximamente" desactivado y además se ocultan el enlace de canje y el cupón. En el modal de la etapa, el botón dice "En preparación". La etiqueta "En preparación" reemplaza a la del cliente en la tarjeta (decisión del usuario, 2026-11-24). Probado en el Local sin cursos, con `datos-f5.php tienda` (también `cdp-compra.mjs`) y con una etapa sin curso.

### Cierre de la F5

- [x] Decidir (pendiente del usuario, 2026-11-15): aceptar el nombre de usuario en `/iniciar-sesion/`. El servidor ya lo acepta (`wp_authenticate()`); solo hay que cambiar el campo de tipo `email` a texto, la etiqueta "Correo electrónico" por "Correo o nombre de usuario" (se aparta de Figma) y el error a "Correo, usuario o contraseña incorrectos". Sirve sobre todo al equipo: el usuario de las alumnas se genera del correo. Spec: `iniciar-sesion.md` Decidido (usuario, 2026-11-20): no. `/iniciar-sesion/` sigue solo con correo, como Figma.
- [x] `check.mjs` de design-import y de wp-acf en 0, y JSON de ACF regenerado. Hecho (2026-11-24, rama `docs/checks-en-cero`), con carlitos-skills 1.41.0, la versión instalada (`~/.claude/plugins/installed_plugins.json`).
    - **Aclaración:** las cuentas viejas (11 o 12 errores en design-import, 16 en wp-acf) y la nota del 2026-11-24 que decía que `generate.mjs` rompía la relación con los cursos, la URL `/soluciones-de-sueno/` e `inc/acf/paginas.php` venían de correr por error los scripts de la 1.39.0, que siguen en la caché de plugins. La 1.41.0 ya entiende `Ubicación: ninguna` y `Origen: plugin`.
    - **design-import:** pasó de 10 errores a 0.
        - `cursos-datos.md`: cada `Destino` es una ruta relativa al tema (`../../plugins/angelitozzz-cursos/includes/<archivo>.php`), así el check comprueba que el archivo existe. La nota "(migración 1)" pasó al `Comportamiento`.
        - `home.md` → cta-final: la nota de Figma está completa en "Indicaciones del diseño".
    - **wp-acf:** pasó de 1 error a 0. `opciones.md` → contacto: el `Destino` son los archivos que leen el grupo desde la parte 1 (`inc/helpers/whatsapp.php`, `inc/helpers/contacto.php` y `template-parts/modals/modal-coach.php`).
    - **JSON:** `generate.mjs` cambió solo las 2 ayudas del grupo `solucion` (etiqueta sin precio y `{precio}` en `beneficios.bajada`). Los otros 20 archivos y `inc/acf/paginas.php` quedaron iguales. Se sincronizó en el Local con `wp acf json sync`.
    - **Avisos que quedan:** 74 en design-import y 1 en wp-acf, ninguno bloquea.
        - Los 5 nuevos son "texto fijo en el destino": el check lee el código PHP del plugin y de los helpers como si fuera texto.
        - Los de "Argumentos" vienen de valores entre comillas invertidas que el revisor toma como argumentos.
        - El de wp-acf sobre "Escríbenos" en Contacto no aplica: el título sale del campo y solo el texto del botón "Escríbenos por WhatsApp" queda fijo, como dice el spec.
- [x] Pruebas en Chrome (`specs/pruebas/`). Hecho (2026-11-24, rama `chore/pruebas-chrome`). Lo pendiente era: desde la F5-b, `cdp-compra.mjs` cubría las landings, el drawer, las etapas, el carrito y el checkout sin pagar, y faltaban gracias, canje, formularios, modales y filtros. Además `cdp-vacios.mjs:36` usaba una usuaria que ningún script creaba, faltaba el script de limpieza que nombra `cdp-bloque4.mjs:2` y había puertos repetidos (9339 y 9341).
    - **Scripts nuevos:** `cdp-gracias`, `cdp-formularios`, `cdp-modales` y `cdp-filtros`, sobre la base común `lib/cdp.mjs`. Los 4 pasan en el Local y terminan con código 1 si algo falla.
        - `cdp-gracias`: pagado, pendiente, regalo con "Copiar código" y cancelado; canje sin sesión, inválido, válido y repetido. El banner de Mundo se probó poniendo un momento un enlace en Ajustes del sitio; hoy ese enlace está vacío (F7) y la prueba lo avisa.
        - `cdp-formularios`: los 3 formularios con errores, éxito y el correo en Mailpit.
        - `cdp-modales`: coach, drawer, acordeón (también sin JS) y, con `tienda`, la etapa y el selector.
        - `cdp-filtros`: blog (pastilla, búsqueda, `noindex`, sin JS) y testimonios. La paginación del blog y el "Ver más" de soluciones no se pudieron probar: ningún listado pasa de 12.
    - **Datos (`datos-f5.php`):**
        - `crear` suma los pedidos de gracias (con el código del regalo), las usuarias `vacia-f5` y `canje-f5`, y vacía los contadores de intentos.
        - Las inscripciones de los cursos B y C usaban el origen `manual`, que el plugin quitó en la 0.7.3: fallaban sin aviso y la alumna quedaba con 1 curso en vez de 3. Ahora usan `compra`.
        - Nuevas limpiezas: `limpiar-f4` y `limpiar-formularios`.
    - **Scripts anteriores:**
        - Puertos únicos: `cdp-vacios` pasa al 9340 y `cdp-constructor` al 9342.
        - `cdp-bloque6` comprobaba la API con la alumna por un 400 que no probaba el permiso: WordPress valida los parámetros antes. Ahora prueba con su nonce y un cuerpo válido y recibe 403.
        - `cdp-compra`, `cdp-bloque4`, `cdp-bloque5`, `cdp-bloque6` y `cdp-vacios` corren bien con los datos nuevos.
    - **Lo que queda en el Local:** los datos de prueba quedaron borrados y comprobados con WP-CLI. Solo quedan los correos de prueba en Mailpit.
- [x] Plugin: prueba con HPOS activado y prueba del registro `wc_get_logger` (hoy ninguna); prueba de la desinstalación (`cursos-datos.md` → `roles`) Hecho (plugin 0.7.3, 2026-11-20). `test_puente_con_y_sin_hpos` (las tablas de HPOS se crean en `tests/bootstrap.php`), `test_alta_y_baja_de_acceso_quedan_en_el_registro_de_woo` y `tests/test-desinstalar.php`.
- [x] Plugin: al desactivarlo, `flush_rewrite_rules()` (`angelitozzz-cursos.php:72`) corre en la misma petición en que el plugin ya registró sus reglas, así que `/cursos/…`, `/cuenta/…` y `/administrador/…` quedan guardadas. Hay que quitarlas antes de vaciar (revisión del 2026-11-18) Hecho (plugin 0.7.3, 2026-11-20). `angelitozzz_cursos_quitar_reglas_de_url()` quita los tipos y las reglas propias (también las del tipo curso que WordPress ya hubiera armado en esa petición) antes de vaciar; con su prueba.
- [x] Tema: el enlace de WhatsApp se arma igual en 4 lugares. Se pasa todo a un solo helper: ya existía `angelitozzz_solucion_whatsapp()` (`inc/soluciones.php:25`). Hecho (2026-11-24, rama `fix/cierre-f5-detalles`). Ahora es `angelitozzz_whatsapp()` en `inc/helpers/whatsapp.php` y lo usan todas las páginas. Solo el modal coach arma su enlace aparte, con otro número (`whatsapp_coach`). Los lugares eran:
    - `pages/template-preguntas-frecuentes.php:21-26`
    - `template-parts/pages/contacto/seccion-de-cta.php:20-25`
    - `template-parts/pages/mundo-angelitozzz/hero.php:22-26`, que además lee `opciones_contacto_whatsapp` como campo suelto, distinto del resto del tema (revisión del 2026-11-18)
- [x] Tema: detalles de la revisión del 2026-11-18. Hecho (2026-11-24, rama `fix/cierre-f5-detalles`). "Made with" con `esc_html__()`. El título del modal coach se imprime con `angelitozzz_highlight()` arriba del formulario, en la línea de la X, y el `<h2>` oculto lleva el texto plano. El enlace del coach sale de `angelitozzz_link_attrs()` y el JS solo cambia el `href`. Lo de `_n()` del motor queda como aviso para la skill wp-query:
    - **Textos sin `__()`:** `footer.php:118` "Made with". Los de `header.php` ("Carrito"), `solucion/hero.php` ("Precio de lanzamiento (antes…", "Desde") y `solucion/galeria.php` (" USD") se arreglaron en la F5-b.
    - **Modal coach:** el título es un textarea, pero `template-parts/modals/modal-coach.php:17,29` lo pasa como `title` del modal sin `angelitozzz_highlight`, así que pierde los saltos de línea y los colores.
    - **Enlace de WhatsApp del modal coach:** `src/js/modules/modal-coach.js:39-40` escribe `target` y `rel` a mano; el aviso `sr-only` sí está.
    - **Motor de wp-query:** `inc/query/motor.php:1072` usa `_n()` sin text domain. Es un archivo del motor: se avisa en la skill wp-query, no se edita.
- [x] Revisión de seguridad de la fase (`/security-review` de Claude Code, sobre una rama temporal con todos los cambios de la F5). Hecho (2026-11-24).
    - **Tema** (`2f2e999..main`): sin hallazgos.
    - **Plugin** (`4065a0b..main`, PR #4 a #9): sin hallazgos sobre el umbral. Revisó el checkout, el canje (un solo uso, con bloqueo), los reembolsos, los nonces y permisos, la privacidad y el SQL.
    - Por debajo del umbral (confianza 7, media) quedó uno real: el código de regalo (`ANGZ-` y 4 caracteres, unos 10⁶ códigos) se podía adivinar probando cupones por `?wc-ajax=apply_coupon`, que no tiene límite. Corregido en el plugin 0.7.4 (rama `fix/codigo-regalo-largo` en los dos repos):
        - el código es `ANGZ-XXXX-XXXX-XXXX` (60 bits);
        - el canje lo acepta con o sin guiones;
        - un código del formato anterior se sigue canjeando;
        - 3 pruebas nuevas (185 en verde, PHPCS en 0).
      En el tema: el placeholder del canje y el código en gracias, que en móvil baja de tamaño para entrar en una línea (probado a 390, 360 y 320 px). `cdp-gracias` pasa con el código nuevo.
    - `/code-review` queda opcional: la auditoría completa de la F7 lo cubre.
- [x] Página 404 (2026-11-25, propuesta sin Figma, `specs/error-404.md`): `404.php` con la tarjeta `confirmacion-tarjeta`, "Ir al inicio" y "Ver soluciones de sueño". Antes caía en `index.php` sin diseño. Probada en Chrome a 1440, 390 y 320 px
- [x] Buscador general de WordPress (`/?s=`) redirige con 301 al Blog con `?q=` (2026-11-25, `inc/busqueda.php`, `blog.md`): un solo buscador, el del blog

## F6 · Pagos y Angelita Pro

- [ ] Openpay en modo prueba con las compras de la verificación (con 3D Secure y webhook, que necesitan URL pública)
- [ ] Angelita Pro cada 4 meses con 7 días de prueba: el plugin de Openpay no soporta WooCommerce Subscriptions; decidir entre renovación manual con enlace de pago o un desarrollo propio con la tarjeta guardada (o las suscripciones de la API de Openpay)
- [ ] Openpay en producción: credenciales de producción cargadas por el usuario y webhook registrado con la URL del sitio

## F7 · Cierre

- [ ] QA en escritorio, tablet y móvil
- [ ] Auditoría de seguridad completa (movida desde la F3, decisión del usuario, 2026-11-19): plugin completo y plantillas del tema, con todo ya integrado (cuentas, compra, regalo y canje, lección, administrador, Angelita Pro). Con la skill `cloudflare/security-audit-skill` (https://github.com/cloudflare/security-audit-skill; leer su código antes de instalarla); si no sirve para PHP/WordPress, otra equivalente. Antes de correrla, preguntar al usuario el perfil y el tope de agentes (usa muchos subagentes). Cada hallazgo se corrige con su prueba
- [ ] Revisión de seguridad final (`/security-review` de todo el plugin y las plantillas)
- [ ] Manual de la clienta con wp-manual (incluye el administrador de cursos)
- [ ] Cuenta de Bárbara en producción: usuaria propia con su correo y rol administradora (no la de la agencia). Con ella entra a `/wp-admin/` (contenido) y a `/administrador/` (cursos)
- [ ] Texto real de Términos y condiciones y de Políticas de privacidad (hoy con relleno; lo pone el usuario). En producción, crear las dos páginas y elegirlas en WooCommerce → Ajustes → Avanzado y en Ajustes → Privacidad
- [ ] Logo del sitio en Apariencia → Personalizar → Identidad del sitio (decisión del usuario, 2026-11-17: no se carga desde `inc/acf/sitio.php`)
- [ ] Ajustes del sitio: enlace del mes gratis en Mundo (nas.io), número de WhatsApp y URLs de las redes de la clienta; cursos de cada solución en `solucion_compra_cursos` (en las etapas, también el de cada etapa). También la URL de Instagram del botón "Síguenos" de Inicio (`home_seccion_instagram_boton`, hoy con `#`, pendiente)
- [x] Fluent SMTP instalado y activo en el Local (2026-11-25). En el Local los correos los recibe Mailpit
- [ ] Fluent SMTP en producción: configurar su conexión (las claves van en su pantalla, no en el repo) y mandar un correo de prueba (pedido, regalo, recuperar contraseña y formularios)
- [ ] Yoast SEO (más adelante, decisión del usuario, 2026-11-25)
- [ ] Decidir dónde se guardan los PDF privados de materiales (pendiente desde el 2026-11-17). Hoy van en `wp-content/uploads/angelitozzz-privado/` y dependen de una regla `deny` de Nginx, o de su equivalente en Apache, en Local y en producción. Si la regla falta, los PDF quedan públicos.
    - **Opción preferida:** una carpeta fuera de la raíz pública, que no necesita regla en el servidor.
    - **Toca el plugin:** `includes/descargas.php` (dónde se guardan y cómo se entregan), `uninstall.php`, una migración que mueva los PDF que ya existan y sus pruebas.
    - **Antes de decidir,** confirmar que el hosting de producción deja escribir fuera de `public_html`.
    - **Si no deja,** se mantiene la regla y se comprueba el 403 en producción.
- [ ] Deploy:
    - [ ] Enlaces permanentes en "Nombre de la entrada" (Ajustes → Enlaces permanentes). Las URL del plugin (`/administrador/…`, `/cuenta/perfil/`, `/cursos/<curso>/<leccion>/`, `/cursos/descargar/<id>/`) usan reglas de reescritura y no funcionan con los enlaces simples. El JS del administrador ya no depende de esto: arma las URL de la API sobre la base de `rest_url()` (F4, bloque 6b).
    - [ ] Tema: `pnpm build` y subir el tema con `dist/`, sin `hot`, `node_modules/`, `src/`, `specs/` ni configs.
    - [ ] Plugin `angelitozzz-cursos`: subirlo sin tests ni configs de desarrollo.
    - [ ] (Opcional, recomendado) Cron real del hosting: `define('DISABLE_WP_CRON', true)` en `wp-config.php` y una tarea del hosting que llame a `wp-cron.php` cada 5 a 15 minutos. Sin ella, las tareas solo corren cuando alguien visita. El acceso no depende de esto, porque cada lectura compara la fecha (`includes/vencimiento.php`). Lo que se atrasa es la tarea diaria (marcar vencidas y limpiar los contadores de intentos) y el reintento del puente a los 5 minutos.

## Verificación (repetir antes de cada deploy)

Las herramientas para probar en el Local (datos de prueba, pruebas en Chrome y cómo armar el entorno en otra PC) están en `specs/pruebas/LEEME.md`.

- [ ] `php -l`, PHPCS y PHPUnit del plugin, `pnpm build` y los `check.mjs` de design-import y wp-acf en 0
- [ ] Compra de 1 curso → inscripción con su vencimiento
- [ ] Horarios Ideales con 2 y con 3 etapas, con el descuento correcto
- [ ] Compra con cupón
- [ ] Regalo: genera el código, el canje inscribe y el acceso empieza ese día (2026-11-19 en el Local: ok)
- [ ] Angelita Pro: prueba de 7 días y renovación
- [ ] Reembolso: el pedido reembolsado quita el acceso
- [ ] Vencimiento: un acceso vencido no abre la lección y figura "Vencido" en el historial
- [ ] Sin acceso: la lección y el PDF de materiales no se abren (ni cambiando el ID en la URL)
- [ ] Inicio de sesión: se bloquea tras varios intentos y no revela si el correo existe
- [ ] Constructor: una alumna no puede usar la API ni ver el administrador
- [ ] Avance y materiales en la lección; banner de Mundo solo en la primera lección
- [ ] Correo de pedido con el bloque de Mundo y sin él (regalo) (2026-11-19 en el Local: ok)
- [ ] Prueba de actualización: Woo y Openpay actualizados en el Local, plantillas al día en Woo → Estado y en `specs/plan/sobrescrituras.md`
