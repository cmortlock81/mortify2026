<?php
/**
 * Mortify 2026 App Template
 *
 * Renders the app shell with header, main content, and footer.
 *
 * @package Mortify2026
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = mortify_get_settings();
$brand    = $settings['brand'];
$pwa      = $settings['pwa'];
$slug     = trim( $settings['app_slug'], '/' );

// Allow other modules (WooCommerce) to modify tabs.
$tabs = apply_filters( 'mortify_tabs', $settings['tabs'] );

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<title><?php wp_title( '•', true, 'right' ); ?></title>

	<!-- PWA Manifest & Theme -->
	<link rel="manifest" href="<?php echo esc_url( home_url( "/{$slug}/manifest.webmanifest" ) ); ?>">
	<meta name="theme-color" content="<?php echo esc_attr( $brand['primary'] ); ?>">
	<link rel="apple-touch-icon" href="<?php echo esc_url( MORTIFY2026_URL . 'assets/icons/icon-192.png' ); ?>">

	<?php wp_head(); ?>
</head>
<body <?php body_class( 'mortify2026-app bg-gray-50 text-gray-900 flex flex-col min-h-screen' ); ?>>

	<!-- Top Navigation -->
	<?php require MORTIFY2026_PATH . 'templates/parts/top-nav.php'; ?>

	<!-- Main Content -->
	<main id="mortify-main" class="flex-1 overflow-y-auto p-4">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</main>

	<!-- Bottom Tabs -->
	<?php require MORTIFY2026_PATH . 'templates/parts/footer-tabs.php'; ?>

	<!-- Service Worker Registration -->
	<script>
	if ('serviceWorker' in navigator) {
		window.addEventListener('load', () => {
			navigator.serviceWorker.register('<?php echo esc_url( home_url( "/{$slug}/sw.js" ) ); ?>');
		});
	}
	</script>

	<?php wp_footer(); ?>
</body>
</html>
