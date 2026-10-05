<?php
/** Lightweight, plugin-compatible SEO helpers. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function storypress_has_seo_plugin() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

function storypress_breadcrumbs() {
	if ( is_front_page() ) { return; }
	echo '<nav class="breadcrumbs reading-width" aria-label="' . esc_attr__( 'Breadcrumbs', 'storypress' ) . '"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'storypress' ) . '</a>';
	if ( is_single() ) {
		$categories = get_the_category();
		if ( ! empty( $categories ) ) { echo '<span aria-hidden="true">/</span><a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>'; }
		echo '<span aria-hidden="true">/</span><span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_archive() || is_search() ) { echo '<span aria-hidden="true">/</span><span aria-current="page">' . esc_html( wp_strip_all_tags( get_the_archive_title() ) ) . '</span>'; }
	echo '</nav>';
}

function storypress_article_schema() {
	if ( ! is_singular( 'post' ) || storypress_has_seo_plugin() ) { return; }
	$post_id = get_queried_object_id();
	$data = array( '@context' => 'https://schema.org', '@type' => 'BlogPosting', 'mainEntityOfPage' => get_permalink( $post_id ), 'headline' => get_the_title( $post_id ), 'datePublished' => get_the_date( DATE_W3C, $post_id ), 'dateModified' => get_the_modified_date( DATE_W3C, $post_id ), 'author' => array( '@type' => 'Person', 'name' => get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) ) ), 'publisher' => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ) ) );
	if ( has_post_thumbnail( $post_id ) ) { $data['image'] = get_the_post_thumbnail_url( $post_id, 'full' ); }
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'storypress_article_schema', 20 );
