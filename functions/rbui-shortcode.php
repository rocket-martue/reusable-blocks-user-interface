<?php
/**
 * shortcode.
 *
 * @param array $atts User defined attributes in shortcode tag.
 * @return string
 * @package reusable-blocks-user-interface
 */
function rbui_shortcode( $atts ) {
	extract(
		shortcode_atts(
			array(
				'slug' => '',
			),
			$atts,
		)
	);
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
