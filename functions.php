<?php
/**
 * StoryPress bootstrap.
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STORYPRESS_VERSION', '0.5.0' );
define( 'STORYPRESS_DIR', get_template_directory() );
define( 'STORYPRESS_URI', get_template_directory_uri() );

require STORYPRESS_DIR . '/inc/setup.php';
require STORYPRESS_DIR . '/inc/enqueue.php';
require STORYPRESS_DIR . '/inc/template-tags.php';
require STORYPRESS_DIR . '/inc/options/register.php';
require STORYPRESS_DIR . '/inc/options/helpers.php';
require STORYPRESS_DIR . '/inc/options/page.php';
require STORYPRESS_DIR . '/inc/features.php';
require STORYPRESS_DIR . '/inc/seo.php';
require STORYPRESS_DIR . '/inc/demo-content.php';

function storypress_home_feed_version_bump() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! in_array( get_post_type(), array( 'post', 'page' ), true ) ) {
		return;
	}
	update_option( 'storypress_home_feed_version', (int) get_option( 'storypress_home_feed_version', 1 ) + 1 );
}
add_action( 'save_post', 'storypress_home_feed_version_bump' );
