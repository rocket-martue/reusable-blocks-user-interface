<?php
/**
 * Class Rbui_Widget
 *
 * Handles the display of reusable blocks in a widget.
 */
class Rbui_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'rbui-widget',
			'Reusable block'
		);
		add_action(
			'widgets_init',
			function () {
				register_widget( 'Rbui_Widget' );
			}
		);
	}

	/**
	 * Retrieve the content of the reusable block.
	 *
	 * @param int $id The ID of the reusable block.
	 * @return string The content of the block.
	 */
	private function get_block( $id ) {
		$content_post = get_post( $id );
		$content      = $content_post->post_content;
		return $content;
	}

	/**
	 * Display the widget content.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget instance settings.
	 */
	public function widget( $args, $instance ) {
		echo esc_html( $args['before_widget'] );
		if ( ! empty( $instance['title'] ) ) {
			echo '<h3>' . esc_html( $instance['title'] ) . '</h3>';
		}
		$block_id = '';
		if ( isset( $instance['block_id'] ) ) {
			$block_id = $instance['block_id'];
		}
		echo wp_kses_post( apply_filters( 'the_content', $this->get_block( $block_id ) ) );
		echo esc_html( $args['after_widget'] );
	}

	/**
	 * Display the widget settings form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title          = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$block_id       = ( ! empty( $instance['block_id'] ) ) ? $instance['block_id'] : '';
		$args           = array(
			'post_type'      => 'wp_block',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);
		$query_reusable = new WP_Query( $args );
		?>

		<?php if ( $query_reusable->have_posts() ) : ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Optional widget title', 'reusable-blocks-user-interface' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'block_id' ) ); ?>"><?php esc_html_e( 'Select reusable block', 'reusable-blocks-user-interface' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'block_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'block_id' ) ); ?>">
			<?php
			while ( $query_reusable->have_posts() ) :
				$query_reusable->the_post();
				?>
				<option value="<?php echo esc_attr( get_the_ID() ); ?>" <?php selected( $block_id, get_the_ID() ); ?>>
					<?php the_title(); ?> - 
					<?php
					// Translators: ID of the reusable block.
					printf( esc_html__( 'ID: %s', 'reusable-blocks-user-interface' ), esc_attr( get_the_ID() ) );
					?>
				</option>
			<?php endwhile; ?>
			</select>
		</p>
		<?php else : ?>
			<p><?php esc_html_e( 'You don’t have any reusable block yet.', 'reusable-blocks-user-interface' ); ?></p>
			<?php
		endif;
		wp_reset_postdata();
		?>

		<?php
	}

	/**
	 * Update the widget settings.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Previous settings.
	 * @return array Updated settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance             = array();
		$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['block_id'] = ( ! empty( $new_instance['block_id'] ) ) ? $new_instance['block_id'] : '';
		return $instance;
	}
}
$rbui_widget = new Rbui_Widget();
