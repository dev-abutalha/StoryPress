<?php
/**
 * Shared blog archive feed.
 * Used by home.php (posts index) and index.php (fallback) so the two
 * templates never drift apart.
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="container archive-layout <?php echo esc_attr( storypress_blog_wrapper_classes() ); ?>">
	<section class="post-feed" aria-label="<?php esc_attr_e( 'Blog posts', 'storypress' ); ?>">
		<header class="archive-header">
			<p class="eyebrow"><?php esc_html_e( 'Latest stories', 'storypress' ); ?></p>
			<h1><?php $storypress_archive_title = single_post_title( '', false ); echo $storypress_archive_title ? esc_html( $storypress_archive_title ) : esc_html__( 'The Blog', 'storypress' ); ?></h1>
		</header>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); get_template_part( 'template-parts/content/content', get_post_type() ); endwhile; else : get_template_part( 'template-parts/content/content', 'none' ); endif; ?>
	</section>
	<?php get_sidebar(); ?>
	<?php storypress_archive_pagination(); ?>
</div>