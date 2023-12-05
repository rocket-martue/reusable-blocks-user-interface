<?php
/**
 * rbui_get_block function.
 * 
 * @param mixed $id The ID of the Synced patterns (Reusable Blocks).
 * @return $content The content of the block.
 */
function rbui_get_block( $id ) {
	$content_post = get_post( $id );
	$content = $content_post->post_content;
	return $content;
}

/**
 * Rbui_Widget class.
 * 
 * @extends WP_Widget
 */
class Rbui_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'rbui-widget',
			'Synced patterns'
		);
		add_action(
			'widgets_init',
			function() {
				register_widget( 'Rbui_Widget' );
			}
		);
	}
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo '<h3>' . esc_html( $instance['title'] ) . '</h3>';
		}
		$block_id = '';
		if ( isset( $instance['block_id'] ) ) {
			$block_id = $instance['block_id'];
		}
		echo apply_filters( 'the_content', rbui_get_block( $block_id ) );
		echo $args['after_widget'];
	}
 
	public function form( $instance ) {
		$title	= ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$block_id = ( ! empty( $instance['block_id'] ) ) ? $instance['block_id'] : '';
		$args = array(
			'post_type' => 'wp_block',
			'posts_per_page' => -1,
			'post_status' => 'publish',
		);
		$query_reusable = new WP_Query( $args );
		?>

		<?php if ( $query_reusable->have_posts() ) : ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Optional widget title', 'reusable-blocks-user-interface' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'block_id' ) ); ?>"><?php echo esc_attr( 'Select Synced patterns (Reusable Blocks)', 'reusable-blocks-user-interface' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'block_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'block_id' ) ); ?>">
			<?php while ( $query_reusable->have_posts() ) : $query_reusable->the_post(); ?>
				<option value="<?php echo get_the_ID(); ?>" <?php selected( $block_id, get_the_ID() ); ?>>
					<?php the_title() ?> - (<?php echo sprintf( esc_html__( 'ID: %s', 'reusable-blocks-user-interface' ), get_the_ID() ); ?>)
				</option>
			<?php endwhile; ?>
			</select>
		</p>
		<?php else : ?>
			<p><?php esc_html_e( 'You don’t have any Synced patterns (Reusable Blocks) yet.', 'reusable-blocks-user-interface' ); ?></p>
		<?php endif; wp_reset_postdata(); ?>

		<?php
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']	= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['block_id'] = ( ! empty( $new_instance['block_id'] ) ) ? $new_instance['block_id'] : '';
		return $instance;
	}
}
$rbui_widget = new Rbui_Widget();