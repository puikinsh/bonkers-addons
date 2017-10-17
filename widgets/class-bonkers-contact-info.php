<?php

class Bonkers_Contact_Info extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'contact-info-widget', // Base ID
			esc_attr__( 'Bonkers - Contact Info', 'bonkers-addons' ), // Name
			array(
				'description' => esc_attr__( 'Display contact info.', 'bonkers-addons' ),
				'customize_selective_refresh' => true,
			)
		);
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

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		?>

			<div class="widget_contact_info">

				<?php
				if ( ! empty( $title ) ) :
					echo $args['before_title'] . wp_kses_post( $title ) . $args['after_title'];
endif;
?>

				<ul>
					<?php if ( ! empty( $instance['address'] ) ) { ?>
						<li class="location"><i class="fa fa-map-marker"></i> <?php echo esc_html( $instance['address'] ); ?></li>
					<?php } ?>
					<?php if ( ! empty( $instance['email'] ) ) { ?>
					<li class="email"><i class="fa fa-envelope-o"></i> <a href="mailto:<?php echo antispambot( $instance['email'] ); ?>"><?php echo antispambot( $instance['email'] ); ?></a></li>
					<?php } ?>
					<?php if ( ! empty( $instance['phone'] ) ) { ?>
					<li class="phone"><i class="fa fa-phone"></i> <?php echo esc_html( $instance['phone'] ); ?></li>
					<?php } ?>
					<?php if ( ! empty( $instance['website'] ) ) { ?>
					<li class="website"><i class="fa fa-globe"></i> <a href="<?php echo esc_url( $instance['website'] ); ?>"><?php echo esc_html( $instance['website'] ); ?></a></li>
					<?php } ?>
				</ul>

				<div class="clearfix"></div>
			</div><!-- widget_contact_info -->

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

		$instance['title'] = wp_filter_post_kses( $new_instance['title'] );

		$instance['address'] = sanitize_text_field( $new_instance['address'] );

		$instance['email'] = sanitize_email( $new_instance['email'] );

		$instance['phone'] = sanitize_text_field( $new_instance['phone'] );

		$instance['website'] = sanitize_text_field( $new_instance['website'] );

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
			'title' => '',
			'address' => '',
			'email' => '',
			'phone' => '',
			'website' => '',
		);

		$instance = wp_parse_args( $instance, $defaults );

		?>

		<p>

			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'bonkers-addons' ); ?></label><br/>

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				   id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat"/>

		</p>


		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address', 'bonkers-addons' ); ?></label><br />

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" value="<?php echo esc_attr( $instance['address'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email', 'bonkers-addons' ); ?></label><br />

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" value="<?php echo esc_attr( $instance['email'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone', 'bonkers-addons' ); ?></label><br />

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" value="<?php echo esc_attr( $instance['phone'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'website' ) ); ?>"><?php esc_html_e( 'Website', 'bonkers-addons' ); ?></label><br />

			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'website' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'website' ) ); ?>" value="<?php echo esc_attr( $instance['website'] ); ?>" class="widefat" />
		</p>





	<?php

	}

}


register_widget( 'Bonkers_Contact_Info' );
