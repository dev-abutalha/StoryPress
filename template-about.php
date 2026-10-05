<?php
/** Template Name: About. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post(); ?>
<div class="storypress-page storypress-about-page">
	<section class="storypress-page-hero">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( 'About the publication', 'storypress' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p><?php esc_html_e( 'Thoughtful travel stories for curious people who would rather understand a place than simply pass through it.', 'storypress' ); ?></p>
		</div>
	</section>
	<div class="container storypress-about-layout">
		<div class="storypress-page-copy">
			<?php if ( trim( get_the_content() ) ) : ?>
				<div class="entry-content"><?php the_content(); wp_link_pages(); ?></div>
			<?php else : ?>
				<p class="eyebrow"><?php esc_html_e( 'Our point of view', 'storypress' ); ?></p>
				<h2><?php esc_html_e( 'Travel is better when the details are honest.', 'storypress' ); ?></h2>
				<p><?php esc_html_e( 'We make destination guides, practical planning notes, and personal stories for readers who want useful information without losing the feeling of discovery.', 'storypress' ); ?></p>
				<p><?php esc_html_e( 'Every piece is shaped around the questions we ask before a trip: What is genuinely worth the time? What should you know before you go? And how can you experience a place with curiosity and care?', 'storypress' ); ?></p>
			<?php endif; ?>
		</div>
		<aside class="storypress-about-note">
			<span><?php esc_html_e( 'Since 2024', 'storypress' ); ?></span>
			<strong><?php esc_html_e( 'Go somewhere. Notice more.', 'storypress' ); ?></strong>
			<?php storypress_social_links( true ); ?>
		</aside>
	</div>
</div>
<?php endwhile;
get_footer();