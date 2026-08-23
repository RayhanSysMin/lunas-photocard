<?php
/**
 * Plugin Name: Luna's PhotoCard
 * Plugin URI: https://github.com/RayhanSysMin/lunas-photocard
 * Description: Generate branded 1080x1080 news photo cards from WordPress posts with templates, shoulder/subheading support, Elementor, shortcode, and browser-side export.
 * Version: 1.0.14
 * Author: Rayhan Sardar
 * Author URI: https://github.com/RayhanSysMin
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: daily-new-nation-bangla-photocard
 * Requires at least: 5.8
 * Tested up to: 6.8
 * Requires PHP: 7.4
 * Tags: news, photo card, elementor, social media, image generator, design, template builder, content branding
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/class-photocard-core.php';

add_action('elementor/widgets/register', function($widgets_manager) {
    require_once plugin_dir_path(__FILE__) . 'includes/class-elementor-widget.php';
    $widgets_manager->register(new DNNBPC_Elementor_Widget());
});
