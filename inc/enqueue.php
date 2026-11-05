<?php
defined('ABSPATH') || exit;

// Fallback si el archivo `hot` está vacío.
const ANGELITOZZZ_VITE_DEV_SERVER = 'http://localhost:5173';
const ANGELITOZZZ_CSS_ENTRY = 'src/css/input.css';
const ANGELITOZZZ_JS_ENTRY = 'src/js/main.js';

function angelitozzz_hot_path()
{
    return get_template_directory() . '/hot';
}

function angelitozzz_manifest_path()
{
    return get_template_directory() . '/dist/manifest.json';
}

// Dev = entorno local + archivo `hot` (lo crea `pnpm dev`). Si no, producción.
// El chequeo de entorno evita servir assets de Vite si `hot` llega por error al servidor.
function angelitozzz_is_vite_dev()
{
    return in_array(wp_get_environment_type(), ['local', 'development'], true)
        && file_exists(angelitozzz_hot_path());
}

function angelitozzz_vite_dev_server()
{
    $url = trim((string) @file_get_contents(angelitozzz_hot_path()));

    return $url !== '' ? untrailingslashit($url) : ANGELITOZZZ_VITE_DEV_SERVER;
}

function angelitozzz_log($message)
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[angelitozzz] ' . $message);
    }
}

// Devuelve el manifest como array, o null si falta o es inválido.
function angelitozzz_get_manifest()
{
    $path = angelitozzz_manifest_path();

    if (!file_exists($path)) {
        angelitozzz_log('dist/manifest.json no existe. Ejecuta `pnpm build`.');
        return null;
    }

    $manifest = wp_json_file_decode($path, ['associative' => true]);

    if (!is_array($manifest)) {
        angelitozzz_log('dist/manifest.json es inválido.');
        return null;
    }

    return $manifest;
}

function angelitozzz_enqueue_assets()
{
    if (angelitozzz_is_vite_dev()) {
        // DESARROLLO - HMR
        $server = angelitozzz_vite_dev_server();
        wp_enqueue_script('vite-client', $server . '/@vite/client', [], null);
        wp_enqueue_script('angelitozzz-js', $server . '/' . ANGELITOZZZ_JS_ENTRY, [], null, true);
        wp_enqueue_style('angelitozzz-css', $server . '/' . ANGELITOZZZ_CSS_ENTRY, [], null);
        return;
    }

    // PRODUCCIÓN
    $manifest = angelitozzz_get_manifest();
    if ($manifest === null) {
        return;
    }

    $dist_uri = get_template_directory_uri() . '/dist/';

    if (isset($manifest[ANGELITOZZZ_CSS_ENTRY]['file'])) {
        wp_enqueue_style('angelitozzz-css', $dist_uri . $manifest[ANGELITOZZZ_CSS_ENTRY]['file'], [], null);
    }

    if (isset($manifest[ANGELITOZZZ_JS_ENTRY]['file'])) {
        wp_enqueue_script('angelitozzz-js', $dist_uri . $manifest[ANGELITOZZZ_JS_ENTRY]['file'], [], null, true);
    }
}
add_action('wp_enqueue_scripts', 'angelitozzz_enqueue_assets');

// Vite emite ES modules: type="module" en dev y producción.
function angelitozzz_add_module_type($tag, $handle)
{
    if ($handle !== 'vite-client' && $handle !== 'angelitozzz-js') {
        return $tag;
    }

    // Solo <script src>, y solo si aún no tiene type.
    return preg_replace('/<script(?=\s[^>]*\ssrc=)(?![^>]*\stype=)/', '<script type="module"', $tag);
}
add_filter('script_loader_tag', 'angelitozzz_add_module_type', 10, 2);
