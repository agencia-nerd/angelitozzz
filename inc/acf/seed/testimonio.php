<?php
defined('ABSPATH') || exit;

/*
 * Testimonios de ejemplo del diseño (specs/testimonio.md y specs/testimonios.md).
 * Nombre = título, testimonio = contenido, categoría = término de categoria_testimonio (el campo la carga del término).
 * 'imagen' es la imagen destacada: el motor no la lee; la pone inc/acf/fotos-destacadas.php (una sola vez por testimonio).
 * Los carga inc/acf/runner.php una sola vez por item.
 */
return [
    'post_type' => 'testimonio',
    'terms'     => ['categoria_testimonio' => ['Soluciones de Sueño', 'Mundo Angelitozzz']],
    'items'     => [
        [
            'id'        => 'testimonio-giannina',
            'titulo'    => 'Giannina',
            'imagen'    => ['_file' => 'assets/img/testimonios/testimonio-foto-giannina.webp', 'alt' => 'Giannina'],
            'contenido' => 'Balli y la comunidad de Mundo Angelitozzz me han enseñado muchísimo! Tanto los talleres, como la interacción en el chat, son herramientas super valiosas para mí. Maternar entre varias que pasan por lo mismo no tiene precio. Empecé con mi hija mayor hace 5 años y sigo aplicando lo que aprendí con mis otros dos hijos <3.',
            'terms'     => ['categoria_testimonio' => ['Mundo Angelitozzz']],
            'fields'    => ['testimonio_ficha' => ['estrellas' => 5, 'edad' => 'Bebé de 2.8 años']],
        ],
        [
            'id'        => 'testimonio-andrea',
            'titulo'    => 'Andrea',
            'imagen'    => ['_file' => 'assets/img/testimonios/testimonio-foto-andrea.webp', 'alt' => 'Andrea'],
            'contenido' => 'Hola Balli!! Quería agradecerte por estas semanas de acompañamiento: pasamos de mini siestas de 30 min a siestas de hora y media, y de levantarme 5 veces en la noche a que duerma de corrido!! Me has dado mucha tranquilidad y por fin puedo entender a mi gordito.',
            'terms'     => ['categoria_testimonio' => ['Soluciones de Sueño']],
            'fields'    => ['testimonio_ficha' => ['estrellas' => 5, 'edad' => 'Bebé de 5 meses']],
        ],
        [
            'id'        => 'testimonio-javiera',
            'titulo'    => 'Javiera',
            'imagen'    => ['_file' => 'assets/img/testimonios/testimonio-foto-javiera.webp', 'alt' => 'Javiera'],
            'contenido' => 'Balli, quiero darte las gracias por toda tu ayuda en el entrenamiento del sueño de mis hijos, primero con Borja y ahora con León de 6 meses. Con tu enfoque compasivo diseñaste un plan personalizado que nos ayudó a establecer rutinas adecuadas. ¡Estamos eternamente agradecidos!',
            'terms'     => ['categoria_testimonio' => ['Soluciones de Sueño']],
            'fields'    => ['testimonio_ficha' => ['estrellas' => 5, 'edad' => 'Bebé de 2 años']],
        ],
        [
            'id'        => 'testimonio-bernardita',
            'titulo'    => 'Bernardita',
            'imagen'    => ['_file' => 'assets/img/testimonios/testimonio-foto-bernardita.webp', 'alt' => 'Bernardita'],
            'contenido' => 'Hay un antes y un después luego de tu asesoría. No fue fácil, pero la consistencia y la seguridad que nos dabas permitió que Lara, con 7 meses, aprenda a dormir sola y en su propia habitación. Gracias además al grupo que nos da orientación permanente.',
            'terms'     => ['categoria_testimonio' => ['Mundo Angelitozzz']],
            'fields'    => ['testimonio_ficha' => ['estrellas' => 5, 'edad' => 'Bebé de 5 meses']],
        ],
        [
            'id'        => 'testimonio-joanna',
            'titulo'    => 'Joanna',
            'imagen'    => ['_file' => 'assets/img/testimonios/testimonio-foto-joanna.webp', 'alt' => 'Joanna'],
            'contenido' => 'Sufrí una fuerte depresión postparto. Encontré un post de Bárbara con su historia y me identifiqué completamente. Comenzamos el curso: al principio fue desafiante, pero mantuvimos horarios y rutinas firmes, y TODO CAMBIÓ.',
            'terms'     => ['categoria_testimonio' => ['Soluciones de Sueño']],
            'fields'    => ['testimonio_ficha' => ['estrellas' => 5, 'edad' => 'Bebé de 9 meses']],
        ],
    ],
];
