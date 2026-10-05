<?php
/**
 * StoryPress option registration, defaults and sanitization.
 *
 * All StoryPress settings live in the single `storypress_options` option,
 * edited from the StoryPress Settings admin page (see inc/options/page.php).
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function storypress_option_defaults() {
	return array(
		'primary_color'            => '#9b4d2d',
		'primary_color_dark'       => '#d88a67',
		'dark_mode'                => 'system',
		'container_width'          => '1180',
		'blog_layout'              => 'three-grid',
		'blog_width'               => 'standard',
		'blog_posts_per_page'      => 9,
		'archive_sidebar'          => false,
		'single_layout'            => 'classic',
		'header_menu'              => 0,
		'footer_menu'              => 0,
		'legal_menu'               => 0,
		'show_comments'            => true,
		'header_sticky'            => false,
		'show_search'              => true,
		'cta_enabled'              => false,
		'cta_text'                 => '',
		'cta_url'                  => '',
		'copyright'                => '',
		'footer_text'              => '',
		'dark_logo'                => 0,
		'logo_width'               => 180,
		'home_page'                => 0,
		'home_design'              => 'classic',
		'home_hero_eyebrow'        => 'Your guide to real travel',
		'home_hero_title'          => 'Find your next destination. Book it smart.',
		'home_hero_text'           => 'Honest, practical travel writing — destination guides, booking advice, and money-saving tips from people who have actually been there.',
		'home_hero_cta_text'       => 'Explore the guides',
		'home_hero_cta_url'        => '',
		'home_destinations_enabled' => true,
		'home_destinations_cat'    => 0,
		'home_destinations_title'  => 'Our Travel Destinations',
		'home_destinations_count'  => 5,
		'home_latest_enabled'      => true,
		'home_latest_cat'          => 0,
		'home_latest_tag'          => 0,
		'home_latest_layout'       => 'list',
		'home_latest_title'        => 'Latest Stories',
		'home_latest_count'        => 3,
		'home_feature_enabled'     => true,
		'home_feature_cat'         => 0,
		'home_feature_tag'         => 0,
		'home_feature_title'       => 'Featured Story',
		'home_feature_count'       => 1,
		'home_popular_enabled'     => true,
		'home_popular_cat'         => 0,
		'home_popular_tag'         => 0,
		'home_popular_title'       => 'Popular Posts',
		'home_popular_count'       => 4,
		'home_editors_enabled'     => true,
		'home_editors_cat'         => 0,
		'home_editors_tag'         => 0,
		'home_editors_title'       => "Editor's Choice",
		'home_editors_count'       => 3,
		'home_latestposts_enabled' => true,
		'home_latestposts_cat'     => 0,
		'home_latestposts_tag'     => 0,
		'home_latestposts_layout'  => 'list',
		'home_latestposts_title'   => 'Latest Posts',
		'home_latestposts_count'   => 8,
		'home_latestposts_loadmore' => true,
		'social_facebook'          => '',
		'social_instagram'         => '',
		'social_twitter'           => '',
		'social_youtube'           => '',
		'social_pinterest'         => '',
		'social_linkedin'          => '',
		'social_tiktok'            => '',
		'back_to_top'              => true,
		'reading_progress'         => true,
		'table_of_contents'        => true,
		'share_links'              => true,
		'related_posts'            => true,
		'show_breadcrumbs'         => true,
		'show_author'              => true,
		'show_date'                => true,
		'show_reading_time'        => true,
		'author_box'               => true,
		'consent_banner'           => true,
		'consent_message'          => '',
		'code_head'                => '',
		'code_body'                => '',
		'code_footer'              => '',
		'perf_remove_emoji'        => false,
		'perf_disable_trackbacks'  => false,
		'perf_disable_embeds'      => false,
		'rest_api'                 => 'enabled',
		'disable_xmlrpc'           => false,
	);
}

function storypress_get_option( $key ) {
	static $options = null;
	if ( null === $options ) {
		$options = wp_parse_args( get_option( 'storypress_options', array() ), storypress_option_defaults() );
	}
	if ( 'blog_layout' === $key && 'editorial' === ( $options[ $key ] ?? '' ) ) {
		$options[ $key ] = 'three-grid';
	}
	return $options[ $key ] ?? null;
}

function storypress_sanitize_options( $input ) {
	$defaults = storypress_option_defaults();
	$o        = array();

	$o['primary_color']   = sanitize_hex_color( $input['primary_color'] ?? '' ) ?: $defaults['primary_color'];
	$o['primary_color_dark'] = sanitize_hex_color( $input['primary_color_dark'] ?? '' ) ?: $defaults['primary_color_dark'];
	$o['dark_mode']       = in_array( $input['dark_mode'] ?? '', array( 'disabled', 'system', 'manual' ), true ) ? $input['dark_mode'] : $defaults['dark_mode'];
	$o['container_width'] = in_array( $input['container_width'] ?? '', array( '1080', '1180', '1320' ), true ) ? $input['container_width'] : $defaults['container_width'];

	$layout = $input['blog_layout'] ?? '';
	if ( 'editorial' === $layout ) {
		$layout = 'three-grid';
	}
	$o['blog_layout']   = in_array( $layout, array( 'list', 'grid', 'three-grid' ), true ) ? $layout : $defaults['blog_layout'];
	$o['blog_width']    = in_array( $input['blog_width'] ?? '', array( 'compact', 'standard', 'wide' ), true ) ? $input['blog_width'] : $defaults['blog_width'];
	$o['single_layout'] = in_array( $input['single_layout'] ?? '', array( 'classic', 'inline' ), true ) ? $input['single_layout'] : $defaults['single_layout'];
	$o['home_design']   = in_array( $input['home_design'] ?? '', array( 'classic', 'studio' ), true ) ? $input['home_design'] : $defaults['home_design'];
	$o['rest_api'] = in_array( $input['rest_api'] ?? '', array( 'enabled', 'logged_in', 'disabled' ), true ) ? $input['rest_api'] : $defaults['rest_api'];

	$ids = array(
		'header_menu',
		'footer_menu',
		'legal_menu',
		'dark_logo',
		'home_page',
		'home_destinations_cat',
		'home_latest_cat',
		'home_latest_tag',
		'home_feature_cat',
		'home_feature_tag',
		'home_popular_cat',
		'home_popular_tag',
		'home_editors_cat',
		'home_editors_tag',
		'home_latestposts_cat',
		'home_latestposts_tag',
	);
	foreach ( $ids as $k ) {
		$o[ $k ] = absint( $input[ $k ] ?? 0 );
	}

	$o['logo_width'] = max( 40, min( 400, absint( $input['logo_width'] ?? 180 ) ) );

	$counts = array(
		'home_destinations_count',
		'home_latest_count',
		'home_feature_count',
		'home_popular_count',
		'home_editors_count',
		'home_latestposts_count',
	);
	foreach ( $counts as $k ) {
		$o[ $k ] = max( 1, min( 12, absint( $input[ $k ] ?? $defaults[ $k ] ) ) );
	}

	$texts = array(
		'footer_text',
		'cta_text',
		'copyright',
		'home_hero_eyebrow',
		'home_hero_title',
		'home_hero_text',
		'home_hero_cta_text',
		'home_destinations_title',
		'home_latest_title',
		'home_feature_title',
		'home_popular_title',
		'home_editors_title',
		'home_latestposts_title',
	);
	foreach ( $texts as $k ) {
		$value = $input[ $k ] ?? '';
		if ( '' !== trim( $value ) ) {
			$o[ $k ] = 'home_hero_text' === $k ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
		} else {
			$o[ $k ] = $defaults[ $k ];
		}
	}

	$o['cta_url']           = esc_url_raw( $input['cta_url'] ?? '' );
	$o['home_hero_cta_url'] = esc_url_raw( $input['home_hero_cta_url'] ?? '' );

	foreach ( array( 'facebook', 'instagram', 'twitter', 'youtube', 'pinterest', 'linkedin', 'tiktok' ) as $s ) {
		$o[ 'social_' . $s ] = esc_url_raw( trim( $input[ 'social_' . $s ] ?? '' ) );
	}

	foreach ( array( 'home_latest_layout', 'home_latestposts_layout' ) as $k ) {
		$o[ $k ] = in_array( $input[ $k ] ?? '', array( 'list', 'cards' ), true ) ? $input[ $k ] : $defaults[ $k ];
	}

	$toggles = array(
		'show_comments',
		'archive_sidebar',
		'header_sticky',
		'show_search',
		'cta_enabled',
		'back_to_top',
		'reading_progress',
		'table_of_contents',
		'share_links',
		'related_posts',
		'show_breadcrumbs',
		'show_author',
		'show_date',
		'show_reading_time',
		'author_box',
		'consent_banner',
		'perf_remove_emoji',
		'perf_disable_trackbacks',
		'perf_disable_embeds',
		'disable_xmlrpc',
		'home_destinations_enabled',
		'home_latest_enabled',
		'home_feature_enabled',
		'home_popular_enabled',
		'home_editors_enabled',
		'home_latestposts_enabled',
		'home_latestposts_loadmore',
	);
	foreach ( $toggles as $k ) {
		$o[ $k ] = ! empty( $input[ $k ] );
	}

	$o['consent_message'] = isset( $input['consent_message'] ) ? sanitize_textarea_field( $input['consent_message'] ) : $defaults['consent_message'];

	foreach ( array( 'code_head', 'code_body', 'code_footer' ) as $k ) {
		$o[ $k ] = isset( $input[ $k ] ) ? $input[ $k ] : $defaults[ $k ];
	}

	update_option( 'blogname', sanitize_text_field( $input['site_title'] ?? get_option( 'blogname' ) ) );
	update_option( 'blogdescription', sanitize_text_field( $input['site_tagline'] ?? get_option( 'blogdescription' ) ) );

	$logo = absint( $input['custom_logo'] ?? get_theme_mod( 'custom_logo' ) );
	if ( $logo ) {
		set_theme_mod( 'custom_logo', $logo );
	} else {
		remove_theme_mod( 'custom_logo' );
	}

	if ( $o['home_page'] ) {
		$home_post = get_post( $o['home_page'] );
		if ( $home_post && 'page' === $home_post->post_type && 'trash' !== $home_post->post_status ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $o['home_page'] );
			update_post_meta( $o['home_page'], '_wp_page_template', 'template-home.php' );
		} else {
			$o['home_page'] = 0;
			update_option( 'show_on_front', 'posts' );
			update_option( 'page_on_front', 0 );
		}
	} else {
		update_option( 'show_on_front', 'posts' );
		update_option( 'page_on_front', 0 );
	}

	if ( isset( $input['site_icon'] ) ) {
		$icon = absint( $input['site_icon'] );
		update_option( 'site_icon', $icon ? $icon : 0 );
	}

	return $o;
}

function storypress_sanitize_settings( $input ) {
	$o                            = storypress_sanitize_options( $input );
	$o['blog_posts_per_page']     = max( 1, min( 24, absint( $input['blog_posts_per_page'] ?? storypress_option_defaults()['blog_posts_per_page'] ) ) );
	update_option( 'posts_per_page', $o['blog_posts_per_page'] );
	return $o;
}

add_action( 'admin_init', function () {
	register_setting( 'storypress_options_group', 'storypress_options', array(
		'sanitize_callback' => 'storypress_sanitize_settings',
		'default'           => storypress_option_defaults(),
	) );
} );

add_action( 'admin_menu', function () {
	$storypress_icon = get_template_directory() . '/assets/img/StoryPressIcon.svg';

	if ( is_readable( $storypress_icon ) ) {
		$storypress_icon = 'data:image/svg+xml;base64,' . base64_encode( (string) file_get_contents( $storypress_icon ) );
	} else {
		$storypress_icon = 'dashicons-book-alt';
	}

	add_menu_page(
		esc_html__( 'StoryPress Settings', 'storypress' ),
		esc_html__( 'StoryPress', 'storypress' ),
		'manage_options',
		'storypress-settings',
		'storypress_options_page',
		$storypress_icon,
		59
	);
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( 'toplevel_page_storypress-settings' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'storypress-options', STORYPRESS_URI . '/assets/css/admin-options.css', array(), STORYPRESS_VERSION );
	wp_add_inline_style( 'storypress-options', '.storypress-savebar .storypress-save-button{display:inline-flex;width:150px;align-items:center;justify-content:center;gap:8px;box-sizing:border-box}.storypress-savebar .storypress-save-button.is-loading{opacity:.75;cursor:progress}.storypress-save-spinner{display:inline-block;width:16px;height:16px;flex:0 0 16px;border:2px solid currentColor;border-right-color:transparent;border-radius:50%;animation:storypress-save-spin .7s linear infinite;vertical-align:middle}.storypress-save-spinner[hidden]{display:none}@keyframes storypress-save-spin{to{transform:rotate(360deg)}}@media(prefers-reduced-motion:reduce){.storypress-save-spinner{animation-duration:1.5s}}' );
	wp_add_inline_style( 'storypress-options', '.storypress-settings.wrap{margin:0 0 36px}.storypress-settings__header{border-radius:0}.storypress-savebar .storypress-save-button,.storypress-savebar .storypress-save-button:hover,.storypress-savebar .storypress-save-button:focus{color:#fff}' );
	wp_enqueue_script( 'storypress-options', STORYPRESS_URI . '/assets/js/admin-options.js', array( 'media-editor' ), STORYPRESS_VERSION, true );
} );