<?php
/**
 * Plugin Name: Reusable Blocks User Interface
 * Plugin URI: https://github.com/rocket-martue/reusable-blocks-user-interface
 * Description: This plugin adds "Reusable Blocks" to the admin menu for easy editing. It also allows you to easily insert blocks into your posts using a shortcode.
 * Version: 1.0.1
 * Tested up to: 5.7
 * Requires at least: 5.6
 * Requires PHP: 5.6
 * Author: Rocket Martue
 * Author URI: https://profiles.wordpress.org/rocketmartue/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reusable-blocks-user-interface
 *
 * @package reusable-blocks-user-interface
 */

if ( ! defined( 'ABSPATH' ) ) exit;

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

/**
 * rbui_get_block function.
 * 
 * @param mixed $id The ID of the reusable block.
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
			'Reusable block'
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
			<label for="<?php echo esc_attr( $this->get_field_id( 'block_id' ) ); ?>"><?php echo esc_attr( 'Select reusable block', 'reusable-blocks-user-interface' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'block_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'block_id' ) ); ?>">
			<?php while ( $query_reusable->have_posts() ) : $query_reusable->the_post(); ?>
				<option value="<?php echo get_the_ID(); ?>" <?php selected( $block_id, get_the_ID() ); ?>>
					<?php the_title() ?> - (<?php echo sprintf( esc_html__( 'ID: %s', 'reusable-blocks-user-interface' ), get_the_ID() ); ?>)
				</option>
			<?php endwhile; ?>
			</select>
		</p>
		<?php else : ?>
			<p><?php esc_html_e( 'You don’t have any reusable block yet.', 'reusable-blocks-user-interface' ); ?></p>
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
