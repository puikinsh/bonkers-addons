<?php

class Bonkers_Team_Member extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'team-member-widget', // Base ID
            esc_attr__( 'Bonkers - Team Member', 'bonkers' ), // Name
            array( 
                'description' => esc_attr__( 'Display info about a team member.', 'bonkers' ),
                'customize_selective_refresh' => true,
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
        <div class="bonkers-team-member">

                <?php if( !empty($instance['image_uri']) ): ?>
                    <img src="<?php echo esc_url( $instance['image_uri'] ); ?>" alt="" class="bonkers-team-image">
                    
                <?php endif; ?>

                <div class="bonker-team-content">
                    <h4 class="bonkers-team-name"><?php if( !empty( $instance['title'] ) ): echo apply_filters( 'widget_title', $instance['title'] ); endif; ?></h4>
                    <span class="bonkers-team-position"><?php if( !empty( $instance['position'] ) ): echo esc_html( $instance['position'] ); endif; ?></span>
                    <ul class="bonkers-team-social">
                        <?php if( !empty( $instance['link_facebook'] ) ): echo '<li><a href="' . esc_url( $instance['link_facebook'] ) . '"><i class="fa fa-facebook"></i></a></li>'; endif; ?>
                        <?php if( !empty( $instance['link_twitter'] ) ): echo '<li><a href="' . esc_url( $instance['link_twitter'] ) . '"><i class="fa fa-twitter"></i></a></li>'; endif; ?>
                        <?php if( !empty( $instance['link_instagram'] ) ): echo '<li><a href="' . esc_url( $instance['link_instagram'] ) . '"><i class="fa fa-instagram"></i></a></li>'; endif; ?>
                        <?php if( !empty( $instance['link_snapchat'] ) ): echo '<li><a href="' . esc_url( $instance['link_snapchat'] ) . '"><i class="fa fa-snapchat-ghost"></i></a></li>'; endif; ?>
                        <?php if( !empty( $instance['link_google_plus'] ) ): echo '<li><a href="' . esc_url( $instance['link_google_plus'] ) . '"><i class="fa fa-google-plus"></i></a></li>'; endif; ?>
                        <?php if( !empty( $instance['link_linkedin'] ) ): echo '<li><a href="' . esc_url( $instance['link_linkedin'] ) . '"><i class="fa fa-linkedin"></i></a></li>'; endif; ?>
                        <?php if( !empty( $instance['link_github'] ) ): echo '<li><a href="' . esc_url( $instance['link_github'] ) . '"><i class="fa fa-github"></i></a></li>'; endif; ?>
                        <?php if( !empty( $instance['link_wordpress'] ) ): echo '<li><a href="' . esc_url( $instance['link_wordpress'] ) . '"><i class="fa fa-wordpress"></i></a></li>'; endif; ?>
                        <?php if( !empty( $instance['link_youtube'] ) ): echo '<li><a href="' . esc_url( $instance['link_youtube'] ) . '"><i class="fa fa-youtube"></i></a></li>'; endif; ?>
                        <?php if( !empty( $instance['link_vimeo'] ) ): echo '<li><a href="' . esc_url( $instance['link_vimeo'] ) . '"><i class="fa fa-vimeo"></i></a></li>'; endif; ?>
                    </ul>
                </div>

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

        $instance['position'] = stripslashes(wp_filter_post_kses($new_instance['position']));

        $instance['title'] = strip_tags($new_instance['title']);

        $instance['link_facebook'] = strip_tags( $new_instance['link_facebook'] );
        $instance['link_twitter'] = strip_tags( $new_instance['link_twitter'] );
        $instance['link_instagram'] = strip_tags( $new_instance['link_instagram'] );
        $instance['link_snapchat'] = strip_tags( $new_instance['link_snapchat'] );
        $instance['link_google_plus'] = strip_tags( $new_instance['link_google_plus'] );
        $instance['link_linkedin'] = strip_tags( $new_instance['link_linkedin'] );
        $instance['link_github'] = strip_tags( $new_instance['link_github'] );
        $instance['link_wordpress'] = strip_tags( $new_instance['link_wordpress'] );
        $instance['link_youtube'] = strip_tags( $new_instance['link_youtube'] );
        $instance['link_vimeo'] = strip_tags( $new_instance['link_vimeo'] );


        $instance['image_uri'] = strip_tags($new_instance['image_uri']);

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

            <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php esc_html_e( 'Image', 'bonkers' ); ?> <small><?php esc_html_e( 'Use square images (1:1)', 'bonkers' ); ?></small></label><br/>

            <?php

            if ( !empty($instance['image_uri']) ) :

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

            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Name', 'bonkers' ); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('title'); ?>"
                   id="<?php echo $this->get_field_id('title'); ?>" value="<?php if( !empty( $instance['title'] ) ): echo $instance['title']; endif; ?>"
                   class="widefat"/>

        </p>

        <p>

            <label for="<?php echo $this->get_field_id('position'); ?>"><?php esc_html_e( 'Position', 'bonkers' ); ?></label><br/>

            <input type="text" name="<?php echo $this->get_field_name('position'); ?>"
                   id="<?php echo $this->get_field_id( 'position' ); ?>" value="<?php if( !empty( $instance['position'] ) ): echo $instance['position']; endif; ?>"
                   class="widefat"/>

        </p>
        <br><br>
		
		<p>
			<label for="<?php echo $this->get_field_id('link_facebook'); ?>"><?php esc_html_e( 'Facebook URL','bonkers' ); ?></label><br />
			<input type="text" name="<?php echo $this->get_field_name('link_facebook'); ?>" id="<?php echo $this->get_field_id('link_facebook'); ?>" value="<?php if( !empty( $instance['link_facebook'] ) ): echo $instance['link_facebook']; endif; ?>" class="widefat" />
		</p>
        <p>
            <label for="<?php echo $this->get_field_id('link_twitter'); ?>"><?php esc_html_e( 'Twitter URL','bonkers' ); ?></label><br />
            <input type="text" name="<?php echo $this->get_field_name('link_twitter'); ?>" id="<?php echo $this->get_field_id('link_twitter'); ?>" value="<?php if( !empty( $instance['link_twitter'] ) ): echo $instance['link_twitter']; endif; ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_instagram'); ?>"><?php esc_html_e( 'Instagram URL','bonkers' ); ?></label><br />
            <input type="text" name="<?php echo $this->get_field_name('link_instagram'); ?>" id="<?php echo $this->get_field_id('link_instagram'); ?>" value="<?php if( !empty( $instance['link_instagram'] ) ): echo $instance['link_instagram']; endif; ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_snapchat'); ?>"><?php esc_html_e( 'Snapchat URL','bonkers' ); ?></label><br />
            <input type="text" name="<?php echo $this->get_field_name('link_snapchat'); ?>" id="<?php echo $this->get_field_id('link_snapchat'); ?>" value="<?php if( !empty( $instance['link_snapchat'] ) ): echo $instance['link_snapchat']; endif; ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_google_plus'); ?>"><?php esc_html_e( 'Google Plus URL','bonkers' ); ?></label><br />
            <input type="text" name="<?php echo $this->get_field_name('link_google_plus'); ?>" id="<?php echo $this->get_field_id('link_google_plus'); ?>" value="<?php if( !empty( $instance['link_google_plus'] ) ): echo $instance['link_google_plus']; endif; ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_linkedin'); ?>"><?php esc_html_e( 'Linkedin URL','bonkers' ); ?></label><br />
            <input type="text" name="<?php echo $this->get_field_name('link_linkedin'); ?>" id="<?php echo $this->get_field_id('link_linkedin'); ?>" value="<?php if( !empty( $instance['link_linkedin'] ) ): echo $instance['link_linkedin']; endif; ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_github'); ?>"><?php esc_html_e( 'Github URL','bonkers' ); ?></label><br />
            <input type="text" name="<?php echo $this->get_field_name('link_github'); ?>" id="<?php echo $this->get_field_id('link_github'); ?>" value="<?php if( !empty( $instance['link_github'] ) ): echo $instance['link_github']; endif; ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_wordpress'); ?>"><?php esc_html_e( 'WordPress URL','bonkers' ); ?></label><br />
            <input type="text" name="<?php echo $this->get_field_name('link_wordpress'); ?>" id="<?php echo $this->get_field_id('link_wordpress'); ?>" value="<?php if( !empty( $instance['link_wordpress'] ) ): echo $instance['link_wordpress']; endif; ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_youtube'); ?>"><?php esc_html_e( 'YouTube URL','bonkers' ); ?></label><br />
            <input type="text" name="<?php echo $this->get_field_name('link_youtube'); ?>" id="<?php echo $this->get_field_id('link_youtube'); ?>" value="<?php if( !empty( $instance['link_youtube'] ) ): echo $instance['link_youtube']; endif; ?>" class="widefat" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link_vimeo'); ?>"><?php esc_html_e( 'Vimeo URL','bonkers' ); ?></label><br />
            <input type="text" name="<?php echo $this->get_field_name('link_vimeo'); ?>" id="<?php echo $this->get_field_id('link_vimeo'); ?>" value="<?php if( !empty( $instance['link_vimeo'] ) ): echo $instance['link_vimeo']; endif; ?>" class="widefat" />
        </p>

        



    <?php

    }

}


register_widget( 'Bonkers_Team_Member' );
