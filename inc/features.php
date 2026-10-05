<?php
/**
 * StoryPress integrations: custom code injection, typography, consent banner
 * and performance trimming.
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Custom code injection (Google Tag Manager, analytics, verification, etc.).
 * Fields are admin-trusted (manage_options) and handled via inc/options.
 * ---------------------------------------------------------------------- */

function storypress_inject_head() {
	$code = storypress_get_option( 'code_head' );
	if ( $code ) {
		echo $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'storypress_inject_head', 1 );

function storypress_inject_body() {
	$code = storypress_get_option( 'code_body' );
	if ( $code ) {
		echo $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_body_open', 'storypress_inject_body' );

function storypress_inject_footer() {
	$code = storypress_get_option( 'code_footer' );
	if ( $code ) {
		echo $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_footer', 'storypress_inject_footer', 0 );

/* -------------------------------------------------------------------------
 * Cookie / GDPR consent banner.
 * ---------------------------------------------------------------------- */

function storypress_consent_banner() {
	if ( ! storypress_get_option( 'consent_banner' ) ) {
		return;
	}
	if ( ! empty( $_COOKIE['storypress_consent'] ) ) {
		return;
	}
	$message = storypress_get_option( 'consent_message' );
	if ( ! $message ) {
		$message = __( 'This site uses cookies to improve your experience and to analyze how it is used. Continuing to browse means you are okay with that.', 'storypress' );
	}
	?>
	<div class="consent-banner" role="region" aria-label="<?php esc_attr_e( 'Cookie consent', 'storypress' ); ?>" data-storypress-consent>
		<p class="consent-banner__text"><?php echo esc_html( $message ); ?></p>
		<div class="consent-banner__actions">
			<button type="button" class="consent-banner__accept button" data-consent-accept><?php esc_html_e( 'Accept', 'storypress' ); ?></button>
			<button type="button" class="consent-banner__decline" data-consent-decline><?php esc_html_e( 'Decline', 'storypress' ); ?></button>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'storypress_consent_banner', 15 );

/* -------------------------------------------------------------------------
 * Performance trimming.
 * ---------------------------------------------------------------------- */

function storypress_performance_go() {
	if ( storypress_get_option( 'perf_remove_emoji' ) ) {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}

	if ( storypress_get_option( 'perf_disable_embeds' ) ) {
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		add_filter( 'embed_oembed_discover', '__return_false' );
		add_filter( 'tiny_mce_plugins', function ( $plugins ) {
			return array_values( array_diff( $plugins, array( 'wpembed' ) ) );
		} );
		remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	}

	if ( storypress_get_option( 'perf_disable_trackbacks' ) ) {
		add_filter( 'pings_open', '__return_false' );
		add_filter( 'wp_headers', function ( $headers ) {
			unset( $headers['X-Pingback'] );
			return $headers;
		} );
	}
}
add_action( 'init', 'storypress_performance_go', 20 );

/* -------------------------------------------------------------------------
 * Comments master switch. When "Show comments" is off, the commenting system
 * is shut down everywhere: forms, REST comment routes, anonymous REST
 * comments, pingbacks/trackbacks, comment feeds and the default status for
 * newly created posts.
 * ---------------------------------------------------------------------- */

function storypress_comments_shutdown() {
	if ( storypress_get_option( 'show_comments' ) ) {
		return;
	}

	add_filter( 'comments_open', '__return_false' );
	add_filter( 'pings_open', '__return_false' );
	add_filter( 'rest_allow_anonymous_comments', '__return_false' );
	add_filter( 'preprocess_comment', function ( $commentdata ) {
		wp_die(
			esc_html__( 'Comments are closed.', 'storypress' ),
			'',
			array( 'response' => 403 )
		);
	} );
	add_filter( 'pre_option_default_comment_status', function () {
		return 'closed';
	} );

	add_filter( 'rest_endpoints', function ( $endpoints ) {
		foreach ( $endpoints as $route => $routes ) {
			if ( 0 === strpos( (string) $route, '/wp/v2/comments' ) ) {
				unset( $endpoints[ $route ] );
			}
		}
		return $endpoints;
	} );
}
add_action( 'init', 'storypress_comments_shutdown', 5 );

/* -------------------------------------------------------------------------
 * Security hardening: REST API policy and XML-RPC.
 * ---------------------------------------------------------------------- */

function storypress_security_go() {
	$mode = storypress_get_option( 'rest_api' );

	if ( 'enabled' !== $mode ) {
		if ( 'disabled' === $mode ) {
			add_action( 'rest_api_init', function () {
				wp_die( esc_html__( 'The REST API is disabled on this site.', 'storypress' ), '', array( 'response' => 403 ) );
			} );
		} else {
			add_filter( 'rest_authentication_errors', function ( $result ) {
				if ( ! is_user_logged_in() ) {
					return new WP_Error( 'storypress_rest_restricted', __( 'The REST API is available to logged-in users only.', 'storypress' ), array( 'status' => 403 ) );
				}
				return $result;
			} );
		}
	}

	if ( storypress_get_option( 'disable_xmlrpc' ) ) {
		add_filter( 'xmlrpc_enabled', '__return_false' );
	}
}
add_action( 'init', 'storypress_security_go', 5 );