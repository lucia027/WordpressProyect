<?php get_header(); ?>
<main id="contenido" class="wrap">
<?php while (have_posts()) { the_post(); ?><article><header class="article-header"><div class="eyebrow">CODEGAMING / EL CLUB</div><h1><?php the_title(); ?></h1></header><div class="prose"><?php the_content(); wp_link_pages(); ?></div></article><?php } ?>
</main>
<?php get_footer(); ?>
