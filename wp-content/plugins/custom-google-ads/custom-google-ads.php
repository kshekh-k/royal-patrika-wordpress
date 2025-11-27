<?php
/*
Plugin Name: Custom Google Ads
Description: Manage and display ads in post content with flexible positioning options
Version: 1.0
Author: Royal Patrika
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('CGA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CGA_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include files
require_once CGA_PLUGIN_DIR . 'includes/admin.php';
require_once CGA_PLUGIN_DIR . 'includes/frontend.php';

// Plugin activation hook
register_activation_hook(__FILE__, 'cga_activate_plugin');
function cga_activate_plugin() {
    // Initialize default options
    for ($i = 1; $i <= 3; $i++) {
        add_option('cga_ad_type_' . $i, 'none');
        add_option('cga_ad_position_' . $i, 'middle');
        add_option('cga_image_ad_' . $i, '');
        add_option('cga_adsense_code_' . $i, '');
        add_option('cga_ad_link_' . $i, '');
    }
}

// Plugin deactivation hook
register_deactivation_hook(__FILE__, 'cga_deactivate_plugin');
function cga_deactivate_plugin() {
    // Clean up if needed (optional)
}

// Add plugin action links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cga_plugin_action_links');
function cga_plugin_action_links($links) {
    $settings_link = '<a href="admin.php?page=custom-google-ads">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}