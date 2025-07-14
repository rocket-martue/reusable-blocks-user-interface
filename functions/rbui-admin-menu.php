<?php
/**
 * Admin Menu
 *
 * @package reusable-blocks-user-interface
 */

/**
 * Add "Patterns" to the admin menu.
 *
 * This function adds a new menu item called "Patterns" to the WordPress admin menu.
 * The menu item links to the wp_block post type (reusable blocks) editing page.
 *
 * @since 1.0.0
 */
add_action(
	'admin_menu',
	function () {
		add_menu_page(
			__( 'Patterns', 'reusable-blocks-user-interface' ), // Page title
			__( 'Patterns', 'reusable-blocks-user-interface' ), // Menu title
			'edit_posts', // Capability required to access the menu
			'edit.php?post_type=wp_block', // Menu slug (links to wp_block post type)
			'', // Function to display the page content (empty because we're linking to existing page)
			'dashicons-block-default', // Menu icon
			22 // Menu position
		);
	}
);

/**
 * Add "Shortcode" column to wp_block posts list table.
 *
 * This filter adds a new column called "Shortcode" to the wp_block post type
 * list table in the WordPress admin area.
 *
 * @param array $columns Existing columns in the posts list table.
 * @return array Modified columns array with the new shortcode column.
 * @since 1.0.0
 */
add_filter(
	'manage_posts_columns',
	function ( $columns ) {
		global $post_type;
		// Only add the column for wp_block post type
		if ( in_array( $post_type, array( 'wp_block' ), true ) ) {
			$new_columns = array(
				'shortcode' => esc_html__( 'Shortcode', 'reusable-blocks-user-interface' ),
			);
			return array_merge( $columns, $new_columns );
		}
		return $columns;
	}
);

/**
 * Display content for the custom "Shortcode" column.
 *
 * This action displays the shortcode for each wp_block post in the custom
 * shortcode column. The shortcode format is [rbui slug=post-slug].
 *
 * @param string $column_name The name of the column to display.
 * @param int    $post_id     The ID of the current post.
 * @since 1.0.0
 */
add_filter(
	'manage_posts_custom_column',
	function ( $column_name, $post_id ) {
		global $post_type;
		// Only process for wp_block post type
		if ( in_array( $post_type, array( 'wp_block' ), true ) ) {
			if ( 'shortcode' === $column_name ) {
				$post = get_post( $post_id );
				$slug = $post->post_name;
				// Display the shortcode with proper escaping
				echo '<span class="rbui-short-code">[rbui slug=' . esc_html( $slug ) . ']</span>';
			}
		}
	},
	10,
	2
);
