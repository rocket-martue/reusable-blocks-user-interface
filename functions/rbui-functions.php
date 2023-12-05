<?php
/**
 * the_rubi_content function.
 * @param mixed $slug The slug of the Synced patterns (Reusable Blocks).
 * @return $content The content of the block.
 */
function rbui_get( $slug ) {
	$args = array(
		'post_type' => array( 'wp_block' ),
		'name'      => $slug,
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$content = get_the_content();
		}
	}
	wp_reset_postdata();
	return $content;
}
function the_rubi_content( $slug ) {
	echo apply_filters( 'the_content', rbui_get( $slug ) );
}