<?php
/**
 * Template Name: Blog
 * A "Blog" page template with the same archive layout as the posts index.
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$storypress_blog_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => get_option( 'posts_per_page' ),
		'paged'               => max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) ),
		'ignore_sticky_posts' => false,
	)
);
?>
<div class="storypress-page-hero storypress-page-hero--blog">
	<div class="container">
		<p class="eyebrow"><?php esc_html_e( 'The journal', 'storypress' ); ?></p>
		<h1><?php the_title(); ?></h1>
		<p><?php esc_html_e( 'Destination notes, practical guides, and ideas for travelling with more intention.', 'storypress' ); ?></p>
	</div>
</div>
<div class="container archive-layout storypress-blog-archive <?php echo esc_attr( storypress_blog_wrapper_classes() ); ?>">
	<section class="post-feed" aria-label="<?php esc_attr_e( 'Blog posts', 'storypress' ); ?>">
		<?php if ( $storypress_blog_query->have_posts() ) : ?>
			<?php while ( $storypress_blog_query->have_posts() ) : $storypress_blog_query->the_post(); ?>
				<?php get_template_part( 'template-parts/content/content' ); ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
		<?php endif; ?>
	</section>
	<?php get_sidebar(); ?>
	<?php storypress_archive_pagination( $storypress_blog_query ); ?>
</div>
<?php
wp_reset_postdata();
get_footer();