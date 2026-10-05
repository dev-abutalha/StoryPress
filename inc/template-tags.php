<?php
/** Reusable presentation helpers. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function storypress_reading_time() {
	$words = str_word_count( wp_strip_all_tags( get_the_content() ) );
	$minutes = max( 1, (int) ceil( $words / 220 ) );
	printf( '<span class="post-reading-time">%s</span>', esc_html( sprintf( _n( '%s minute read', '%s minutes read', $minutes, 'storypress' ), number_format_i18n( $minutes ) ) ) );
}

function storypress_posted_on() {
	$time = sprintf( '<time class="entry-date" datetime="%1$s">%2$s</time>', esc_attr( get_the_date( DATE_W3C ) ), esc_html( get_the_date() ) );
	printf( '<span class="posted-on">%s</span>', $time ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function storypress_posted_by() {
	printf( '<span class="byline">%s <a href="%s">%s</a></span>', esc_html__( 'By', 'storypress' ), esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_html( get_the_author() ) );
}

function storypress_archive_pagination( $query = null ) {
	global $wp_query;
	$pagination_query = $query instanceof WP_Query ? $query : $wp_query;
	if ( ! $pagination_query || $pagination_query->max_num_pages < 2 ) { return; }
	$current = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	$links = paginate_links( array( 'current' => $current, 'total' => $pagination_query->max_num_pages, 'mid_size' => 1, 'type' => 'list', 'prev_text' => esc_html__( 'Newer', 'storypress' ), 'next_text' => esc_html__( 'Older', 'storypress' ) ) );
	if ( $links ) {
		printf( '<nav class="navigation pagination storypress-pagination" aria-label="%s"><h2 class="screen-reader-text">%s</h2>%s</nav>', esc_attr__( 'Posts navigation', 'storypress' ), esc_html__( 'Posts navigation', 'storypress' ), wp_kses_post( $links ) );
	}
}

function storypress_table_of_contents() {
	if ( ! is_singular( 'post' ) ) { return; }
	printf( '<details class="table-of-contents" data-storypress-toc hidden><summary>%s</summary><ol></ol></details>', esc_html__( 'On this page', 'storypress' ) );
}

function storypress_share_links() {
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );
	$links = array(
		'X'        => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
		'LinkedIn' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
		'Facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		'Email'    => 'mailto:?subject=' . $title . '&body=' . $url,
	);
	echo '<section class="share-links" aria-label="' . esc_attr__( 'Share this article', 'storypress' ) . '"><span>' . esc_html__( 'Share', 'storypress' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	foreach ( $links as $label => $href ) {
		printf( '<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>', esc_url( $href ), esc_html( $label ) );
	}
	echo '<button type="button" data-copy-link data-copy-label="' . esc_attr__( 'Copy link', 'storypress' ) . '" data-copied-label="' . esc_attr__( 'Copied', 'storypress' ) . '">' . esc_html__( 'Copy link', 'storypress' ) . '</button></section>';
}

function storypress_posts_page_url() {
	$posts_page = absint( get_option( 'page_for_posts' ) );
	if ( $posts_page ) {
		return get_permalink( $posts_page );
	}
	$blog_page = get_page_by_path( 'blog' );
	if ( $blog_page ) {
		return get_permalink( $blog_page );
	}
	return home_url( '/' );
}

function storypress_site_logo() {
	$light_id = absint( get_theme_mod( 'custom_logo' ) );
	$dark_id  = absint( storypress_get_option( 'dark_logo' ) );
	$name     = get_bloginfo( 'name' );
	if ( ! $light_id && ! $dark_id ) {
		echo '<a class="site-title" href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( $name ) . '</a>';
		return;
	}
	$width = max( 40, min( 400, absint( storypress_get_option( 'logo_width' ) ) ) );
	echo '<a class="custom-logo-link" href="' . esc_url( home_url( '/' ) ) . '" rel="home" style="width:' . esc_attr( (string) $width ) . 'px">';
	if ( $light_id ) {
		echo wp_get_attachment_image( $light_id, 'full', false, array( 'class' => 'custom-logo storypress-logo-light', 'alt' => $name ) );
	}
	if ( $dark_id ) {
		echo wp_get_attachment_image( $dark_id, 'full', false, array( 'class' => 'custom-logo ' . ( $light_id ? 'storypress-logo-dark' : '' ), 'alt' => $name ) );
	}
	echo '</a>';
}

function storypress_has_social_links() {
	foreach ( array_keys( storypress_social_networks() ) as $network ) {
		if ( storypress_get_option( 'social_' . $network ) ) { return true; }
	}
	return false;
}

function storypress_social_links( $with_heading = false ) {
	if ( ! storypress_has_social_links() ) { return; }
	echo '<div class="storypress-social">';
	if ( $with_heading ) {
		echo '<span class="storypress-social__label">' . esc_html__( 'Follow us', 'storypress' ) . '</span>';
	}
	foreach ( storypress_social_networks() as $network => $details ) {
		$url = storypress_get_option( 'social_' . $network );
		if ( ! $url ) { continue; }
		$icon = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="' . esc_attr( $details['icon'] ) . '"/></svg>';
		printf(
			'<a class="storypress-social__link storypress-social__link--%1$s" href="%2$s" aria-label="%3$s" target="_blank" rel="noopener noreferrer">%4$s</a>',
			esc_attr( $network ),
			esc_url( $url ),
			esc_attr( sprintf( __( 'Follow us on %s', 'storypress' ), $details['label'] ) ),
			$icon // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
	echo '</div>';
}

function storypress_related_posts() {
	$categories = wp_get_post_categories( get_the_ID() );
	if ( empty( $categories ) ) { return; }
	$query = new WP_Query( array( 'post__not_in' => array( get_the_ID() ), 'posts_per_page' => 3, 'ignore_sticky_posts' => true, 'category__in' => $categories, 'no_found_rows' => true ) );
	if ( ! $query->have_posts() ) { return; }
	echo '<section class="related-posts container" aria-labelledby="related-posts-heading"><header class="related-posts__head"><p class="eyebrow">' . esc_html__( 'Keep reading', 'storypress' ) . '</p><h2 id="related-posts-heading">' . esc_html__( 'Related stories', 'storypress' ) . '</h2></header><div class="related-grid">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	while ( $query->have_posts() ) { $query->the_post(); get_template_part( 'template-parts/content/content-related' ); }
	echo '</div></section>';
	wp_reset_postdata();
}

function storypress_author_box() {
	$author_id = (int) get_post_field( 'post_author', get_the_ID() );
	if ( ! $author_id ) {
		return;
	}
	$name = get_the_author_meta( 'display_name', $author_id );
	$bio  = get_the_author_meta( 'description', $author_id );
	$url  = get_author_posts_url( $author_id );
	echo '<aside class="author-box reading-width" aria-label="' . esc_attr__( 'About the author', 'storypress' ) . '">';
	echo get_avatar( $author_id, 96, '', $name );
	echo '<div class="author-box__body">';
	echo '<span class="author-box__label">' . esc_html__( 'Written by', 'storypress' ) . '</span>';
	echo '<a class="author-box__name" href="' . esc_url( $url ) . '">' . esc_html( $name ) . '</a>';
	if ( $bio ) { echo '<p class="author-box__bio">' . esc_html( wp_trim_words( $bio, 15, '...' ) ) . '</p>'; }
	echo '</div>';
	echo '<a class="author-box__link button" href="' . esc_url( $url ) . '" rel="author">' . esc_html__( 'About the author', 'storypress' ) . '</a>';
	echo '</aside>';
}
