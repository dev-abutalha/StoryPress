<?php
/** Asset registration. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function storypress_enqueue_assets() {
	wp_enqueue_style( 'storypress-style', get_stylesheet_uri(), array(), STORYPRESS_VERSION );
	wp_enqueue_style( 'storypress-main', STORYPRESS_URI . '/assets/css/main.css', array( 'storypress-style' ), STORYPRESS_VERSION );
	if ( is_front_page() || is_page_template( 'template-home.php' ) ) {
		wp_enqueue_style( 'storypress-swiper', STORYPRESS_URI . '/assets/vendor/swiper/swiper-bundle.min.css', array(), '11.2.10' );
		wp_enqueue_style( 'storypress-home', STORYPRESS_URI . '/assets/css/home.css', array( 'storypress-main', 'storypress-swiper' ), STORYPRESS_VERSION );
		wp_enqueue_script( 'storypress-swiper', STORYPRESS_URI . '/assets/vendor/swiper/swiper-bundle.min.js', array(), '11.2.10', true );
		wp_enqueue_script( 'storypress-home', STORYPRESS_URI . '/assets/js/home.js', array( 'storypress-swiper' ), STORYPRESS_VERSION, true );
	}
	$accent      = sanitize_hex_color( (string) storypress_get_option( 'primary_color' ) );
	$accent_dark = sanitize_hex_color( (string) storypress_get_option( 'primary_color_dark' ) );
	$container   = absint( (int) storypress_get_option( 'container_width' ) );
	if ( $container ) {
		wp_add_inline_style( 'storypress-main', ':root{--container:' . $container . 'px;}' );
	}
	if ( $accent ) {
		wp_add_inline_style( 'storypress-main', ':root{--accent:' . esc_attr( $accent ) . ';}' );
	}
	if ( $accent_dark ) {
		wp_add_inline_style( 'storypress-main', '.theme-dark{--accent:' . esc_attr( $accent_dark ) . ';}@media (prefers-color-scheme:dark){.storypress-dark-system{--accent:' . esc_attr( $accent_dark ) . ';}}' );
	}
	wp_enqueue_script( 'storypress-navigation', STORYPRESS_URI . '/assets/js/theme.js', array(), STORYPRESS_VERSION, true );
	if ( storypress_get_option( 'show_comments' ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_action( 'wp_enqueue_scripts', 'storypress_enqueue_assets' );

function storypress_early_color_mode() {
	if ( 'manual' !== storypress_get_option( 'dark_mode' ) ) { return; }
	echo '<script>(function(){try{if(window.localStorage.getItem("storypress-color-mode")==="dark"){document.documentElement.classList.add("theme-dark");}}catch(e){}})();</script>' . "\n";
}
add_action( 'wp_head', 'storypress_early_color_mode', 0 );
