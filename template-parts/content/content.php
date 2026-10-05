<?php /** Post card. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?><a class="post-card__image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'storypress-card', array( 'loading' => 'lazy' ) ); ?></a><?php endif; ?>
	<div class="post-card__body"><div class="post-card__meta"><?php the_category( ', ' ); ?><?php if ( storypress_get_option( 'show_reading_time' ) ) : ?><span aria-hidden="true">·</span><?php storypress_reading_time(); endif; ?></div><h2 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p><div class="post-card__footer"><?php if ( storypress_get_option( 'show_author' ) ) { storypress_posted_by(); } if ( storypress_get_option( 'show_author' ) && storypress_get_option( 'show_date' ) ) { echo '<span aria-hidden="true">·</span>'; } if ( storypress_get_option( 'show_date' ) ) { storypress_posted_on(); } ?></div></div>
</article>
