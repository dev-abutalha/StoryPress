<?php /** Header. @package StoryPress */ if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!doctype html><html <?php language_attributes(); ?><?php echo 'system' === storypress_get_option( 'dark_mode' ) ? ' class="storypress-dark-system"' : ''; ?>><head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class( 'storypress-dark-' . storypress_get_option( 'dark_mode' ) ); ?>><?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'storypress' ); ?></a>
<header class="site-header<?php echo storypress_get_option( 'header_sticky' ) ? ' site-header--sticky' : ''; ?>"><div class="site-header__inner container">
	<div class="site-branding"><?php storypress_site_logo(); ?></div>
	<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><span class="screen-reader-text"><?php esc_html_e( 'Toggle navigation', 'storypress' ); ?></span><span class="menu-toggle__bar"></span><span class="menu-toggle__bar"></span><span class="menu-toggle__bar"></span></button>
	<nav class="main-navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'storypress' ); ?>"><?php $header_menu = absint( storypress_get_option( 'header_menu' ) ); wp_nav_menu( array( 'theme_location' => $header_menu ? '' : 'primary', 'menu' => $header_menu ?: '', 'menu_id' => 'primary-menu', 'container' => false, 'fallback_cb' => false ) ); ?></nav>
	<?php if ( storypress_get_option( 'cta_enabled' ) && storypress_get_option( 'cta_text' ) && storypress_get_option( 'cta_url' ) ) : ?><a class="header-cta" href="<?php echo esc_url( storypress_get_option( 'cta_url' ) ); ?>"><?php echo esc_html( storypress_get_option( 'cta_text' ) ); ?></a><?php endif; ?>
	<?php if ( 'manual' === storypress_get_option( 'dark_mode' ) ) : ?><button class="color-mode-toggle" type="button" aria-pressed="false"><span class="screen-reader-text"><?php esc_html_e( 'Toggle dark mode', 'storypress' ); ?></span><span aria-hidden="true">◐</span></button><?php endif; ?>
	<?php if ( storypress_get_option( 'show_search' ) ) : ?><button class="search-toggle" aria-expanded="false" aria-controls="site-search"><span class="screen-reader-text"><?php esc_html_e( 'Open search', 'storypress' ); ?></span>⌕</button><?php endif; ?>
</div></header>
<?php if ( storypress_get_option( 'show_search' ) ) : ?><div id="site-search" class="search-panel" hidden><div class="container"><?php get_search_form(); ?></div></div><?php endif; ?>
<main id="content" class="site-main">
