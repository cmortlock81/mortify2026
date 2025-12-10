<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Mortify2026_Router {

    public function __construct() {
        add_action('init', [$this, 'register_template']);
        add_filter('template_include', [$this, 'include_template']);
        add_filter('body_class', [$this, 'add_body_class']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public static function activate() {
        $settings = mortify_get_settings();
        $slug = $settings['app_slug'];
        if ( ! get_page_by_path($slug) ) {
            wp_insert_post([
                'post_title' => 'App',
                'post_name' => $slug,
                'post_type' => 'page',
                'post_status' => 'publish',
            ]);
        }
        flush_rewrite_rules();
    }

    public function register_template() {
        add_filter('theme_page_templates', function($templates){
            $templates['mortify-app.php'] = 'Mortify App Template';
            return $templates;
        });
    }

    public function include_template($template) {
        if ( mortify_in_app_scope() ) {
            $plugin_template = MORTIFY2026_PATH . 'templates/mortify-app.php';
            if ( file_exists($plugin_template) ) return $plugin_template;
        }
        return $template;
    }

    public function add_body_class($classes) {
        if ( mortify_in_app_scope() ) $classes[] = 'mortify-app';
        return $classes;
    }

    public function enqueue_assets() {
        if ( ! mortify_in_app_scope() ) return;
        wp_enqueue_style('mortify2026-app', MORTIFY2026_URL . 'assets/css/app.css', [], '1.0');
        wp_enqueue_script('mortify2026-js', MORTIFY2026_URL . 'assets/js/app.js', [], '1.0', true);
    }
}
