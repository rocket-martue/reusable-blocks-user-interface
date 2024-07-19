<?php
/**
 * Functions
 *
 * @package reusable-blocks-user-interface
 */

/**
 * Rbui_get function.
 *
 * @param string $slug The slug of the reusable block.
 * @return string $content The content of the block.
 */
function rbui_get( $slug ) {
	$content = '';
	$args    = array(
		'post_type' => array( 'wp_block' ),
		'name'      => $slug,
	);
	$query   = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$content = get_the_content();
		}
	}
	wp_reset_postdata();
	return $content;
}

/**
 * The_rubi_content function.
 *
 * @param string $slug The slug of the reusable block.
 */
function the_rubi_content( $slug ) {
	$content = rbui_get( $slug );
	echo wp_kses_post( $content );
}
