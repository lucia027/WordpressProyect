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
            <img class="site-logo" src="<?php echo esc_url(cg_logo_url()); ?>" width="100" height="100" alt="CodeGaming Arcade, inicio">
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
