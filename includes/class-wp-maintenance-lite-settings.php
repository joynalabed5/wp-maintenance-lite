<?php
if (!defined('ABSPATH')) exit;

class WP_Maintenance_Lite_Settings {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page() {
        add_options_page(
            __('WP Maintenance Lite', 'wp-maintenance-lite'),
            __('WP Maintenance Lite', 'wp-maintenance-lite'),
            'manage_options',
            'wp-maintenance-lite',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('wp_maintenance_lite_settings', 'wpm_maintenance_lite_enabled');
        register_setting('wp_maintenance_lite_settings', 'wpm_maintenance_lite_title');
        register_setting('wp_maintenance_lite_settings', 'wpm_maintenance_lite_message');
        register_setting('wp_maintenance_lite_settings', 'wpm_maintenance_lite_bg');
        register_setting('wp_maintenance_lite_settings', 'wpm_maintenance_lite_exclude');

        add_settings_section('general', __('General Settings', 'wp-maintenance-lite'), null, 'wp-maintenance-lite');

        add_settings_field('wpm_maintenance_lite_enabled', __('Enable Maintenance Mode', 'wp-maintenance-lite'), function() {
            $val = get_option('wpm_maintenance_lite_enabled', false);
            echo '<input type="checkbox" name="wpm_maintenance_lite_enabled" value="1"' . checked(1, $val, false) . '> ' . __('Enable', 'wp-maintenance-lite');
        }, 'wp-maintenance-lite', 'general');

        add_settings_field('wpm_maintenance_lite_title', __('Title', 'wp-maintenance-lite'), function() {
            $val = esc_attr(get_option('wpm_maintenance_lite_title', __('We’ll be back soon!', 'wp-maintenance-lite')));
            echo '<input type="text" name="wpm_maintenance_lite_title" value="' . $val . '" class="regular-text">';
        }, 'wp-maintenance-lite', 'general');

        add_settings_field('wpm_maintenance_lite_message', __('Message', 'wp-maintenance-lite'), function() {
            $val = esc_textarea(get_option('wpm_maintenance_lite_message', __('Our site is currently undergoing maintenance.', 'wp-maintenance-lite')));
            echo '<textarea name="wpm_maintenance_lite_message" rows="5" cols="50">' . $val . '</textarea>';
        }, 'wp-maintenance-lite', 'general');

        add_settings_field('wpm_maintenance_lite_bg', __('Background Color', 'wp-maintenance-lite'), function() {
            $val = esc_attr(get_option('wpm_maintenance_lite_bg', '#ffffff'));
            echo '<input type="color" name="wpm_maintenance_lite_bg" value="' . $val . '">';
        }, 'wp-maintenance-lite', 'general');

        add_settings_field('wpm_maintenance_lite_exclude', __('Excluded URLs (comma-separated)', 'wp-maintenance-lite'), function() {
            $val = esc_attr(get_option('wpm_maintenance_lite_exclude', ''));
            echo '<input type="text" name="wpm_maintenance_lite_exclude" value="' . $val . '" class="regular-text">';
        }, 'wp-maintenance-lite', 'general');
    }

    public function render_settings_page() {
        echo '<div class="wrap"><h1>' . __('WP Maintenance Lite Settings', 'wp-maintenance-lite') . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('wp_maintenance_lite_settings');
        do_settings_sections('wp-maintenance-lite');
        submit_button();
        echo '</form></div>';
    }
}
