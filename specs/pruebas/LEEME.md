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
- **Cursos y compras:** 3 cursos publicados con lecciones, un curso en borrador, un producto ligado y dos pedidos (pagado y pendiente).

Son usuarias que solo existen en el Local mientras dura la prueba; no son claves reales.

```powershell
cd "~/Local Sites/angelitozzz/app/public"
cp wp-content/themes/angelitozzz/specs/pruebas/datos-f5.php zz-datos-f5.php
php -d mysqli.default_port=10090 zz-datos-f5.php crear
# ... probar ...
php -d mysqli.default_port=10090 zz-datos-f5.php borrar
Remove-Item zz-datos-f5.php
```

- Guarda los IDs en la opción `angelitozzz_prueba_f5`; `borrar` solo borra lo que creó `crear`.
- Los avisos `material: No tienes permiso…` son normales: el script corre sin usuaria y no puede subir los PDF. Los materiales se prueban desde el constructor.
- Los cursos que se creen a mano en el constructor **no** los borra `borrar`: se borran desde el administrador (`/administrador/cursos/` → Eliminar) o desde `/wp-admin/` → Cursos, junto con su producto en WooCommerce.
- **Nunca** borrar adjuntos con una consulta por "padre" que pueda quedar vacía: `post_parent__in` con `0` devuelve todas las imágenes sin padre de la biblioteca (pasó el 2026-11-15 y hubo que recuperarlas del respaldo). Listar primero los IDs y borrar solo esos.

## Pruebas en Chrome (CDP)

Chrome sin ventana, por el protocolo de depuración. Se corren desde esta carpeta con los datos de prueba creados:

| Script | Qué prueba | Ojo |
| --- | --- | --- |
| `cdp-bloque4.mjs` | Acceso: crear cuenta, iniciar sesión, recuperar contraseña, enlace vencido y bloqueo por intentos | Crea `prueba-f4@angelitozzz.local` y **bloquea la IP a propósito** (6 intentos fallidos). Al terminar: borrar esa usuaria y los transitorios de abajo |
| `cdp-bloque5.mjs` | Cuenta de la alumna (4 vistas) y lecciones | Usa la alumna de `datos-f5.php` |
| `cdp-vacios.mjs` | La cuenta sin cursos ni pedidos | |
| `cdp-bloque6.mjs [lista]` | Administrador: acceso, escritorio y lista; con `lista`, el menú y las acciones publicar, borrador y eliminar | Con `lista` cambia los datos de prueba |
| `cdp-constructor.mjs <carpeta temporal>` | Constructor de punta a punta: paso 1 con error del servidor y portada, módulo, lecciones con video de Vimeo, texto y PDF, orden, eliminar y publicar | Crea un curso de cero (escribe un PNG y un PDF en la carpeta temporal): borrarlo aparte |

- **Entrar siempre con la contraseña correcta:** con 5 fallos en 15 minutos el plugin bloquea la IP (también a la administradora).
- **Para desbloquear:** borrar los transitorios `_transient_angelitozzz_lim_%` y `_transient_timeout_angelitozzz_lim_%` de `wp_options`.
- **Un navegador tiene una sola sesión.** Para probar dos cuentas a la vez, una ventana normal y otra privada.

## Base de datos del Local

MySQL de Local: `127.0.0.1:10090`, `root` / `root`, base `local`, prefijo `wp_` (`mysql.exe` en `%APPDATA%/Local/lightning-services/mysql-8.4.0+2/bin/win64/bin/`). Los scripts PHP usan `-d mysqli.default_port=10090` porque `DB_HOST` es `localhost`.

## Entorno en otra PC

1. **Sitio:** importarlo con All-in-One WP Migration (trae el tema, el plugin `angelitozzz-cursos`, las subidas y la base).
2. **PHP por línea de comandos** (archivos en `entorno/`; cambiar `<USUARIO>` y la versión de PHP de Local):
    - `php-cli.ini` → `~/Local Sites/angelitozzz/wp-tests/php-cli.ini`;
    - `php` y `composer` (Git Bash) y `php.bat` y `composer.bat` (PowerShell) → `~/bin/`, con `~/bin` en el `PATH`;
    - `composer.phar` (Composer 2.x de getcomposer.org, verificando su SHA-256) → `~/bin/`.
3. **Pruebas PHPUnit del plugin:** la suite de WordPress, el núcleo de pruebas, la base `local_tests` y el `phpunit.xml` local se arman como dice el README del plugin (`wp-content/plugins/angelitozzz-cursos/README.md` → "Entorno de pruebas en el Local"). Después: `composer install`, `composer test` y `composer lint` desde la carpeta del plugin.
4. **Nginx:** pegar `entorno/nginx-angelitozzz-privado.conf` en `~/Local Sites/angelitozzz/conf/nginx/site.conf.hbs` (protege los PDF privados; comprobar el 403).
5. **Revisor de specs:** `node ~/.claude/plugins/cache/carlitos-skills/carlitos-skills/<versión>/skills/design-import/scripts/check.mjs` desde el tema (necesita las skills de carlitos-skills instaladas).
