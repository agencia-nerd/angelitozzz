<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial de Inicio (specs/home.md, "valor actual" de cada campo).
 * Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en el admin no se pisa.
 * Los botones con '#' llevan a páginas o cursos que aún no existen (pendiente en el spec).
 */
return [
    'page'   => 'home',
    'fields' => [
        'home_hero' => [
            'eyebrow'      => 'Coach de Sueño Pediátrico · Desde 2016',
            'titulo'       => "La maternidad tiene muchos sacrificios.\nEl sueño no debería ser uno de ellos.",
            'bajada'       => 'Cursos, asesorías y acompañamiento para que tu bebé aprenda a dormir bien — desde recién nacido hasta los 4 años.',
            'credenciales' => [
                ['texto' => '8.000+ familias acompañadas'],
                ['texto' => '10 años de experiencia'],
                ['texto' => '12+ países'],
            ],
            'boton'  => ['title' => 'Encuentra tu curso ideal', 'url' => '#'],
            'imagen' => ['_file' => 'assets/img/home/hero-foto-madre-bebe.webp'],
        ],
        'home_como_ayudarte' => [
            'titulo' => '¿Cómo puedo ayudarte?',
            'bajada' => 'Distintas formas de acompañarte, según lo que necesitas hoy.',
            'items'  => [
                [
                    'imagen'         => '', // Sin imagen a propósito: la tarjeta usa la imagen por defecto.
                    'etiqueta'       => 'Nuevo',
                    'etiqueta_icono' => 'destellos',
                    'titulo'         => 'Angelita Pro',
                    'edad'           => '(4 a 24 meses)',
                    'descripcion'    => "Tu coach de sueño con IA, disponible 24/7 por WhatsApp.\n\nPlan integral de sueño, respuestas inmediatas, sin turnos ni horarios.",
                    'boton'          => ['title' => 'Quiero conocer a Angelita', 'url' => '#'],
                ],
                [
                    'imagen'         => ['_file' => 'assets/img/home/ayudarte-asesoria-barbara.webp'],
                    'etiqueta'       => 'Asesoría con Bárbara',
                    'etiqueta_icono' => 'estrella',
                    'titulo'         => 'A Dormir Por Fin',
                    'edad'           => '(4 meses a 4 años)',
                    'descripcion'    => "Bárbara diseña el plan para tu bebé y te acompaña durante 3 semanas.\n\nPara quien quiere la mano de Bárbara directamente.",
                    'boton'          => ['title' => 'Quiero mi asesoría personal', 'url' => '#'],
                ],
                [
                    'imagen'         => ['_file' => 'assets/img/home/ayudarte-recien-nacidos-go.webp'],
                    'etiqueta'       => 'Curso Online',
                    'etiqueta_icono' => 'ninguno',
                    'titulo'         => 'Recién Nacidos Go',
                    'edad'           => '(0 a 12 semanas)',
                    'descripcion'    => "Entiende el sueño de tu recién nacido desde el día uno.\n\nVideos cortos, guías prácticas y bases sólidas para toda la etapa.",
                    'boton'          => ['title' => 'Ver curso', 'url' => '#'],
                ],
                [
                    'imagen'         => ['_file' => 'assets/img/home/ayudarte-horarios-ideales.webp'],
                    'etiqueta'       => 'Curso Online',
                    'etiqueta_icono' => 'ninguno',
                    'titulo'         => 'Cursos de Horarios Ideales',
                    'edad'           => '(4 meses a 3 años)',
                    'descripcion'    => "Aprende a crear el horario perfecto para tu bebé según su edad.\n\nDisponible para 4–9, 9–15 y 15–36 meses.",
                    'boton'          => ['title' => 'Ver cursos', 'url' => '#'],
                ],
            ],
        ],
        'home_quien_soy' => [
            'titulo' => '¡Hola, soy Bárbara!',
            'texto'  => "Soy Coach de Sueño Pediátrico certificada, mami de dos y fundadora de Angelitozzz desde 2016.\nEn 2012, cuando nació mi hija mayor, conocí de primera mano la desesperación de no dormir y la culpa de no saber cómo resolverlo.\nEn 10 años he acompañado a más de 8.000 familias en 12 países a lograr algo que muchas creían imposible: que su bebé durmiera de corrido — noches y siestas.\nPara que no te pase lo que me pasó a mí.\nPara que tu bebé, tu familia y tú puedan florecer.",
            'boton'  => ['title' => 'Conoce mi historia', 'url' => ['_page' => 'conoce-a-barbara']],
            'imagen' => ['_file' => 'assets/img/home/quien-soy-foto-barbara.webp'],
        ],
        'home_seccion_cta' => [
            'logo'   => ['_file' => 'assets/img/home/cta-logo-mundo-angelitozzz.webp', 'alt' => 'Mundo Angelitozzz'],
            'titulo' => "El sueño de tu bebé cambia todo el tiempo.\nY cada vez que cambia, aparecen nuevas preguntas.\nMundo Angelitozzz es el lugar donde siempre hay respuestas.",
            'texto'  => 'Cada semana, una sesión en vivo conmigo para resolver lo que está pasando ahora mismo con el sueño de tu bebé. Más talleres grabados, recursos al instante y una comunidad de mamás que ya pasaron por donde tú estás.',
            'puntos' => [
                ['texto' => 'Sesiones en vivo semanales con Bárbara — resuelves dudas en tiempo real'],
                ['texto' => 'Talleres y recursos para cada etapa, disponibles al instante'],
                ['texto' => 'Chat privado con mamás que entienden lo que estás viviendo'],
            ],
            'precio' => 'Desde $14/mes · Cancela cuando quieras',
            'boton'  => ['title' => 'Quiero tener siempre una respuesta', 'url' => ['_page' => 'mundo-angelitozzz']],
        ],
        'home_beneficios' => [
            'titulo' => 'Beneficios del sueño saludable',
            'items'  => [
                ['texto' => 'Dormir promueve el sano crecimiento y desarrollo de tu bebé.'],
                ['texto' => 'Un bebé descansado es más feliz y menos estresado.'],
                ['texto' => 'Mejora la salud mental materna y fortalece el vínculo madre-bebé.'],
                ['texto' => 'Dormir bien fomenta su aprendizaje y desarrollo del lenguaje'],
                ['texto' => 'Mejora la tolerancia a la frustración.'],
            ],
        ],
        'home_seccion_instagram' => [
            'titulo'   => '¡Síguenos en Instagram!',
            'imagenes' => [
                ['_file' => 'assets/img/home/instagram-post-1.webp'],
                ['_file' => 'assets/img/home/instagram-post-2.webp'],
                ['_file' => 'assets/img/home/instagram-post-3.webp'],
            ],
            // URL del perfil de Instagram: aún no está.
            'boton' => ['title' => 'Síguenos', 'url' => '#', 'target' => '_blank'],
        ],
        'home_testimonios' => [
            'titulo' => 'SHHH... Bebés durmiendo felices',
            'items'  => [
                [
                    'texto'     => '“Probé de todo y nada funcionaba. Con el plan de Bárbara, en una semana mi bebé dormía en su cuna.”',
                    'autora'    => 'Carolina, mamá de Tomás',
                    'detalle'   => 'Bebé de 6 meses',
                    'estrellas' => 5,
                ],
                [
                    'texto'     => '“Bárbara, te hago un altar: en 4 días avanzamos lo que jamás pensé lograr, sin dejarlo llorar.”',
                    'autora'    => 'Daniela, mamá de Mateo',
                    'detalle'   => 'Bebé de 8 meses',
                    'estrellas' => 5,
                ],
                [
                    'texto'     => '“Compré el curso con mi hija de 8 semanas; a los 4 meses dormía de corrido. Hoy tiene 2 años y sigue durmiendo increíble.”',
                    'autora'    => 'Lucía, mamá de Emilia',
                    'detalle'   => 'Bebé de 2 años',
                    'estrellas' => 5,
                ],
                [
                    'texto'     => '“Mi bebé tiene 12 días y ya la puedo poner en el moisés. Antes solo dormía en brazos.”',
                    'autora'    => 'Valeria, mamá de Joaquín',
                    'detalle'   => 'Bebé de 12 días',
                    'estrellas' => 5,
                ],
                [
                    'texto'     => '“Pensé que nunca iba a volver a dormir una noche completa. Hoy dormimos todos.”',
                    'autora'    => 'Andrea, mamá de Lucas',
                    'detalle'   => 'Bebé de 7 meses',
                    'estrellas' => 5,
                ],
            ],
            'boton' => ['title' => 'Ver más testimonios', 'url' => ['_page' => 'testimonios']],
        ],
        'home_cta_final' => [
            'titulo' => 'La maternidad ya tiene suficientes sacrificios. El sueño no tiene que ser uno de ellos.',
            'boton'  => ['title' => 'Encuentra tu solución', 'url' => '#'],
        ],
    ],
];
