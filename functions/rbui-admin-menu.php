<?php
/**
 * admin menu
 *
 * @package reusable-blocks-user-interface
 */

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