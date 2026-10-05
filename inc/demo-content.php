<?php
/** Default content installer. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function storypress_demo_page( $title, $slug, $content, $template ) {
	$existing = get_page_by_path( $slug );
	if ( $existing && 'trash' !== $existing->post_status ) {
		if ( $template ) { update_post_meta( $existing->ID, '_wp_page_template', $template ); }
		return $existing->ID;
	}
	$id = wp_insert_post( array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_content' => $content,
	) );
	if ( $id && ! is_wp_error( $id ) && $template ) {
		update_post_meta( $id, '_wp_page_template', $template );
	}
	return $id;
}

function storypress_demo_menu( $name, $title_slugs ) {
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu ) {
		return $menu->term_id;
	}
	$menu_id = wp_create_nav_menu( $name );
	if ( is_wp_error( $menu_id ) ) {
		return 0;
	}
	foreach ( $title_slugs as $title_slug ) {
		$page = get_page_by_path( $title_slug );
		if ( $page && 'trash' !== $page->post_status ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => $page->post_title,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			) );
		}
	}
	return $menu_id;
}

function storypress_install_default_content() {
	if ( get_option( 'storypress_default_content_done' ) ) { return; }

	$home      = storypress_demo_page( __( 'Home', 'storypress' ), 'home', '', 'template-home.php' );
	$about     = storypress_demo_page( __( 'About Us', 'storypress' ), 'about', '', 'template-about.php' );
	$contact   = storypress_demo_page( __( 'Contact Us', 'storypress' ), 'contact', '', 'template-contact.php' );
	$privacy   = storypress_demo_page( __( 'Privacy Policy', 'storypress' ), 'privacy-policy', '', '' );
	$terms     = storypress_demo_page( __( 'Terms & Conditions', 'storypress' ), 'terms-conditions', '', '' );
	$blog      = storypress_demo_page( __( 'Blog', 'storypress' ), 'blog', '', '' );

	if ( $home ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home );
	}
	if ( $blog ) {
		update_option( 'page_for_posts', $blog );
	}

	$primary = storypress_demo_menu( 'Primary', array( 'home', 'about', 'contact', 'blog' ) );
	$footer  = storypress_demo_menu( 'Footer', array( 'home', 'about', 'contact', 'privacy-policy', 'terms-conditions' ) );
	$legal   = storypress_demo_menu( 'Legal', array( 'privacy-policy', 'terms-conditions' ) );

	$locations = array();
	if ( $primary ) { $locations['primary'] = $primary; }
	if ( $footer ) { $locations['footer'] = $footer; }
	if ( $legal ) { $locations['footer-legal'] = $legal; }
	if ( $locations ) {
		set_theme_mod( 'nav_menu_locations', array_merge( (array) get_theme_mod( 'nav_menu_locations', array() ), $locations ) );
	}

	$options          = wp_parse_args( get_option( 'storypress_options', array() ), storypress_option_defaults() );
	if ( $legal ) { $options['legal_menu'] = $legal; }
	if ( $footer ) { $options['footer_menu'] = $footer; }
	if ( $home ) { $options['home_page'] = $home; }
	update_option( 'storypress_options', $options );

	update_option( 'storypress_default_content_done', 1 );
}
add_action( 'after_switch_theme', 'storypress_install_default_content' );
add_action( 'admin_init', 'storypress_install_default_content' );