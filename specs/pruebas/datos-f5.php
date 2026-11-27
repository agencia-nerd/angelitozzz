<?php
/*
 * Datos de prueba del bloque 5 (F4) en el Local: crea la página /cuenta/, una alumna, 3 cursos con
 * lecciones, video y materiales (archivos privados), un producto de Woo ligado al curso A, un pedido pagado (el puente del
 * plugin inscribe) y uno pendiente. Guarda los IDs en la opción angelitozzz_prueba_f5 para borrarlos.
 * Uso: php zz-datos-f5.php crear|borrar
 *
 * F5-b, compra: "tienda" crea un curso con su producto (por el constructor del plugin: virtual y vender
 * individualmente) para cada solución de venta directa y uno por etapa de Horarios Ideales, y los liga a las
 * soluciones (solucion_compra_cursos y el curso de cada etapa). Guarda los metadatos que tenían las soluciones en la
 * opción angelitozzz_prueba_tienda; "tienda-borrar" los devuelve y borra los cursos, sus lecciones y productos, y
 * los pedidos de esos productos. Lista los IDs antes de borrar.
 * Uso: php zz-datos-f5.php tienda|tienda-borrar
 *
 * "crear" también hace los pedidos de gracias (pagado, pendiente, regalo con su código y cancelado) y la usuaria
 * vacia-f5@angelitozzz.local (cuenta vacía y canje); "borrar" los quita junto con el cupón del regalo.
 * F5-d: "archivos" crea la administradora prueba-admin-f5@angelitozzz.local (Prueba123) para cdp-archivos-admin.mjs;
 * "archivos-borrar" lista y borra los archivos privados que subió y a ella.
 * F5-e: "video-landing" pone un enlace de Vimeo en la galería de Angelita Pro y en el modal de la primera
 * etapa de Horarios Ideales (cdp-landing-video.mjs); "video-landing-borrar" devuelve los valores.
 * Limpiezas sueltas: "limpiar-f4" (usuarias de cdp-bloque4.mjs y contadores del límite de intentos) y
 * "limpiar-formularios" (envíos guardados por cdp-formularios.mjs con prueba-formularios@angelitozzz.local).
 * El puerto de MySQL se lee del sites.json de Local (cambia en cada PC). Para forzarlo:
 * ANGELITOZZZ_DB_PUERTO=<puerto> o php -d mysqli.default_port=<puerto>.
 */
// Solo por línea de comandos: copiado en la raíz pública, no se ejecuta por URL.
if (PHP_SAPI !== 'cli') {
    exit(1);
}

// DB_HOST es "localhost" (sin puerto): el puerto va por mysqli.default_port.
if (getenv('ANGELITOZZZ_DB_PUERTO')) {
    ini_set('mysqli.default_port', getenv('ANGELITOZZZ_DB_PUERTO'));
} elseif ((int) ini_get('mysqli.default_port') === 3306 || !ini_get('mysqli.default_port')) {
    $sitio   = strtolower(str_replace('\\', '/', dirname(__DIR__, 2))); // zz-datos-f5.php está en app/public
    $casa    = (string) (getenv('USERPROFILE') ?: getenv('HOME'));
    $locales = json_decode((string) @file_get_contents(getenv('APPDATA') . '/Local/sites.json'), true) ?: [];
    foreach ($locales as $local) {
        $ruta = (string) ($local['path'] ?? '');
        $ruta = strtolower(rtrim(str_replace('\\', '/', str_starts_with($ruta, '~') ? $casa . substr($ruta, 1) : $ruta), '/'));
        if ($ruta === $sitio && !empty($local['services']['mysql']['ports']['MYSQL'][0])) {
            ini_set('mysqli.default_port', (string) $local['services']['mysql']['ports']['MYSQL'][0]);
        }
    }
    if ((int) ini_get('mysqli.default_port') === 3306) {
        fwrite(STDERR, "No encontré el puerto de MySQL del sitio en sites.json de Local: usa ANGELITOZZZ_DB_PUERTO=<puerto>.\n");
        exit(1);
    }
}

$_SERVER['HTTP_HOST'] = 'angelitozzz.local';
define('WP_USE_THEMES', false);
require __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/user.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$accion = $argv[1] ?? 'crear';

// F5-d, bloque 3: una administradora de prueba para la pantalla "Archivos privados" (cdp-archivos-admin.mjs).
// "archivos-borrar" lista y borra los archivos privados que ella subió y después a ella.
if ($accion === 'archivos' || $accion === 'archivos-borrar') {
    $admin = get_user_by('email', 'prueba-admin-f5@angelitozzz.local');

    if ($accion === 'archivos-borrar') {
        if (!$admin) { echo "no hay administradora de prueba\n"; exit; }
        $archivos = get_posts(['post_type' => 'angelitozzz_archivo', 'post_status' => 'any', 'author' => $admin->ID, 'posts_per_page' => -1, 'fields' => 'ids']);
        echo "se borran: administradora ", $admin->ID, ", archivos ", wp_json_encode(array_map('intval', $archivos)), "\n";
        wp_set_current_user($admin->ID);
        foreach ($archivos as $archivo_id) {
            angelitozzz_cursos_borrar_archivo_del_disco((int) $archivo_id);
            wp_delete_post((int) $archivo_id, true);
        }
        wp_delete_user($admin->ID);
        echo "borrado\n";
        exit;
    }

    if ($admin) { echo "ya existe la administradora de prueba: ", $admin->ID, "\n"; exit; }
    $id = wp_insert_user(['user_login' => 'prueba-admin-f5', 'user_email' => 'prueba-admin-f5@angelitozzz.local', 'user_pass' => 'Prueba123', 'first_name' => 'Admin', 'last_name' => 'Prueba', 'role' => 'administrator']);
    echo is_wp_error($id) ? $id->get_error_message() : "administradora de prueba: " . $id, "\n";
    exit;
}

// F5-e (antes F5-d, bloque 5): un video público de Vimeo en la galería de Angelita Pro (primera diapositiva) y
// en el modal de la primera etapa de Horarios Ideales, para cdp-landing-video.mjs (campos oembed de ACF). Guarda
// los valores que tenían esos campos en la opción angelitozzz_prueba_video_landing; "video-landing-borrar" los
// devuelve (y borra el adjunto de Medios si quedó uno de la versión anterior de este script).
if ($accion === 'video-landing' || $accion === 'video-landing-borrar') {
    $guardado = get_option('angelitozzz_prueba_video_landing', []);

    if ($accion === 'video-landing-borrar') {
        if (!$guardado) { echo "no hay video de landing de prueba\n"; exit; }
        echo "se devuelven ", wp_json_encode($guardado['antes']), "\n";
        foreach ($guardado['antes'] as $clave => $valor) {
            [$post_id, $meta] = explode('|', $clave);
            update_post_meta((int) $post_id, $meta, $valor);
        }
        echo "se quitan: ", wp_json_encode($guardado['nuevas'] ?? []), "\n";
        foreach ($guardado['nuevas'] ?? [] as $clave) {
            [$post_id, $meta] = explode('|', $clave);
            delete_post_meta((int) $post_id, $meta);
        }
        if (!empty($guardado['adjunto'])) { wp_delete_attachment((int) $guardado['adjunto'], true); }
        delete_option('angelitozzz_prueba_video_landing');
        echo "borrado\n";
        exit;
    }

    if ($guardado) { echo "ya está el video de landing de prueba: ", wp_json_encode(array_keys($guardado['antes'])), "\n"; exit; }
    $vimeo = 'https://vimeo.com/76979871'; // Video público de la cuenta oficial de Vimeo.

    $campos = [
        'angelita-pro'     => '/^solucion_secciones_bloques_\d+_diapositivas_0_video$/',
        'horarios-ideales' => '/^solucion_secciones_bloques_\d+_items_0_modal_video$/',
    ];
    $antes = [];
    foreach ($campos as $slug => $patron) {
        $solucion = get_page_by_path($slug, OBJECT, 'solucion');
        if (!$solucion) { echo "no está la solución $slug\n"; continue; }
        foreach (array_keys(get_post_meta($solucion->ID)) as $meta) {
            if (preg_match($patron, $meta)) {
                $antes[$solucion->ID . '|' . $meta] = (string) get_post_meta($solucion->ID, $meta, true);
                update_post_meta($solucion->ID, $meta, $vimeo);
            }
        }
    }
    // La galería de Angelita Pro tiene una sola diapositiva: se agrega una segunda (la misma imagen, sin video)
    // para probar que el video se detiene al cambiar de diapositiva. Se quita en "video-landing-borrar".
    $nuevas = [];
    $pro    = get_page_by_path('angelita-pro', OBJECT, 'solucion');
    foreach ($pro ? array_keys(get_post_meta($pro->ID)) : [] as $meta) {
        if (!preg_match('/^(solucion_secciones_bloques_\d+_diapositivas)$/', $meta, $m) || (int) get_post_meta($pro->ID, $meta, true) !== 1) {
            continue;
        }
        $antes[$pro->ID . '|' . $meta] = '1';
        update_post_meta($pro->ID, $meta, 2);
        foreach (['imagen', 'video'] as $campo) {
            $origen_meta = $m[1] . '_0_' . $campo;
            $destino     = $m[1] . '_1_' . $campo;
            update_post_meta($pro->ID, $destino, $campo === 'imagen' ? get_post_meta($pro->ID, $origen_meta, true) : '');
            update_post_meta($pro->ID, '_' . $destino, get_post_meta($pro->ID, '_' . $origen_meta, true));
            $nuevas[] = $pro->ID . '|' . $destino;
            $nuevas[] = $pro->ID . '|_' . $destino;
        }
    }
    update_option('angelitozzz_prueba_video_landing', ['adjunto' => 0, 'antes' => $antes, 'nuevas' => $nuevas], false);
    echo "video de landing: $vimeo en ", wp_json_encode(array_keys($antes)), "\n";
    echo "landings: ", get_permalink(get_page_by_path('angelita-pro', OBJECT, 'solucion')), " y ", get_permalink(get_page_by_path('horarios-ideales', OBJECT, 'solucion')), "\n";
    exit;
}

// F6, renovación de Angelita Pro: "renovar [días]" (necesita "tienda") crea la alumna renovar-f5@angelitozzz.local
// (Prueba123) con una compra pagada de "Angelita Pro (prueba)" que vence en esos días (5 por defecto; negativo = ya
// venció). Después se corre la tarea con "renovar-avisar" (los correos van a Mailpit). "renovar-borrar" lista y borra
// sus pedidos y a ella (el plugin borra sus inscripciones y su avance).
if (in_array($accion, ['renovar', 'renovar-avisar', 'renovar-borrar'], true)) {
    $renovar = get_option('angelitozzz_prueba_renovar', []);

    if ($accion === 'renovar-avisar') {
        echo "correos enviados: ", angelitozzz_cursos_avisar_renovaciones(), "\n";
        exit;
    }
    if ($accion === 'renovar-borrar') {
        if (!$renovar) { echo "no hay datos de renovar\n"; exit; }
        $pedidos = wc_get_orders(['customer_id' => (int) $renovar['usuaria'], 'limit' => -1, 'return' => 'ids', 'status' => array_keys(wc_get_order_statuses())]);
        echo "se borran: ", wp_json_encode(['usuaria' => $renovar['usuaria'], 'pedidos' => $pedidos]), "\n";
        foreach ($pedidos as $pedido_id) { wc_get_order($pedido_id)->delete(true); }
        wp_delete_user((int) $renovar['usuaria']);
        delete_option('angelitozzz_prueba_renovar');
        echo "borrado\n";
        exit;
    }

    $tienda = get_option('angelitozzz_prueba_tienda', []);
    $curso_id = 0;
    foreach ((array) ($tienda['cursos'] ?? []) as $id) {
        if (get_the_title((int) $id) === 'Angelita Pro (prueba)') { $curso_id = (int) $id; }
    }
    if (!$curso_id) { echo "falta la tienda: php zz-datos-f5.php tienda\n"; exit(1); }
    if ($renovar) { echo "ya hay datos de renovar: usuaria ", (int) $renovar['usuaria'], "\n"; exit; }
    update_post_meta($curso_id, '_angelitozzz_tipo', 'suscripcion'); // Por si la tienda se creó antes de la F6.

    $usuaria = wp_insert_user(['user_login' => 'renovar-f5', 'user_email' => 'renovar-f5@angelitozzz.local', 'user_pass' => 'Prueba123', 'first_name' => 'Renata', 'last_name' => 'Prueba', 'role' => 'customer']);
    if (is_wp_error($usuaria)) { echo $usuaria->get_error_message(), "\n"; exit(1); }

    $pedido = wc_create_order(['customer_id' => $usuaria]);
    $pedido->add_product(wc_get_product(angelitozzz_cursos_producto_de($curso_id)), 1);
    $pedido->set_billing_first_name('Renata');
    $pedido->set_billing_email('renovar-f5@angelitozzz.local');
    $pedido->calculate_totals();
    $pedido->save();
    $pedido->update_status('completed'); // El puente inscribe.

    // La compra empezó hace tiempo y vence en N días (o ya venció).
    $dias = (int) ($argv[2] ?? 5);
    global $wpdb;
    $wpdb->update(
        angelitozzz_cursos_tabla_inscripciones(),
        ['inicio' => gmdate('Y-m-d H:i:s', time() - 115 * DAY_IN_SECONDS), 'vence' => gmdate('Y-m-d H:i:s', time() + $dias * DAY_IN_SECONDS), 'estado' => $dias > 0 ? 'activa' : 'vencida'],
        ['usuario_id' => $usuaria, 'curso_id' => $curso_id]
    );
    update_option('angelitozzz_prueba_renovar', ['usuaria' => $usuaria, 'curso' => $curso_id], false);
    echo "alumna renovar-f5@angelitozzz.local (", $usuaria, "), pedido ", $pedido->get_id(), ", vence en ", $dias, " días\n";
    echo "renovar: ", angelitozzz_cursos_url_renovar($curso_id, $usuaria), "\n";
    exit;
}

if ($accion === 'tienda' || $accion === 'tienda-borrar') {
    $tienda = get_option('angelitozzz_prueba_tienda', []);

    if ($accion === 'tienda-borrar') {
        if (!$tienda) { echo "no hay datos de la tienda\n"; exit; }
        echo "se borran: ", wp_json_encode(['cursos' => $tienda['cursos'], 'lecciones' => $tienda['lecciones'], 'productos' => $tienda['productos']]), "\n";
        // Pedidos de los productos de prueba (las compras hechas en el navegador).
        foreach (wc_get_orders(['limit' => -1, 'return' => 'ids', 'status' => array_keys(wc_get_order_statuses())]) as $pedido_id) {
            $pedido = wc_get_order($pedido_id);
            foreach ($pedido->get_items() as $item) {
                if (in_array((int) $item->get_product_id(), $tienda['productos'], true)) {
                    echo "pedido ", $pedido_id, "\n";
                    $pedido->delete(true);
                    continue 2;
                }
            }
        }
        global $wpdb;
        foreach ($tienda['cursos'] as $curso_id) {
            $wpdb->delete($wpdb->prefix . 'angelitozzz_inscripciones', ['curso_id' => (int) $curso_id]);
            $wpdb->delete($wpdb->prefix . 'angelitozzz_progreso', ['curso_id' => (int) $curso_id]);
        }
        foreach (array_merge($tienda['productos'], $tienda['lecciones'], $tienda['cursos']) as $id) { wp_delete_post((int) $id, true); }
        // Metadatos de las soluciones como estaban (null = no existía).
        foreach ($tienda['meta'] as $post_id => $metas) {
            foreach ($metas as $clave => $valor) {
                $valor === null ? delete_post_meta((int) $post_id, $clave) : update_post_meta((int) $post_id, $clave, $valor);
            }
        }
        delete_option('angelitozzz_prueba_tienda');
        echo "borrado\n";
        exit;
    }

    if ($tienda) { echo "ya hay datos de la tienda: ", wp_json_encode($tienda['cursos']), "\n"; exit; }
    $tienda = ['cursos' => [], 'lecciones' => [], 'productos' => [], 'meta' => []];

    // Guarda el valor actual de un metadato de una solución (una sola vez) y lo cambia.
    $poner = static function (int $post_id, string $clave, $valor) use (&$tienda): void {
        if (!isset($tienda['meta'][$post_id]) || !array_key_exists($clave, $tienda['meta'][$post_id])) {
            $tienda['meta'][$post_id][$clave] = metadata_exists('post', $post_id, $clave) ? get_post_meta($post_id, $clave, true) : null;
        }
        update_post_meta($post_id, $clave, $valor);
    };
    // Curso publicado con una lección y su producto (precio normal y de lanzamiento).
    $curso = static function (string $titulo, int $meses, string $precio, string $lanzamiento, string $tipo = 'curso_online') use (&$tienda): int {
        $id = wp_insert_post(['post_type' => 'curso', 'post_status' => 'publish', 'post_title' => $titulo, 'post_excerpt' => 'Curso de prueba de la F5-b.']);
        update_post_meta($id, '_angelitozzz_tipo', $tipo);
        update_post_meta($id, '_angelitozzz_duracion_acceso', $meses);
        update_post_meta($id, '_angelitozzz_modulos', [['id' => 'm1', 'titulo' => 'Módulo 1']]);
        $leccion = wp_insert_post(['post_type' => 'leccion', 'post_status' => 'publish', 'post_parent' => $id, 'post_title' => 'Bienvenida', 'post_content' => '<p>Primera lección.</p>', 'menu_order' => 1]);
        update_post_meta($leccion, '_angelitozzz_modulo', 'm1');
        $producto = angelitozzz_cursos_guardar_producto($id, $titulo, $precio, $lanzamiento);
        $tienda['cursos'][]    = $id;
        $tienda['lecciones'][] = $leccion;
        $tienda['productos'][] = $producto;
        return $id;
    };

    $precios = [
        'a-dormir-por-fin'  => ['A Dormir Por Fin (prueba)', 12, '399', ''],
        'recien-nacidos-go' => ['Recién Nacidos Go (prueba)', 4, '97', '67'],
        'angelita-pro'      => ['Angelita Pro (prueba)', 4, '97', '67', 'suscripcion'], // Se renueva a mano (F6).
    ];
    foreach (get_posts(['post_type' => 'solucion', 'post_status' => 'publish', 'posts_per_page' => -1]) as $solucion) {
        $tipo = (string) get_post_meta($solucion->ID, 'solucion_compra_tipo', true);
        if ($tipo !== 'etapas' && isset($precios[$solucion->post_name])) {
            $poner($solucion->ID, 'solucion_compra_cursos', [(string) $curso(...$precios[$solucion->post_name])]);
        }
        if ($tipo === 'etapas') {
            // El bloque `etapas` del flexible solucion_secciones_bloques: un curso de $37 por etapa.
            $bloques = (array) get_post_meta($solucion->ID, 'solucion_secciones_bloques', true);
            $indice  = array_search('etapas', $bloques, true);
            if ($indice === false) { continue; }
            $items = (int) get_post_meta($solucion->ID, "solucion_secciones_bloques_{$indice}_items", true);
            for ($i = 0; $i < $items; $i++) {
                $poner($solucion->ID, "solucion_secciones_bloques_{$indice}_items_{$i}_curso", $curso('Horarios Ideales etapa ' . ($i + 1) . ' (prueba)', 6, '37', ''));
            }
        }
    }

    update_option('angelitozzz_prueba_tienda', $tienda, false);
    echo "creado: ", wp_json_encode(['cursos' => $tienda['cursos'], 'productos' => $tienda['productos']]), "\n";
    exit;
}

$ids = get_option('angelitozzz_prueba_f5', []);

// Limpieza de cdp-bloque4.mjs: sus usuarias (prueba-f4*@angelitozzz.local) y los contadores del límite
// de intentos (ese script bloquea la IP a propósito). Lista antes de borrar.
if ($accion === 'limpiar-f4') {
    global $wpdb;
    // get_users solo admite el comodín al principio o al final: se busca "prueba-f4*" y se filtra el dominio.
    $usuarias = array_values(array_map('intval', array_keys(array_filter(
        array_column(get_users(['search' => 'prueba-f4*', 'search_columns' => ['user_email'], 'fields' => ['ID', 'user_email']]), 'user_email', 'ID'),
        static fn($correo) => str_ends_with((string) $correo, '@angelitozzz.local')
    ))));
    echo "usuarias: ", wp_json_encode($usuarias), "\n";
    foreach ($usuarias as $id) {
        $wpdb->delete($wpdb->prefix . 'angelitozzz_inscripciones', ['usuario_id' => (int) $id]);
        $wpdb->delete($wpdb->prefix . 'angelitozzz_progreso', ['usuario_id' => (int) $id]);
        wp_delete_user((int) $id);
    }
    echo "intentos borrados: ", (int) $wpdb->query("DELETE FROM {$wpdb->prefix}angelitozzz_intentos"), "\n";
    exit;
}

// Limpieza de cdp-formularios.mjs: solo los envíos guardados con el correo de prueba.
if ($accion === 'limpiar-formularios') {
    $envios = get_posts(['post_type' => ANGELITOZZZ_ENTRY_TYPE, 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'meta_key' => '_angelitozzz_email', 'meta_value' => 'prueba-formularios@angelitozzz.local']);
    echo "envíos: ", wp_json_encode($envios), "\n";
    foreach ($envios as $id) { wp_delete_post((int) $id, true); }
    exit;
}

if ($accion === 'borrar') {
    foreach ((array) ($ids['pedidos'] ?? []) as $id) {
        $pedido = wc_get_order($id);
        if ($pedido) { $pedido->delete(true); }
    }
    // Cupón del regalo, la usuaria vacía y la del canje (con la inscripción que le deja el canje).
    foreach ((array) ($ids['cupones'] ?? []) as $id) { wp_delete_post((int) $id, true); }
    foreach (['vacia', 'canje'] as $clave) {
        if (empty($ids[$clave])) { continue; }
        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'angelitozzz_inscripciones', ['usuario_id' => (int) $ids[$clave]]);
        $wpdb->delete($wpdb->prefix . 'angelitozzz_progreso', ['usuario_id' => (int) $ids[$clave]]);
        wp_delete_user((int) $ids[$clave]);
    }
    foreach ((array) ($ids['productos'] ?? []) as $id) { wp_delete_post($id, true); }
    foreach ((array) ($ids['adjuntos'] ?? []) as $id) { wp_delete_attachment($id, true); }
    // Archivos privados (F5-d): del disco y su registro.
    foreach ((array) ($ids['archivos'] ?? []) as $id) {
        angelitozzz_cursos_borrar_archivo_del_disco((int) $id);
        wp_delete_post((int) $id, true);
    }
    foreach ((array) ($ids['lecciones'] ?? []) as $id) { wp_delete_post($id, true); }
    foreach ((array) ($ids['cursos'] ?? []) as $id) { wp_delete_post($id, true); }
    if (!empty($ids['alumna'])) {
        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'angelitozzz_inscripciones', ['usuario_id' => (int) $ids['alumna']]);
        $wpdb->delete($wpdb->prefix . 'angelitozzz_progreso', ['usuario_id' => (int) $ids['alumna']]);
        wp_delete_user((int) $ids['alumna']);
    }
    if (!empty($ids['instructora'])) { wp_delete_user((int) $ids['instructora']); }
    delete_option('angelitozzz_prueba_f5');
    @unlink(sys_get_temp_dir() . '/angelitozzz-datos-f5.json');
    $GLOBALS['wpdb']->query("DELETE FROM {$GLOBALS['wpdb']->prefix}angelitozzz_intentos");
    echo "borrado: ", wp_json_encode($ids), "\n";
    exit;
}

if ($ids) { echo "ya hay datos de prueba: ", wp_json_encode($ids), "\n"; exit; }
$ids = ['cursos' => [], 'lecciones' => [], 'archivos' => [], 'productos' => [], 'pedidos' => []];

// Contadores del límite de intentos en cero (login, crear cuenta y canje), para que las pruebas se puedan repetir.
$GLOBALS['wpdb']->query("DELETE FROM {$GLOBALS['wpdb']->prefix}angelitozzz_intentos");

// Página /cuenta/ (queda: en producción la crea wp-acf en la F5).
if (!get_page_by_path('cuenta')) {
    $ids['pagina_cuenta'] = wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'cuenta', 'post_title' => 'Mi cuenta', 'meta_input' => ['_wp_page_template' => 'pages/template-cuenta.php']]);
}

// Alumna.
$alumna = wp_insert_user(['user_login' => 'prueba-f5', 'user_email' => 'prueba-f5@angelitozzz.local', 'user_pass' => 'Prueba123', 'first_name' => 'Ana Lucía', 'last_name' => 'Prueba', 'role' => 'customer']);
$ids['alumna'] = $alumna;

$curso = function (string $titulo, string $tipo, int $meses, bool $mundo, array $modulos, string $extracto) use (&$ids): int {
    $id = wp_insert_post(['post_type' => 'curso', 'post_status' => 'publish', 'post_title' => $titulo, 'post_excerpt' => $extracto]);
    update_post_meta($id, '_angelitozzz_tipo', $tipo);
    update_post_meta($id, '_angelitozzz_duracion_acceso', $meses);
    update_post_meta($id, '_angelitozzz_mes_mundo', $mundo);
    update_post_meta($id, '_angelitozzz_modulos', $modulos);
    $ids['cursos'][] = $id;
    return $id;
};
$leccion = function (int $curso_id, string $titulo, string $modulo, int $orden, string $texto, array $extra = []) use (&$ids): int {
    $id = wp_insert_post(['post_type' => 'leccion', 'post_status' => 'publish', 'post_parent' => $curso_id, 'post_title' => $titulo, 'post_content' => $texto, 'menu_order' => $orden]);
    update_post_meta($id, '_angelitozzz_modulo', $modulo);
    foreach ($extra as $clave => $valor) { update_post_meta($id, $clave, $valor); }
    $ids['lecciones'][] = $id;
    return $id;
};

// Curso A: en progreso, con mes gratis en Mundo, video, "PD:" y materiales.
$a = $curso('Curso de prueba F5', 'curso_online', 4, true, [['id' => 'm1', 'titulo' => 'Primeros pasos'], ['id' => 'm2', 'titulo' => 'Rutinas']], 'Curso de prueba para revisar la cuenta y la lección.');
$texto = "<p>Bienvenida al curso. En este video te cuento <strong>cómo funciona</strong> todo.</p>\n<p>Revisa tu correo: ahí está el acceso a tu mes gratis.</p>\n<p>PD: si tienes dudas, escríbeme por WhatsApp.</p>";
$a1 = $leccion($a, 'Video 1: bienvenida', 'm1', 1, $texto);
$a2 = $leccion($a, 'Video 2: el sueño del recién nacido', 'm1', 2, '<p>Lección de vista previa.</p>', ['_angelitozzz_vista_previa' => true]);
$a3 = $leccion($a, 'Video 3: la rutina de la noche', 'm2', 1, '<p>Tercera lección.</p>');
$a4 = $leccion($a, 'Video 4: siestas', 'm2', 2, '<p>Cuarta lección.</p>');

// F5-d: video y materiales como archivos privados del plugin (angelitozzz_cursos_registrar_archivo() revisa
// el tipo real y los mueve al almacén). "borrar" los quita del disco y de la base.
$archivo = function (string $bytes, string $nombre, string $uso) use (&$ids): int {
    $temporal = wp_tempnam($nombre);
    file_put_contents($temporal, $bytes);
    $id = angelitozzz_cursos_registrar_archivo($temporal, $nombre, $uso);
    if (is_wp_error($id)) {
        echo "archivo $nombre: ", $id->get_error_message(), "\n";
        @unlink($temporal);
        return 0;
    }
    $ids['archivos'][] = $id;
    return $id;
};
$zip = function (array $partes): string {
    $ruta = wp_tempnam('office.zip');
    $z    = new ZipArchive();
    $z->open($ruta, ZipArchive::OVERWRITE);
    foreach ($partes as $nombre => $contenido) { $z->addFromString($nombre, $contenido); }
    $z->close();
    $bytes = (string) file_get_contents($ruta);
    @unlink($ruta);
    return $bytes;
};
$tipos_ooxml = static fn(string $parte, string $tipo): string => '<?xml version="1.0"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Override PartName="' . $parte . '" ContentType="' . $tipo . '"/></Types>';
$rels        = '<?xml version="1.0"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"/>';
$png         = (string) base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
$pdf         = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Kids[]/Count 0>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n";

// Video de A1 y de A2 (vista previa). Con un video real en %TEMP% (lo arma cdp-leccion-video.mjs), se puede
// reproducir y adelantar; si no, un MP4 mínimo que no se reproduce (la lección muestra igual el reproductor).
$temp        = rtrim(str_replace('\\', '/', sys_get_temp_dir()), '/');
$real        = $temp . '/angelitozzz-video-prueba.mp4';
$portada_png = $temp . '/angelitozzz-video-prueba.png';
$video       = is_file($real)
    ? $archivo((string) file_get_contents($real), 'Bienvenida al curso.mp4', 'video')
    : $archivo("\x00\x00\x00\x18ftypmp42\x00\x00\x00\x00mp42isom" . str_repeat("\x00", 2048), 'Bienvenida al curso.mp4', 'video');
echo is_file($real) ? "video: el de $real\n" : "video: MP4 mínimo (para uno real, corre antes cdp-leccion-video.mjs video)\n";
if ($video) {
    $portada = $archivo(is_file($portada_png) ? (string) file_get_contents($portada_png) : $png, 'Portada.png', 'portada');
    if ($portada) { update_post_meta($video, '_angelitozzz_portada', $portada); }
    update_post_meta($a1, '_angelitozzz_video', (string) $video);
    update_post_meta($a2, '_angelitozzz_video', (string) $video);
}

// F5-e: A3 con un video público de Vimeo (de su cuenta oficial), para probar el otro tipo de video. El plugin
// lee su duración y su miniatura por oEmbed al guardarlo (sin red, quedan vacías y la lección se ve igual).
update_post_meta($a3, '_angelitozzz_video', 'https://vimeo.com/76979871');
echo 'vimeo en A3: duración ', (int) get_post_meta($a3, '_angelitozzz_duracion', true), " s\n";

// Materiales de A1, uno de cada tipo (el ícono y el audio de la pestaña Materiales), y un PDF del curso.
$materiales_a1 = array_filter([
    $archivo($pdf, 'Guía de horarios.pdf', 'material'),
    $archivo($zip(['[Content_Types].xml' => $tipos_ooxml('/word/document.xml', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml'), '_rels/.rels' => $rels, 'word/document.xml' => '<?xml version="1.0"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body/></w:document>']), 'Plan de siestas.docx', 'material'),
    $archivo($zip(['[Content_Types].xml' => $tipos_ooxml('/xl/workbook.xml', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml'), '_rels/.rels' => $rels, 'xl/workbook.xml' => '<?xml version="1.0"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"/>']), 'Registro de sueño.xlsx', 'material'),
    $archivo($zip(['[Content_Types].xml' => $tipos_ooxml('/ppt/presentation.xml', 'application/vnd.openxmlformats-officedocument.presentationml.presentation.main+xml'), '_rels/.rels' => $rels, 'ppt/presentation.xml' => '<?xml version="1.0"?><p:presentation xmlns:p="http://schemas.openxmlformats.org/presentationml/2006/main"/>']), 'Rutina en diapositivas.pptx', 'material'),
    $archivo($png, 'Tabla de ventanas.png', 'material'),
    $archivo("ID3\x03\x00\x00\x00\x00\x00\x00" . str_repeat("\xFF\xFB\x90\x00" . str_repeat("\x00", 413), 20), 'Ruido blanco.mp3', 'material'),
]);
update_post_meta($a1, '_angelitozzz_materiales', array_values($materiales_a1));
$del_curso = $archivo($pdf, 'PDF del curso.pdf', 'material');
if ($del_curso) {
    update_post_meta($a, '_angelitozzz_materiales', [['adjunto_id' => $del_curso, 'modulo' => 'm2', 'orden' => 99]]);
}

// Curso B: completado. Curso C: suscripción (Angelita Pro), sin empezar.
$b = $curso('Curso completado F5', 'curso_online', 6, false, [['id' => 'm1', 'titulo' => 'Todo']], 'Un curso que la alumna ya terminó.');
$b1 = $leccion($b, 'Única lección B1', 'm1', 1, '<p>B1.</p>');
$b2 = $leccion($b, 'Única lección B2', 'm1', 2, '<p>B2.</p>');
$c = $curso('Angelita Pro de prueba F5', 'suscripcion', 4, false, [['id' => 'm1', 'titulo' => 'Comienza aquí']], 'Suscripción de prueba.');
$c1 = $leccion($c, 'Conoce a Angelita', 'm1', 1, '<p>Primera lección de la suscripción.</p>');

// Producto ligado al curso A, pedido pagado (el puente inscribe) y pedido pendiente.
$producto = new WC_Product_Simple();
$producto->set_name('Curso de prueba F5');
$producto->set_regular_price('97');
$producto->set_virtual(true);
$producto->set_status('private');
$producto->update_meta_data('_angelitozzz_curso_id', $a);
$producto->save();
$ids['productos'][] = $producto->get_id();
update_post_meta($a, '_angelitozzz_producto_id', $producto->get_id());

// Pedidos de la alumna sobre el curso A, para la cuenta y para gracias (cdp-gracias.mjs):
// pagado (el puente inscribe), pendiente, pagado como regalo (el plugin genera el código) y cancelado.
$ids['pedidos_por_estado'] = [];
foreach (['pagado' => 'completed', 'pendiente' => 'pending', 'regalo' => 'processing', 'cancelado' => 'cancelled'] as $clave => $estado) {
    $pedido = wc_create_order(['customer_id' => $alumna]);
    $item_id = $pedido->add_product($producto, 1);
    if ($clave === 'regalo') {
        wc_add_order_item_meta($item_id, '_angelitozzz_regalo', 1, true); // Como "Regalar este curso" en el carrito.
    }
    $pedido->set_billing_first_name('Ana Lucía');
    $pedido->set_billing_email('prueba-f5@angelitozzz.local');
    $pedido->calculate_totals();
    $pedido->save();
    $pedido->update_status($estado);
    $ids['pedidos'][] = $pedido->get_id();
    $ids['pedidos_por_estado'][$clave] = $pedido->get_id();
}

// Usuaria sin cursos ni pedidos (estados vacíos de la cuenta, cdp-vacios.mjs) y otra que canjea el regalo
// (cdp-gracias.mjs): separadas para que el canje no llene la cuenta vacía.
$ids['vacia'] = wp_insert_user(['user_login' => 'vacia-f5', 'user_email' => 'vacia-f5@angelitozzz.local', 'user_pass' => 'Prueba123', 'first_name' => 'Vacía', 'last_name' => 'Prueba', 'role' => 'customer']);
$ids['canje'] = wp_insert_user(['user_login' => 'canje-f5', 'user_email' => 'canje-f5@angelitozzz.local', 'user_pass' => 'Prueba123', 'first_name' => 'Canje', 'last_name' => 'Prueba', 'role' => 'customer']);

// Inscripciones de B y C sin pedido (desde el plugin 0.7.3 el origen es "compra" o "regalo"),
// y avance: A con la lección 1 completa y vista; B al 100 %.
foreach ([$b, $c] as $curso_id) {
    $inscripcion = angelitozzz_cursos_inscribir($alumna, $curso_id, 'compra');
    if (is_wp_error($inscripcion)) { echo "inscripción {$curso_id}: ", $inscripcion->get_error_message(), "\n"; }
}
angelitozzz_cursos_registrar_vista($alumna, $a1);
angelitozzz_cursos_marcar_completada($alumna, $a1, true);
angelitozzz_cursos_registrar_vista($alumna, $b2);
angelitozzz_cursos_marcar_completada($alumna, $b1, true);
angelitozzz_cursos_marcar_completada($alumna, $b2, true);

// Bloque 6: instructora (administrador), portada del curso A (una imagen que ya está en la biblioteca) y un curso D en borrador sin producto.
$ids['instructora'] = wp_insert_user(['user_login' => 'prueba-instructora', 'user_email' => 'prueba-instructora@angelitozzz.local', 'user_pass' => 'Prueba123', 'first_name' => 'Bárbara', 'role' => 'instructora']);
$imagen = get_posts(['post_type' => 'attachment', 'post_mime_type' => 'image', 'post_status' => 'inherit', 'posts_per_page' => 1, 'fields' => 'ids']);
if ($imagen) { set_post_thumbnail($a, (int) $imagen[0]); }
$d = $curso('Curso borrador F5', 'curso_online', 12, false, [['id' => 'm1', 'titulo' => 'Módulo 1']], 'Curso en borrador para el administrador.');
wp_update_post(['ID' => $d, 'post_status' => 'draft']);

// El código del regalo y el cupón que lo respalda (para la prueba de canje y para borrarlo después).
$ids['cupones'] = [];
foreach (wc_get_order($ids['pedidos_por_estado']['regalo'])->get_items() as $item_id => $item) {
    $cupon_id = absint(wc_get_order_item_meta($item_id, '_angelitozzz_regalo_cupon_id', true));
    if ($cupon_id) { $ids['cupones'][] = $cupon_id; }
}

update_option('angelitozzz_prueba_f5', $ids, false);
wp_set_current_user($alumna);
echo "creado: ", wp_json_encode($ids), "\n";
echo "resumen: ", wp_json_encode(angelitozzz_cursos_resumen()), "\n";
echo "lecciones A: ", implode(', ', array_map(static fn($id) => get_permalink($id), [$a1, $a2, $a3])), "\n";
echo "código del regalo: ", wp_json_encode(array_map(static fn($id) => get_the_title($id), $ids['cupones'])), "\n";
// Lo que leen los cdp-*.mjs (URL de gracias y código del regalo), fuera del tema: carpeta temporal del sistema.
$para_pruebas = [
    'gracias'         => [],
    'codigos'         => array_map(static fn($id) => get_the_title($id), $ids['cupones']),
    // Sin enlace del mes gratis en Ajustes del sitio, el banner de Mundo no se imprime (banner-mundo.php).
    'mundo_con_enlace' => trim((string) ((get_field('opciones_mes_gratis_mundo', 'option') ?: [])['enlace'] ?? '')) !== '',
];
foreach ($ids['pedidos_por_estado'] as $clave => $pedido_id) {
    $para_pruebas['gracias'][$clave] = wc_get_order($pedido_id)->get_checkout_order_received_url();
    echo "gracias {$clave}: ", $para_pruebas['gracias'][$clave], "\n";
}
file_put_contents(sys_get_temp_dir() . '/angelitozzz-datos-f5.json', wp_json_encode($para_pruebas));
echo "datos para las pruebas: ", sys_get_temp_dir() . '/angelitozzz-datos-f5.json', "\n";
