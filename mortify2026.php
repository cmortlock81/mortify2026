<?php
/**
 * Plugin Name: Mortify 2026
 * Description: App-style mobile shell under /app/* with PWA + WooCommerce awareness.
 * Version: 1.0.0
 * Author: PulsR Labs
 * Requires at least: 6.4
 * Requires PHP: 8.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MORTIFY2026_PATH', plugin_dir_path( __FILE__ ) );
define( 'MORTIFY2026_URL',  plugin_dir_url( __FILE__ ) );

require_once MORTIFY2026_PATH . 'includes/helpers.php';
require_once MORTIFY2026_PATH . 'includes/class-router.php';
require_once MORTIFY2026_PATH . 'includes/class-pwa.php';
require_once MORTIFY2026_PATH . 'includes/class-admin.php';
require_once MORTIFY2026_PATH . 'includes/class-woocommerce.php';

class Mortify2026 {
    public function __construct() {
        add_action('plugins_loaded', [$this, 'boot']);
    }

    public function boot() {
        new Mortify2026_Router();
        new Mortify2026_PWA();
        new Mortify2026_Admin();
        if ( class_exists('WooCommerce') ) new Mortify2026_WooCommerce();
    }

    public static function activate() {
        Mortify2026_Router::activate();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}

register_activation_hook(__FILE__, ['Mortify2026', 'activate']);
register_deactivation_hook(__FILE__, ['Mortify2026', 'deactivate']);
new Mortify2026();
