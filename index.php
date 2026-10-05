<?php
/**
 * Fallback index template.
 * Picks up any query type without a dedicated template.
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part( 'template-parts/blog/archive' );
get_footer();