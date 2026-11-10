# Checklist: cursos, compra y cuentas

Avance del plan de cursos (Tutor LMS + WooCommerce + ACF). Una rama y un PR por fase. Se marca `- [x]` al terminar y verificar cada punto. Es documentación del tema: no se sube al servidor.

## Decisiones tomadas

| Tema | Decisión |
| --- | --- |
| Cursos y lecciones | Tutor LMS (gratis ahora, Pro más adelante) |
| Cobro | WooCommerce como motor de Tutor; pasarela Izipay o Niubiz, en USD y que guarde tarjetas |
| Landing de venta | Tipo de contenido `solucion` con secciones ACF flexibles, unida a 1 o más cursos de Tutor |
| URL del listado | `/soluciones-de-sueno/` (y cada solución en `/soluciones-de-sueno/<slug>/`) |
| Angelita Pro | Suscripción cada 4 meses con 7 días de prueba (WooCommerce Subscriptions + Tutor Pro) |
| Garantía de 7 días | Devolución: se paga al momento; si se reembolsa el pedido, se quita el acceso |
| Regalo | Código de regalo (cupón de Woo de 100 %, un solo uso, 6 meses) que se canjea en la web |
| Administrador de cursos | Escritorio y lista de cursos propios; crear y editar con el constructor oficial de Tutor, con la marca |
| Contenido relacionado | Automático: 3 soluciones distintas de la actual |
| Textos de plugins | Filtro `gettext` en `inc/textos-plugins.php` (sin Loco Translate) |

## Reglas de mantenimiento

- Nunca editar archivos de plugins. Orden de preferencia: hooks y filtros → CSS → sobrescribir una plantilla.
- Cada plantilla sobrescrita (`woocommerce/`, `tutor/`) conserva todos sus `do_action()` y se anota en `specs/sobrescrituras.md`.
- La lógica propia va en `inc/tutor/`, `inc/woocommerce/` e `inc/regalos/`, con el prefijo `angelitozzz_`. Con Woo, solo `WC_Order` y `WC_Product` (compatibles con HPOS).
- Antes de actualizar Woo o Tutor en producción: actualizar en el Local, revisar Woo → Estado → plantillas y `specs/sobrescrituras.md`, y repetir las compras de prueba.

## F0 · Preparación

- [ ] Instalar Tutor LMS (gratis) en el Local
- [ ] Tutor → Ajustes → Monetización: WooCommerce
- [ ] Ocultar `/tienda/` de Woo y redirigirla a `/soluciones-de-sueno/`
- [ ] Elegir la pasarela (Izipay o Niubiz) y revisar su plugin de Woo:
    - [ ] Cobra en USD
    - [ ] Tiene modo de prueba
    - [ ] Guarda tarjetas (tokenización) compatible con WooCommerce Subscriptions
    - [ ] Funciona con checkout clásico y/o en bloques
- [ ] Crear los cursos de prueba en Tutor, cada uno con su producto de Woo (Simple + Virtual + For Tutor)
- [ ] `specs/_shared.md`: "Contenido dinámico" con `solucion` (cpt), `curso` (Tutor) y `producto` (Woo, solo cobro)
- [ ] `specs/_shared.md`: tabla WooCommerce (carrito clásico, checkout clásico, mini-carrito en drawer, cuenta en Tutor)
- [ ] Crear `specs/sobrescrituras.md`

## F1 · Specs desde Figma

- [ ] Soluciones de sueño: actualizar el spec al tipo `solucion` (listado `archive-solucion.php`)
- [ ] Landing Angelita Pro
- [ ] Landing A Dormir Por Fin
- [ ] Landing Horarios Ideales (con modal de etapas y modal "Selecciona la edad")
- [ ] Landing Recién Nacidos Go
- [ ] Biblioteca de layouts de la landing (los que se repiten entre cursos van una sola vez)
- [ ] Drawer del carrito
- [ ] Carrito
- [ ] Checkout
- [ ] Página de gracias (con bloque del mes gratis en Mundo)
- [ ] Regalo: compra exitosa con código, "¿Te regalaron un curso?" y "Regalo canjeado"
- [ ] Iniciar sesión, crear cuenta y recuperar contraseña
- [ ] Cuenta de la alumna: escritorio, mi perfil (datos y seguridad), cursos inscritos, historial de pedidos
- [ ] Cursos inscritos: proponer las vistas "Inscritos" y "Completados" (siguiendo "Activos")
- [ ] Vista de lección (temario, video, Resumen, Materiales, banner de Mundo, mensaje de Angelita Pro, cita "PD")
- [ ] Administrador de cursos: escritorio y lista de cursos de Bárbara
- [ ] Comportamiento en móvil propuesto en cada spec (no hay frames móviles)
- [ ] Todos los specs aprobados en `specs/_index.md`

## F2 · Maquetación

- [x] Listado de Soluciones de sueño maquetado (provisional en `woocommerce/archive-product.php`, rama `feat/soluciones-de-sueno`)
- [ ] Mover el listado a `archive-solucion.php` reutilizando `shop.php` y `producto-card`
- [ ] `single-solucion.php` + un archivo por layout en `template-parts/solucion/`
- [ ] Modales con wp-modal: drawer del carrito, etapas, selector de edad
- [ ] Preguntas frecuentes de cada landing con wp-accordion
- [ ] Carrito, checkout y gracias en `woocommerce/`
- [ ] Regalo: pantallas de compra, canje y canjeado
- [ ] Login, registro y recuperar en `tutor/`
- [ ] Cuenta de la alumna en `tutor/dashboard/`
- [ ] Vista de lección en `tutor/`
- [ ] Escritorio y lista de cursos de Bárbara en `tutor/dashboard/`
- [ ] Constructor de Tutor con los colores de la marca (ajustes de Tutor + CSS acotado)

## F3 · Administrable (wp-acf)

- [ ] Tipo de contenido `solucion` (archivo `/soluciones-de-sueno/`)
- [ ] Campos de la tarjeta: etiqueta, ícono, edad, beneficios (la foto es la imagen destacada)
- [ ] Relación `cursos` con los cursos de Tutor
- [ ] `tipo_compra`: directa, selector de etapas o suscripción
- [ ] `precios_por_cantidad` (repetidor)
- [ ] `secciones` con Flexible Content y su biblioteca de layouts
- [ ] Texto de la garantía de 7 días en la landing
- [ ] Ajustes del sitio: enlace y textos del mes gratis en Mundo, textos del regalo
- [ ] Contenido inicial (seed) de las 4 soluciones
- [ ] Listado y landings leen ACF, sin texto fijo

## F4 · Conexión

- [ ] wp-query: listado de soluciones (12 por página, "Ver más")
- [ ] wp-woocommerce: soporte del tema, sobrescrituras con `@version`, CSS clásico de Woo quitado
- [ ] wp-woocommerce: mini-carrito que se actualiza solo (drawer)
- [ ] Borrar `wp-content/mu-plugins/angelitozzz-preview-woo.php`
- [ ] `inc/tutor/`: curso sin comprar → su solución; inscrita → la lección
- [ ] `inc/tutor/`: precio de la landing leído del producto de Woo (precio de lanzamiento = precio rebajado)
- [ ] `inc/tutor/`: banner de Mundo solo en la primera lección de cada curso (no en Angelita Pro)
- [ ] `inc/tutor/`: mensaje inicial de Angelita Pro sobre el video
- [ ] `inc/tutor/`: cita "PD" (formato "Cita" del editor + CSS)
- [ ] `inc/tutor/`: pedido reembolsado → se quita la inscripción (comprobar si Tutor lo hace solo)
- [ ] `inc/woocommerce/`: "Agregar al carrito" sin recargar y abre el drawer
- [ ] `inc/woocommerce/`: el selector de etapas agrega varios cursos
- [ ] `inc/woocommerce/`: descuento por cantidad leído de ACF
- [ ] `inc/woocommerce/`: campo de cupón en el hero
- [ ] `inc/woocommerce/`: bloque de Mundo en gracias y en el correo de pedido, excepto en regalos
- [ ] `inc/regalos/`: comprar como regalo genera el cupón (100 %, ese producto, un uso, 6 meses)
- [ ] `inc/regalos/`: canje con sesión o creando la cuenta, inscripción con la API de Tutor, cupón marcado como usado
- [ ] `inc/textos-plugins.php`: "Exercise files" → "Materiales" y demás textos de los plugins
- [ ] Relacionados automáticos: 3 soluciones distintas de la actual
- [ ] `specs/sobrescrituras.md` al día

## F5 · Pagos y Pro

- [ ] Pasarela en modo prueba
- [ ] Comprar Tutor LMS Pro
- [ ] Vencimiento de inscripción: 4, 6, 12 meses o ilimitado (también en el regalo: el acceso empieza el día del canje)
- [ ] WooCommerce Subscriptions + complemento de suscripciones de Tutor Pro
- [ ] Angelita Pro: cada 4 meses con 7 días de prueba, renovación con la tarjeta guardada
- [ ] Pasarela en producción

## F6 · Cierre

- [ ] QA en escritorio, tablet y móvil
- [ ] Manual de la clienta con wp-manual
- [ ] Deploy: `pnpm build`, subir `dist/` y los PHP (sin `hot`, `node_modules/`, `src/` ni configs)

## Verificación (repetir antes de cada deploy)

- [ ] `php -l`, `pnpm build` y los `check.mjs` de design-import y wp-acf en 0
- [ ] Compra de 1 curso
- [ ] Horarios Ideales con 2 y con 3 etapas, con el descuento correcto
- [ ] Compra con cupón
- [ ] Regalo: genera el código, el canje inscribe y el acceso empieza ese día
- [ ] Angelita Pro: prueba de 7 días y renovación
- [ ] Reembolso: el pedido reembolsado quita el acceso
- [ ] Inscripción, progreso y materiales en la lección
- [ ] Banner de Mundo solo en la primera lección
- [ ] Correo de pedido con el bloque de Mundo y sin él (regalo)
- [ ] Prueba de actualización: Woo y Tutor actualizados en el Local, plantillas al día en Woo → Estado y en `specs/sobrescrituras.md`
