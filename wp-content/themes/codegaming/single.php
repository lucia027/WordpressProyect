<?php get_header(); ?>
<main id="contenido" class="wrap">
<?php while (have_posts()) { the_post(); ?>
    <article>
        <header class="article-header"><div class="breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a> / <a href="<?php echo esc_url(home_url('/articulos/')); ?>">Artículos</a></div><div class="eyebrow"><?php $categories = get_the_category(); if ($categories) { echo esc_html($categories[0]->name); } ?></div><h1><?php the_title(); ?></h1><p class="article-deck"><?php echo esc_html(get_the_excerpt()); ?></p><div class="article-meta">CODEGAMING · <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time> · <?php echo esc_html(cg_minutes()); ?> MIN DE LECTURA</div></header>
        <figure class="article-cover"><?php if (has_post_thumbnail()) { the_post_thumbnail('large'); } else { ?><img src="<?php echo esc_url(cg_asset(cg_art())); ?>" width="660" height="400" alt="Ilustración editorial de estética arcade para acompañar el artículo"><?php } ?></figure>
        <div class="prose"><?php the_content(); wp_link_pages(); ?></div>
    </article>
    <?php the_post_navigation(array('prev_text' => '← %title', 'next_text' => '%title →')); ?>
<?php } ?>
</main>
<?php get_footer(); ?>
