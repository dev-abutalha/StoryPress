<?php if ( ! defined( 'ABSPATH' ) ) { exit; } get_header(); ?>
<?php if ( have_posts() ) : ?>
<div class="container archive-layout <?php echo esc_attr( storypress_blog_wrapper_classes() ); ?>">
	<section class="post-feed">
		<header class="archive-header">
			<p class="eyebrow"><?php esc_html_e( 'Search', 'storypress' ); ?></p>
			<h1><?php printf( esc_html__( 'Results for “%s”', 'storypress' ), esc_html( get_search_query() ) ); ?></h1>
			<?php get_search_form(); ?>
		</header>
		<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/content/content', get_post_type() ); endwhile; ?>
	</section>
	<?php get_sidebar(); ?>
	<?php storypress_archive_pagination(); ?>
</div>
<?php else : ?>
<div class="container"><?php get_template_part( 'template-parts/content/content', 'none' ); ?></div>
<?php endif; ?>
<?php get_footer();