<?php
/**
 * Option-driven homepage.
 * Every section below is controlled from StoryPress Settings → Home page, so the
 * homepage can be rebuilt without any page template.
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$storypress_home_paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

$storypress_home_query = static function ( $count, $cat, $tag, $extra = array(), $paged = false ) {
	$args = array_merge(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => max( 1, (int) $count ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => ! $paged,
			'orderby'             => 'date',
			'order'               => 'DESC',
		),
		$extra
	);
	if ( $cat ) {
		$args['category__in'] = array( (int) $cat );
	}
	if ( $tag && ! $cat ) {
		$args['tag__in'] = array( (int) $tag );
	}

	if ( $paged ) {
		return new WP_Query( $args );
	}

	$cache_key = 'storypress_home_' . md5( (string) wp_json_encode( $args ) ) . '_' . (int) get_option( 'storypress_home_feed_version', 1 );
	$post_ids  = get_transient( $cache_key );

	if ( false === $post_ids ) {
		$post_ids = (array) ( new WP_Query( array_merge( $args, array( 'fields' => 'ids' ) ) ) )->posts;
		set_transient( $cache_key, $post_ids, 5 * MINUTE_IN_SECONDS );
	}

	if ( empty( $post_ids ) ) {
		return new WP_Query( array( 'post_type' => 'post', 'post__in' => array( 0 ), 'posts_per_page' => 1, 'no_found_rows' => true ) );
	}

	return new WP_Query( array( 'post_type' => 'post', 'post__in' => $post_ids, 'orderby' => 'post__in', 'posts_per_page' => count( $post_ids ), 'ignore_sticky_posts' => true, 'no_found_rows' => true ) );
};

$storypress_home_hero    = $storypress_home_query(
	12,
	0,
	0,
	array(
	'meta_query' => array(
		array(
			'key'     => '_thumbnail_id',
			'compare' => 'EXISTS',
		),
	),
	)
);
$storypress_home_hero->posts = array_slice(
	array_values(
	array_filter(
	$storypress_home_hero->posts,
	static function ( $storypress_home_post ) {
		$storypress_home_thumbnail_id = get_post_thumbnail_id( $storypress_home_post );
		return $storypress_home_thumbnail_id && wp_get_attachment_image_src( $storypress_home_thumbnail_id, 'storypress-triv-card' );
	}
	)
	),
	0,
	6
);
$storypress_home_hero->post_count = count( $storypress_home_hero->posts );
$storypress_home_stories = $storypress_home_query( storypress_get_option( 'home_latest_count' ), storypress_get_option( 'home_latest_cat' ), storypress_get_option( 'home_latest_tag' ) );
$storypress_home_feature = $storypress_home_query( storypress_get_option( 'home_feature_count' ), storypress_get_option( 'home_feature_cat' ), storypress_get_option( 'home_feature_tag' ) );
$storypress_home_popular = $storypress_home_query( storypress_get_option( 'home_popular_count' ), storypress_get_option( 'home_popular_cat' ), storypress_get_option( 'home_popular_tag' ), array( 'orderby' => 'comment_count' ) );
$storypress_home_editors = $storypress_home_query( storypress_get_option( 'home_editors_count' ), storypress_get_option( 'home_editors_cat' ), storypress_get_option( 'home_editors_tag' ) );
$storypress_home_latest  = $storypress_home_query( storypress_get_option( 'home_latestposts_count' ), storypress_get_option( 'home_latestposts_cat' ), storypress_get_option( 'home_latestposts_tag' ), array( 'paged' => $storypress_home_paged ), true );

$storypress_home_dest_parent = (int) storypress_get_option( 'home_destinations_cat' );
$storypress_home_dest_args   = array(
	'taxonomy'   => 'category',
	'orderby'    => 'count',
	'order'      => 'DESC',
	'number'     => max( 1, (int) storypress_get_option( 'home_destinations_count' ) ),
	'hide_empty' => true,
);
if ( $storypress_home_dest_parent ) {
	$storypress_home_dest_args['parent'] = $storypress_home_dest_parent;
} else {
	$storypress_home_dest_args['exclude'] = array( (int) get_option( 'default_category' ) );
}
$storypress_home_destinations = get_terms( $storypress_home_dest_args );

$storypress_home_cat_badge = static function () {
	if ( ! is_array( get_the_category() ) ) {
		return;
	}
	$cat = get_the_category();
	if ( empty( $cat ) ) {
		return;
	}
	printf(
		'<a class="cat-badge" href="%s">%s</a>',
		esc_url( get_category_link( $cat[0]->term_id ) ),
		esc_html( $cat[0]->name )
	);
};

$storypress_home_heading = static function ( $id, $title, $link_url = '' ) {
	if ( '' === trim( (string) $title ) ) {
		return;
	}
	printf( '<header class="storypress-section__head"><h2 id="%1$s">%2$s</h2>', esc_attr( $id ), esc_html( $title ) );
	if ( $link_url ) {
		printf( '<a href="%s">%s</a>', esc_url( $link_url ), esc_html__( 'View all', 'storypress' ) );
	}
	echo '</header>';
};

$storypress_home_source_link = static function ( $cat, $tag, $fallback = '' ) {
	if ( $cat ) {
		$link = get_category_link( $cat );
		return $link && ! is_wp_error( $link ) ? $link : $fallback;
	}
	if ( $tag ) {
		$link = get_tag_link( $tag );
		return $link && ! is_wp_error( $link ) ? $link : $fallback;
	}
	return $fallback;
};

$storypress_home_cta_url = storypress_get_option( 'home_hero_cta_url' );
if ( '' === trim( (string) $storypress_home_cta_url ) ) {
	$storypress_home_cta_url = storypress_posts_page_url();
}
?>

<div class="storypress-home storypress-home--<?php echo esc_attr( storypress_get_option( 'home_design' ) ); ?>">
<section class="storypress-hero container">
	<div class="storypress-hero__layout">
		<div class="storypress-hero__intro">
			<p class="eyebrow"><?php echo esc_html( storypress_get_option( 'home_hero_eyebrow' ) ); ?></p>
			<h1><?php echo esc_html( storypress_get_option( 'home_hero_title' ) ); ?></h1>
			<div class="storypress-hero__aside">
				<p class="storypress-hero__text"><?php echo esc_html( storypress_get_option( 'home_hero_text' ) ); ?></p>
				<a class="button storypress-hero__cta" href="<?php echo esc_url( $storypress_home_cta_url ); ?>"><?php echo esc_html( storypress_get_option( 'home_hero_cta_text' ) ); ?></a>
			</div>
		</div>
		<?php if ( $storypress_home_hero->have_posts() ) : ?>
			<div class="hero-slider" data-hero-slider role="region" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Featured posts', 'storypress' ); ?>">
				<div class="hero-slider__viewport swiper">
					<div class="hero-slider__track swiper-wrapper" id="hero-slider-track">
						<?php
						$storypress_home_slide_index = 0;
						while ( $storypress_home_hero->have_posts() ) :
							$storypress_home_hero->the_post();
							?>
							<article <?php post_class( 'hero-slide swiper-slide' ); ?> data-slide-index="<?php echo esc_attr( $storypress_home_slide_index ); ?>" aria-roledescription="slide" aria-hidden="<?php echo 0 === $storypress_home_slide_index ? 'false' : 'true'; ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="hero-slide__media" href="<?php the_permalink(); ?>" tabindex="<?php echo 0 === $storypress_home_slide_index ? '0' : '-1'; ?>" aria-hidden="true"><?php the_post_thumbnail( 'storypress-triv-card', array( 'loading' => 'lazy' ) ); ?></a>
								<?php else : ?>
									<span class="hero-slide__media hero-slide__media--empty" aria-hidden="true"></span>
								<?php endif; ?>
								<div class="hero-slide__body">
									<?php $storypress_home_cat_badge(); ?>
									<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<div class="entry-meta"><?php storypress_posted_on(); ?><?php if ( storypress_get_option( 'show_reading_time' ) ) : ?><span aria-hidden="true">·</span><?php storypress_reading_time(); endif; ?></div>
								</div>
							</article>
							<?php
							$storypress_home_slide_index++;
						endwhile;
						?>
					</div>
				</div>
				<div class="hero-slider__controls" aria-label="<?php esc_attr_e( 'Slider controls', 'storypress' ); ?>">
					<div class="hero-slider__dots" data-slide-dots aria-label="<?php esc_attr_e( 'Choose a post', 'storypress' ); ?>"></div>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		endif;
		?>
	</div>
</section>

<?php if ( storypress_get_option( 'home_destinations_enabled' ) && ! is_wp_error( $storypress_home_destinations ) && ! empty( $storypress_home_destinations ) ) : ?>
	<section class="storypress-section storypress-destinations container" aria-labelledby="storypress-dest-heading">
		<div class="destinations-intro">
			<span class="destinations-intro__label"><?php esc_html_e( 'Plan your next escape', 'storypress' ); ?></span>
			<?php
			$storypress_home_dest_link = $storypress_home_dest_parent ? get_category_link( $storypress_home_dest_parent ) : '';
			$storypress_home_heading( 'storypress-dest-heading', storypress_get_option( 'home_destinations_title' ), $storypress_home_dest_link && ! is_wp_error( $storypress_home_dest_link ) ? $storypress_home_dest_link : '' );
			?>
			<p><?php esc_html_e( 'Browse places worth putting on your map, from familiar favorites to somewhere new.', 'storypress' ); ?></p>
		</div>
		<?php if ( 'studio' === storypress_get_option( 'home_design' ) ) : ?>
			<div class="destinations-carousel swiper" data-destinations-carousel role="region" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Travel destinations', 'storypress' ); ?>">
				<div class="destinations-grid swiper-wrapper">
		<?php else : ?>
			<div class="destinations-grid">
		<?php endif; ?>
			<?php foreach ( $storypress_home_destinations as $storypress_home_dest_index => $storypress_home_term ) : ?>
				<?php
				$storypress_home_dest_image = get_term_meta( $storypress_home_term->term_id, 'storypress_category_image', true );
				$storypress_home_dest_url   = get_term_link( $storypress_home_term );
				if ( empty( $storypress_home_dest_image ) ) {
					$storypress_home_dest_posts = get_posts(
						array(
							'post_type'           => 'post',
							'post_status'         => 'publish',
							'posts_per_page'      => 4,
							'ignore_sticky_posts' => true,
							'no_found_rows'       => true,
							'category__in'        => array( (int) $storypress_home_term->term_id ),
							'meta_query'          => array(
								array(
									'key'     => '_thumbnail_id',
									'compare' => 'EXISTS',
								),
							),
						)
					);
					foreach ( $storypress_home_dest_posts as $storypress_home_dest_post ) {
						$storypress_home_dest_thumbnail_id = get_post_thumbnail_id( $storypress_home_dest_post );
						if ( $storypress_home_dest_thumbnail_id && wp_get_attachment_image_src( $storypress_home_dest_thumbnail_id, 'storypress-triv-card' ) ) {
							$storypress_home_dest_image = $storypress_home_dest_thumbnail_id;
							break;
						}
					}
				}
				?>
				<a class="destination-card<?php echo 'studio' === storypress_get_option( 'home_design' ) ? ' swiper-slide' : ''; ?><?php echo 0 === $storypress_home_dest_index ? ' destination-card--lead' : ''; ?>" href="<?php echo esc_url( $storypress_home_dest_url ); ?>">
					<?php if ( $storypress_home_dest_image && is_numeric( $storypress_home_dest_image ) ) : ?>
						<?php echo wp_get_attachment_image( (int) $storypress_home_dest_image, 'storypress-triv-card', false, array( 'loading' => 'lazy', 'alt' => $storypress_home_term->name ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php elseif ( $storypress_home_dest_image ) : ?>
						<img src="<?php echo esc_url( $storypress_home_dest_image ); ?>" alt="<?php echo esc_attr( $storypress_home_term->name ); ?>" loading="lazy">
					<?php else : ?>
						<span class="destination-card__fallback" aria-hidden="true"><?php echo esc_html( mb_substr( $storypress_home_term->name, 0, 1 ) ); ?></span>
					<?php endif; ?>
					<span class="destination-card__body">
						<strong><?php echo esc_html( $storypress_home_term->name ); ?></strong>
						<em><?php echo esc_html( sprintf( _n( '%s Entry', '%s Entries', $storypress_home_term->count, 'storypress' ), number_format_i18n( $storypress_home_term->count ) ) ); ?></em>
					</span>
				</a>
			<?php endforeach; ?>
			</div>
		<?php if ( 'studio' === storypress_get_option( 'home_design' ) ) : ?>
			</div>
			<?php if ( count( $storypress_home_destinations ) > 1 ) : ?>
				<div class="destinations-controls" aria-label="<?php esc_attr_e( 'Destination controls', 'storypress' ); ?>">
					<button class="destinations-controls__arrow" type="button" data-dest-prev aria-label="<?php esc_attr_e( 'Previous destinations', 'storypress' ); ?>"><span aria-hidden="true">&#8592;</span></button>
					<button class="destinations-controls__arrow" type="button" data-dest-next aria-label="<?php esc_attr_e( 'Next destinations', 'storypress' ); ?>"><span aria-hidden="true">&#8594;</span></button>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</section>
<?php endif; ?>

<div class="container storypress-main">
	<div class="storypress-content">

		<?php if ( storypress_get_option( 'home_latest_enabled' ) && $storypress_home_stories->have_posts() ) : ?>
			<section class="storypress-section latest-stories" aria-labelledby="storypress-latest-heading">
				<?php
				$storypress_home_heading(
					'storypress-latest-heading',
					storypress_get_option( 'home_latest_title' ),
					$storypress_home_source_link( (int) storypress_get_option( 'home_latest_cat' ), (int) storypress_get_option( 'home_latest_tag' ), storypress_posts_page_url() )
				);
				?>
				<?php if ( 'cards' === storypress_get_option( 'home_latest_layout' ) ) : ?>
					<div class="news-grid">
						<?php while ( $storypress_home_stories->have_posts() ) : ?>
							<?php $storypress_home_stories->the_post(); ?>
							<?php get_template_part( 'template-parts/content/content', 'related' ); ?>
						<?php endwhile; ?>
					</div>
				<?php else : ?>
					<div class="news-list">
						<?php while ( $storypress_home_stories->have_posts() ) : ?>
							<?php $storypress_home_stories->the_post(); ?>
							<article <?php post_class( 'news-card' ); ?>>
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="news-card__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1"><?php the_post_thumbnail( 'storypress-triv-thumb' ); ?></a>
								<?php endif; ?>
								<div class="news-card__body">
									<?php $storypress_home_cat_badge(); ?>
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<div class="entry-meta">
										<?php storypress_posted_by(); ?>
										<span aria-hidden="true">·</span>
										<?php storypress_posted_on(); ?>
									</div>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</section>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

		<?php if ( storypress_get_option( 'home_feature_enabled' ) && $storypress_home_feature->have_posts() ) : ?>
			<?php $storypress_home_feature->the_post(); ?>
			<section class="storypress-section feature-story" aria-labelledby="storypress-feature-heading">
				<?php $storypress_home_heading( 'storypress-feature-heading', storypress_get_option( 'home_feature_title' ) ); ?>
				<article <?php post_class( 'feature-card' ); ?>>
					<a class="feature-card__media" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'storypress-triv-portrait' ); ?>
						<?php else : ?>
							<span class="feature-card__media-fallback" aria-hidden="true"></span>
						<?php endif; ?>
					</a>
					<div class="feature-card__body">
						<p class="feature-card__kicker"><?php esc_html_e( "Editor's pick", 'storypress' ); ?></p>
						<?php $storypress_home_cat_badge(); ?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="feature-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34 ) ); ?></p>
						<div class="feature-card__meta">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 42, '', get_the_author(), array( 'class' => 'feature-card__avatar' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php storypress_posted_by(); ?></span>
							<span aria-hidden="true">·</span>
							<?php storypress_posted_on(); ?>
						</div>
						<a class="feature-card__link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read the story', 'storypress' ); ?><span aria-hidden="true">&rarr;</span></a>
					</div>
				</article>
			</section>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

	</div>

	<aside class="storypress-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'storypress' ); ?>">

		<?php if ( storypress_get_option( 'home_popular_enabled' ) && $storypress_home_popular->have_posts() ) : ?>
			<section class="storypress-section sidebar-card popular-section" aria-labelledby="storypress-popular-heading">
				<?php $storypress_home_heading( 'storypress-popular-heading', storypress_get_option( 'home_popular_title' ) ); ?>
				<div class="popular-list">
					<?php while ( $storypress_home_popular->have_posts() ) : ?>
						<?php $storypress_home_popular->the_post(); ?>
						<article <?php post_class( 'popular-card' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<a class="popular-card__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1"><?php the_post_thumbnail( 'storypress-triv-thumb' ); ?></a>
							<?php endif; ?>
							<div class="popular-card__body">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<div class="entry-meta"><?php storypress_posted_on(); ?><span aria-hidden="true">·</span><span class="comments-count"><?php echo esc_html( get_comments_number_text( __( 'No Comment', 'storypress' ), __( '1 Comment', 'storypress' ), __( '% Comments', 'storypress' ) ) ); ?></span></div>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
			</section>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

		<?php if ( storypress_get_option( 'home_editors_enabled' ) && $storypress_home_editors->have_posts() ) : ?>
			<section class="storypress-section sidebar-card editors-section" aria-labelledby="storypress-editors-heading">
				<?php $storypress_home_heading( 'storypress-editors-heading', storypress_get_option( 'home_editors_title' ) ); ?>
				<?php $storypress_home_editors->the_post(); ?>
				<article <?php post_class( 'editor-feature' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="editor-feature__media" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'storypress-triv-square' ); ?></a>
					<?php endif; ?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php storypress_posted_on(); ?>
				</article>
				<?php if ( $storypress_home_editors->have_posts() ) : ?>
					<div class="editor-grid">
						<?php while ( $storypress_home_editors->have_posts() ) : ?>
							<?php $storypress_home_editors->the_post(); ?>
							<article <?php post_class( 'editor-mini' ); ?>>
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="editor-mini__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1"><?php the_post_thumbnail( 'storypress-triv-thumb' ); ?></a>
								<?php endif; ?>
								<div class="editor-mini__body">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<?php if ( storypress_get_option( 'show_reading_time' ) ) : ?>
										<span class="editor-mini__time"><?php storypress_reading_time(); ?></span>
									<?php endif; ?>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</section>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

	</aside>
</div>

<?php if ( storypress_get_option( 'home_latestposts_enabled' ) && $storypress_home_latest->have_posts() ) : ?>
	<section class="storypress-section storypress-latest container" aria-labelledby="storypress-posts-heading">
		<?php
		$storypress_home_heading(
			'storypress-posts-heading',
			storypress_get_option( 'home_latestposts_title' ),
			$storypress_home_source_link( (int) storypress_get_option( 'home_latestposts_cat' ), (int) storypress_get_option( 'home_latestposts_tag' ), storypress_posts_page_url() )
		);
		?>
		<?php if ( 'cards' === storypress_get_option( 'home_latestposts_layout' ) ) : ?>
			<div class="posts-grid">
				<?php while ( $storypress_home_latest->have_posts() ) : ?>
					<?php $storypress_home_latest->the_post(); ?>
					<?php get_template_part( 'template-parts/content/content', 'related' ); ?>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<div class="latest-posts">
				<?php while ( $storypress_home_latest->have_posts() ) : ?>
					<?php $storypress_home_latest->the_post(); ?>
					<article <?php post_class( 'latest-post' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="latest-post__media" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'storypress-triv-card' ); ?></a>
						<?php endif; ?>
						<div class="latest-post__body">
							<?php $storypress_home_cat_badge(); ?>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="latest-post__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34 ) ); ?></p>
							<div class="entry-meta">
								<?php storypress_posted_on(); ?>
								<span aria-hidden="true">·</span>
								<span class="comments-count"><?php echo esc_html( get_comments_number_text( __( 'No Comment', 'storypress' ), __( '1 Comment', 'storypress' ), __( '% Comments', 'storypress' ) ) ); ?></span>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
		<?php if ( storypress_get_option( 'home_latestposts_loadmore' ) && $storypress_home_latest->max_num_pages > $storypress_home_paged ) : ?>
			<a class="storypress-load" href="<?php echo esc_url( storypress_posts_page_url() ); ?>"><?php esc_html_e( 'Show all stories', 'storypress' ); ?><span aria-hidden="true">&rarr;</span></a>
		<?php endif; ?>
	</section>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>

<?php if ( storypress_has_social_links() ) : ?>
	<section class="container storypress-connect" aria-labelledby="storypress-connect-heading">
		<div class="storypress-connect__inner">
			<p class="eyebrow"><?php esc_html_e( 'Keep In Touch', 'storypress' ); ?></p>
			<h2 id="storypress-connect-heading"><?php esc_html_e( 'Travel With Us', 'storypress' ); ?></h2>
			<p><?php esc_html_e( 'Follow along for destination guides, practical tips, and fresh travel ideas.', 'storypress' ); ?></p>
		</div>
		<div class="storypress-connect__action">
			<?php storypress_social_links(); ?>
		</div>
	</section>
<?php endif; ?>
</div>
