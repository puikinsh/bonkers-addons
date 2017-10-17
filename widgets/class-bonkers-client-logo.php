<?php

class Bonkers_Client_Logo extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'client-logo-widget', // Base ID
			esc_attr__( 'Bonkers - Client Logo', 'bonkers-addons' ), // Name
			array(
				'description' => esc_attr__( 'Display a client logo.', 'bonkers-addons' ),
				'customize_selective_refresh' => true,
			)
		);

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	public function enqueue_scripts() {

		wp_enqueue_media();
		wp_enqueue_script( 'bonkers-addon-widgets-script', BONKERS_ADDONS__PLUGIN_URL . 'assets/js/widget.js', false, '1.0', true );

	}



	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		?>
		<div class="bonkers-clients-logo">
			<?php
			if ( ! empty( $instance['link'] ) ) :
?>
<a href="<?php echo esc_url( $instance['link'] ); ?>" target="_blank"><?php endif; ?>
				<?php if ( ! empty( $instance['image_uri'] ) ) : ?>
					<img src="<?php echo esc_url( $instance['image_uri'] ); ?>" alt="" class="bonkers-clients-image">
				<?php endif; ?>
			<?php
			if ( ! empty( $instance['link'] ) ) :
?>
</a><?php endif; ?>
		</div>
		<?php

		echo $args['after_widget'];

	}





	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['link'] = esc_url_raw( $new_instance['link'] );

		$instance['image_uri'] = esc_url_raw( $new_instance['image_uri'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$defaults = array(
			'image_uri' => '',
			'link' => '',
		);

		$instance = wp_parse_args( $instance, $defaults );

		?>
		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>"><?php esc_html_e( 'Image', 'bonkers-addons' ); ?></label><br/>

			<?php

			if ( ! empty( $instance['image_uri'] ) ) :

				echo '<img class="custom_media_image" src="' . esc_url( $instance['image_uri'] ) . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" /><br />';

			endif;

			?>

			<input type="text" class="widefat custom_media_url" name="<?php echo esc_attr( $this->get_field_name( 'image_uri' ) ); ?>"
				   id="<?php echo esc_attr( $this->get_field_id( 'image_uri' ) ); ?>" value="<?php echo esc_attr( $instance['image_uri'] ); ?>" style="margin-top:5px;">

			<input type="button" class="button button-primary custom_media_button" id="custom_media_button"
				   name="<?php echo esc_attr( $this->get_field_name( 'image_uri' ) ); ?>" value="<?php esc_attr_e( 'Upload Image', 'bonkers-addons' ); ?>"
				   style="margin-top:5px;"/>

		</p>

		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Link', 'bonkers-addons' ); ?></label><br/>

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>"
				   id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" value="<?php echo esc_attr( $instance['link'] ); ?>"  class="widefat"/>

		</p>

	<?php

	}

}


register_widget( 'Bonkers_Client_Logo' );
