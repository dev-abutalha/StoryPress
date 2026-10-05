<?php
/** Theme setup and registrations. @package StoryPress */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function storypress_setup() {
	load_theme_textdomain( 'storypress', STORYPRESS_DIR . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );
	add_theme_support( 'custom-logo', array( 'height' => 64, 'width' => 280, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-background', array( 'default-color' => 'fbfaf8' ) );
	register_nav_menus( array(
		'primary'            => esc_html__( 'Primary Menu', 'storypress' ),
		'footer'             => esc_html__( 'Footer Menu', 'storypress' ),
		'footer-legal'       => esc_html__( 'Footer legal menu', 'storypress' ),
	) );
	add_image_size( 'storypress-featured', 1440, 810, true );
	add_image_size( 'storypress-card', 760, 500, true );
	add_image_size( 'storypress-triv-card', 1100, 800, true );
	add_image_size( 'storypress-triv-portrait', 1100, 1350, true );
	add_image_size( 'storypress-triv-square', 1100, 1100, true );
	add_image_size( 'storypress-triv-thumb', 320, 240, true );
}
add_action( 'after_setup_theme', 'storypress_setup' );

function storypress_content_width() { $GLOBALS['content_width'] = apply_filters( 'storypress_content_width', 760 ); }
add_action( 'after_setup_theme', 'storypress_content_width', 0 );

function storypress_widgets_init() {
	$areas = array(
		'sidebar-1' => esc_html__( 'Sidebar', 'storypress' ),
		'footer-1'  => esc_html__( 'Footer Column One', 'storypress' ),
		'footer-2'  => esc_html__( 'Footer Column Two', 'storypress' ),
		'footer-3'  => esc_html__( 'Footer Column Three', 'storypress' ),
	);
	foreach ( $areas as $id => $name ) {
		register_sidebar( array( 'name' => $name, 'id' => $id, 'description' => esc_html__( 'Add widgets here.', 'storypress' ), 'before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>' ) );
	}
}
add_action( 'widgets_init', 'storypress_widgets_init' );

function storypress_register_term_meta() {
	register_term_meta( 'category', 'storypress_category_image', array(
		'type'       => 'string',
		'single'     => true,
		'show_in_rest' => false,
		'sanitize_callback' => null,
	) );
}
add_action( 'init', 'storypress_register_term_meta' );

function storypress_category_image_picker( $value ) { ?>
	<div class="storypress-logo-picker" data-logo-block>
		<div class="storypress-logo-preview" data-logo-preview><?php if ( $value ) { if ( is_numeric( $value ) ) { echo wp_get_attachment_image( (int) $value, 'thumbnail' ); } else { echo '<img src="' . esc_url( $value ) . '" alt="">'; } } ?></div>
		<input type="hidden" name="storypress_category_image" value="<?php echo esc_attr( $value ); ?>" data-logo-input>
		<button type="button" class="button" data-select-logo><?php esc_html_e( 'Choose image', 'storypress' ); ?></button>
		<button type="button" class="button-link-delete" data-remove-logo <?php echo $value ? '' : 'hidden'; ?>><?php esc_html_e( 'Remove', 'storypress' ); ?></button>
	</div>
	<?php
}

function storypress_category_image_add_field( $taxonomy ) { ?>
	<div class="form-field term-storypress-image-wrap">
		<label for="storypress-category-image"><?php esc_html_e( 'Category image', 'storypress' ); ?></label>
		<?php storypress_category_image_picker( '' ); ?>
		<?php wp_nonce_field( 'storypress_category_image', 'storypress_category_image_nonce' ); ?>
		<p class="description"><?php esc_html_e( 'Shown on the category destination card on the homepage.', 'storypress' ); ?></p>
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'storypress_category_image_add_field' );

function storypress_category_image_edit_field( $term ) { ?>
	<tr class="form-field term-storypress-image-wrap">
		<th scope="row"><label for="storypress-category-image"><?php esc_html_e( 'Category image', 'storypress' ); ?></label></th>
		<td>
			<?php storypress_category_image_picker( get_term_meta( $term->term_id, 'storypress_category_image', true ) ); ?>
			<?php wp_nonce_field( 'storypress_category_image', 'storypress_category_image_nonce' ); ?>
			<p class="description"><?php esc_html_e( 'Shown on the category destination card on the homepage.', 'storypress' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'storypress_category_image_edit_field' );

function storypress_category_image_save( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	if ( ! isset( $_POST['storypress_category_image'], $_POST['storypress_category_image_nonce'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['storypress_category_image_nonce'] ) ), 'storypress_category_image' ) ) {
		return;
	}
	$value = sanitize_text_field( wp_unslash( $_POST['storypress_category_image'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( is_numeric( $value ) ) {
		$value = absint( $value );
		if ( 0 < $value && ! wp_attachment_is_image( $value ) ) {
			$value = '';
		}
	} else {
		$value = esc_url_raw( $value );
	}
	update_term_meta( $term_id, 'storypress_category_image', $value );
}
add_action( 'created_category', 'storypress_category_image_save' );
add_action( 'edited_category', 'storypress_category_image_save' );

function storypress_admin_term_assets( $hook ) {
	if ( ! in_array( $hook, array( 'edit-tags.php', 'term.php' ), true ) ) { return; }
	$screen = get_current_screen();
	if ( ! $screen || 'category' !== $screen->taxonomy ) { return; }
	wp_enqueue_media();
	wp_enqueue_style( 'storypress-options', STORYPRESS_URI . '/assets/css/admin-options.css', array(), STORYPRESS_VERSION );
	wp_enqueue_script( 'storypress-options', STORYPRESS_URI . '/assets/js/admin-options.js', array( 'media-editor' ), STORYPRESS_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'storypress_admin_term_assets' );
