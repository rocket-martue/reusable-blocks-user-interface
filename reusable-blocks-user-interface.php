<?php
/**
 * Plugin Name: Reusable Blocks User Interface
 * Plugin URI: https://github.com/rocket-martue/reusable-blocks-user-interface
 * Description: This plugin adds "Reusable Blocks" to the admin menu for easy editing. It also allows you to easily insert blocks into your posts using a shortcode.
 * Version: 1.0.0
 * Tested up to: 5.7
 * Requires at least: 5.6
 * Requires PHP: 5.6
 * Author: Rocket Martue
 * Author URI: https://web-ya3.com
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reusable-blocks-user-interface
 *
 * @package reusable-blocks-user-interface
 * @author Rocket Martue
 * @license GPL-2.0+
 */

define( 'RBUI_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'RBUI_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * Function : plugin loaded
 */
function rbui_plugins_loaded() {
	load_plugin_textdomain( 'reusable-blocks-user-interface', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'plugins_loaded', 'rbui_plugins_loaded' );

add_action(
	'admin_menu',
	function () {
		add_menu_page(
			__( 'Reusable Blocks', 'reusable-blocks-user-interface' ),
			__( 'Reusable Blocks', 'reusable-blocks-user-interface' ),
			'edit_posts',
			'edit.php?post_type=wp_block',
			'',
			'dashicons-block-default',
			22
		);
	}
);

add_filter(
	'manage_posts_columns',
	function ( $columns ) {
		global $post_type;
		if( in_array( $post_type, array( 'wp_block' ) ) ) {
			$new_columns = array(
				'shortcode' => esc_html__( 'Shortcode', 'reusable-blocks-user-interface' ),
			);
			return array_merge( $columns, $new_columns );
		}
		return $columns;
	}
);

add_filter(
	'manage_posts_custom_column',
	function ( $column_name, $post_id ) {
		if ( 'shortcode' === $column_name ) {
			$post = get_post( $post_id );
			$slug = $post->post_name;
			echo '<span class="rbui-short-code">[rbui slug=' . esc_html( $slug ) . ']</span>';
		}
	},
	10,
	2
);

/**
 * shortcode.
 *
 * @param array $atts User defined attributes in shortcode tag.
 *
 * @return string
 */
function rbui_shortcode( $atts ) {
	extract( shortcode_atts(
		array(
			'slug' => '',
		), $atts ) );
	ob_start();
	$args = array(
		'post_type' => array( 'wp_block' ),
		'name'      => $slug,
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			the_content();
		}
	}
	wp_reset_postdata();
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode( 'rbui', 'rbui_shortcode' );