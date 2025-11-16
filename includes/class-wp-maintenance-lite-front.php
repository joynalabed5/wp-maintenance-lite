<?php
if (!defined('ABSPATH')) exit;

class WP_Maintenance_Lite_Front {

    public function __construct() {
        add_action('template_redirect', [$this, 'check_maintenance']);
    }

    public function check_maintenance() {
        if (!get_option('wpm_maintenance_lite_enabled')) return;
        if (current_user_can('manage_options')) return;

        $excluded = array_map('trim', explode(',', get_option('wpm_maintenance_lite_exclude', '')));
        $current_url = $_SERVER['REQUEST_URI'];
        foreach ($excluded as $url) {
            if ($url && strpos($current_url, $url) !== false) return;
        }

        $this->render_maintenance_page();
        exit;
    }

    private function render_maintenance_page() {
        status_header(503);
        header('Retry-After: 3600');
        $title = esc_html(get_option('wpm_maintenance_lite_title', __('We’ll be back soon!', 'wp-maintenance-lite')));
        $msg = esc_html(get_option('wpm_maintenance_lite_message', __('Our site is currently undergoing maintenance.', 'wp-maintenance-lite')));
        $bg = esc_attr(get_option('wpm_maintenance_lite_bg', '#ffffff'));

        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>' . $title . '</title>';
        echo '<link rel="stylesheet" href="' . WP_MAINTENANCE_LITE_URL . 'assets/css/front.css">';
        echo '</head><body style="background:' . $bg . '">';
        echo '<div class="ml-container"><h1>' . $title . '</h1><p>' . $msg . '</p></div></body></html>';
    }
}
