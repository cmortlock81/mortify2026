<?php
/**
 * Mortify 2026 PWA Handler
 *
 * Registers PWA routes for manifest, service worker,
 * and offline fallback. Provides headers and JSON
 * responses for modern browsers.
 *
 * @package Mortify2026
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mortify2026_PWA {

	/**
	 * Constructor — hook into WP routing.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'add_rewrites' ] );
		add_action( 'template_redirect', [ $this, 'serve_manifest' ] );
		add_action( 'template_redirect', [ $this, 'serve_service_worker' ] );
		add_action( 'template_redirect', [ $this, 'serve_offline' ] );
	}

	/**
	 * Add rewrite rules for PWA assets.
	 *
	 * @return void
	 */
	public function add_rewrites(): void {
		$settings = mortify_get_settings();
		$slug     = trim( $settings['app_slug'], '/' );

		add_rewrite_rule( "^{$slug}/manifest.webmanifest$", 'index.php?mortify_manifest=1', 'top' );
		add_rewrite_rule( "^{$slug}/sw.js$", 'index.php?mortify_sw=1', 'top' );
		add_rewrite_rule( "^{$slug}/offline.html$", 'index.php?mortify_offline=1', 'top' );

		add_rewrite_tag( '%mortify_manifest%', '1' );
		add_rewrite_tag( '%mortify_sw%', '1' );
		add_rewrite_tag( '%mortify_offline%', '1' );
	}

	/**
	 * Serve the web manifest dynamically.
	 *
	 * @return void
	 */
	public function serve_manifest(): void {
		if ( get_query_var( 'mortify_manifest' ) ) {
			$settings = mortify_get_settings();
			$pwa      = $settings['pwa'];

			$manifest = [
				'name'             => $pwa['name'],
				'short_name'       => $pwa['short_name'],
				'start_url'        => esc_url( $pwa['start_url'] ),
				'display'          => $pwa['display'],
				'background_color' => $pwa['background_color'],
				'theme_color'      => $pwa['theme_color'],
				'icons' => [
					[
						'src'   => esc_url( MORTIFY2026_URL . 'assets/icons/icon-192.png' ),
						'sizes' => '192x192',
						'type'  => 'image/png',
					],
					[
						'src'   => esc_url( MORTIFY2026_URL . 'assets/icons/icon-512.png' ),
						'sizes' => '512x512',
						'type'  => 'image/png',
					],
				],
			];

			header( 'Content-Type: application/manifest+json' );
			echo wp_json_encode( $manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			exit;
		}
	}

	/**
	 * Serve the Service Worker JS for shell caching.
	 *
	 * @return void
	 */
	public function serve_service_worker(): void {
		if ( get_query_var( 'mortify_sw' ) ) {
			header( 'Content-Type: application/javascript' );

			?>
// Mortify 2026 Service Worker
const CACHE_NAME = 'mortify2026-v1';
const OFFLINE_URL = '/app/offline.html';
const CORE_ASSETS = [
  '/app/',
  '/app/manifest.webmanifest',
  '/app/offline.html',
  '<?php echo esc_url( MORTIFY2026_URL . 'assets/css/app.css' ); ?>',
  '<?php echo esc_url( MORTIFY2026_URL . 'assets/js/app.js' ); ?>',
];

self.addEventListener('install', e => {
  e.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(CORE_ASSETS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys().then(keys => Promise.all(keys.map(key => {
      if (key !== CACHE_NAME) return caches.delete(key);
    })))
  );
  self.clients.claim();
});

self.addEventListener('fetch', e => {
  if (e.request.mode === 'navigate') {
    e.respondWith(
      fetch(e.request).catch(() => caches.match(OFFLINE_URL))
    );
  }
});
			<?php
			exit;
		}
	}

	/**
	 * Serve offline fallback HTML page.
	 *
	 * @return void
	 */
	public function serve_offline(): void {
		if ( get_query_var( 'mortify_offline' ) ) {
			header( 'Content-Type: text/html; charset=UTF-8' );
			readfile( MORTIFY2026_PATH . 'assets/offline.html' );
			exit;
		}
	}
}
