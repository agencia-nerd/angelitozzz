<?php

/**
 * TEMPORAL (F4): layout `iconos` de cada solución, con los textos de Figma (specs/solucion.md).
 * Se borra en la F5: el contenido inicial lo carga wp-acf.
 */

defined('ABSPATH') || exit;

return [
    'angelita-pro' => [
        'fondo'     => 'crema',
        'corazon'   => true,
        'titulo'    => 'Angelita Pro es para ti si...',
        'bajada'    => '',
        'items'     => [
            ['icono' => 'reloj-de-arena', 'titulo' => '', 'texto' => 'Sus siestas duran 30 minutos o menos — o son una lucha constante'],
            ['icono' => 'bebe-luna', 'titulo' => '', 'texto' => 'Tu bebé tiene entre 4 y 24 meses y se despierta varias veces por noche'],
            ['icono' => 'cuna', 'titulo' => '', 'texto' => 'Necesitas apoyo real a cualquier hora, incluso a las 3am — sin citas, sin esperas'],
            ['icono' => 'bebe-envuelto', 'titulo' => '', 'texto' => 'Estás lista para dejar de vivir agotada, confundida y al límite'],
            ['icono' => 'biberon', 'titulo' => '', 'texto' => 'Quieres avanzar sin dudar ni cometer errores que frustren tus avances'],
        ],
        'no_titulo' => '',
        'no_items'  => '',
    ],
    'a-dormir-por-fin' => [
        'fondo'     => 'crema',
        'corazon'   => true,
        'titulo'    => 'Esta asesoría es para ti si...',
        'bajada'    => '',
        'items'     => [
            ['icono' => 'bebe-luna', 'titulo' => '', 'texto' => 'Prefieres trabajar directamente con Bárbara — la persona detrás del método.'],
            ['icono' => 'reloj-de-arena', 'titulo' => '', 'texto' => 'Tu caso es complejo — múltiples intentos fallidos, gemelos, situaciones médicas, colecho arraigado.'],
            ['icono' => 'documento', 'titulo' => '', 'texto' => 'Tu bebé o niño tiene entre 4 meses y 4 años — incluyendo mayores de 2 años y niños en su propia cama.'],
            ['icono' => 'cuna', 'titulo' => '', 'texto' => 'Sus siestas son cortas, con mucha lucha o en horarios que no funcionan.'],
            ['icono' => 'bebe-envuelto', 'titulo' => '', 'texto' => 'Solo duerme en brazos, con pecho o en movimiento — y quieres cambiar eso.'],
            ['icono' => 'biberon', 'titulo' => '', 'texto' => 'Quieres la certeza de que alguien con 10 años de experiencia está leyendo tu caso.'],
            ['icono' => 'reloj-de-arena', 'titulo' => '', 'texto' => 'Quieres sentirte completamente acompañada y segura en cada paso.'],
        ],
        'no_titulo' => 'Esta asesoría NO es para ti si...',
        'no_items'  => "Tu bebé tiene menos de 4 meses o más de 4 años\nBuscas una solución sin ningún compromiso de tu parte",
    ],
    'horarios-ideales' => [
        'fondo'     => 'crema',
        'corazon'   => false,
        'titulo'    => 'Este curso es para ti si...',
        'bajada'    => '',
        'items'     => [
            ['icono' => 'reloj', 'titulo' => '', 'texto' => 'No entiendes la lógica detrás de los horarios y sientes que todo es prueba y error.'],
            ['icono' => 'bebe-sol', 'titulo' => '', 'texto' => 'Tu bebé vive cansado — siestas cortas, mal humor y un círculo vicioso que no para.'],
            ['icono' => 'reloj-de-arena', 'titulo' => '', 'texto' => 'Has probado diferentes horarios online y sigues sin encontrar el que funciona para tu bebé.'],
            ['icono' => 'biberon', 'titulo' => '', 'texto' => 'Cada vez que crees haber encontrado el horario ideal, algo cambia y vuelves a cero.'],
            ['icono' => 'bebe-envuelto', 'titulo' => '', 'texto' => 'Los cuadros de ventanas de vigilia que encuentras online no aplican para tu bebé.'],
            ['icono' => 'reloj', 'titulo' => '', 'texto' => 'No sabes cómo ajustar los horarios a medida que tu bebé crece.'],
        ],
        'no_titulo' => '',
        'no_items'  => '',
    ],
    'recien-nacidos-go' => [
        'fondo'     => 'crema',
        'corazon'   => false,
        'titulo'    => 'No es otro curso de sueño infantil.',
        'bajada'    => 'Es el único recurso que combina la ciencia del sueño del recién nacido con herramientas prácticas aplicables desde hoy — en videos cortos, claros y sin abrumarte.',
        'items'     => [
            ['icono' => 'bebe-sol', 'titulo' => 'Ciencia + práctica, sin complicaciones', 'texto' => 'Entiende cómo funciona el sueño de tu bebé según su edad y etapa — y qué puedes hacer hoy mismo. Sin teoría interminable, sin tecnicismos.'],
            ['icono' => 'reloj-de-arena', 'titulo' => 'A tu ritmo, sin presión', 'texto' => 'No es un plan rígido. Prueba las técnicas que se adapten mejor a ti y a tu bebé — sin estrés, sin culpa.'],
            ['icono' => 'bebe-envuelto', 'titulo' => 'Compatible con tu crianza', 'texto' => 'Funciona con lactancia materna exclusiva, colecho y crianza con apego. No tienes que elegir entre dormir mejor y criar como quieres.'],
        ],
        'no_titulo' => '',
        'no_items'  => '',
    ],
];
