<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial de Mundo Angelitozzz (specs/mundo-angelitozzz.md, "valor actual" de cada campo).
 * Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en el admin no se pisa.
 * Pendiente del cliente: la URL real de suscripción (por ahora https://example.com/).
 */
return [
    'page'   => 'mundo-angelitozzz',
    'fields' => [
        'mundo_angelitozzz_hero' => [
            'logo'               => ['_file' => 'assets/img/mundo-angelitozzz/hero-logo-mundo.webp'],
            'titulo'             => "El sueño de tu bebé no para de cambiar.\nCada etapa trae nuevas preguntas. Aquí siempre hay respuestas.",
            'texto'              => 'Mundo Angelitozzz es la comunidad donde resuelves tus dudas de sueño con Bárbara cada semana — para que dejes de adivinar con cada cambio.',
            'enlace_suscripcion' => 'https://example.com/',
            'boton'              => 'Quiero unirme',
            'precio'             => 'Desde $14/mes · Cancela cuando quieras',
            'regalo_texto'       => '¿Quieres regalar Mundo Angelitozzz?',
            'regalo_enlace'      => 'Escríbeme por WhatsApp.',
            'imagen'             => ['_file' => 'assets/img/mundo-angelitozzz/hero-foto-madre.webp'],
        ],
        'mundo_angelitozzz_promocinar_marca' => [
            'situaciones_titulo' => '¿Te suena alguna de estas?',
            'situaciones'        => [
                ['texto' => 'Tu bebé dormía bien y de repente todo cambió — y no sabes cómo volver.'],
                ['texto' => 'Necesitas una guía confiable sobre el sueño de tu bebé pero no sabes por dónde empezar.'],
                ['texto' => 'Cada vez que buscas en Google encuentras información contradictoria o demasiado general.'],
                ['texto' => 'Tienes preguntas pero no estás lista todavía para una asesoría completa.'],
                ['texto' => 'Sientes que vives adivinando en vez de realmente entendiendo a tu bebé.'],
            ],
            'situaciones_nota'   => 'El sueño infantil cambia constantemente. No es que lo estés haciendo mal — es que nadie te está guiando en tiempo real.',
            'perfiles_titulo'    => 'Mundo Angelitozzz es para ti si...',
            'perfiles'           => [
                ['texto' => 'Eres mamá primeriza y el sueño de tu bebé te tiene confundida — necesitas apoyarte en alguien que realmente sepa.'],
                ['texto' => 'Siempre estás dudando sobre cómo ayudar a tu bebé a dormir mejor — y el cansancio ya pesa.'],
                ['texto' => 'Quieres apoyo experto continuo sin pagar una asesoría completa cada vez que algo cambia.'],
                ['texto' => 'Buscas información confiable y directa — sin más Google, sin más contradicciones.'],
                ['texto' => 'Necesitas un espacio seguro donde preguntar sin miedo a ser juzgada.'],
                ['texto' => 'Tu bebé ya duerme bien pero quieres estar preparada para los cambios — sin retrocesos ni sorpresas.'],
            ],
        ],
        'mundo_angelitozzz_que_incluye_mundo' => [
            'antetitulo' => 'Qué incluye tu suscripción',
            'titulo'     => 'Todo lo que necesitas para nunca quedarte sin respuesta.',
            'bajada'     => 'Respuestas en vivo, contenido para cada etapa y una comunidad que te acompaña.',
            'beneficios' => [
                [
                    'imagen'   => ['_file' => 'assets/img/mundo-angelitozzz/que-incluye-sesiones-en-vivo.webp'],
                    'titulo'   => 'Sesiones en vivo con Bárbara',
                    'texto'    => 'Cada semana resuelves tus dudas en vivo.',
                    'etiqueta' => 'Miércoles · 10am Perú/Colombia',
                ],
                [
                    'imagen'   => ['_file' => 'assets/img/mundo-angelitozzz/que-incluye-talleres.webp'],
                    'titulo'   => 'Talleres y recursos grabados',
                    'texto'    => 'Regresiones, siestas, horarios, viajes y más.',
                    'etiqueta' => 'Disponibles al instante',
                ],
                [
                    'imagen'   => ['_file' => 'assets/img/mundo-angelitozzz/que-incluye-comunidad.webp'],
                    'titulo'   => 'Comunidad privada de mamás',
                    'texto'    => 'Mamás que viven lo mismo que tú, para compartir, apoyarse y acompañarse.',
                    'etiqueta' => 'Chat privado 24/7',
                ],
            ],
            'boton'      => 'Unirme a la comunidad',
        ],
        'mundo_angelitozzz_testimonios' => [
            'titulo'       => 'Lo que dicen las mamás que ya son parte de Mundo Angelitozzz.',
            'testimonios'  => [
                [
                    'texto'     => '“Bárbara, te quiero agradecer por tu guía durante todo este proceso. ¡Noah está durmiendo mejor que nunca y yo no lo puedo creer!”',
                    'autora'    => 'Raisa',
                    'detalle'   => 'Bebé de 5 meses',
                    'estrellas' => 5,
                ],
                [
                    'texto'     => '“¡Amelia es una niña feliz! Lograste lo imposible: ahora le cuento un cuento, le digo buenas noches y se duerme tranquila.”',
                    'autora'    => 'Cami',
                    'detalle'   => 'Niña de 2 años',
                    'estrellas' => 5,
                ],
                [
                    'texto'     => '“Balli, miles de gracias por la ayuda y la paciencia. Ni te imaginas cuán agradecida estoy: Martín ya duerme de corrido.”',
                    'autora'    => 'Bernardita y Martín',
                    'detalle'   => 'Bebé de 5 meses',
                    'estrellas' => 5,
                ],
                [
                    'texto'     => '“Probé de todo y nada funcionaba. Con el plan de Bárbara, en una semana mi bebé dormía en su cuna.”',
                    'autora'    => 'Carolina, mamá de Tomás',
                    'detalle'   => 'Bebé de 6 meses',
                    'estrellas' => 5,
                ],
                [
                    'texto'     => '“Pensé que nunca iba a volver a dormir una noche completa. Hoy dormimos todos.”',
                    'autora'    => 'Andrea, mamá de Lucas',
                    'detalle'   => 'Bebé de 7 meses',
                    'estrellas' => 5,
                ],
            ],
            'boton'        => ['title' => 'Ver más testimonios', 'url' => '/testimonios/'],
        ],
        'mundo_angelitozzz_cta_mundo' => [
            'titulo'     => 'Todo esto, desde $14 al mes.',
            'beneficios' => [
                ['texto' => 'Sesiones grupales en vivo con Bárbara'],
                ['texto' => 'Videoteca completa de talleres'],
                ['texto' => 'Comunidad privada de mamás'],
            ],
            'precio'     => 'Desde $14/mes · Sin contratos · Cancela cuando quieras',
            'boton'      => 'Quiero unirme a Mundo Angelitozzz',
            'nota'       => 'Sin compromisos. Cancela cuando quieras, sin penalidad.',
        ],
    ],
];
