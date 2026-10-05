<?php /** Related post card. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'related-card' ); ?>>
	<a class="related-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'storypress-triv-card', array( 'loading' => 'lazy' ) ); } else { echo '<span class="related-card__ph" aria-hidden="true"></span>'; } ?></a>
	<div class="related-card__body">
		<?php $related_cat = get_the_category(); ?>
		<?php if ( ! empty( $related_cat ) ) : ?><a class="related-card__cat" href="<?php echo esc_url( get_category_link( $related_cat[0]->term_id ) ); ?>"><?php echo esc_html( $related_cat[0]->name ); ?></a><?php endif; ?>
		<h3 class="related-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<time class="related-card__date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	</div>
</article>