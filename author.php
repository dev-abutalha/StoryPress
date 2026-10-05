<?php
/**
 * Dynamic author profile page. Reached via /author/{username}/ — no page
 * needs to be created; the bio and details come straight from the user's
 * profile in the admin panel.
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$user = get_queried_object();
if ( ! ( $user instanceof WP_User ) ) {
	get_template_part( 'template-parts/content/content', 'none' );
	get_footer();
	return;
}

$author_id   = (int) $user->ID;
$name        = get_the_author_meta( 'display_name', $author_id );
$bio         = get_the_author_meta( 'description', $author_id );
$website     = get_the_author_meta( 'user_url', $author_id );
$post_count  = count_user_posts( $author_id, 'post', true );
$post_count_text = sprintf( _n( '%s article on this site', '%s articles on this site', $post_count, 'storypress' ), number_format_i18n( $post_count ) );
?>
<div class="container archive-layout <?php echo esc_attr( storypress_blog_wrapper_classes() ); ?>">
	<section class="author-profile" aria-label="<?php esc_attr_e( 'About the author', 'storypress' ); ?>">
		<div class="author-profile__media"><?php echo get_avatar( $author_id, 160 ); ?></div>
		<div class="author-profile__body">
			<p class="eyebrow"><?php esc_html_e( 'About the author', 'storypress' ); ?></p>
			<h1><?php echo esc_html( $name ); ?></h1>
			<?php if ( $bio ) : ?>
				<p class="author-profile__bio"><?php echo esc_html( $bio ); ?></p>
			<?php endif; ?>
			<?php if ( $website || $post_count ) : ?>
				<p class="author-profile__meta">
					<?php if ( $post_count ) : ?><span><?php echo esc_html( $post_count_text ); ?></span><?php endif; ?>
					<?php if ( $post_count && $website ) : ?><span aria-hidden="true">·</span><?php endif; ?>
					<?php if ( $website ) : ?><a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Website', 'storypress' ); ?></a><?php endif; ?>
				</p>
			<?php endif; ?>
		</div>
	</section>
	<section class="post-feed">
		<header class="archive-header">
			<p class="eyebrow"><?php esc_html_e( 'Browse', 'storypress' ); ?></p>
			<h2><?php echo esc_html( sprintf( __( 'Articles by %s', 'storypress' ), $name ) ); ?></h2>
			<?php if ( have_posts() ) : ?>
				<div class="archive-description"><?php echo esc_html( sprintf( __( 'Everything %s has written on this site, newest first.', 'storypress' ), $name ) ); ?></div>
			<?php endif; ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content/content', get_post_type() ); ?>
			<?php endwhile; ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
		<?php endif; ?>
	</section>
	<?php get_sidebar(); ?>
	<?php storypress_archive_pagination(); ?>
</div>
<?php get_footer();