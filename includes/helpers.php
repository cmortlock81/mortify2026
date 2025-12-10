<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function mortify_get_settings() {
    $defaults = [
        'app_slug' => 'app',
        'brand' => [
            'primary' => '#0066ff',
            'accent' => '#00cc88',
            'font' => 'system-ui'
        ],
        'tabs' => [],
        'pwa' => [
            'name' => 'My App',
            'short_name' => 'App',
            'theme_color' => '#0066ff',
            'background_color' => '#ffffff',
            'display' => 'standalone',
            'start_url' => '/app/',
        ],
        'mobile_preview_force' => false
    ];
    return wp_parse_args( get_option('mortify_settings', []), $defaults );
}

function mortify_in_app_scope() : bool {
    $settings = mortify_get_settings();
    $slug = trim($settings['app_slug'], '/');
    return str_contains($_SERVER['REQUEST_URI'], '/' . $slug . '/');
}
