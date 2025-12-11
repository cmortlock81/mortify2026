<?php
/**
 * Mortify 2026 Router (Diagnostic Build)
 *
 * Handles routing, template loading, and asset enqueueing for the /app/ shell.
 * This version includes detailed debug logs and visible page comments.
 *
 * @package Mortify2026
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mortify2026_Router {

	public function __construct() {
		add_action( 'init', [ $this, 'register_routes' ] );
		add_filter( 'template_include', [ $this, 'load_app_template' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_action( 'template_redirect', [ $this, 'debug_marker' ] );
	}

	/**
	 * Register rewrite rules and query var.
	 */
	public function register_routes(): void {
		global $wp;
		$wp->add_query_var( 'mortify_app' );

		$settings = mortify_get_settings();
		$slug     = trim( $settings['app_slug'], '/' );

		add_rewrite_rule(
			"^{$slug}/?$",
			'index.php?mortify_app=1',
			'top'
		);

		add_rewrite_tag( '%mortify_app%', '1' );

		mortify_log("Router → Rewrite rule added for /{$slug}/");
	}

	/**
	 * Load the Mortify app template when /app/ is requested.
	 */
	public function load_app_template( string $template ): string {
		if ( get_query_var( 'mortify_app' ) ) {
			mortify_log('Router → Mortify app template triggered.');
			echo "\n<!-- Mortify Router: template loaded -->\n";
			return MORTIFY2026_PATH . 'templates/mortify-app.php';
		}

		mortify_log('Router → Default template in use.');
		return $template;
	}

	/**
	 * Enqueue CSS and JS assets for /app/.
	 */
	public function enqueue_assets(): void {
		if ( ! mortify_in_app_scope() ) {
			mortify_log('Router → Not in /app/ scope, skipping assets.');
			return;
		}

		mortify_log('Router → Enqueueing assets.');
		echo "\n<!-- Mortify Router: enqueue triggered -->\n";

		wp_enqueue_style(
			'mortify2026-app',
			MORTIFY2026_URL . 'assets/css/app.css',
			[],
			MORTIFY2026_VERSION
		);

		wp_enqueue_script(
			'mortify2026-app',
			MORTIFY2026_URL . 'assets/js/app.js',
			[ 'jquery' ],
			MORTIFY2026_VERSION,
			true
		);

		wp_localize_script(
			'mortify2026-app',
			'mortifyApp',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'home_url' => home_url(),
				'cart_api' => esc_url( home_url( '/wp-json/wc/store/cart' ) ),
			]
		);
	}

	/**
	 * Add debug marker comment for visibility.
	 */
	public function debug_marker(): void {
		if ( mortify_in_app_scope() ) {
			echo "\n<!-- Mortify Router: app scope detected -->\n";
			mortify_log('Router → In app scope. Query var = ' . print_r( get_query_var('mortify_app'), true ));
		}
	}

	/**
	 * Create /app/ page on activation.
	 */
	public static function activate(): void {
		$settings = mortify_get_settings();
		$slug     = trim( $settings['app_slug'], '/' );

		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			wp_insert_post( [
				'post_title'   => ucfirst( $slug ),
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => 'This is the Mortify 2026 app shell.',
			] );
			mortify_log("Router → Created page /{$slug}/");
		}

		flush_rewrite_rules();
		mortify_log("Router → Activation complete. Rewrite rules flushed.");
	}
}
