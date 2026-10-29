<?php get_header(); ?>
<main id="contenido">
    <section class="wrap hero" aria-labelledby="hero-title">
        <div>
            <div class="eyebrow"><span class="status-dot"></span>Una vida extra para los clásicos</div>
            <h1 id="hero-title">El juego cambia.<br>La pasión<br><em>no se apaga.</em></h1>
            <p class="intro"><?php echo esc_html(get_bloginfo('description')); ?></p>
            <div class="actions"><a class="button" href="#articulos">Explorar artículos <span aria-hidden="true">↗</span></a><a class="text-link" href="<?php echo esc_url(home_url('/sobre-nosotros/')); ?>">Conoce el club</a></div>
            <p class="hero-note">SIN PRISAS. SIN PAY TO WIN. SOLO UNA PARTIDA MÁS.</p>
        </div>
        <div class="hero-art"><img class="project-logo" src="<?php echo esc_url(cg_logo_url()); ?>" width="1000" height="1000" alt="Logo de CodeGaming Arcade: una recreativa pixelada en cian y violeta sobre el nombre del proyecto" fetchpriority="high"><span class="art-label">EST. 2026 / PRESS START_</span></div>
    </section>
    <div class="ticker" aria-hidden="true"><div class="wrap ticker-inner"><span>INSERT COIN</span><b>✦</b><span>CULTURA ARCADE</span><b>✦</b><span>PÍXELES CON HISTORIA</span><b>✦</b><span>ONE MORE GAME</span></div></div>
    <section class="wrap stories" id="articulos" aria-labelledby="stories-title">
        <div class="section-head"><div><div class="eyebrow">LEVEL 01 / EL BLOG</div><h2 id="stories-title">Píxeles que cuentan historias.</h2></div><span class="counter">SELECT YOUR READING ↓</span></div>
        <div class="cards">
            <?php $stories = new WP_Query(array('posts_per_page' => 3, 'ignore_sticky_posts' => true)); if ($stories->have_posts()) { while ($stories->have_posts()) { $stories->the_post(); cg_card(); } } else { echo '<p>Pronto habrá nuevas historias. ¡Prepara tu moneda!</p>'; } wp_reset_postdata(); ?>
        </div>
    </section>
    <section class="wrap manifesto"><div class="pixel-heart" aria-hidden="true">♥</div><div><div class="eyebrow">MÁS QUE UNA PUNTUACIÓN</div><h2>Las buenas partidas se comparten.</h2><p>Somos el punto de encuentro para quienes siguen mirando una recreativa con ilusión. Descubre los juegos, su historia y las ganas de volver a jugar.</p></div><a class="text-link" href="<?php echo esc_url(home_url('/sobre-nosotros/')); ?>">Esto es CodeGaming ↗</a></section>
    <?php while (have_posts()) { the_post(); if (trim(get_the_content())) { ?><div class="wrap"><div class="prose"><?php the_content(); ?></div></div><?php } } ?>
</main>
<?php get_footer(); ?>
