# Pruebas del tema y del plugin (Local)

Herramientas para probar en el Local lo de la F4 (acceso, cuenta, lección, administrador y constructor). Son documentación del proyecto: `specs/` **no se sube al servidor**, y `datos-f5.php` nunca debe quedar en un servidor (crea usuarias y cursos).

Antes venían de `~/Local Sites/angelitozzz/wp-tests/f4-herramientas/` (fuera del repo). Desde el 2026-11-15 viven aquí para no perderlas al cambiar de PC.

## Requisitos

- Local con el sitio `angelitozzz` encendido y `pnpm dev` corriendo en el tema (o sin el archivo `hot`, con `pnpm build`).
- Node 24 (los `cdp-*.mjs` usan el `WebSocket` nativo) y Google Chrome.
- PHP de Local por línea de comandos: ver "Entorno en otra PC".
- Variables opcionales de los `cdp-*.mjs`: `SITIO` (por defecto `http://angelitozzz.local`) y `CHROME` (por defecto `C:/Program Files/Google/Chrome/Application/chrome.exe`).

## Datos de prueba: `datos-f5.php`

Crea con las funciones del plugin:

- **Alumna:** `prueba-f5@angelitozzz.local` / `Prueba123`.
- **Instructora:** `prueba-instructora@angelitozzz.local` / `Prueba123`.
- **Cursos y compras:** 3 cursos publicados con lecciones, un curso en borrador y un producto ligado al curso A, con 4 pedidos de la alumna: pagado, pendiente, pagado como regalo (el plugin genera su código) y cancelado.
- **Usuarias sin cursos:** `vacia-f5@angelitozzz.local` (estados vacíos de la cuenta) y `canje-f5@angelitozzz.local` (la que canjea el regalo), las dos con `Prueba123`.
- **Para las pruebas en Chrome:** deja las URL de gracias de cada pedido, el código del regalo y si Ajustes del sitio tiene el enlace del mes gratis en Mundo en `%TEMP%/angelitozzz-datos-f5.json` (fuera del tema; `borrar` lo quita). `crear` y `borrar` vacían también los contadores del límite de intentos (`wp_angelitozzz_intentos`), para que las pruebas se puedan repetir.

Son usuarias que solo existen en el Local mientras dura la prueba; no son claves reales.

```powershell
cd "~/Local Sites/angelitozzz/app/public"
cp wp-content/themes/angelitozzz/specs/pruebas/datos-f5.php zz-datos-f5.php
php zz-datos-f5.php crear
# ... probar ...
php zz-datos-f5.php borrar
Remove-Item zz-datos-f5.php
```

- El puerto de MySQL lo toma solo del `sites.json` de Local. Para forzarlo: `ANGELITOZZZ_DB_PUERTO=<puerto>` o `php -d mysqli.default_port=<puerto>`.

- Guarda los IDs en la opción `angelitozzz_prueba_f5`; `borrar` solo borra lo que creó `crear` (también el cupón del regalo y la inscripción que deja el canje).
- Los avisos `material: No tienes permiso…` son normales: el script corre sin usuaria y no puede subir los PDF. Los materiales se prueban desde el constructor.
- Los cursos que se creen a mano en el constructor **no** los borra `borrar`: se borran desde el administrador (`/administrador/cursos/` → Eliminar) o desde `/wp-admin/` → Cursos, junto con su producto en WooCommerce.
- **Tienda (F5-b):** `php zz-datos-f5.php tienda` crea un curso con su producto (por el constructor del plugin: virtual y vender individualmente) para A Dormir Por Fin ($399), Recién Nacidos Go y Angelita Pro ($97, lanzamiento $67) y uno de $37 por etapa de Horarios Ideales, y los liga a las soluciones. Guarda en `angelitozzz_prueba_tienda` los metadatos que tenían las soluciones; `tienda-borrar` los devuelve y borra esos cursos, lecciones, productos e inscripciones, y los pedidos que tengan esos productos (lista los IDs antes). Independiente de `crear`/`borrar`.
- **Limpiezas sueltas:** `limpiar-f4` borra las usuarias `prueba-f4*@angelitozzz.local` que crea `cdp-bloque4.mjs` y vacía los contadores de intentos; `limpiar-formularios` borra los envíos guardados por `cdp-formularios.mjs` (solo los de `prueba-formularios@angelitozzz.local`; los correos quedan en Mailpit).
- **Nunca** borrar adjuntos con una consulta por "padre" que pueda quedar vacía: `post_parent__in` con `0` devuelve todas las imágenes sin padre de la biblioteca (pasó el 2026-11-15 y hubo que recuperarlas del respaldo). Listar primero los IDs y borrar solo esos.

## Pruebas en Chrome (CDP)

Chrome sin ventana, por el protocolo de depuración. Se corren desde esta carpeta (`node <script>`) con los datos que pide cada uno. Cada script usa su propio puerto, así que se pueden correr a la vez. Los nuevos (desde el 2026-11-24) usan la base común `lib/cdp.mjs` (abrir Chrome, navegar, teclas y clics reales, avisos de PHP, iniciar sesión, comprobaciones) y terminan con código 1 si algo falla; los anteriores llevan su propia copia de ese código y solo imprimen lo que vieron.

| Script                                         | Qué prueba                                                                                                                                                                                                                                                                         | Ojo                                                                                                                                                                                                                                                                                                                    |
| ---------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `cdp-bloque4.mjs` | Acceso: crear cuenta, iniciar sesión, recuperar contraseña, enlace vencido y bloqueo por intentos | Crea `prueba-f4@angelitozzz.local` y **bloquea la IP a propósito** (6 intentos fallidos). Al terminar: `php zz-datos-f5.php limpiar-f4`. Puerto 9337 |
| `cdp-bloque5.mjs` | Cuenta de la alumna (4 vistas) y lecciones | Necesita `datos-f5.php crear`. Puerto 9338 |
| `cdp-vacios.mjs` | La cuenta sin cursos ni pedidos | Necesita `datos-f5.php crear` (usuaria `vacia-f5`). Puerto 9340 |
| `cdp-bloque6.mjs [lista]` | Administrador: acceso, escritorio y lista; con `lista`, el menú y las acciones publicar, borrador y eliminar. La alumna no puede usar la API del constructor: con su nonce y un cuerpo válido recibe 403 (sin ellos WordPress responde 400 por los parámetros, que no prueba el permiso) | Necesita `datos-f5.php crear`. Con `lista` cambia los datos de prueba. Puerto 9339 |
| `cdp-compra.mjs <carpeta para capturas>`       | Compra sin pagar (F5-b): precio de Woo en la landing, agregar y abrir el drawer, contador del header, selector de etapas con el descuento, regalo, quitar desde el drawer, cupón del hero, carrito y checkout (campos, prefijo, flecha de "Pagar ahora", pasarela) y avisos de PHP | Necesita `datos-f5.php tienda`. Sin sesión: deja un carrito en la sesión de Woo de ese Chrome. Puerto 9341                                                                                                                                                                                                             |
| `cdp-pago.mjs <carpeta> [aprobada\|rechazada]` | Checkout lleno sin sesión y pago con Openpay; registra las peticiones a Openpay                                                                                                                                                                                                    | **No llega a pagar**: el sandbox de Openpay responde 403 al Chrome sin ventana. El pago se prueba a mano en una ventana de incógnito: landing de Recién Nacidos Go → Ir a pagar → datos de prueba → tarjeta `4111 1111 1111 1111`, `12/30`, CVV `123` (aprobada en el sandbox de Perú, 2026-11-18). Más tarjetas abajo |
| `cdp-constructor.mjs <carpeta temporal>`       | Constructor de punta a punta: paso 1 con error del servidor y portada, módulo, lecciones con video de Vimeo, texto y PDF, enlace del editor (inválido, válido, quitar, Escape), "¿Descartar los cambios?", orden, eliminar y publicar. Si aparece una ventana nativa del navegador, queda en `dialogosNativos` (falla)                                                                                                                               | Crea un curso de cero (escribe un PNG y un PDF en la carpeta temporal): borrarlo aparte. Puerto 9342 |
| `cdp-gracias.mjs` | Gracias por tu compra: pagado (con el banner de Mundo si Ajustes del sitio tiene su enlace; si no, comprueba que no salga y lo avisa), pendiente, regalo (código y "Copiar código" con un clic real) y cancelado ("Pedido sin efecto"). Canje: sin sesión lleva a crear la cuenta; con `canje-f5`, un código inválido da error, el del regalo lleva a "Regalo canjeado" y el curso aparece en Mis cursos, y el mismo código otra vez da error | Necesita `datos-f5.php crear`. **Gasta el código del regalo:** para repetir, `borrar` y `crear`. Puerto 9344 |
| `cdp-formularios.mjs` | Contacto, newsletter del pie y coach del modal: enviado vacío marca los obligatorios (`aria-invalid`) y pone el foco en el primero; bien lleno muestra el éxito, el coach pasa a "gracias" con el nombre y el correo llega a Mailpit (por su API, con el puerto del `sites.json`) | Sin datos de prueba. Espera los 3 s mínimos del anti-spam; wp-form deja 10 envíos por hora por IP y formulario. Al terminar: `php zz-datos-f5.php limpiar-formularios`. Puerto 9345 |
| `cdp-modales.mjs` | Coach y drawer del carrito vacío: se abren como modal, el foco entra, Esc los cierra, el foco vuelve al botón y el clic en el fondo los cierra. Acordeón de Preguntas frecuentes: la primera abierta, Enter y Espacio abren y cierran, y sin JS sigue andando. Con `datos-f5.php tienda`, también el modal de una etapa y el selector de edad (sin esos datos lo salta y lo dice) | Puerto 9346 |
| `cdp-filtros.mjs` | Blog: la pastilla lleva al archivo de su categoría y queda marcada; la búsqueda `q` no recarga, lleva `noindex` y canonical al blog, y sin JS da lo mismo. Testimonios: los radios filtran sin recargar, "Todos" vuelve al total y sin JS el filtro por URL da lo mismo, con `noindex`. Paginación del blog y "Ver más" de soluciones solo si el contenido pasa de 12 (si no, los salta y lo dice) | Sin datos de prueba. Puerto 9347 |

### Tarjetas de prueba de Openpay Perú (sandbox)

Fuente: https://documents.openpay.pe/documentacion/pruebas/ (solo con las llaves de prueba). Vencimiento: cualquier fecha futura (`12/30`). CVV: 3 dígitos en Visa y Mastercard (`123`), **4 en Amex** (`1234`). Cada intento con el checkout recién cargado.

| Número                                 | Marca       | Resultado                                             |
| -------------------------------------- | ----------- | ----------------------------------------------------- |
| `4111111111111111`, `4242424242424242` | Visa        | Aprobada                                              |
| `5555555555554444`, `5105105105105100` | Mastercard  | Aprobada                                              |
| `345678000000007`, `341111111111111`   | Amex        | Aprobada                                              |
| `4444444444444448` / `370000000000002` | Visa / Amex | 3003 fondos insuficientes (probada 2026-11-18)        |
| `4222222222222220` / `340000000000009` | Visa / Amex | 3001 rechazada por el banco (Visa probada 2026-11-18) |
| `4000000000000069` / `373737373737374` | Visa / Amex | 3002 tarjeta expirada                                 |
| `4000000000000119`                     | Visa        | 3004 tarjeta robada                                   |
| `4000000000000044`                     | Visa        | 3005 antifraude                                       |

Con un rechazo, Woo se queda en el checkout con el aviso de error y el pedido queda "Fallido", sin inscripción. Se puede reintentar con otra tarjeta sin recargar: se reintenta el mismo pedido.

Errores de datos o de integración vistos:

| HTTP | error_code | Descripción                        | Causa                                                                                     |
| ---- | ---------- | ---------------------------------- | ----------------------------------------------------------------------------------------- |
| 400  | 1001       | cvv2 length must be 4 digits       | Amex con CVV de 3 dígitos                                                                 |
| 422  | 2004       | Dígito verificador inválido (Luhn) | Número mal escrito (un dígito de más)                                                     |
| 422  | 1003       | This source_id was already used    | Botón fuera del form → se reenviaba el token. Corregido en `checkout-boton.js` (18/11/26) |

- **Entrar siempre con la contraseña correcta:** con 5 fallos en 15 minutos el plugin bloquea la IP (también a la administradora).
- **Para desbloquear:** vaciar la tabla de contadores, `DELETE FROM wp_angelitozzz_intentos;` (desde el plugin 0.6.1 los intentos viven ahí, no en transitorios). Los contadores vencen solos: 15 minutos el login, 1 hora crear cuenta y 15 minutos el canje.
- **Un navegador tiene una sola sesión.** Para probar dos cuentas a la vez, una ventana normal y otra privada.

## Base de datos del Local

MySQL de Local: `127.0.0.1:<puerto>`, `root` / `root`, base `local`, prefijo `wp_`. El puerto y la versión de MySQL cambian en cada PC: están en Local → el sitio → Database (por ejemplo, `10090` con `mysql-8.4.0+2` o `10222` con `mysql-8.0.35+4`); `mysql.exe` está en `%APPDATA%/Local/lightning-services/mysql-<versión>/bin/win64/bin/`. `DB_HOST` es `localhost` sin puerto: los scripts y las pruebas leen el puerto del `sites.json` de Local (`%APPDATA%/Local/sites.json`).

## Entorno en otra PC

All-in-One WP Migration solo lleva `app/public/` y la base: **no** lleva `.git`, `~/bin`, `~/Local Sites/angelitozzz/wp-tests/` ni `conf/nginx/`. Por eso:

1. **Base y subidas:** importar el sitio con All-in-One WP Migration en Local, y encender el sitio.
2. **Código, siempre por git**, porque la copia importada no trae `.git`:
    - **Tema:** `git clone` de `agencia-nerd/angelitozzz` en `wp-content/themes/angelitozzz`. Si ya había una copia, se aparta antes.
    - **Plugin:** `git clone` de `agencia-nerd/angelitozzz-cursos` en `wp-content/plugins/angelitozzz-cursos`.
    - En una PC que ya los tiene clonados, basta con `git pull` en los dos.
3. **Entorno**, desde el tema:

    ```bash
    node specs/pruebas/entorno/instalar.mjs             # revisa (no cambia nada)
    node specs/pruebas/entorno/instalar.mjs --instalar  # arma lo que falte
    ```

    El script lee de Local el usuario, la versión de PHP y de MySQL y el puerto, y arma:
    - `~/bin`: `php`, `php.bat`, `composer`, `composer.bat` y `composer.phar` con su SHA-256 verificado. Los `.bat` quedan con CRLF.
    - `wp-tests/php-cli.ini`.
    - La suite de WordPress en el tag del sitio.
    - El núcleo de pruebas.
    - La base `local_tests`.
    - La regla de Nginx de los PDF privados.
    - `vendor/` del plugin.

    Lo que reemplaza lo deja al lado como `.viejo-<fecha>`: nunca borra. Solo avisa si `~/bin` no está en el `PATH`, y de paso te da el comando para agregarlo. Si agregó la regla de Nginx, reinicia el sitio en Local y vuelve a correrlo para comprobar el 403.

4. **Comprobar:** `composer test` y `composer lint` desde la carpeta del plugin.
5. **Revisor de specs:** `node ~/.claude/plugins/cache/carlitos-skills/carlitos-skills/<versión>/skills/design-import/scripts/check.mjs` desde el tema (necesita las skills de carlitos-skills instaladas).

**Lo que vive solo en la base o en `app/public/` (no viaja por git, sí con All-in-One):** por eso la exportación de All-in-One se hace **después** del último cambio en el admin.

- Plugin "Openpay Cards" 3.2.0 y sus llaves de sandbox (WooCommerce → Ajustes → Pagos → Openpay). El archivo `.wpress` lleva esas llaves: no se comparte. En un sitio nuevo, el plugin se instala y las llaves las carga el usuario (nunca en el repo ni en el chat).
- Ajustes de Woo: moneda USD con el símbolo a la izquierda, "próximamente" apagado, Carrito y Finalizar compra con shortcode (opción `angelitozzz_woo_paginas`), página de términos.
- Páginas `/terminos-y-condiciones/` y `/politicas-de-privacidad/` (texto de relleno) y la página de privacidad en Ajustes → Privacidad.
- Contenido de ACF (soluciones, Ajustes del sitio) y las páginas del tema.
- Datos de prueba: ninguno (borrados el 2026-11-19, al cerrar la F5-c). Para probar compras, `datos-f5.php tienda`.

Si algo falla después de una importación (por ejemplo, el puerto de MySQL cambió), basta con volver a correr el script sin `--instalar`: dice qué está mal.

Las plantillas de `entorno/` (`php`, `php.bat`, `composer`, `composer.bat`, `php-cli.ini` y `nginx-angelitozzz-privado.conf`) las usa el script. No hace falta copiarlas a mano.
