<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial de Conoce a Bárbara (specs/conoce-a-barbara.md, "valor actual" de cada campo).
 * Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en el admin no se pisa.
 * Los botones con '#' llevan a páginas que aún no existen (la tienda y el blog): pendiente en el spec.
 */
return [
    'page'   => 'conoce-a-barbara',
    'fields' => [
        'conoce_a_barbara_hero' => [
            'antetitulo' => 'Conoce a Bárbara',
            'titulo'     => 'Hola, soy Bárbara.',
            'bajada'     => 'Del agotamiento al propósito — mi historia.',
            'texto'      => "Hola, soy Bárbara — fundadora de Angelitozzz y mamá de dos angelitos que transformaron mi vida de la manera más intensa... y dolorosa.\n\nCuando nació mi hija Emma, la maternidad me golpeó con una fuerza que no esperaba. A pesar de estar informada y “lista”, terminé envuelta en noches infinitas, llanto constante, dolor físico y una angustia que no podía nombrar. Me enfrenté a una Depresión Post Parto que me hizo sentir aislada, agotada, perdida. Me sentía una extraña en mi propia historia.\n\nY lo más duro: sentía culpa por no estar disfrutando la experiencia que se suponía debía ser la más hermosa de mi vida.",
            'imagen'     => ['_file' => 'assets/img/conoce-a-barbara/hero-foto-barbara.webp'],
        ],
        'conoce_a_barbara_reflexion' => [
            'cita'    => '“Dormía mal. Mi bebé dormía peor. Y sin descanso no podía pensar, ni decidir, ni vivir... y mucho menos amar mi maternidad como merecía hacerlo.”',
            'imagen'  => ['_file' => 'assets/img/conoce-a-barbara/reflexion-foto-barbara.webp'],
            'texto'   => "Pero algo cambió cuando entendí que necesitaba ayuda. Que mi bienestar también importa. Que para cuidar de mi bebé, primero tenía que cuidarme a mí.\n\nFue entonces cuando empecé a investigar, a formarme, a estudiar el sueño infantil — no solo como técnica, sino como una herramienta para sanar y empoderar. Y encontré mi vocación.\n\nDesde 2016 he acompañado a más de 8.000 familias en 12 países. Porque cuando un bebé duerme bien, la madre renace. Y esa transformación es lo que me mueve.\n\nHoy soy Coach de Sueño Pediátrico — pero más que eso: soy una mujer comprometida con ayudarte a soltar la culpa, tomar decisiones conscientes y vivir tu maternidad desde el goce, la salud y la conexión real. Mi enfoque es personalizado, respetuoso, basado en ciencia y lleno de humanidad.",
            'mision'  => 'Mi misión no es solo enseñarte a mejorar el sueño de tu bebé. Mi misión es devolverte el poder sobre tu maternidad.',
            'cierre'  => "Tu derecho a descansar. Tu derecho a disfrutar. Tu derecho a ser tú, entera.\nAquí empieza tu transformación. Y yo estoy aquí para caminarla contigo.",
            'firma'   => 'Con cariño infinito, Balli',
            'cargo'   => 'Tu Coach de Sueño Pediátrico',
            'boton'   => ['title' => 'Conoce mis soluciones de sueño', 'url' => '#'],
        ],
        'conoce_a_barbara_seccion_blog' => [
            'titulo' => 'Últimos artículos de nuestro blog',
            'boton'  => ['title' => 'Ver más artículos', 'url' => '#'],
        ],
        'conoce_a_barbara_cta_final' => [
            'titulo' => 'La maternidad ya tiene suficientes sacrificios. El sueño no tiene que ser uno de ellos.',
            'boton'  => ['title' => 'Encuentra tu solución', 'url' => '#'],
        ],
    ],
];
