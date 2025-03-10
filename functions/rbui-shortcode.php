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
function earb_shortcode( $atts ) {
	$args    = shortcode_atts(
		array(
			'post_id' => '',
		),
		$atts
	);
	$post_id = $args['post_id'];
	if ( $post_id ) {
		$args  = array(
			'post_type' => 'wp_block',
			'p'         => $post_id,
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
}
add_shortcode( 'rbui', 'rbui_shortcode' );
