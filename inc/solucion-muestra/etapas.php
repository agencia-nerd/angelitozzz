<?php

/**
 * TEMPORAL (F4): layout `etapas` de muestra (Horarios Ideales, 4013:19194) y los datos de sus modales
 * (modal-etapas 3752:5964). Solo la etapa de 4 a 9 meses trae texto y portada del modal en el diseño.
 * Se borra en la F5.
 */

defined('ABSPATH') || exit;

$img = static fn(string $archivo): string => get_theme_file_uri('assets/img/solucion/horarios-ideales/' . $archivo);

return [
    'horarios-ideales' => [
        'fondo'            => 'crema',
        'titulo'           => 'El sueño cambia con cada etapa. Los horarios también. Elige la tuya.',
        'modal_antetitulo' => 'Introducción al curso de Horarios',
        'items'            => [
            [
                'curso'         => 0, // f5: post_object del curso de esta etapa.
                'precio'        => '$37', // woo: precio del producto del curso.
                'edad'          => '4 a 9 meses',
                'imagen'        => $img('etapa-4-a-9-meses.webp'),
                'titulo'        => 'Curso Para bebés de 4 a 9 meses',
                'texto'         => '¿Sientes que tu bebé parece tener sueño todo el día pero duerme poco? Esta etapa es un laberinto de señales confusas. Aprenderás a identificar las ventanas de vigilia correctas, evitar el círculo vicioso de siestas cortas y descubrir el secreto de la última ventana del día para que las tres siestas funcionen como relojito. Todo lo que necesitas para descifrar el horario ideal — sin adivinar.',
                'modal_titulo'  => 'Para bebés de 4 a 9 meses',
                'modal_texto'   => '¿Sientes que tu bebé parece tener sueño todo el día pero igual duerme poco? En este curso aprenderás a priorizar las ventanas de vigilia y a evitar el círculo de siestas cortas y horarios caóticos. Descubre cómo lograr que las tres siestas funcionen como un reloj.',
                'modal_video'   => '',
                'modal_portada' => $img('etapa-video-portada.webp'),
            ],
            [
                'curso'         => 0,
                'precio'        => '$37', // woo
                'edad'          => '9 a 15 meses',
                'imagen'        => $img('etapa-9-a-15-meses.webp'),
                'titulo'        => "Curso Para bebés de\n9 a 15 meses",
                'texto'         => 'Los horarios cambiaron, tu bebé dejó la tercera siesta — y el caos amenaza con volver. Esta etapa viene cargada de cambios, crisis y confusión. Aprenderás a combinar ventanas de vigilia con los horarios ideales para su edad y su tanque de sueño — para que tus días fluyan mejor y tus noches vuelvan a ser tranquilas.',
                'modal_titulo'  => 'Para bebés de 9 a 15 meses',
                'modal_texto'   => '',
                'modal_video'   => '',
                'modal_portada' => '',
            ],
            [
                'curso'         => 0,
                'precio'        => '$37', // woo
                'edad'          => '15 a 36 meses',
                'imagen'        => $img('etapa-15-a-36-meses.webp'),
                'titulo'        => 'Curso Para bebés de 15 a 36 meses',
                'texto'         => 'Bienvenida a la etapa de los mini exploradores que ya no quieren siestas y madrugan como si fueran gallos. Tu guía para las crisis de los 18 y 24 meses, entender qué hacer cuando la siesta desaparece y cómo reajustar los horarios cuando todo parece desbordarse. Más que horarios — claridad y calma.',
                'modal_titulo'  => 'Para bebés de 15 a 36 meses',
                'modal_texto'   => '',
                'modal_video'   => '',
                'modal_portada' => '',
            ],
        ],
    ],
];
