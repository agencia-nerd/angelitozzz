<?php
defined('ABSPATH') || exit;

/*
 * Envíos guardados (skill wp-form, módulo opcional).
 *
 * Se instala solo si el proyecto quiere ver los envíos en el admin. Cada form decide
 * qué guarda con 'store' (none | all | failed).
 *
 * - Solo administradores (manage_options): los envíos tienen datos personales.
 * - Solo lectura: no se crean ni se editan desde el admin. Sí se pueden borrar.
 * - Datos personales (Ley 29733): conexión con Herramientas → Exportar / Borrar datos
 *   personales de WordPress (busca por email). Sin borrado automático por antigüedad.
 */

const ANGELITOZZZ_ENTRY_TYPE = 'angelitozzz_envio';
const ANGELITOZZZ_ENTRY_CAP = 'manage_options';

function angelitozzz_register_entries()
{
    $cap = ANGELITOZZZ_ENTRY_CAP;

    register_post_type(ANGELITOZZZ_ENTRY_TYPE, [
        'labels' => [
            'name'               => 'Envíos',
            'singular_name'      => 'Envío',
            'menu_name'          => 'Envíos',
            'all_items'          => 'Todos los envíos',
            'edit_item'          => 'Ver envío',
            'search_items'       => 'Buscar envíos',
            'not_found'          => 'No hay envíos.',
            'not_found_in_trash' => 'No hay envíos en la papelera.',
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => false,
        'exclude_from_search' => true,
        'menu_icon'           => 'dashicons-email-alt',
        'supports'            => false,
        'map_meta_cap'        => false,
        'capabilities'        => [
            'create_posts'           => 'do_not_allow',
            'publish_posts'          => 'do_not_allow',
            'edit_post'              => $cap,
            'read_post'              => $cap,
            'delete_post'            => $cap,
            'edit_posts'             => $cap,
            'edit_others_posts'      => $cap,
            'edit_private_posts'     => $cap,
            'edit_published_posts'   => $cap,
            'read_private_posts'     => $cap,
            'delete_posts'           => $cap,
            'delete_others_posts'    => $cap,
            'delete_private_posts'   => $cap,
            'delete_published_posts' => $cap,
        ],
        'rewrite'             => false,
        'query_var'           => false,
    ]);
}
add_action('init', 'angelitozzz_register_entries');

// Lo llama el motor de forms según el valor de 'store'.
function angelitozzz_form_store_entry(array $form, array $data, $sent)
{
    $rows = [];
    foreach ($form['fields'] as $key => $field) {
        $rows[] = [
            'label'     => $field['label'] !== '' ? $field['label'] : $key,
            'value'     => angelitozzz_form_value_text($field, $data[$key] ?? ''),
            'multiline' => $field['type'] === 'textarea',
        ];
    }

    $email = '';
    foreach ($form['fields'] as $key => $field) {
        if ($field['type'] === 'email' && !empty($data[$key])) {
            $email = strtolower((string) $data[$key]);
            break;
        }
    }

    // wp_insert_post y update_post_meta quitan barras invertidas: se escapan antes
    // para que un "\" escrito por el usuario se guarde tal cual.
    $post_id = wp_insert_post(wp_slash([
        'post_type'   => ANGELITOZZZ_ENTRY_TYPE,
        'post_status' => 'private',
        'post_title'  => $form['title'] . ($email !== '' ? ' — ' . $email : ''),
    ]), true);

    if (is_wp_error($post_id)) {
        angelitozzz_form_log('No se pudo guardar el envío: ' . $post_id->get_error_message());
        return;
    }

    update_post_meta($post_id, '_angelitozzz_form', $form['id']);
    update_post_meta($post_id, '_angelitozzz_status', $sent ? 'sent' : 'failed');
    update_post_meta($post_id, '_angelitozzz_email', $email);
    update_post_meta($post_id, '_angelitozzz_rows', wp_slash($rows));
}

/* -------------------------------------------------------------------------
 * Admin: listado y detalle de solo lectura
 * ---------------------------------------------------------------------- */

function angelitozzz_entries_columns($columns)
{
    return [
        'cb'                => $columns['cb'] ?? '',
        'title'             => 'Envío',
        'angelitozzz_form'   => 'Formulario',
        'angelitozzz_status' => 'Estado',
        'date'              => 'Fecha',
    ];
}
add_filter('manage_' . ANGELITOZZZ_ENTRY_TYPE . '_posts_columns', 'angelitozzz_entries_columns');

function angelitozzz_entries_column($column, $post_id)
{
    if ($column === 'angelitozzz_form') {
        echo esc_html((string) get_post_meta($post_id, '_angelitozzz_form', true));
    }
    if ($column === 'angelitozzz_status') {
        $sent = get_post_meta($post_id, '_angelitozzz_status', true) === 'sent';
        printf(
            '<span style="color:%s;font-weight:600;">%s</span>',
            $sent ? '#00703c' : '#b32d2e',
            $sent ? 'Enviado' : 'Correo no enviado'
        );
    }
}
add_action('manage_' . ANGELITOZZZ_ENTRY_TYPE . '_posts_custom_column', 'angelitozzz_entries_column', 10, 2);

// Sin edición rápida ni edición masiva: solo ver y borrar.
function angelitozzz_entries_row_actions($actions, $post)
{
    if ($post->post_type !== ANGELITOZZZ_ENTRY_TYPE) {
        return $actions;
    }
    unset($actions['inline hide-if-no-js']);
    if (isset($actions['edit'])) {
        $actions['edit'] = sprintf('<a href="%s">Ver</a>', esc_url(get_edit_post_link($post->ID)));
    }

    return $actions;
}
add_filter('post_row_actions', 'angelitozzz_entries_row_actions', 10, 2);

function angelitozzz_entries_bulk_actions($actions)
{
    unset($actions['edit']);

    return $actions;
}
add_filter('bulk_actions-edit-' . ANGELITOZZZ_ENTRY_TYPE, 'angelitozzz_entries_bulk_actions');

function angelitozzz_entries_meta_boxes()
{
    // Sin caja "Publicar": no se cambia estado ni fecha. Se borra desde el listado.
    remove_meta_box('submitdiv', ANGELITOZZZ_ENTRY_TYPE, 'side');
    remove_meta_box('slugdiv', ANGELITOZZZ_ENTRY_TYPE, 'normal');
    add_meta_box('angelitozzz_entry_data', 'Datos del envío', 'angelitozzz_entries_render', ANGELITOZZZ_ENTRY_TYPE, 'normal', 'high');
}
add_action('add_meta_boxes', 'angelitozzz_entries_meta_boxes');

// Aunque alguien fuerce un POST al editor, el envío no cambia.
function angelitozzz_entries_block_save($data, $postarr)
{
    if ($data['post_type'] === ANGELITOZZZ_ENTRY_TYPE && !empty($postarr['ID']) && is_admin() && isset($_POST['action']) && $_POST['action'] === 'editpost') {
        $original = get_post((int) $postarr['ID']);
        if ($original) {
            $data['post_title'] = wp_slash($original->post_title);
            $data['post_status'] = $original->post_status;
            $data['post_date'] = $original->post_date;
            $data['post_date_gmt'] = $original->post_date_gmt;
        }
    }

    return $data;
}
add_filter('wp_insert_post_data', 'angelitozzz_entries_block_save', 10, 2);

function angelitozzz_entries_render($post)
{
    $rows = (array) get_post_meta($post->ID, '_angelitozzz_rows', true);
    $sent = get_post_meta($post->ID, '_angelitozzz_status', true) === 'sent';

    echo '<p><strong>Estado:</strong> ' . esc_html($sent ? 'Correo enviado' : 'El correo no se pudo enviar') . '</p>';
    echo '<p><strong>Recibido:</strong> ' . esc_html(get_the_date('', $post) . ' ' . get_the_time('', $post)) . '</p>';
    echo '<table class="widefat striped"><tbody>';
    foreach ($rows as $row) {
        $value = esc_html((string) ($row['value'] ?? ''));
        printf(
            '<tr><th style="width:25%%;">%s</th><td>%s</td></tr>',
            esc_html((string) ($row['label'] ?? '')),
            !empty($row['multiline']) ? nl2br($value) : $value
        );
    }
    echo '</tbody></table>';
}

/* -------------------------------------------------------------------------
 * Privacidad: Herramientas → Exportar / Borrar datos personales
 * ---------------------------------------------------------------------- */

function angelitozzz_entries_by_email($email, $page)
{
    return get_posts([
        'post_type'      => ANGELITOZZZ_ENTRY_TYPE,
        'post_status'    => 'any',
        'posts_per_page' => 50,
        'paged'          => (int) $page,
        'meta_key'       => '_angelitozzz_email',
        'meta_value'     => strtolower((string) $email),
    ]);
}

function angelitozzz_entries_exporter($email, $page = 1)
{
    $items = [];
    $posts = angelitozzz_entries_by_email($email, $page);

    foreach ($posts as $post) {
        $data = [['name' => 'Fecha', 'value' => get_the_date('Y-m-d H:i', $post)]];
        foreach ((array) get_post_meta($post->ID, '_angelitozzz_rows', true) as $row) {
            $data[] = ['name' => (string) ($row['label'] ?? ''), 'value' => (string) ($row['value'] ?? '')];
        }
        $items[] = [
            'group_id'    => 'angelitozzz_form_entries',
            'group_label' => 'Envíos de formularios',
            'item_id'     => 'entry-' . $post->ID,
            'data'        => $data,
        ];
    }

    return ['data' => $items, 'done' => count($posts) < 50];
}

function angelitozzz_entries_eraser($email, $page = 1)
{
    // Siempre página 1: lo borrado ya no aparece en la siguiente consulta.
    $posts = angelitozzz_entries_by_email($email, 1);
    foreach ($posts as $post) {
        wp_delete_post($post->ID, true);
    }

    return [
        'items_removed'  => count($posts) > 0,
        'items_retained' => false,
        'messages'       => [],
        'done'           => count($posts) < 50,
    ];
}

function angelitozzz_entries_register_exporter($exporters)
{
    $exporters['angelitozzz-form-entries'] = [
        'exporter_friendly_name' => 'Envíos de formularios',
        'callback'               => 'angelitozzz_entries_exporter',
    ];

    return $exporters;
}
add_filter('wp_privacy_personal_data_exporters', 'angelitozzz_entries_register_exporter');

function angelitozzz_entries_register_eraser($erasers)
{
    $erasers['angelitozzz-form-entries'] = [
        'eraser_friendly_name' => 'Envíos de formularios',
        'callback'             => 'angelitozzz_entries_eraser',
    ];

    return $erasers;
}
add_filter('wp_privacy_personal_data_erasers', 'angelitozzz_entries_register_eraser');
