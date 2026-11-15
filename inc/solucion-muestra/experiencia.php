<?php

/**
 * TEMPORAL (F4): layout `experiencia` de solucion_secciones_bloques (solo A Dormir Por Fin).
 * El primer y el último párrafo van con el marcador de negrita de wp-helper (^…^). Se borra en la F5.
 */

defined('ABSPATH') || exit;

$contacto = function_exists('get_field') ? (get_field('opciones_contacto', 'option') ?: []) : [];
$numero   = preg_replace('/\D+/', '', (string) ($contacto['whatsapp'] ?? ''));
$mensaje  = trim((string) ($contacto['whatsapp_mensaje'] ?? ''));

return [
    'a-dormir-por-fin' => [
        'fondo'  => 'rosa',
        'titulo' => 'La experiencia más exclusiva de Angelitozzz.',
        'texto'  => "^Acceso directo a Bárbara. Sin intermediarios. Sin sistemas.^\n\nAngelita fue entrenada con mi método y puede acompañar a miles de familias simultáneamente. Yo no puedo — y eso es exactamente lo que hace esta asesoría especial. Recibo una cantidad limitada de familias al mismo tiempo para garantizar mi total atención a cada caso.\n\nEsta asesoría es para quien quiere trabajar con la persona detrás del método. Para casos complejos que necesitan una mirada experta. Para mamás que prefieren un ser humano de su lado. Y para bebés y niños de hasta 4 años — incluyendo mayores de 2 años y niños que duermen en su propia cama.\n\n^El resultado: tu bebé durmiendo 10 a 12 horas de corrido por las noches, con siestas largas y sin batalla, en los horarios perfectos para su edad.^",
        'imagen' => get_theme_file_uri('assets/img/solucion/a-dormir-por-fin/experiencia-foto.webp'),
        'boton'  => $numero === '' ? null : [
            'url'    => 'https://wa.me/' . $numero . ($mensaje !== '' ? '?text=' . rawurlencode($mensaje) : ''),
            'title'  => 'Escríbeme por WhatsApp',
            'target' => '_blank',
        ],
    ],
];
