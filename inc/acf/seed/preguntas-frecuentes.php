<?php
defined('ABSPATH') || exit;

/*
 * Contenido inicial de Preguntas frecuentes (specs/preguntas-frecuentes.md, "valor actual" de cada campo).
 * Lo carga inc/acf/runner.php una sola vez por grupo: lo que se edite en el admin no se pisa.
 * PENDIENTE DEL CLIENTE: el diseño solo trae la respuesta de la pregunta 1. Las respuestas 2 a 6 son
 * provisionales (sin datos inventados: plazos, políticas ni precios) y deben reemplazarse desde el admin.
 */
return [
    'page'   => 'preguntas-frecuentes',
    'fields' => [
        'preguntas_frecuentes_seccion_preguntas_frecuentes' => [
            'titulo'    => '¡Respondemos a tus preguntas!',
            'preguntas' => [
                [
                    'pregunta'  => '¿Cuál de las soluciones es para mí?',
                    'respuesta' => 'Depende de la edad de tu bebé y del acompañamiento que buscas. Recién Nacidos Go es para 0 a 12 semanas; los Cursos de Horarios, de 4 meses a 3 años; Angelita Pro te acompaña por WhatsApp de 4 a 24 meses, y A Dormir Por Fin es la asesoría personalizada con Bárbara. Si tienes dudas, escríbenos por WhatsApp.',
                ],
                [
                    'pregunta'  => '¿Por cuánto tiempo tengo acceso a los cursos?',
                    'respuesta' => 'Los detalles del acceso a cada curso se están actualizando. Escríbenos por WhatsApp y te confirmamos el tiempo de acceso del curso que te interesa.',
                ],
                [
                    'pregunta'  => '¿Puedo regalar un curso?',
                    'respuesta' => 'Sí, escríbenos por WhatsApp y te ayudamos a organizar el regalo para esa mamá o ese papá que lo necesita.',
                ],
                [
                    'pregunta'  => '¿Este curso logrará que mi bebé duerma de corrido?',
                    'respuesta' => 'Cada bebé es distinto y su sueño cambia con la edad. Los cursos te dan una guía clara para acompañarlo mejor; si quieres saber cuál se ajusta a tu caso, escríbenos por WhatsApp.',
                ],
                [
                    'pregunta'  => '¿Cómo funciona Mundo Angelitozzz?',
                    'respuesta' => 'Mundo Angelitozzz es la comunidad donde resuelves tus dudas de sueño con Bárbara. Puedes ver todos los detalles en la página de Mundo Angelitozzz.',
                ],
                [
                    'pregunta'  => '¿Los métodos implican dejar llorar al bebé?',
                    'respuesta' => 'Bárbara trabaja con un enfoque respetuoso y adaptado a cada familia. Si quieres conocer cómo acompañamos a tu bebé, escríbenos por WhatsApp y lo conversamos.',
                ],
            ],
        ],
        'preguntas_frecuentes_cta_final' => [
            'titulo'      => '¿No encontraste tu respuesta?',
            'descripcion' => 'Escríbenos y te ayudamos a encontrar la mejor opción para tu bebé.',
            'boton_texto' => 'Escríbeme por WhatsApp',
        ],
    ],
];
