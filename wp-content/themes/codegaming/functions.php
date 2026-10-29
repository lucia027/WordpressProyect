<?php
if (!defined('ABSPATH')) { exit; }

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('custom-logo', array('height' => 180, 'width' => 180, 'flex-height' => true, 'flex-width' => true));
    add_theme_support('editor-styles');
    add_editor_style('editor-style.css');
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('codegaming', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
});

add_filter('document_title_parts', function ($parts) {
    if (is_front_page()) {
        $parts['title'] = 'Videojuegos arcade y cultura retro';
        $parts['site'] = get_bloginfo('name');
        unset($parts['tagline']);
    }
    return $parts;
});

function cg_asset($name) { return get_template_directory_uri() . '/assets/' . $name . '.svg'; }
function cg_logo_url() { return get_template_directory_uri() . '/assets/logo.jpg'; }
function cg_minutes() { return max(1, (int) ceil(str_word_count(wp_strip_all_tags(get_the_content())) / 200)); }
function cg_art($id = null) {
    $id = $id ?: get_the_ID();
    $art = get_post_meta($id, '_cg_art', true);
    return in_array($art, array('maze', 'invaders', 'cabinet'), true) ? $art : 'cabinet';
}
function cg_card() {
    ?>
    <article class="card">
        <a class="card-image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php if (has_post_thumbnail()) { the_post_thumbnail('medium_large'); } else { ?><img src="<?php echo esc_url(cg_asset(cg_art())); ?>" width="660" height="400" alt="" loading="lazy"><?php } ?>
        </a>
        <div class="meta"><span class="category"><?php $cats = get_the_category(); if ($cats) { echo esc_html($cats[0]->name); } ?></span><span><?php echo esc_html(cg_minutes()); ?> MIN DE LECTURA</span></div>
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <a class="read-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr('Leer artículo: ' . get_the_title()); ?>">Leer artículo <span aria-hidden="true">↗</span></a>
    </article>
    <?php
}

// Descripcion basica. Se omite si se instala un plugin SEO que la gestione.
add_action('wp_head', function () {
    if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION')) { return; }
    $description = is_singular() ? get_the_excerpt(get_queried_object_id()) : get_bloginfo('description');
    if ($description) { echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($description)) . '">' . "\n"; }
}, 2);
