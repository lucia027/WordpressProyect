<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#contenido">Saltar al contenido</a>
<header class="site-header">
    <div class="wrap header-inner">
        <?php if (has_custom_logo()) { the_custom_logo(); } else { ?>
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
            <svg class="brand-icon" viewBox="0 0 36 44" aria-hidden="true"><path d="M5 2h26v7l-3 4v12l5 5v12H3V30l5-5V13L5 9Z"/><path d="M10 7h16M11 14h14v12H11zM8 32h20M12 32v-4m9 4v-2m5 2v-2M10 37h16"/></svg>
            <span>CodeGaming<small>ARCADE CLUB</small></span>
        </a>
        <?php } ?>
        <nav class="nav" aria-label="Navegación principal">
            <a href="<?php echo esc_url(home_url('/')); ?>" <?php if (is_front_page()) { echo 'aria-current="page"'; } ?>>Inicio</a>
            <a href="<?php echo esc_url(home_url('/articulos/')); ?>" <?php if (is_home()) { echo 'aria-current="page"'; } ?>>Artículos</a>
            <a href="<?php echo esc_url(home_url('/sobre-nosotros/')); ?>" <?php if (is_page('sobre-nosotros')) { echo 'aria-current="page"'; } ?>>El club</a>
            <a class="admin-link" href="<?php echo esc_url(admin_url()); ?>">PLAYER LOGIN ↗</a>
        </nav>
    </div>
</header>
