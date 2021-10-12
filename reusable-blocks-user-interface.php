<?php
/**
 * Plugin Name: Reusable Blocks User Interface
 * Plugin URI: https://github.com/rocket-martue/reusable-blocks-user-interface
 * Description: This plugin adds "Reusable Blocks" to the admin menu for easy editing. It also allows you to easily insert blocks into your posts using a shortcode.
 * Version: 1.0.1
 * Tested up to: 5.7
 * Requires at least: 5.6
 * Requires PHP: 5.6
 * Author: Rocket Martue
 * Author URI: https://profiles.wordpress.org/rocketmartue/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reusable-blocks-user-interface
 *
 * @package reusable-blocks-user-interface
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'RBUI_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'RBUI_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * Function : plugin loaded
 */
function rbui_plugins_loaded() {
	load_plugin_textdomain( 'reusable-blocks-user-interface', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'plugins_loaded', 'rbui_plugins_loaded' );

$dir = RBUI_PATH .'/functions/';
if ( ! file_exists( $dir) ) {
	return;
} else {
	opendir( $dir );
	while( ( $file = readdir() ) !== false ) {
		if( ! is_dir( $file ) && ( strtolower( substr( $file, -4 ) ) == ".php" ) && ( substr( $file, 0, 1 ) != "_" ) ) {
			$load_file = $dir.$file;
			require_once( $load_file );
		}
	}
	closedir();
}