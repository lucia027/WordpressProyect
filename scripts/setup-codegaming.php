<?php
/** Ejecutar dentro del contenedor despues de hacer una copia de seguridad. */
if (PHP_SAPI !== 'cli') { exit; }
require '/var/www/html/wp-load.php';

function cg_seed_post($slug, $title, $excerpt, $content, $type = 'post', $category = '', $art = '') {
    $existing = get_page_by_path($slug, OBJECT, $type);
    if ($existing) {
        echo "Se conserva el contenido existente: $slug\n";
        return $existing->ID;
    }
    // Bloques HTML editables con el editor nativo de WordPress.
    $blocks = preg_replace_callback('~<(p|h2|h3|ul|ol|blockquote)(\s[^>]*)?>.*?</\1>~su', function ($match) {
        $tag = $match[1];
        $block = $tag === 'p' ? 'paragraph' : (in_array($tag, array('h2', 'h3'), true) ? 'heading' : ($tag === 'blockquote' ? 'quote' : 'list'));
        $attributes = $tag === 'h3' ? ' {"level":3}' : ($tag === 'ol' ? ' {"ordered":true}' : '');
        if ($block === 'heading') { $match[0] = preg_replace('/^<' . $tag . '>/', '<' . $tag . ' class="wp-block-heading">', $match[0]); }
        if ($block === 'list') {
            $match[0] = preg_replace('/^<' . $tag . '>/', '<' . $tag . ' class="wp-block-list">', $match[0]);
            $match[0] = preg_replace('~<li>(.*?)</li>~su', '<!-- wp:list-item --><li>$1</li><!-- /wp:list-item -->', $match[0]);
        }
        return "<!-- wp:$block$attributes -->\n" . $match[0] . "\n<!-- /wp:$block -->\n";
    }, $content);
    $admins = get_users(array('role' => 'administrator', 'number' => 1, 'orderby' => 'ID'));
    $data = array('post_title' => $title, 'post_name' => $slug, 'post_excerpt' => $excerpt, 'post_content' => $blocks, 'post_status' => 'publish', 'post_type' => $type, 'post_author' => $admins ? $admins[0]->ID : 1, 'comment_status' => 'closed', 'ping_status' => 'closed');
    if ($category) {
        $term = term_exists($category, 'category');
        if (!$term) { $term = wp_insert_term($category, 'category'); }
        if (is_wp_error($term)) { throw new RuntimeException($term->get_error_message()); }
        $data['post_category'] = array((int) $term['term_id']);
    }
    $id = wp_insert_post(wp_slash($data), true);
    if (is_wp_error($id)) { throw new RuntimeException($id->get_error_message()); }
    if ($art) { update_post_meta($id, '_cg_art', $art); }
    echo "Creado: $slug ($id)\n";
    return $id;
}

update_option('blogname', 'CodeGaming Arcade');
update_option('blogdescription', 'Historias, clásicos y guías de videojuegos arcade. Un rincón para quienes creen que siempre hay tiempo para una partida más.');
update_option('timezone_string', 'Europe/Madrid');
update_option('default_comment_status', 'closed');

$home = cg_seed_post('inicio', 'CodeGaming Arcade: videojuegos retro y cultura arcade', 'Descubre videojuegos arcade, su historia y guías para empezar. Pac-Man, Space Invaders y mucha pasión retro en CodeGaming Arcade.', '', 'page');
$blog = cg_seed_post('articulos', 'Artículos de videojuegos arcade', 'Historias y guías sobre videojuegos arcade: descubre los clásicos y encuentra tu próxima partida.', '', 'page');
cg_seed_post('sobre-nosotros', 'Un club para quienes siempre quieren otra partida', 'Conoce CodeGaming Arcade, un proyecto de estudiantes de DAW dedicado a la historia y la cultura de los videojuegos arcade.', <<<'HTML'
<p>Hay sonidos que no se olvidan: una moneda al caer, el inicio de una partida y esa melodía que sigue contigo mucho después de apagar la pantalla. <strong>CodeGaming Arcade</strong> nace de esa curiosidad por los juegos que hicieron de algo sencillo una experiencia inolvidable.</p>
<h2>Qué vas a encontrar aquí</h2>
<p>Somos un pequeño proyecto educativo creado para clase de <strong>2º de Desarrollo de Aplicaciones Web</strong>. Queremos hablar de videojuegos arcade con un lenguaje cercano, tanto si creciste entre recreativas como si acabas de descubrirlas.</p>
<p>Publicamos historias de clásicos, curiosidades sobre su diseño y guías para empezar. Nos interesan las ideas que hay detrás de cada partida: cómo unos pocos botones pueden crear un reto y por qué seguimos volviendo a juegos de hace décadas.</p>
<h2>Nuestra selección para empezar</h2>
<ul><li><a href="/pac-man-historia-clasico-arcade/">Pac-Man: el laberinto que cambió los recreativos</a>. Un buen punto de partida para entender el diseño de un clásico.</li><li><a href="/space-invaders-historia/">Space Invaders: la invasión que hizo historia</a>. Ritmo, estrategia y una tensión que no necesita grandes gráficos.</li><li><a href="/guia-empezar-videojuegos-arcade/">Cómo empezar con los videojuegos arcade</a>. Consejos para elegir juego y disfrutar sin obsesionarte con la puntuación.</li></ul>
<h2>Un proyecto para aprender, no una tienda</h2>
<p>Esta web es nuestro espacio de práctica con WordPress y posicionamiento en buscadores. No vendemos juegos, no distribuimos ROMs y no estamos afiliados a las marcas mencionadas. Las ilustraciones del tema son interpretaciones gráficas propias, no capturas oficiales.</p>
<p>Los nombres de juegos y marcas pertenecen a sus respectivos titulares. Cuando recomendamos jugar, nos referimos a ediciones, recopilatorios o máquinas disponibles de forma legal.</p>
<h2>La mejor puntuación es pasarlo bien</h2>
<p>No hace falta saberlo todo ni haber jugado a todo para formar parte del club. Solo necesitas curiosidad, ganas de aprender y, quizá, un hueco para <a href="/articulos/">una lectura más</a>.</p>
HTML
, 'page');

cg_seed_post('pac-man-historia-clasico-arcade', 'Pac-Man: el laberinto que cambió los recreativos', 'Un personaje amarillo, cuatro fantasmas y una idea brillante. Descubre por qué Pac-Man sigue siendo un clásico del arcade desde 1980.', <<<'HTML'
<p>Antes de que las partidas se guardaran en la nube, bastaba un laberinto y un personaje amarillo para quedarse pegado a una pantalla. <strong>Pac-Man, publicado por Namco en 1980</strong>, demostró que un videojuego arcade podía conquistar al público sin naves, disparos ni escenarios complicados.</p>
<p>Su propuesta se entiende en segundos: recorre los pasillos, come los puntos y evita a los fantasmas. Pero entender las reglas no significa dominar la partida. Esa distancia entre lo sencillo y lo difícil es una de las claves de su éxito.</p>
<h2>Una idea diferente para la sala de recreativos</h2>
<p>El diseñador Toru Iwatani buscaba una alternativa a los juegos de combate que ocupaban buena parte de los salones. Comer se convirtió en la acción central del juego, y el laberinto permitió construir un reto alrededor de decisiones rápidas: avanzar, dar la vuelta o arriesgar un giro.</p>
<p>Pac-Man apareció primero en Japón con el nombre Puck Man. En su lanzamiento norteamericano pasó a llamarse Pac-Man, el nombre con el que se convertiría en un icono internacional. Su silueta funciona incluso sin detalles: un círculo incompleto basta para reconocerlo.</p>
<h2>Los fantasmas no son simples obstáculos</h2>
<p>Blinky, Pinky, Inky y Clyde aportan algo más que color. Sus patrones de movimiento distintos hacen que el jugador perciba persecuciones, anticipaciones y cambios de presión. La sensación de estar rodeado no depende solo de la velocidad, sino también de cómo se combinan sus posiciones.</p>
<p>Las grandes pastillas de energía cambian temporalmente las reglas: los perseguidores se vuelven vulnerables. De pronto, escapar deja paso a perseguir. Este pequeño giro crea momentos de alivio y decisiones interesantes: ¿aprovechas para sumar puntos o para limpiar una zona difícil?</p>
<h2>Tres consejos para tu primera partida</h2>
<ul><li><strong>Mira más allá del siguiente giro.</strong> Presta atención a los cruces y a la posición de varios fantasmas, no solo al que tienes más cerca.</li><li><strong>No gastes las pastillas por costumbre.</strong> Pueden ayudarte a salir de una zona peligrosa; piensa en ellas como una oportunidad táctica.</li><li><strong>Conoce los túneles laterales.</strong> Conectar ambos extremos del laberinto abre rutas que al principio pasan desapercibidas.</li></ul>
<h2>Por qué sigue funcionando hoy</h2>
<p>Pac-Man ofrece información visual clara, una respuesta inmediata a los controles y un objetivo fácil de recordar. No necesita una larga introducción para que quieras mejorar tu recorrido. Cada intento te deja una pequeña lección: aquel giro sobraba, esa esquina era peligrosa o ese punto podía esperar.</p>
<p>También es un ejemplo estupendo para observar cómo se construye una identidad visual. Personaje, colores, sonidos y reglas forman un conjunto reconocible. Para conocer sus orígenes y sus distintas entregas, puedes visitar el <a href="https://www.pacman.com/en/">sitio oficial de Pac-Man</a>.</p>
<h2>Dónde continuar la partida</h2>
<p>Busca recopilatorios oficiales o recreativas autorizadas y comprueba qué versión incluyen: controles, opciones y variantes pueden cambiar de una edición a otra. Si es tu primer acercamiento al género, nuestra <a href="/guia-empezar-videojuegos-arcade/">guía para empezar con los videojuegos arcade</a> te ayudará a elegir tu siguiente reto.</p>
<p>Y si quieres pasar del laberinto al espacio, descubre <a href="/space-invaders-historia/">cómo Space Invaders convirtió una invasión en historia del videojuego</a>. Cambia la pantalla, pero las ganas de intentarlo otra vez siguen ahí.</p>
HTML
, 'post', 'Clásicos', 'maze');

cg_seed_post('space-invaders-historia', 'Space Invaders: la invasión que hizo historia', 'Así convirtió Space Invaders una fila de alienígenas en un fenómeno arcade: historia, diseño y consejos para entender el clásico de Taito.', <<<'HTML'
<p>Un cañón en la parte inferior de la pantalla. Varias filas de alienígenas que se acercan. Y una pregunta sencilla: ¿cuánto tiempo podrás resistir? <strong>Space Invaders, lanzado por Taito en 1978</strong>, convirtió esos elementos en una de las imágenes más reconocibles del videojuego arcade.</p>
<p>Diseñado por Tomohiro Nishikado, el juego ayudó a popularizar los salones recreativos y dejó una huella enorme en la forma de entender las partidas por puntuación. Su fuerza no estaba en contar una historia complicada, sino en hacerte sentir cada segundo de la invasión.</p>
<h2>Una pantalla, muchas decisiones</h2>
<p>El movimiento del jugador se limita a izquierda y derecha. Puede parecer poco, pero esa restricción concentra toda la atención en la posición y el momento de disparar. Si miras únicamente a tu objetivo, puedes olvidar el proyectil que está cayendo sobre tu cañón.</p>
<p>Los escudos ofrecen protección al principio, aunque se van deteriorando con los impactos. No son un refugio permanente: debes aprovecharlos sin confiarte. El espacio seguro se reduce y obliga a adaptar tu estrategia durante la misma partida.</p>
<h2>Por qué los invasores aceleran</h2>
<p>Uno de los rasgos más conocidos de Space Invaders es el aumento de velocidad cuando quedan menos enemigos. En el desarrollo original, el hardware podía actualizar a los supervivientes más rápido al tener menos elementos en pantalla. Ese comportamiento se mantuvo y terminó dando al juego una curva de tensión inolvidable.</p>
<p>Eliminar invasores te acerca al objetivo, pero también hace más exigente el siguiente momento. Es una manera elegante de convertir el progreso en desafío: cuando parece que lo tienes bajo control, la última fila te exige volver a concentrarte.</p>
<h2>El sonido también juega</h2>
<p>El ritmo que acompaña al avance de los enemigos hace que la amenaza se perciba incluso sin mirar a cada alienígena. A medida que la formación acelera, la sensación de urgencia crece. Imagen, movimiento y sonido trabajan juntos con muy pocos recursos.</p>
<p>Es una buena lección de diseño: no siempre hacen falta más elementos; a veces basta con que los que ya existen refuercen la misma emoción. En este caso, todo comunica que la invasión está cada vez más cerca.</p>
<h2>Consejos para no perder la calma</h2>
<ul><li><strong>Observa los proyectiles.</strong> Esquivar a tiempo suele ser más útil que mantener el disparo sin mirar.</li><li><strong>Usa los escudos con intención.</strong> Fíjate en los huecos que se abren y evita tratarlos como paredes indestructibles.</li><li><strong>Practica el final de cada oleada.</strong> Los últimos enemigos cambian el ritmo; ajustar el movimiento importa más que precipitarse.</li></ul>
<h2>Un legado que va más allá del píxel</h2>
<p>Su alienígena pixelado se ha convertido en un símbolo de la cultura retro. Pero su legado también está en la búsqueda de una puntuación mejor, las oleadas de enemigos y la claridad de una partida que puedes aprender rápido y perfeccionar durante mucho tiempo.</p>
<p>Puedes explorar más sobre la serie en la <a href="https://spaceinvaders.jp/">web oficial de Space Invaders de Taito</a>. Para jugar, elige ediciones o recopilatorios oficiales y revisa qué versiones ofrecen.</p>
<p>Si te interesa comparar dos formas de crear tensión, lee nuestro artículo sobre <a href="/pac-man-historia-clasico-arcade/">Pac-Man y su laberinto</a>. Y si aún estás buscando tu primer clásico, empieza por la <a href="/guia-empezar-videojuegos-arcade/">guía de iniciación al arcade</a>.</p>
HTML
, 'post', 'Historia arcade', 'invaders');

cg_seed_post('guia-empezar-videojuegos-arcade', 'Cómo empezar con los videojuegos arcade: guía para principiantes', 'Elige tu primer juego arcade, aprende sus reglas y disfruta de los clásicos con consejos sencillos y opciones legales para jugar.', <<<'HTML'
<p>No necesitas una colección enorme ni una recreativa en el salón para descubrir los <strong>videojuegos arcade</strong>. Lo importante es encontrar un juego que te despierte curiosidad y dedicarle unas cuantas partidas con calma. Esta guía te ayuda a empezar sin convertir una afición en una lista de tareas.</p>
<h2>Qué hace que un juego sea arcade</h2>
<p>El término nació ligado a las máquinas recreativas: juegos pensados para sesiones relativamente cortas, controles directos y objetivos claros. Muchos proponen superar una puntuación, resistir oleadas o completar pantallas cada vez más exigentes.</p>
<p>Hoy también llamamos arcade a juegos de consola u ordenador que comparten ese enfoque inmediato. No todos son antiguos ni todos buscan lo mismo. Hay puzles, carreras, juegos de lucha, plataformas y títulos de disparos con ritmos muy diferentes.</p>
<h2>Elige un primer juego según lo que te apetezca</h2>
<ul><li><strong>Si disfrutas planificando rutas:</strong> prueba Pac-Man. Su laberinto te enseña a combinar memoria, observación y decisiones rápidas.</li><li><strong>Si te gusta medir el momento:</strong> Space Invaders propone un reto de disparo y evasión con controles sencillos.</li><li><strong>Si prefieres jugar en compañía:</strong> busca un recopilatorio oficial con juegos cooperativos y confirma que la edición incluya multijugador local.</li></ul>
<p>No es necesario empezar por el juego más difícil ni por el más famoso. Un título con un ritmo que te guste te dará más ganas de practicar. Puedes descubrir las diferencias entre dos clásicos en nuestros artículos de <a href="/pac-man-historia-clasico-arcade/">Pac-Man</a> y <a href="/space-invaders-historia/">Space Invaders</a>.</p>
<h2>Dónde jugar de forma legal</h2>
<p>La opción más sencilla suele ser una edición oficial o un recopilatorio publicado por el titular de los juegos. Comprueba la ficha de la tienda: plataformas compatibles, controles, idiomas, versiones incluidas y opciones de guardado pueden variar.</p>
<p>También puedes visitar un salón con máquinas recreativas o probar reediciones autorizadas. Que un juego sea antiguo no significa que sea de dominio público. La emulación tiene usos legítimos, pero eso no convierte cualquier descarga de una ROM en legal. Esta web no distribuye juegos ni archivos de ese tipo.</p>
<h2>Configura los controles antes de perseguir récords</h2>
<p>Empieza con el mando o teclado que ya tengas. Revisa la asignación de botones y procura que resulte cómoda. Un joystick arcade puede ser agradable, pero no es un requisito y no merece la pena comprarlo sin saber qué juegos vas a disfrutar.</p>
<p>Si una edición permite modificar dificultad, vidas o ayudas, no pasa nada por hacerlo. Solo recuerda mantener los mismos ajustes cuando compares tus propias puntuaciones: así sabrás si estás mejorando en condiciones parecidas.</p>
<h2>Una rutina pequeña que sí ayuda</h2>
<ol><li><strong>Primera partida: observar.</strong> Aprende las reglas y descubre qué provoca que pierdas una vida.</li><li><strong>Segunda partida: elegir un objetivo.</strong> Intenta superar una pantalla o evitar un error concreto, no batir todos los récords.</li><li><strong>Tercera partida: repetir y descansar.</strong> Comprueba qué has aprendido y haz una pausa si aparece frustración.</li></ol>
<h2>La nostalgia no es un requisito</h2>
<p>Puedes disfrutar de los clásicos aunque no los conocieras en su época. Observa cómo presentan la información, cómo enseñan sin largos tutoriales y cómo convierten pocos elementos en una partida intensa. Esa curiosidad es una forma estupenda de aprender sobre videojuegos y sobre diseño.</p>
<p>En <a href="/sobre-nosotros/">CodeGaming Arcade</a> preferimos compartir descubrimientos a presumir de puntuaciones. El mejor primer paso es sencillo: elige un juego, dale una oportunidad y quédate con lo que te haga querer volver.</p>
HTML
, 'post', 'Guías', 'cabinet');

// Solo se retiran de la portada los ejemplos conocidos; permanecen recuperables.
$sample = get_page_by_path('sample-page', OBJECT, 'page');
if (!$sample) { $sample = get_page_by_path('pagina-ejemplo', OBJECT, 'page'); }
if ($sample && in_array($sample->post_title, array('Sample Page', 'Página de ejemplo'), true)) { wp_update_post(array('ID' => $sample->ID, 'post_status' => 'draft')); }
$hello = get_page_by_path('hola-mundo', OBJECT, 'post');
if (!$hello) { $hello = get_page_by_path('hello-world', OBJECT, 'post'); }
if ($hello && in_array($hello->post_title, array('¡Hola, mundo!', 'Hello world!'), true)) { wp_update_post(array('ID' => $hello->ID, 'post_status' => 'draft')); }

update_option('show_on_front', 'page');
update_option('page_on_front', $home);
update_option('page_for_posts', $blog);
update_option('posts_per_page', 9);
update_option('permalink_structure', '/%postname%/');
switch_theme('codegaming');
// La ejecucion CLI ya habia cargado las reglas anteriores al cambiar la opcion.
global $wp_rewrite;
$wp_rewrite->init();
flush_rewrite_rules();
// En CLI WordPress no detecta Apache: guardar solo su bloque, conservando el resto.
require_once ABSPATH . 'wp-admin/includes/misc.php';
if (!insert_with_markers(ABSPATH . '.htaccess', 'WordPress', explode("\n", $wp_rewrite->mod_rewrite_rules()))) {
    throw new RuntimeException('No se han podido guardar los enlaces permanentes en .htaccess.');
}
echo "CodeGaming preparado. No se han cambiado usuarios ni contrasenas.\n";
