<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
?>
<?php if ( storypress_get_option( 'reading_progress' ) ) : ?><div class="reading-progress" aria-hidden="true"><span></span></div><?php endif; ?>
<?php if ( storypress_get_option( 'show_breadcrumbs' ) ) { storypress_breadcrumbs(); } ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post single-layout-' . sanitize_html_class( storypress_get_option( 'single_layout' ) ) ); ?> itemscope itemtype="https://schema.org/BlogPosting">
	<header class="entry-header reading-width">
		<div class="post-card__meta"><?php the_category( ', ' ); ?></div>
		<h1 itemprop="headline"><?php the_title(); ?></h1>
		<?php if ( has_excerpt() ) : ?><p class="entry-lede"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
		<div class="entry-meta">
			<?php if ( storypress_get_option( 'show_author' ) ) { storypress_posted_by(); } ?>
			<?php if ( storypress_get_option( 'show_author' ) && ( storypress_get_option( 'show_date' ) || storypress_get_option( 'show_reading_time' ) ) ) { echo '<span aria-hidden="true">·</span>'; } ?>
			<?php if ( storypress_get_option( 'show_date' ) ) { storypress_posted_on(); } ?>
			<?php if ( storypress_get_option( 'show_date' ) && storypress_get_option( 'show_reading_time' ) ) { echo '<span aria-hidden="true">·</span>'; } ?>
			<?php if ( storypress_get_option( 'show_reading_time' ) ) { storypress_reading_time(); } ?>
		</div>
	</header>
	<?php if ( has_post_thumbnail() ) : ?><figure class="entry-figure container-wide"><?php the_post_thumbnail( 'storypress-featured', array( 'itemprop' => 'image' ) ); ?></figure><?php endif; ?>
	<div class="single-reading-layout">
		<?php if ( storypress_get_option( 'table_of_contents' ) && 'inline' !== storypress_get_option( 'single_layout' ) ) : ?><aside class="single-toc"><?php storypress_table_of_contents(); ?></aside><?php endif; ?>
		<div class="single-reading-main">
			<?php if ( storypress_get_option( 'table_of_contents' ) && 'inline' === storypress_get_option( 'single_layout' ) ) : ?><div class="reading-width toc-wrap"><?php storypress_table_of_contents(); ?></div><?php endif; ?>
			<div class="entry-content reading-width" itemprop="articleBody"><?php the_content(); wp_link_pages(); ?></div>
			<footer class="entry-footer reading-width"><?php the_tags( '<div class="post-tags">', '', '</div>' ); if ( storypress_get_option( 'share_links' ) ) { storypress_share_links(); } ?></footer>
			<?php if ( storypress_get_option( 'author_box' ) ) { storypress_author_box(); } ?>
		</div>
	</div>
	<?php if ( storypress_get_option( 'related_posts' ) ) { storypress_related_posts(); } ?>
</article>
<?php if ( storypress_get_option( 'show_comments' ) && ( comments_open() || get_comments_number() ) ) { comments_template(); } ?>
<?php endwhile; get_footer();
