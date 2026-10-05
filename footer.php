<?php
/** Footer. @package StoryPress */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</main>
<footer class="site-footer">
<div class="container footer-grid">
	<div class="footer-brand"><?php storypress_site_logo(); ?><?php $footer_text = storypress_get_option( 'footer_text' ); if ( $footer_text ) { echo '<p class="footer-brand__text">' . esc_html( $footer_text ) . '</p>'; } storypress_social_links(); ?></div>
	<?php for ( $i = 1; $i <= 3; $i++ ) : ?><div class="footer-widget"><?php if ( is_active_sidebar( 'footer-' . $i ) ) { dynamic_sidebar( 'footer-' . $i ); } ?></div><?php endfor; ?>
	<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'storypress' ); ?>"><?php $footer_menu = absint( storypress_get_option( 'footer_menu' ) ); if ( $footer_menu || has_nav_menu( 'footer' ) ) { echo '<p class="eyebrow">' . esc_html__( 'Explore', 'storypress' ) . '</p>'; wp_nav_menu( array( 'theme_location' => $footer_menu ? '' : 'footer', 'menu' => $footer_menu ?: '', 'container' => false, 'fallback_cb' => false, 'menu_class' => 'footer-menu' ) ); } ?></nav>
</div>
<div class="container footer-bottom"><span class="footer-bottom__copyright"><?php $copyright = storypress_get_option( 'copyright' ); echo $copyright ? esc_html( $copyright ) : esc_html( sprintf( __( '© %1$s %2$s', 'storypress' ), wp_date( 'Y' ), get_bloginfo( 'name' ) ) ); ?></span><?php $legal_menu = absint( storypress_get_option( 'legal_menu' ) ); if ( $legal_menu || has_nav_menu( 'footer-legal' ) ) { wp_nav_menu( array( 'theme_location' => $legal_menu ? '' : 'footer-legal', 'menu' => $legal_menu ?: '', 'container' => false, 'fallback_cb' => false, 'depth' => 1, 'menu_class' => 'footer-legal' ) ); } ?></div>
</footer>
<?php if ( storypress_get_option( 'back_to_top' ) ) : ?><button class="back-to-top" type="button"><span class="screen-reader-text"><?php esc_html_e( 'Back to top', 'storypress' ); ?></span><span aria-hidden="true">↑</span></button><?php endif; ?>
<?php wp_footer(); ?></body></html>