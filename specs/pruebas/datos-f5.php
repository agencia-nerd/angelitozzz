<?php
/*
 * Datos de prueba del bloque 5 (F4) en el Local: crea la página /cuenta/, una alumna, 3 cursos con
 * lecciones, un material PDF, un producto de Woo ligado al curso A, un pedido pagado (el puente del
 * plugin inscribe) y uno pendiente. Guarda los IDs en la opción angelitozzz_prueba_f5 para borrarlos.
 * Uso: php zz-datos-f5.php crear|borrar
 *
 * F5-b, compra: "tienda" crea un curso con su producto (por el constructor del plugin: virtual y vender
 * individualmente) para cada solución de venta directa y uno por etapa de Horarios Ideales, y los liga a las
 * soluciones (solucion_compra_cursos y el curso de cada etapa). Guarda los metadatos que tenían las soluciones en la
 * opción angelitozzz_prueba_tienda; "tienda-borrar" los devuelve y borra los cursos, sus lecciones y productos, y
 * los pedidos de esos productos. Lista los IDs antes de borrar.
 * Uso: php zz-datos-f5.php tienda|tienda-borrar
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
    $curso = static function (string $titulo, int $meses, string $precio, string $lanzamiento) use (&$tienda): int {
        $id = wp_insert_post(['post_type' => 'curso', 'post_status' => 'publish', 'post_title' => $titulo, 'post_excerpt' => 'Curso de prueba de la F5-b.']);
        update_post_meta($id, '_angelitozzz_tipo', 'curso_online');
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
        'angelita-pro'      => ['Angelita Pro (prueba)', 4, '97', '67'],
    ];
    foreach (get_posts(['post_type' => 'solucion', 'post_status' => 'publish', 'posts_per_page' => -1]) as $solucion) {
        $tipo = (string) get_post_meta($solucion->ID, 'solucion_compra_tipo', true);
        if ($tipo !== 'etapas' && isset($precios[$solucion->post_name])) {
            [$titulo, $meses, $precio, $lanzamiento] = $precios[$solucion->post_name];
            $poner($solucion->ID, 'solucion_compra_cursos', [(string) $curso($titulo, $meses, $precio, $lanzamiento)]);
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

if ($accion === 'borrar') {
    foreach ((array) ($ids['pedidos'] ?? []) as $id) {
        $pedido = wc_get_order($id);
        if ($pedido) { $pedido->delete(true); }
    }
    foreach ((array) ($ids['productos'] ?? []) as $id) { wp_delete_post($id, true); }
    foreach ((array) ($ids['adjuntos'] ?? []) as $id) { wp_delete_attachment($id, true); }
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
    echo "borrado: ", wp_json_encode($ids), "\n";
    exit;
}

if ($ids) { echo "ya hay datos de prueba: ", wp_json_encode($ids), "\n"; exit; }
$ids = ['cursos' => [], 'lecciones' => [], 'adjuntos' => [], 'productos' => [], 'pedidos' => []];

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
$a1 = $leccion($a, 'Video 1: bienvenida', 'm1', 1, $texto, ['_angelitozzz_video' => 'https://vimeo.com/76979871']);
$a2 = $leccion($a, 'Video 2: el sueño del recién nacido', 'm1', 2, '<p>Lección de vista previa.</p>', ['_angelitozzz_vista_previa' => true]);
$a3 = $leccion($a, 'Video 3: la rutina de la noche', 'm2', 1, '<p>Tercera lección.</p>');
$a4 = $leccion($a, 'Video 4: siestas', 'm2', 2, '<p>Cuarta lección.</p>');

// Material PDF (carpeta privada del plugin) en la lección 1 y otro del curso.
$pdf = wp_tempnam('material.pdf');
file_put_contents($pdf, "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Kids[]/Count 0>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n");
foreach ([[$a1, 'Guía de horarios.pdf'], [$a, 'PDF del curso.pdf']] as [$padre, $nombre]) {
    $copia = wp_tempnam('material.pdf');
    copy($pdf, $copia);
    $adjunto = angelitozzz_cursos_subir_material(['name' => $nombre, 'tmp_name' => $copia, 'error' => 0], $padre);
    if (is_wp_error($adjunto)) { echo "material: ", $adjunto->get_error_message(), "\n"; continue; }
    $ids['adjuntos'][] = $adjunto;
    if ($padre === $a1) {
        update_post_meta($a1, '_angelitozzz_materiales', [$adjunto]);
    } else {
        update_post_meta($a, '_angelitozzz_materiales', [['adjunto_id' => $adjunto, 'modulo' => 'm2', 'orden' => 99]]);
    }
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

foreach (['completed', 'pending'] as $estado) {
    $pedido = wc_create_order(['customer_id' => $alumna]);
    $pedido->add_product($producto, 1);
    $pedido->set_billing_first_name('Ana Lucía');
    $pedido->set_billing_email('prueba-f5@angelitozzz.local');
    $pedido->calculate_totals();
    $pedido->save();
    $pedido->update_status($estado);
    $ids['pedidos'][] = $pedido->get_id();
}

// Inscripciones manuales de B y C, y avance: A con la lección 1 completa y vista; B al 100 %.
angelitozzz_cursos_inscribir($alumna, $b, 'manual');
angelitozzz_cursos_inscribir($alumna, $c, 'manual');
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

update_option('angelitozzz_prueba_f5', $ids, false);
wp_set_current_user($alumna);
echo "creado: ", wp_json_encode($ids), "\n";
echo "resumen: ", wp_json_encode(angelitozzz_cursos_resumen()), "\n";
echo "lecciones A: ", implode(', ', array_map(static fn($id) => get_permalink($id), [$a1, $a2, $a3])), "\n";
