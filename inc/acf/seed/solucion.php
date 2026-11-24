<?php
defined('ABSPATH') || exit;

/*
 * Las 4 soluciones de sueño del diseño (specs/solucion.md y specs/soluciones-de-sueno.md).
 * Nombre = título; 'imagen' = imagen destacada (la foto de la tarjeta del listado): el motor no la lee,
 * la pone inc/acf/fotos-destacadas.php una sola vez por solución.
 * El precio no es un campo: sale del producto de WooCommerce de cada curso (F5-b). Los cursos que vende
 * cada solución (solucion_compra_cursos) y el curso de cada etapa se ligan desde el admin.
 * La fecha da el orden del listado (más nueva primero). Los carga inc/acf/runner.php una sola vez por item.
 */
return [
    'post_type' => 'solucion',
    'items' => [
        [
            'id' => 'solucion-angelita-pro',
            'titulo' => 'Angelita Pro',
            'fecha' => '2026-11-04 09:03:00',
            'imagen' => '',
            'fields' => [
                'solucion_hero' => [
                    'etiqueta' => 'Nuevo',
                    'etiqueta_icono' => 'destellos',
                    'titular' => 'Angelita Pro',
                    'subtitulo' => 'Coach de sueño con IA · 4 a 24 meses',
                    'descripcion' => 'Conoce a Angelita: la coach de sueño que nunca duerme — para que tú y tu bebé por fin puedan hacerlo. Entrenada con 10 años de experiencia real y más de 8.000 familias acompañadas. Disponible a cualquier hora, por WhatsApp. Sin citas. Sin esperas.',
                    'datos' => [
                        [
                            'icono' => 'reloj',
                            'texto' => '2 semanas 24/7',
                        ],
                        [
                            'icono' => 'whatsapp',
                            'texto' => 'WhatsApp',
                        ],
                    ],
                    'imagen' => '',
                    'boton' => 'Quiero a Angelita',
                    'nota' => '7 días de prueba completa',
                    'regalo' => true,
                    'whatsapp' => false,
                ],
                'solucion_tarjeta' => [
                    'etiqueta' => 'Nuevo',
                    'etiqueta_icono' => 'destellos',
                    'edad' => '(4 a 24 meses)',
                    'descripcion' => 'Tu coach de sueño con inteligencia artificial, entrenada con el método Angelitozzz. Disponible 24/7 por WhatsApp — sin turnos, sin horarios, sin esperas.',
                    'beneficios' => [
                        [
                            'texto' => 'Plan integral de sueño — noches y siestas',
                        ],
                        [
                            'texto' => 'Respuestas inmediatas a tus dudas en tiempo real',
                        ],
                        [
                            'texto' => 'Seguimiento paso a paso las 24 horas',
                        ],
                        [
                            'texto' => 'Acceso desde cualquier lugar por WhatsApp',
                        ],
                        [
                            'texto' => 'Empieza esta noche',
                        ],
                    ],
                    'boton' => 'Quiero conocer a Angelita',
                ],
                'solucion_compra' => [
                    'tipo' => 'suscripcion',
                    'descuentos' => [],
                ],
                'solucion_secciones' => [
                    'bloques' => [
                        [
                            'acf_fc_layout' => 'iconos',
                            'fondo' => 'crema',
                            'corazon' => true,
                            'titulo' => 'Angelita Pro es para ti si...',
                            'bajada' => '',
                            'items' => [
                                [
                                    'icono' => 'reloj-de-arena',
                                    'titulo' => '',
                                    'texto' => 'Sus siestas duran 30 minutos o menos — o son una lucha constante',
                                ],
                                [
                                    'icono' => 'bebe-luna',
                                    'titulo' => '',
                                    'texto' => 'Tu bebé tiene entre 4 y 24 meses y se despierta varias veces por noche',
                                ],
                                [
                                    'icono' => 'cuna',
                                    'titulo' => '',
                                    'texto' => 'Necesitas apoyo real a cualquier hora, incluso a las 3am — sin citas, sin esperas',
                                ],
                                [
                                    'icono' => 'bebe-envuelto',
                                    'titulo' => '',
                                    'texto' => 'Estás lista para dejar de vivir agotada, confundida y al límite',
                                ],
                                [
                                    'icono' => 'biberon',
                                    'titulo' => '',
                                    'texto' => 'Quieres avanzar sin dudar ni cometer errores que frustren tus avances',
                                ],
                            ],
                            'no_titulo' => '',
                            'no_items' => '',
                        ],
                        [
                            'acf_fc_layout' => 'lista',
                            'fondo' => 'crema',
                            'titulo' => '¿Qué incluye Angelita Pro?',
                            'imagen' => '',
                            'items' => [
                                [
                                    'texto' => 'Angelita — 2 semanas de acompañamiento 24/7 por WhatsApp. La activas cuando estés lista.',
                                ],
                                [
                                    'texto' => 'Videos de preparación e implementación: Bárbara te explica qué va a pasar cada noche.',
                                ],
                                [
                                    'texto' => 'Tu Plan de Sueño descargable: el paso a paso completo, siempre a mano.',
                                ],
                                [
                                    'texto' => 'Plan de siestas incluido: noches y siestas, tú decides si trabajas ambas o solo una.',
                                ],
                                [
                                    'texto' => 'Semanas adicionales a $27 USD cada una, si necesitas más tiempo.',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'beneficios',
                            'fondo' => 'crema',
                            'titulo' => 'Cómo funciona',
                            'bajada' => 'Angelita no trabaja sola. Es un sistema completo de tres partes.',
                            'items' => [
                                [
                                    'icono' => 'libro',
                                    'texto' => 'El plan: videos temáticos y Tu Plan de Sueño, disponibles desde tu compra.',
                                ],
                                [
                                    'icono' => 'diamante',
                                    'texto' => 'La activación: cuando estés lista, le dices a Angelita “esta noche empiezo”.',
                                ],
                                [
                                    'icono' => 'video',
                                    'texto' => 'Angelita: te acompaña 24/7 por WhatsApp durante 2 semanas.',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'galeria',
                            'fondo' => 'rosa',
                            'titulo' => 'Conoce Angelita Pro por dentro',
                            'bajada' => 'En este video Bárbara te explica cómo usar Angelita y cómo es tu plan de sueño.',
                            'diapositivas' => [
                                [
                                    'imagen' => [
                                        '_file' => 'assets/img/solucion/angelita-pro/galeria-video-madre-bebe.webp',
                                    ],
                                    'video' => '',
                                ],
                            ],
                            'texto' => 'Tienes 4 meses de acceso a los videos y activas a Angelita cuando tú decidas.',
                            'boton' => 'Quiero a Angelita',
                            'boton_precio' => false,
                        ],
                        [
                            'acf_fc_layout' => 'testimonios',
                            'fondo' => 'beige',
                            'titulo' => '¡Historias reales de mamás felices!',
                            'items' => [
                                [
                                    'texto' => 'Mi bebé duerme 11 horas de corrido. No lo puedo creer.',
                                    'autora' => 'María L., Colombia',
                                ],
                                [
                                    'texto' => 'Hay un antes y un después de la asesoría. La consistencia y la seguridad que nos dabas permitió que Lara aprenda a dormir sola.',
                                    'autora' => 'Bernardita, mamá de Lara (7 meses)',
                                ],
                                [
                                    'texto' => 'Por fin entendí las ventanas de vigilia de mi bebé. En una semana sus siestas pasaron de 30 minutos a más de una hora.',
                                    'autora' => 'Andrea, mamá de Lucas (7 meses)',
                                ],
                                [
                                    'texto' => 'Mi bebé tiene 12 días y ya la puedo poner en el moisés. Antes solo dormía en brazos.',
                                    'autora' => 'Valeria, mamá de Joaquín (12 días)',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'preguntas',
                            'fondo' => 'crema',
                            'titulo' => '¡Respondemos a tus preguntas!',
                            'items' => [
                                [
                                    'pregunta' => '¿Va a llorar mi bebé?',
                                    'respuesta' => 'Es probable que proteste — está aprendiendo algo nuevo. Pero ninguno de los dos métodos implica dejarlo llorando solo sin respuesta. La evidencia científica confirma que el entrenamiento de sueño es seguro, efectivo y no afecta el apego ni el desarrollo emocional del bebé.',
                                ],
                                [
                                    'pregunta' => '¿Cuándo se activa Angelita?',
                                    'respuesta' => '',
                                ],
                                [
                                    'pregunta' => '¿Funciona si doy pecho?',
                                    'respuesta' => '',
                                ],
                                [
                                    'pregunta' => '¿Puedo agregar más tiempo?',
                                    'respuesta' => '',
                                ],
                                [
                                    'pregunta' => '¿Angelita reemplaza a Bárbara?',
                                    'respuesta' => '',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 'solucion-a-dormir-por-fin',
            'titulo' => 'A Dormir Por Fin',
            'fecha' => '2026-11-04 09:02:00',
            'imagen' => [
                '_file' => 'assets/img/shop-a-dormir-por-fin.webp',
                'alt' => 'A Dormir Por Fin',
            ],
            'fields' => [
                'solucion_hero' => [
                    'etiqueta' => 'Asesoría personalizada',
                    'titular' => "A Dormir Por Fin.\nTu bebé durmiendo 10 a 12 horas de corrido — con un plan diseñado por Bárbara, *para ti*.",
                    'subtitulo' => 'Asesoría Personalizada · 4 meses a 4 años',
                    'descripcion' => 'Tres semanas de acompañamiento directo conmigo por WhatsApp. Cada duda, cada pregunta, cada noche difícil — respondida por Bárbara personalmente.',
                    'datos' => [
                        [
                            'icono' => 'reloj',
                            'texto' => '3 semanas',
                        ],
                        [
                            'icono' => 'barbara',
                            'texto' => 'Con Bárbara',
                        ],
                    ],
                    'imagen' => [
                        '_file' => 'assets/img/solucion/a-dormir-por-fin/hero-foto.webp',
                    ],
                    'boton' => 'Comprar ahora',
                    'nota' => '7 días de prueba completa',
                    'regalo' => true,
                    'whatsapp' => false,
                ],
                'solucion_tarjeta' => [
                    'etiqueta' => 'Asesoría con Bárbara',
                    'etiqueta_icono' => 'estrella',
                    'edad' => '(4 meses a 4 años)',
                    'descripcion' => 'Bárbara diseña el plan específico para tu bebé y te acompaña de cerca durante 3 semanas.',
                    'beneficios' => [
                        [
                            'texto' => 'Plan personalizado para noches y/o siestas',
                        ],
                        [
                            'texto' => 'WhatsApp ilimitado con Bárbara durante 3 semanas',
                        ],
                        [
                            'texto' => 'Ajustes diarios según la respuesta de tu bebé',
                        ],
                        [
                            'texto' => 'Acceso premium directo con Bárbara',
                        ],
                    ],
                    'boton' => 'Quiero mi asesoría personal',
                ],
                'solucion_compra' => [
                    'tipo' => 'directa',
                    'descuentos' => [],
                ],
                'solucion_secciones' => [
                    'bloques' => [
                        [
                            'acf_fc_layout' => 'comparacion',
                            'fondo' => 'blanco',
                            'titulo_izquierda' => '¿Te suena familiar?',
                            'texto_izquierda' => "Llevas meses sin dormir una noche completa\nTu bebé solo duerme en brazos, con pecho o contigo en la cama\nYa intentaste métodos, consejos, rutinas — nada funciona de forma consistente\nTe sientes agotada, confundida y al límite\nQuieres una solución real — no otro consejo genérico de internet",
                            'titulo_derecha' => 'Ahora imagina esto.',
                            'texto_derecha' => "Acuestas a tu bebé en su cuna — y se duerme solo.\nDuerme 10 a 12 horas de corrido, sin despertarte.\nSus siestas son largas, predecibles y en los horarios correctos para su edad.\nLas tomas nocturnas innecesarias — eliminadas, gradual y respetuosamente.\nY tú — descansada, con energía, disfrutando tu maternidad.",
                        ],
                        [
                            'acf_fc_layout' => 'iconos',
                            'fondo' => 'crema',
                            'corazon' => true,
                            'titulo' => 'Esta asesoría es para ti si...',
                            'bajada' => '',
                            'items' => [
                                [
                                    'icono' => 'bebe-luna',
                                    'titulo' => '',
                                    'texto' => 'Prefieres trabajar directamente con Bárbara — la persona detrás del método.',
                                ],
                                [
                                    'icono' => 'reloj-de-arena',
                                    'titulo' => '',
                                    'texto' => 'Tu caso es complejo — múltiples intentos fallidos, gemelos, situaciones médicas, colecho arraigado.',
                                ],
                                [
                                    'icono' => 'documento',
                                    'titulo' => '',
                                    'texto' => 'Tu bebé o niño tiene entre 4 meses y 4 años — incluyendo mayores de 2 años y niños en su propia cama.',
                                ],
                                [
                                    'icono' => 'cuna',
                                    'titulo' => '',
                                    'texto' => 'Sus siestas son cortas, con mucha lucha o en horarios que no funcionan.',
                                ],
                                [
                                    'icono' => 'bebe-envuelto',
                                    'titulo' => '',
                                    'texto' => 'Solo duerme en brazos, con pecho o en movimiento — y quieres cambiar eso.',
                                ],
                                [
                                    'icono' => 'biberon',
                                    'titulo' => '',
                                    'texto' => 'Quieres la certeza de que alguien con 10 años de experiencia está leyendo tu caso.',
                                ],
                                [
                                    'icono' => 'reloj-de-arena',
                                    'titulo' => '',
                                    'texto' => 'Quieres sentirte completamente acompañada y segura en cada paso.',
                                ],
                            ],
                            'no_titulo' => 'Esta asesoría NO es para ti si...',
                            'no_items' => "Tu bebé tiene menos de 4 meses o más de 4 años\nBuscas una solución sin ningún compromiso de tu parte",
                        ],
                        [
                            'acf_fc_layout' => 'lista',
                            'fondo' => 'crema',
                            'titulo' => '¿Qué incluye tu asesoría?',
                            'imagen' => [
                                '_file' => 'assets/img/solucion/a-dormir-por-fin/lista-foto.webp',
                            ],
                            'items' => [
                                [
                                    'texto' => 'Videollamada inicial — una sesión de 1 hora para conocernos y crear el plan ideal para ti y tu bebé de acuerdo a tu estilo.',
                                ],
                                [
                                    'texto' => 'Plan de sueño integral personalizado — métodos, horarios, rutinas y todo lo que necesitas para empezar.',
                                ],
                                [
                                    'texto' => '3 semanas de WhatsApp ilimitado con Bárbara — cada duda, cada pregunta, respondida por Bárbara personalmente.',
                                ],
                                [
                                    'texto' => 'Plan de siestas incluido (si deseas).',
                                ],
                                [
                                    'texto' => 'Ajustes según la respuesta de tu bebé.',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'beneficios',
                            'fondo' => 'crema',
                            'titulo' => 'Además, incluye',
                            'bajada' => 'Todo por {precio} USD · Cupos limitados — trabajo con un número reducido de familias a la vez.',
                            'items' => [
                                [
                                    'icono' => 'libro',
                                    'texto' => 'E-book con guías de horarios — 4 meses a 3 años. Para navegar cada cambio con confianza.',
                                ],
                                [
                                    'icono' => 'diamante',
                                    'texto' => '1 mes en Mundo Angelitozzz — 4 sesiones en vivo con Bárbara y videoteca completa.',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'experiencia',
                            'fondo' => 'rosa',
                            'titulo' => 'La experiencia más exclusiva de Angelitozzz.',
                            'texto' => "^Acceso directo a Bárbara. Sin intermediarios. Sin sistemas.^\n\nAngelita fue entrenada con mi método y puede acompañar a miles de familias simultáneamente. Yo no puedo — y eso es exactamente lo que hace esta asesoría especial. Recibo una cantidad limitada de familias al mismo tiempo para garantizar mi total atención a cada caso.\n\nEsta asesoría es para quien quiere trabajar con la persona detrás del método. Para casos complejos que necesitan una mirada experta. Para mamás que prefieren un ser humano de su lado. Y para bebés y niños de hasta 4 años — incluyendo mayores de 2 años y niños que duermen en su propia cama.\n\n^El resultado: tu bebé durmiendo 10 a 12 horas de corrido por las noches, con siestas largas y sin batalla, en los horarios perfectos para su edad.^",
                            'imagen' => [
                                '_file' => 'assets/img/solucion/a-dormir-por-fin/experiencia-foto.webp',
                            ],
                            'whatsapp' => true,
                            'whatsapp_texto' => 'Escríbeme por WhatsApp',
                        ],
                        [
                            'acf_fc_layout' => 'pasos',
                            'fondo' => 'salvia',
                            'antetitulo' => 'Cómo funciona la asesoría',
                            'titulo' => 'Un proceso de tres pasos — simple, claro y completamente guiado.',
                            'bajada' => 'Cada semana te guiaremos con estrategias claras y prácticas adaptadas al crecimiento y desarrollo de tu bebé.',
                            'items' => [
                                [
                                    'imagen' => [
                                        '_file' => 'assets/img/solucion/a-dormir-por-fin/pasos-1.webp',
                                    ],
                                    'titulo' => 'Videollamada inicial',
                                    'texto' => 'Una sesión de 1 hora para conocernos y crear el plan de sueño ideal para ti y tu bebé, de acuerdo a tu estilo.',
                                    'etiqueta' => '1 hora · por videollamada',
                                ],
                                [
                                    'imagen' => [
                                        '_file' => 'assets/img/solucion/a-dormir-por-fin/pasos-2.webp',
                                    ],
                                    'titulo' => 'Tu plan de sueño',
                                    'texto' => 'Recibes tu plan integral personalizado por escrito: métodos, horarios, rutinas y todo lo que necesitas para empezar con claridad.',
                                    'etiqueta' => 'Plan escrito y personalizado',
                                ],
                                [
                                    'imagen' => [
                                        '_file' => 'assets/img/solucion/a-dormir-por-fin/pasos-3.webp',
                                    ],
                                    'titulo' => '3 semanas de WhatsApp conmigo',
                                    'texto' => 'Me escribes cuando lo necesites: cada duda y cada noche difícil la respondo yo, personalmente.',
                                    'etiqueta' => 'Acompañamiento directo',
                                ],
                            ],
                            'whatsapp' => true,
                            'whatsapp_texto' => 'Escríbeme por WhatsApp para empezar',
                        ],
                        [
                            'acf_fc_layout' => 'testimonios',
                            'fondo' => 'beige',
                            'titulo' => 'Lo que dicen las mamás que trabajaron con Bárbara.',
                            'items' => [
                                [
                                    'texto' => 'Hay un antes y un después de la asesoría. La consistencia y la seguridad que nos dabas permitió que Lara aprenda a dormir sola.',
                                    'autora' => 'Bernardita, mamá de Lara (7 meses)',
                                ],
                                [
                                    'texto' => 'Mi bebé duerme 11 horas de corrido. No lo puedo creer.',
                                    'autora' => 'María L., Colombia',
                                ],
                                [
                                    'texto' => 'Por fin entendí las ventanas de vigilia de mi bebé. En una semana sus siestas pasaron de 30 minutos a más de una hora.',
                                    'autora' => 'Andrea, mamá de Lucas (7 meses)',
                                ],
                                [
                                    'texto' => 'Mi bebé tiene 12 días y ya la puedo poner en el moisés. Antes solo dormía en brazos.',
                                    'autora' => 'Valeria, mamá de Joaquín (12 días)',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'preguntas',
                            'fondo' => 'crema',
                            'titulo' => '¡Respondemos a tus preguntas!',
                            'items' => [
                                [
                                    'pregunta' => '¿Cómo funciona el proceso?',
                                    'respuesta' => 'Empezamos con una videollamada de 1 hora para conocernos y crear el plan de sueño ideal para ti y tu bebé. Después recibes tu plan personalizado y te acompaño por WhatsApp durante 3 semanas.',
                                ],
                                [
                                    'pregunta' => '¿En cuánto tiempo veo resultados?',
                                    'respuesta' => 'El acompañamiento dura 3 semanas. En ese tiempo ajustamos el plan cada día según la respuesta de tu bebé.',
                                ],
                                [
                                    'pregunta' => '¿Cómo se agenda la videollamada?',
                                    'respuesta' => 'Después de tu compra te escribo por WhatsApp para coordinar el día y la hora.',
                                ],
                                [
                                    'pregunta' => '¿Funciona con lactancia y colecho?',
                                    'respuesta' => 'Sí. El plan se diseña de acuerdo a tu estilo y a la forma en que duerme hoy tu bebé.',
                                ],
                                [
                                    'pregunta' => '¿Qué pasa si mi bebé se enferma?',
                                    'respuesta' => 'Me lo cuentas por WhatsApp y ajustamos el plan mientras se recupera.',
                                ],
                                [
                                    'pregunta' => '¿Esta asesoría es para niños mayores de 2 años?',
                                    'respuesta' => 'Sí. Es para bebés y niños de hasta 4 años, incluyendo mayores de 2 años y niños que duermen en su propia cama.',
                                ],
                                [
                                    'pregunta' => '¿Cuántos cupos hay?',
                                    'respuesta' => 'Los cupos son limitados: trabajo con un número reducido de familias a la vez para darle toda mi atención a cada caso.',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 'solucion-recien-nacidos-go',
            'titulo' => 'Recién Nacidos Go',
            'fecha' => '2026-11-04 09:01:00',
            'imagen' => [
                '_file' => 'assets/img/shop-recien-nacidos-go.webp',
                'alt' => 'Recién Nacidos Go',
            ],
            'fields' => [
                'solucion_hero' => [
                    'etiqueta' => 'Curso Online',
                    'titular' => 'Todo lo que necesitas saber para que tu recién nacido duerma mejor — desde los primeros días.',
                    'subtitulo' => 'Curso Online · 0 a 12 semanas',
                    'descripcion' => "Técnicas suaves, rutinas claras y 100% compatible con la lactancia y el colecho.\n\n11 videos cortos · PDF descargable · 4 meses de acceso · Bonus: 4 sesiones de coaching grupal en vivo con Bárbara",
                    'datos' => [],
                    'imagen' => [
                        '_file' => 'assets/img/solucion/recien-nacidos-go/hero-foto.webp',
                    ],
                    'boton' => 'Comprar ahora',
                    'nota' => '7 días de prueba completa',
                    'regalo' => true,
                    'whatsapp' => true,
                    'whatsapp_texto' => '¿Tienes preguntas? Escríbeme por WhatsApp',
                ],
                'solucion_tarjeta' => [
                    'etiqueta' => 'Curso Online',
                    'etiqueta_icono' => 'ninguno',
                    'edad' => '(0 a 12 semanas)',
                    'descripcion' => 'Todo lo que necesitas saber para que tu recién nacido duerma mejor — técnicas suaves, rutinas claras y 100% compatible con la lactancia.',
                    'beneficios' => [
                        [
                            'texto' => '11 videos cortos y prácticos',
                        ],
                        [
                            'texto' => 'PDF con teoría, rutinas y horarios',
                        ],
                        [
                            'texto' => 'Acceso por 6 meses',
                        ],
                        [
                            'texto' => 'Bonus: 4 sesiones de coaching grupal en vivo con Bárbara',
                        ],
                    ],
                    'boton' => 'Ver curso',
                ],
                'solucion_compra' => [
                    'tipo' => 'directa',
                    'descuentos' => [],
                ],
                'solucion_secciones' => [
                    'bloques' => [
                        [
                            'acf_fc_layout' => 'iconos',
                            'fondo' => 'crema',
                            'corazon' => false,
                            'titulo' => 'No es otro curso de sueño infantil.',
                            'bajada' => 'Es el único recurso que combina la ciencia del sueño del recién nacido con herramientas prácticas aplicables desde hoy — en videos cortos, claros y sin abrumarte.',
                            'items' => [
                                [
                                    'icono' => 'bebe-sol',
                                    'titulo' => 'Ciencia + práctica, sin complicaciones',
                                    'texto' => 'Entiende cómo funciona el sueño de tu bebé según su edad y etapa — y qué puedes hacer hoy mismo. Sin teoría interminable, sin tecnicismos.',
                                ],
                                [
                                    'icono' => 'reloj-de-arena',
                                    'titulo' => 'A tu ritmo, sin presión',
                                    'texto' => 'No es un plan rígido. Prueba las técnicas que se adapten mejor a ti y a tu bebé — sin estrés, sin culpa.',
                                ],
                                [
                                    'icono' => 'bebe-envuelto',
                                    'titulo' => 'Compatible con tu crianza',
                                    'texto' => 'Funciona con lactancia materna exclusiva, colecho y crianza con apego. No tienes que elegir entre dormir mejor y criar como quieres.',
                                ],
                            ],
                            'no_titulo' => '',
                            'no_items' => '',
                        ],
                        [
                            'acf_fc_layout' => 'lista',
                            'fondo' => 'blanco',
                            'titulo' => '¿Qué aprenderás?',
                            'imagen' => [
                                '_file' => 'assets/img/solucion/recien-nacidos-go/lista-foto.webp',
                            ],
                            'items' => [
                                [
                                    'texto' => 'Entender el sueño de tu recién nacido según su edad y etapa — sin confusión ni información contradictoria.',
                                ],
                                [
                                    'texto' => 'Identificar sus señales de sueño tempranas para actuar antes de que llegue al llanto.',
                                ],
                                [
                                    'texto' => 'Calmar el llanto con 5 herramientas prácticas y efectivas.',
                                ],
                                [
                                    'texto' => 'Usar correctamente el swaddle, el ruido blanco y otras herramientas de calma para ayudarle a dormir mejor.',
                                ],
                                [
                                    'texto' => 'Crear el ambiente de sueño ideal para tu bebé.',
                                ],
                                [
                                    'texto' => 'Establecer ventanas de vigilia y horarios flexibles para su edad.',
                                ],
                                [
                                    'texto' => 'Construir rutinas suaves que le ayuden a anticipar el sueño.',
                                ],
                                [
                                    'texto' => 'Manejar los despertares nocturnos para alargar sus bloques de sueño.',
                                ],
                                [
                                    'texto' => 'Sentar las bases para que aprenda a conciliar el sueño de manera gradual e independiente.',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'galeria',
                            'fondo' => 'rosa',
                            'titulo' => 'Así se ve el curso por dentro.',
                            'bajada' => 'Cada video es corto, claro y lleno de información práctica — para que puedas verlo cuando tu bebé te lo permita y aplicarlo ese mismo día.',
                            'diapositivas' => [
                                [
                                    'imagen' => [
                                        '_file' => 'assets/img/solucion/recien-nacidos-go/galeria-foto-curso.webp',
                                    ],
                                    'video' => '',
                                ],
                            ],
                            'texto' => 'Desde tu compra tienes 4 meses para acceder y ver el curso las veces que quieras.',
                            'boton' => 'Comprar ahora',
                            'boton_precio' => true,
                        ],
                        [
                            'acf_fc_layout' => 'testimonios',
                            'fondo' => 'beige',
                            'titulo' => 'Lo que dicen las mamás que ya lo tomaron.',
                            'items' => [
                                [
                                    'texto' => 'Mi bebé tiene 12 días y ya la puedo poner en el moisés. Antes solo dormía en brazos.',
                                    'autora' => 'Valeria, mamá de Joaquín (12 días)',
                                ],
                                [
                                    'texto' => 'Mi bebé duerme 11 horas de corrido. No lo puedo creer.',
                                    'autora' => 'María L., Colombia',
                                ],
                                [
                                    'texto' => 'Hay un antes y un después de la asesoría. La consistencia y la seguridad que nos dabas permitió que Lara aprenda a dormir sola.',
                                    'autora' => 'Bernardita, mamá de Lara (7 meses)',
                                ],
                                [
                                    'texto' => 'Por fin entendí las ventanas de vigilia de mi bebé. En una semana sus siestas pasaron de 30 minutos a más de una hora.',
                                    'autora' => 'Andrea, mamá de Lucas (7 meses)',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'preguntas',
                            'fondo' => 'crema',
                            'titulo' => '¡Respondemos a tus preguntas!',
                            'items' => [
                                [
                                    'pregunta' => '¿Este curso es para mí?',
                                    'respuesta' => 'Si tu bebé tiene entre 0 y 12 semanas de nacido (¡edad corregida también cuenta!) o si estás embarazada, entonces sí, este curso es perfecto para ti. Especialmente si estás en busca de una base clara, amorosa y realista para ayudar a tu recién nacido a descansar mejor desde los primeros días.',
                                ],
                                [
                                    'pregunta' => '¿Qué incluye este curso?',
                                    'respuesta' => '',
                                ],
                                [
                                    'pregunta' => '¿Puedo verlo en cualquier momento?',
                                    'respuesta' => '',
                                ],
                                [
                                    'pregunta' => '¿Es compatible con la lactancia materna?',
                                    'respuesta' => '',
                                ],
                                [
                                    'pregunta' => '¿Necesito comprar cosas especiales para aplicar lo que aprendo?',
                                    'respuesta' => '',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'id' => 'solucion-horarios-ideales',
            'titulo' => 'Horarios Ideales',
            'fecha' => '2026-11-04 09:00:00',
            'imagen' => [
                '_file' => 'assets/img/shop-horarios-ideales.webp',
                'alt' => 'Horarios Ideales',
            ],
            'fields' => [
                'solucion_hero' => [
                    'etiqueta' => 'Curso Online',
                    'titular' => 'El horario ideal de sueño para tu bebé — según su edad.',
                    'subtitulo' => 'Curso Online · 4 a 36 meses',
                    'descripcion' => 'Aprende a leer a tu bebé y crear rutinas que funcionen de verdad. Sin adivinar. Sin confusión.',
                    'datos' => [
                        [
                            'icono' => 'reloj',
                            'texto' => '3 semanas',
                        ],
                    ],
                    'imagen' => [
                        '_file' => 'assets/img/solucion/horarios-ideales/hero-foto.webp',
                    ],
                    'boton' => 'Comprar ahora',
                    'nota' => '7 días de prueba completa',
                    'regalo' => true,
                    'whatsapp' => false,
                ],
                'solucion_tarjeta' => [
                    'etiqueta' => 'Curso Online',
                    'etiqueta_icono' => 'ninguno',
                    'edad' => '(4 meses a 3 años)',
                    'descripcion' => 'Aprende a crear el horario ideal de sueño para tu bebé según su edad. Olvida las tablas genéricas y deja de adivinar.',
                    'beneficios' => [
                        [
                            'texto' => 'Videos explicativos por etapa',
                        ],
                        [
                            'texto' => 'Guías de horarios descargables',
                        ],
                        [
                            'texto' => 'Ejemplos prácticos para días difíciles',
                        ],
                        [
                            'texto' => 'Disponible para 4–9, 9–15 y 15–36 meses',
                        ],
                    ],
                    'boton' => 'Ver cursos por edad',
                ],
                'solucion_compra' => [
                    'tipo' => 'etapas',
                    'descuentos' => [
                        [
                            'cantidad' => 2,
                            'total' => 67,
                        ],
                        [
                            'cantidad' => 3,
                            'total' => 97,
                        ],
                    ],
                ],
                'solucion_secciones' => [
                    'bloques' => [
                        [
                            'acf_fc_layout' => 'etapas',
                            'fondo' => 'crema',
                            'titulo' => 'El sueño cambia con cada etapa. Los horarios también. Elige la tuya.',
                            'modal_antetitulo' => 'Introducción al curso de Horarios',
                            'items' => [
                                [
                                    'edad' => '4 a 9 meses',
                                    'imagen' => [
                                        '_file' => 'assets/img/solucion/horarios-ideales/etapa-4-a-9-meses.webp',
                                    ],
                                    'titulo' => 'Curso Para bebés de 4 a 9 meses',
                                    'texto' => '¿Sientes que tu bebé parece tener sueño todo el día pero duerme poco? Esta etapa es un laberinto de señales confusas. Aprenderás a identificar las ventanas de vigilia correctas, evitar el círculo vicioso de siestas cortas y descubrir el secreto de la última ventana del día para que las tres siestas funcionen como relojito. Todo lo que necesitas para descifrar el horario ideal — sin adivinar.',
                                    'modal_titulo' => 'Para bebés de 4 a 9 meses',
                                    'modal_texto' => '¿Sientes que tu bebé parece tener sueño todo el día pero igual duerme poco? En este curso aprenderás a priorizar las ventanas de vigilia y a evitar el círculo de siestas cortas y horarios caóticos. Descubre cómo lograr que las tres siestas funcionen como un reloj.',
                                    'modal_video' => '',
                                    'modal_portada' => [
                                        '_file' => 'assets/img/solucion/horarios-ideales/etapa-video-portada.webp',
                                    ],
                                ],
                                [
                                    'edad' => '9 a 15 meses',
                                    'imagen' => [
                                        '_file' => 'assets/img/solucion/horarios-ideales/etapa-9-a-15-meses.webp',
                                    ],
                                    'titulo' => "Curso Para bebés de\n9 a 15 meses",
                                    'texto' => 'Los horarios cambiaron, tu bebé dejó la tercera siesta — y el caos amenaza con volver. Esta etapa viene cargada de cambios, crisis y confusión. Aprenderás a combinar ventanas de vigilia con los horarios ideales para su edad y su tanque de sueño — para que tus días fluyan mejor y tus noches vuelvan a ser tranquilas.',
                                    'modal_titulo' => 'Para bebés de 9 a 15 meses',
                                    'modal_texto' => '',
                                    'modal_video' => '',
                                    'modal_portada' => '',
                                ],
                                [
                                    'edad' => '15 a 36 meses',
                                    'imagen' => [
                                        '_file' => 'assets/img/solucion/horarios-ideales/etapa-15-a-36-meses.webp',
                                    ],
                                    'titulo' => 'Curso Para bebés de 15 a 36 meses',
                                    'texto' => 'Bienvenida a la etapa de los mini exploradores que ya no quieren siestas y madrugan como si fueran gallos. Tu guía para las crisis de los 18 y 24 meses, entender qué hacer cuando la siesta desaparece y cómo reajustar los horarios cuando todo parece desbordarse. Más que horarios — claridad y calma.',
                                    'modal_titulo' => 'Para bebés de 15 a 36 meses',
                                    'modal_texto' => '',
                                    'modal_video' => '',
                                    'modal_portada' => '',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'iconos',
                            'fondo' => 'crema',
                            'corazon' => false,
                            'titulo' => 'Este curso es para ti si...',
                            'bajada' => '',
                            'items' => [
                                [
                                    'icono' => 'reloj',
                                    'titulo' => '',
                                    'texto' => 'No entiendes la lógica detrás de los horarios y sientes que todo es prueba y error.',
                                ],
                                [
                                    'icono' => 'bebe-sol',
                                    'titulo' => '',
                                    'texto' => 'Tu bebé vive cansado — siestas cortas, mal humor y un círculo vicioso que no para.',
                                ],
                                [
                                    'icono' => 'reloj-de-arena',
                                    'titulo' => '',
                                    'texto' => 'Has probado diferentes horarios online y sigues sin encontrar el que funciona para tu bebé.',
                                ],
                                [
                                    'icono' => 'biberon',
                                    'titulo' => '',
                                    'texto' => 'Cada vez que crees haber encontrado el horario ideal, algo cambia y vuelves a cero.',
                                ],
                                [
                                    'icono' => 'bebe-envuelto',
                                    'titulo' => '',
                                    'texto' => 'Los cuadros de ventanas de vigilia que encuentras online no aplican para tu bebé.',
                                ],
                                [
                                    'icono' => 'reloj',
                                    'titulo' => '',
                                    'texto' => 'No sabes cómo ajustar los horarios a medida que tu bebé crece.',
                                ],
                            ],
                            'no_titulo' => '',
                            'no_items' => '',
                        ],
                        [
                            'acf_fc_layout' => 'lista',
                            'fondo' => 'rosa',
                            'titulo' => '¿Qué aprenderás?',
                            'imagen' => [
                                '_file' => 'assets/img/solucion/horarios-ideales/lista-foto.webp',
                            ],
                            'items' => [
                                [
                                    'texto' => 'Cómo combinar señales de sueño y ventanas de vigilia para crear rutinas que funcionen.',
                                ],
                                [
                                    'texto' => 'A identificar los rangos de horario ideales para tu bebé — no promedios genéricos.',
                                ],
                                [
                                    'texto' => 'Cómo manejar días complicados: siestas cortas, cansancio acumulado y saltos de desarrollo.',
                                ],
                                [
                                    'texto' => 'Qué hacer cuando los horarios cambian con cada etapa — para adaptarte sin volver a cero.',
                                ],
                                [
                                    'texto' => 'Cómo organizar tu día para que el sueño fluya naturalmente, sin peleas ni frustración.',
                                ],
                                [
                                    'texto' => 'Cómo manejar las madrugadas y los despertares tempranos antes de las 6am.',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'beneficios',
                            'fondo' => 'rosa',
                            'titulo' => 'Lo que incluye además del curso.',
                            'bajada' => '',
                            'items' => [
                                [
                                    'icono' => 'libro',
                                    'texto' => 'Guías de horarios descargables — una guía por cada etapa, para tener siempre a mano el horario ideal.',
                                ],
                                [
                                    'icono' => 'diamante',
                                    'texto' => '1 mes en Mundo Angelitozzz — 4 sesiones grupales en vivo con Bárbara, todos los miércoles a las 10am hora Perú/Colombia, y videoteca completa.',
                                ],
                                [
                                    'icono' => 'video',
                                    'texto' => 'Grabaciones de talleres de transiciones de siestas — según la etapa en la que estás.',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'testimonios',
                            'fondo' => 'beige',
                            'titulo' => 'Lo que dicen las mamás que ya lo tomaron.',
                            'items' => [
                                [
                                    'texto' => 'Por fin entendí las ventanas de vigilia de mi bebé. En una semana sus siestas pasaron de 30 minutos a más de una hora.',
                                    'autora' => 'Andrea, mamá de Lucas (7 meses)',
                                ],
                                [
                                    'texto' => 'Mi bebé duerme 11 horas de corrido. No lo puedo creer.',
                                    'autora' => 'María L., Colombia',
                                ],
                                [
                                    'texto' => 'Hay un antes y un después de la asesoría. La consistencia y la seguridad que nos dabas permitió que Lara aprenda a dormir sola.',
                                    'autora' => 'Bernardita, mamá de Lara (7 meses)',
                                ],
                                [
                                    'texto' => 'Mi bebé tiene 12 días y ya la puedo poner en el moisés. Antes solo dormía en brazos.',
                                    'autora' => 'Valeria, mamá de Joaquín (12 días)',
                                ],
                            ],
                        ],
                        [
                            'acf_fc_layout' => 'preguntas',
                            'fondo' => 'crema',
                            'titulo' => '¡Respondemos a tus preguntas!',
                            'items' => [
                                [
                                    'pregunta' => '¿Cómo funciona el curso?',
                                    'respuesta' => 'Acceso inmediato. Videos cortos que puedes ver en un solo día. Incluye guías descargables.',
                                ],
                                [
                                    'pregunta' => '¿Por qué están divididos por edad?',
                                    'respuesta' => '',
                                ],
                                [
                                    'pregunta' => '¿Qué pasa si mi bebé está entre dos grupos?',
                                    'respuesta' => '',
                                ],
                                [
                                    'pregunta' => '¿Este curso logrará que mi bebé duerma de corrido?',
                                    'respuesta' => '',
                                ],
                                [
                                    'pregunta' => '¿Puedo comprar más de uno?',
                                    'respuesta' => '',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
