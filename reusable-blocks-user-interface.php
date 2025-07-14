<?php
/**
 * Plugin Name: Reusable Blocks User Interface
 * Plugin URI: https://github.com/rocket-martue/reusable-blocks-user-interface
 * Description: This plugin adds "Reusable Blocks" to the admin menu for easy editing. It also allows you to easily insert blocks into your posts using a shortcode.
 * Version: 1.0.8
 * Tested up to: 6.6
 * Requires at least: 5.7
 * Requires PHP: 7.3
 * Author: Rocket Martue
 * Author URI: https://profiles.wordpress.org/rocketmartue/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reusable-blocks-user-interface
 *
 * @package reusable-blocks-user-interface
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'RBUI_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'RBUI_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * Load plugin textdomain.
 *
 * This function loads the translation files for the plugin to enable
 * multilingual support. Called during the 'plugins_loaded' action.
 */
function rbui_plugins_loaded() {
	load_plugin_textdomain( 'reusable-blocks-user-interface', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'plugins_loaded', 'rbui_plugins_loaded' );

/**
 * Load plugin functionality modules.
 *
 * Manually include each required file for better security and performance.
 * This approach is more predictable than auto-loading and easier to debug.
 */
require_once RBUI_PATH . '/functions/rbui-admin-menu.php';
require_once RBUI_PATH . '/functions/rbui-functions.php';
require_once RBUI_PATH . '/functions/rbui-shortcode.php';
require_once RBUI_PATH . '/functions/class-rbui-widget.php';
