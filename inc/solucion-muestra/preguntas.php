<?php

/**
 * TEMPORAL (F4): layout `preguntas` de cada solución, con los textos de Figma (specs/solucion.md).
 * El diseño solo trae la respuesta de la primera pregunta (y A Dormir Por Fin, ninguna): las demás
 * van vacías y no se muestran hasta que la clienta las complete. Se borra en la F5.
 */

defined('ABSPATH') || exit;

return [
    'angelita-pro' => [
        'fondo'  => 'crema',
        'titulo' => '¡Respondemos a tus preguntas!',
        'items'  => [
            ['pregunta' => '¿Va a llorar mi bebé?', 'respuesta' => 'Es probable que proteste — está aprendiendo algo nuevo. Pero ninguno de los dos métodos implica dejarlo llorando solo sin respuesta. La evidencia científica confirma que el entrenamiento de sueño es seguro, efectivo y no afecta el apego ni el desarrollo emocional del bebé.'],
            ['pregunta' => '¿Cuándo se activa Angelita?', 'respuesta' => ''],
            ['pregunta' => '¿Funciona si doy pecho?', 'respuesta' => ''],
            ['pregunta' => '¿Puedo agregar más tiempo?', 'respuesta' => ''],
            ['pregunta' => '¿Angelita reemplaza a Bárbara?', 'respuesta' => ''],
        ],
    ],
    'a-dormir-por-fin' => [
        'fondo'  => 'crema',
        'titulo' => '¡Respondemos a tus preguntas!',
        'items'  => [
            ['pregunta' => '¿Cómo funciona el proceso?', 'respuesta' => ''],
            ['pregunta' => '¿En cuánto tiempo veo resultados?', 'respuesta' => ''],
            ['pregunta' => '¿Cómo se agenda la videollamada?', 'respuesta' => ''],
            ['pregunta' => '¿Funciona con lactancia y colecho?', 'respuesta' => ''],
            ['pregunta' => '¿Qué pasa si mi bebé se enferma?', 'respuesta' => ''],
            ['pregunta' => '¿Esta asesoría es para niños mayores de 2 años?', 'respuesta' => ''],
            ['pregunta' => '¿Cuántos cupos hay?', 'respuesta' => ''],
        ],
    ],
    'horarios-ideales' => [
        'fondo'  => 'crema',
        'titulo' => '¡Respondemos a tus preguntas!',
        'items'  => [
            ['pregunta' => '¿Cómo funciona el curso?', 'respuesta' => 'Acceso inmediato. Videos cortos que puedes ver en un solo día. Incluye guías descargables.'],
            ['pregunta' => '¿Por qué están divididos por edad?', 'respuesta' => ''],
            ['pregunta' => '¿Qué pasa si mi bebé está entre dos grupos?', 'respuesta' => ''],
            ['pregunta' => '¿Este curso logrará que mi bebé duerma de corrido?', 'respuesta' => ''],
            ['pregunta' => '¿Puedo comprar más de uno?', 'respuesta' => ''],
        ],
    ],
    'recien-nacidos-go' => [
        'fondo'  => 'crema',
        'titulo' => '¡Respondemos a tus preguntas!',
        'items'  => [
            ['pregunta' => '¿Este curso es para mí?', 'respuesta' => 'Si tu bebé tiene entre 0 y 12 semanas de nacido (¡edad corregida también cuenta!) o si estás embarazada, entonces sí, este curso es perfecto para ti. Especialmente si estás en busca de una base clara, amorosa y realista para ayudar a tu recién nacido a descansar mejor desde los primeros días.'],
            ['pregunta' => '¿Qué incluye este curso?', 'respuesta' => ''],
            ['pregunta' => '¿Puedo verlo en cualquier momento?', 'respuesta' => ''],
            ['pregunta' => '¿Es compatible con la lactancia materna?', 'respuesta' => ''],
            ['pregunta' => '¿Necesito comprar cosas especiales para aplicar lo que aprendo?', 'respuesta' => ''],
        ],
    ],
];
