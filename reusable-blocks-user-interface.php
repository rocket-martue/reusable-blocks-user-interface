<?php
/**
 * Plugin Name: Reusable Blocks User Interface
 * Plugin URI: https://github.com/rocket-martue/reusable-blocks-user-interface
 * Description: This plugin adds "Reusable Blocks" to the admin menu for easy editing. It also allows you to easily insert blocks into your posts using a shortcode.
 * Version: 1.0.6
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RBUI_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'RBUI_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * Load plugin textdomain.
 */
function rbui_plugins_loaded() {
	load_plugin_textdomain( 'reusable-blocks-user-interface', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'plugins_loaded', 'rbui_plugins_loaded' );

$dir = RBUI_PATH . '/functions/';
if ( ! file_exists( $dir ) ) {
	return;
}

$dh = opendir( $dir );
if ( false !== $dh ) {
	while ( true ) {
		$file = readdir( $dh );
		if ( false === $file ) {
			break;
		}
		if ( ( ! is_dir( $file ) ) && ( '.php' === strtolower( substr( $file, -4 ) ) ) && ( '_' !== substr( $file, 0, 1 ) ) ) {
			$load_file = $dir . $file;
			include_once $load_file;
		}
	}
	closedir( $dh );
}
