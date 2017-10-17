<?php

class Bonkers_Service extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'service-widget', // Base ID
			esc_attr__( 'Bonkers - Service widget', 'bonkers-addons' ), // Name
			array(
				'description' => esc_attr__( 'Display a Service or Feature description.', 'bonkers-addons' ),
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

		$wp_kses_args = array(
			'a' => array(
				'href' => array(),
				'title' => array(),
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);

		?>
			<div class="bonkers-service col-md-4 col-sm-6">

							<?php if ( ! empty( $instance['image_uri'] ) ) : ?>
					<img src="<?php echo esc_url( $instance['image_uri'] ); ?>" alt="" class="bonkers-service-icon"/>
				<?php endif; ?>

				<h4>
				<?php
				if ( ! empty( $instance['title'] ) ) :
					echo wp_kses( apply_filters( 'widget_title', $instance['title'] ), $wp_kses_args );
endif;
?>
</h4>

				<?php
				if ( ! empty( $instance['text'] ) ) :

					echo '<p>';
						echo htmlspecialchars_decode( wp_kses( apply_filters( 'widget_title', $instance['text'] ), $wp_kses_args ) );
					echo '</p>';
				endif;
				?>

									<?php if ( ! empty( $instance['link'] ) ) : ?>
				<a href="<?php echo esc_url( $instance['link'] ); ?>" class="bonkers-service-btn">
									<?php
									if ( ! empty( $instance['link_title'] ) ) :
										echo esc_html( $instance['link_title'] );
endif;
?>
</a>
				<?php endif; ?>
				<div class="clearfix"></div>
			</div><!-- service -->

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

		$instance['text'] = wp_filter_post_kses( $new_instance['text'] );
		$instance['title'] = wp_filter_post_kses( $new_instance['title'] );
		$instance['link_title'] = sanitize_text_field( $new_instance['link_title'] );
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
			'text' => '',
			'title' => '',
			'link_title' => '',
			'link' => '',
			'image_uri' => '',
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

			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'bonkers-addons' ); ?></label><br/>

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat"/>

		</p>

		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text', 'bonkers-addons' ); ?></label><br/>

			<textarea class="widefat" rows="8" cols="20" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"
					  id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php echo htmlspecialchars_decode( $instance['text'] ); ?></textarea>

		</p>

				<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'link_title' ) ); ?>"><?php esc_html_e( 'Link Title','bonkers-addons' ); ?></label><br />

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'link_title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'link_title' ) ); ?>" value="<?php echo esc_attr( $instance['link_title'] ); ?>" class="widefat" />

		</p>
		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e( 'Link','bonkers-addons' ); ?></label><br />

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" value="<?php echo esc_attr( $instance['link'] ); ?>" class="widefat" />

		</p>


		


	<?php

	}

}


register_widget( 'Bonkers_Service' );
