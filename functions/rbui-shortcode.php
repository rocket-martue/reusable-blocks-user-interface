<?php
/**
 * Shortcode
 *
 * @package reusable-blocks-user-interface
 */

/**
 * Add shortcode.
 *
 * @param array $atts User defined attributes in shortcode tag.
 * @return string
 */
function rbui_shortcode( $atts ) {
	$args = shortcode_atts(
		array(
			'slug' => '',
		),
		$atts
	);
	$slug = $args['slug'];
	ob_start();
	$args  = array(
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
