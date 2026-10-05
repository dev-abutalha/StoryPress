<?php /** Empty state. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<section class="empty-state">
<?php if ( is_search() ) : ?>
	<p class="eyebrow"><?php esc_html_e( 'No results', 'storypress' ); ?></p>
	<h1><?php esc_html_e( 'Nothing matched your search.', 'storypress' ); ?></h1>
	<p><?php printf( esc_html__( 'We couldn’t find anything for “%s”. Try another keyword — or search again below.', 'storypress' ), esc_html( get_search_query() ) ); ?></p>
	<?php get_search_form(); ?>
<?php else : ?>
	<p class="eyebrow"><?php esc_html_e( 'Nothing here yet', 'storypress' ); ?></p>
	<h1><?php esc_html_e( 'The next story is on its way.', 'storypress' ); ?></h1>
	<p><?php esc_html_e( 'This space is still taking shape. Check back soon.', 'storypress' ); ?></p>
<?php endif; ?>
<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'storypress' ); ?></a>
</section>