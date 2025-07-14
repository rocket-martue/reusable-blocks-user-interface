<?php
/**
 * Plugin Name: Reusable Blocks User Interface
 * Plugin URI: https://github.com/rocket-martue/reusable-blocks-user-interface
 * Description: This plugin adds "Reusable Blocks" to the admin menu for easy editing. It also allows you to easily insert blocks into your posts using a shortcode.
 * Version: 1.0.7
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
 * Auto-load all PHP files from the functions directory.
 *
 * This section automatically includes all PHP files from the 'functions'
 * directory to load the plugin's functionality modules.
 */
$dir = RBUI_PATH . '/functions/';
// Check if the functions directory exists.
if ( ! file_exists( $dir ) ) {
	return;
}

// Open the directory handle.
$dh = opendir( $dir );
if ( false !== $dh ) {
	// Read all files in the directory.
	while ( true ) {
		$file = readdir( $dh );
		if ( false === $file ) {
			break; // No more files to read.
		}
		// Include only PHP files that don't start with hyphen (private files).
		if ( ( ! is_dir( $file ) ) && ( '.php' === strtolower( substr( $file, -4 ) ) ) && ( '-' !== substr( $file, 0, 1 ) ) ) {
			$load_file = $dir . $file;
			include_once $load_file;
		}
	}
	// Close the directory handle.
	closedir( $dh );
}
