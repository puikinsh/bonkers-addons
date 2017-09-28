<?php

class Bonkers_Phone_Feature extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'phone-feature-widget', // Base ID
            esc_attr__( 'Bonkers - Phone Feature', 'bonkers' ), // Name
            array( 
                'description' => esc_attr__( 'Display a Phone Feature description.', 'bonkers' ),
            )
        );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public function enqueue_scripts(){

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
    public function widget( $args, $instance ){


        echo $args['before_widget'];

        ?>
            <div class="bonkers-phone-service">
            
                <?php if( !empty($instance['image_uri']) ): ?>
                    <img src="<?php echo esc_url( $instance['image_uri'] ); ?>" alt="" class="bonkers-phone-icon"/>
                <?php endif; ?>

                <div class="bonkers-phone-service-content">
                    <h4><?php if( ! empty( $instance['title'] ) ): echo apply_filters( 'widget_title', $instance['title'] ); endif; ?></h4>
                    <?php 
                    if( !empty($instance['text']) ):
                    
                        echo '<p>';
                            $wp_kses_args = array(
                                'a' => array(
                                    'href' => array(),
                                    'title' => array()
                                ),
                                'br' => array(),
                                'em' => array(),
                                'strong' => array(),
                            );
                            echo htmlspecialchars_decode( wp_kses( apply_filters( 'widget_title', $instance['text'] ), $wp_kses_args ) );
                        echo '</p>';
                    endif;
                    ?> 
                </div>
                <div class="clearfix"></div>
            </div><!-- bonkers-phone-service -->

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

        $instance['text'] = stripslashes( wp_filter_post_kses( $new_instance['text'] ) );

        $instance['title'] = strip_tags( $new_instance['title'] );

        $instance['image_uri'] = strip_tags( $new_instance['image_uri'] );

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

            <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php esc_html_e('Image', 'bonkers'); ?></label><br/>



            <?php

            if ( ! empty( $instance['image_uri'] ) ) :

                echo '<img class="custom_media_image" src="' . $instance['image_uri'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" /><br />';

            endif;

            ?>



            <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name( 'image_uri' ); ?>"
                   id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php if( !empty( $instance['image_uri'] ) ): echo $instance['image_uri']; endif; ?>"
                   style="margin-top:5px;">


            <input type="button" class="button button-primary custom_media_button" id="custom_media_button"
                   name="<?php echo $this->get_field_name('image_uri'); ?>" value="<?php esc_attr_e( 'Upload Image', 'bonkers' ); ?>"
                   style="margin-top:5px;"/>

        </p>


        <p>

            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Title', 'bonkers' ); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('title'); ?>"
                   id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty( $instance['title'] ) ): echo $instance['title']; endif; ?>"
                   class="widefat"/>

        </p>

        <p>

            <label for="<?php echo $this->get_field_id('text'); ?>"><?php esc_html_e( 'Text', 'bonkers' ); ?></label><br/>

            <textarea class="widefat" rows="8" cols="20" name="<?php echo $this->get_field_name( 'text' ); ?>"
                      id="<?php echo $this->get_field_id('text'); ?>"><?php
                        if( !empty( $instance['text'] ) ): echo htmlspecialchars_decode( $instance['text'] ); endif;
            ?></textarea>

        </p>

    <?php

    }

}

register_widget( 'Bonkers_Phone_Feature' );
