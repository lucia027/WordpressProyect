<?php get_header(); ?>
<main id="contenido" class="wrap">
    <header class="archive-intro"><div class="eyebrow">EL ARCHIVO / CODEGAMING</div><h1 class="page-title"><?php if (is_search()) { echo 'Resultados para: ' . esc_html(get_search_query()); } elseif (is_archive()) { echo esc_html(get_the_archive_title()); } else { echo 'Historias con una vida extra.'; } ?></h1><p>Clásicos, curiosidades y guías para volver a la sala de recreativos.</p><?php if (is_archive()) { the_archive_description(); } ?><?php get_search_form(); ?></header>
    <section class="stories"><div class="cards"><?php if (have_posts()) { while (have_posts()) { the_post(); cg_card(); } } else { ?><p>No hay artículos que coincidan. Prueba con «arcade» o «Pac-Man».</p><?php } ?></div><?php the_posts_pagination(array('prev_text' => '← Anterior', 'next_text' => 'Siguiente →')); ?></section>
</main>
<?php get_footer(); ?>
