<?php
/** Template Name: Contact. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post(); ?>
<div class="storypress-page storypress-contact-page">
	<section class="storypress-page-hero">
		<div class="container">
			<p class="eyebrow"><?php esc_html_e( "Let's talk", 'storypress' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p><?php esc_html_e( 'Have a question, a story idea, or a place we should know about? We would love to hear from you.', 'storypress' ); ?></p>
		</div>
	</section>
	<div class="container storypress-contact-layout">
		<div class="storypress-contact-card">
			<span class="storypress-contact-card__number">01</span>
			<h2><?php esc_html_e( 'Send a note', 'storypress' ); ?></h2>
			<?php if ( trim( get_the_content() ) ) : ?>
				<div class="entry-content"><?php the_content(); wp_link_pages(); ?></div>
			<?php else : ?>
				<p><?php esc_html_e( 'For editorial ideas, partnerships, or a simple hello, email us directly. We read every message and reply as soon as we can.', 'storypress' ); ?></p>
			<?php endif; ?>
			<a class="storypress-contact__mail" href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>"><?php echo esc_html( antispambot( get_option( 'admin_email' ) ) ); ?><span aria-hidden="true">&rarr;</span></a>
		</div>
		<div class="storypress-contact-details">
			<div><span><?php esc_html_e( 'Editorial desk', 'storypress' ); ?></span><strong><?php esc_html_e( 'Stories, guides, and collaborations', 'storypress' ); ?></strong></div>
			<div><span><?php esc_html_e( 'Response time', 'storypress' ); ?></span><strong><?php esc_html_e( 'Usually within two working days', 'storypress' ); ?></strong></div>
			<?php storypress_social_links( true ); ?>
		</div>
	</div>
</div>
<?php endwhile;
get_footer();