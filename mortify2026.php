<?php
/**
 * Plugin Name: Mortify 2026
 * Description: App-style mobile shell for WordPress with PWA, WooCommerce integration, and modern Tailwind-inspired UI.
 * Version: 1.0.0
 * Author: Chris Mortlock
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Text Domain: mortify2026
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Define core plugin constants
 */
define( 'MORTIFY2026_PATH', plugin_dir_path( __FILE__ ) );
define( 'MORTIFY2026_URL', plugin_dir_url( __FILE__ ) );
define( 'MORTIFY2026_VERSION', '1.0.0' );

/**
 * Load core includes
 */
require_once MORTIFY2026_PATH . 'includes/helpers.php';
require_once MORTIFY2026_PATH . 'includes/class-router.php';
require_once MORTIFY2026_PATH . 'includes/class-pwa.php';
require_once MORTIFY2026_PATH . 'includes/class-admin.php';
require_once MORTIFY2026_PATH . 'includes/class-woocommerce.php';

/**
 * Main plugin class.
 */
class Mortify2026 {

    /**
     * Initialize hooks and classes.
     */
    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'boot' ] );
    }

    /**
     * Bootstraps the plugin modules after all plugins are loaded.
     *
     * @return void
     */
    public function boot(): void {
        new Mortify2026_Router();
        new Mortify2026_PWA();
        new Mortify2026_Admin();

        // Load WooCommerce support if WC is active
        if ( class_exists( 'WooCommerce' ) ) {
            new Mortify2026_WooCommerce();
        }
    }

    /**
     * On activation: create the /app/ page and flush rewrite rules.
     *
     * @return void
     */
    public static function activate(): void {
        Mortify2026_Router::activate();
    }

    /**
     * On deactivation: flush rewrite rules.
     *
     * @return void
     */
    public static function deactivate(): void {
        flush_rewrite_rules();
    }
}

/**
 * Register activation/deactivation hooks
 */
register_activation_hook( __FILE__, [ 'Mortify2026', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'Mortify2026', 'deactivate' ] );

/**
 * Initialize plugin
 */
new Mortify2026();
