<?php

class Bonkers_Contact_Info extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'contact-info-widget', // Base ID
            esc_attr__( 'Bonkers - Contact Info', 'bonkers' ), // Name
            array(
                'description' => esc_attr__( 'Display contact info.', 'bonkers' ),
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
    public function widget( $args, $instance ){


        echo $args['before_widget'];

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        ?>

            <div class="widget_contact_info">

                <?php if( ! empty( $title ) ): echo $args['before_title'] . $title . $args['after_title']; endif; ?>

                <ul>
                    <?php if( !empty( $instance['address'] ) ){ ?>
                        <li class="location"><i class="fa fa-map-marker"></i> <?php echo $instance['address']; ?></li>
                    <?php } ?>
                    <?php if( !empty( $instance['email'] ) ){ ?>
                    <li class="email"><i class="fa fa-envelope-o"></i> <a href="mailto:<?php echo $instance['email']; ?>"><?php  echo $instance['email']; ?></a></li>
                    <?php } ?>
                    <?php if( !empty( $instance['phone'] ) ){ ?>
                    <li class="phone"><i class="fa fa-phone"></i> <?php echo $instance['phone']; ?></li>
                    <?php } ?>
                    <?php if( !empty( $instance['website'] ) ){ ?>
                    <li class="website"><i class="fa fa-globe"></i> <a href="<?php echo esc_url( $instance['website'] ); ?>"><?php echo $instance['website']; ?></a></li>
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

        $instance['title'] = strip_tags( $new_instance['title'] );

		$instance['address'] = strip_tags( $new_instance['address'] );

        $instance['email'] = strip_tags( $new_instance['email'] );

        $instance['phone'] = strip_tags( $new_instance['phone'] );

        $instance['website'] = strip_tags( $new_instance['website'] );


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

        ?>

        <p>

            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Title', 'bonkers' ); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('title'); ?>"
                   id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty( $instance['title'] ) ): echo $instance['title']; endif; ?>"
                   class="widefat"/>

        </p>


        <p>
            <label for="<?php echo $this->get_field_id('address'); ?>"><?php esc_html_e( 'Address', 'bonkers' ); ?></label><br />

            <input type="text" name="<?php echo $this->get_field_name('address'); ?>" id="<?php echo $this->get_field_id('address'); ?>" value="<?php if( !empty( $instance['address'] ) ): echo $instance['address']; endif; ?>" class="widefat" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('email'); ?>"><?php esc_html_e( 'Email', 'bonkers' ); ?></label><br />

            <input type="text" name="<?php echo $this->get_field_name('email'); ?>" id="<?php echo $this->get_field_id('email'); ?>" value="<?php if( !empty( $instance['email'] ) ): echo $instance['email']; endif; ?>" class="widefat" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('phone'); ?>"><?php esc_html_e( 'Phone', 'bonkers' ); ?></label><br />

            <input type="text" name="<?php echo $this->get_field_name('phone'); ?>" id="<?php echo $this->get_field_id('phone'); ?>" value="<?php if( !empty( $instance['phone'] ) ): echo $instance['phone']; endif; ?>" class="widefat" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('website'); ?>"><?php esc_html_e( 'Website', 'bonkers' ); ?></label><br />

            <input type="text" name="<?php echo $this->get_field_name('website'); ?>" id="<?php echo $this->get_field_id('website'); ?>" value="<?php if( !empty( $instance['website'] ) ): echo $instance['website']; endif; ?>" class="widefat" />
        </p>





    <?php

    }

}


register_widget( 'Bonkers_Contact_Info' );
