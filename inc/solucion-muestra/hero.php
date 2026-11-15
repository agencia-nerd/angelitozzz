<?php

/**
 * TEMPORAL (F4): grupo `solucion_hero` de las 4 soluciones, con los textos de Figma y las
 * correcciones de specs/solucion.md (subtítulo de Horarios Ideales, etiqueta de Recién Nacidos Go).
 * Se borra en la F5 (contenido inicial de wp-acf).
 */

defined('ABSPATH') || exit;

$img = static fn(string $archivo): string => get_theme_file_uri('assets/img/solucion/' . $archivo);

// Enlace de WhatsApp de Ajustes del sitio (sin número, no hay enlace).
$contacto = function_exists('get_field') ? (get_field('opciones_contacto', 'option') ?: []) : [];
$numero   = preg_replace('/\D+/', '', (string) ($contacto['whatsapp'] ?? ''));
$mensaje  = trim((string) ($contacto['whatsapp_mensaje'] ?? ''));
$whatsapp = static fn(string $texto): ?array => $numero === '' ? null : [
    'url'    => 'https://wa.me/' . $numero . ($mensaje !== '' ? '?text=' . rawurlencode($mensaje) : ''),
    'title'  => $texto,
    'target' => '_blank',
];

return [
    'angelita-pro' => [
        'etiqueta'    => 'Nuevo',
        'titular'     => 'Angelita Pro',
        'subtitulo'   => 'Coach de sueño con IA · 4 a 24 meses',
        'descripcion' => 'Conoce a Angelita: la coach de sueño que nunca duerme — para que tú y tu bebé por fin puedan hacerlo. Entrenada con 10 años de experiencia real y más de 8.000 familias acompañadas. Disponible a cualquier hora, por WhatsApp. Sin citas. Sin esperas.',
        'datos'       => [
            ['icono' => 'reloj', 'texto' => '2 semanas 24/7'],
            ['icono' => 'whatsapp', 'texto' => 'WhatsApp'],
        ],
        'imagen'      => '', // Sin foto propia: la vista usa la imagen "not found".
        'boton'       => 'Quiero a Angelita',
        'nota'        => '7 días de prueba completa',
        'regalo'      => true,
        'whatsapp'    => null,
    ],
    'a-dormir-por-fin' => [
        'etiqueta'    => 'Asesoría personalizada',
        'titular'     => "A Dormir Por Fin.\nTu bebé durmiendo 10 a 12 horas de corrido — con un plan diseñado por Bárbara, *para ti*.",
        'subtitulo'   => 'Asesoría Personalizada · 4 meses a 4 años',
        'descripcion' => 'Tres semanas de acompañamiento directo conmigo por WhatsApp. Cada duda, cada pregunta, cada noche difícil — respondida por Bárbara personalmente.',
        'datos'       => [
            ['icono' => 'reloj', 'texto' => '3 semanas'],
            ['icono' => 'barbara', 'texto' => 'Con Bárbara'],
        ],
        'imagen'      => $img('a-dormir-por-fin/hero-foto.webp'),
        'boton'       => 'Comprar ahora',
        'nota'        => '7 días de prueba completa',
        'regalo'      => true,
        'whatsapp'    => null,
    ],
    'horarios-ideales' => [
        'etiqueta'    => 'Curso Online',
        'titular'     => 'El horario ideal de sueño para tu bebé — según su edad.',
        'subtitulo'   => 'Curso Online · 4 a 36 meses',
        'descripcion' => 'Aprende a leer a tu bebé y crear rutinas que funcionen de verdad. Sin adivinar. Sin confusión.',
        'datos'       => [
            ['icono' => 'reloj', 'texto' => '3 semanas'],
        ],
        'imagen'      => $img('horarios-ideales/hero-foto.webp'),
        'boton'       => 'Comprar ahora',
        'nota'        => '7 días de prueba completa',
        'regalo'      => true,
        'whatsapp'    => null,
    ],
    'recien-nacidos-go' => [
        'etiqueta'    => 'Curso Online',
        'titular'     => 'Todo lo que necesitas saber para que tu recién nacido duerma mejor — desde los primeros días.',
        'subtitulo'   => 'Curso Online · 0 a 12 semanas',
        'descripcion' => "Técnicas suaves, rutinas claras y 100% compatible con la lactancia y el colecho.\n\n11 videos cortos · PDF descargable · 4 meses de acceso · Bonus: 4 sesiones de coaching grupal en vivo con Bárbara",
        'datos'       => [],
        'imagen'      => $img('recien-nacidos-go/hero-foto.webp'),
        'boton'       => 'Comprar ahora',
        'nota'        => '7 días de prueba completa',
        'regalo'      => true,
        'whatsapp'    => $whatsapp('¿Tienes preguntas? Escríbeme por WhatsApp'),
    ],
];
