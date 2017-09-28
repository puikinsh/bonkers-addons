<?php
/**
 * Bonkers Addons
 *
 * @package   Bonkers_Addons
 * @author    Quema Labs
 * @license   GPL-2.0+
 * @link      https://colorlib.com
 * @copyright 2017 Quema Labs
 *
 * @wordpress-plugin
 * Plugin Name: Bonkers Addons
 * Plugin URI:  https://colorlib.com
 * Description: Addons for Bonkers theme.
 * Version:     1.0.0
 * Author:      Colorlib
 * Author URI:  https://colorlib.com
 * Text Domain: bonkers-addons
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BONKERS_ADDONS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BONKERS_ADDONS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

function bonkers_addons_init(){

    $bonker_addons_current_theme = wp_get_theme();

    if ( 'Bonkers' == $bonker_addons_current_theme->get( 'Name' ) ) {

        require_once( BONKERS_ADDONS__PLUGIN_DIR . 'shortcodes/product-carousel.php' );


        /**
         * Add postMessage support for site title and description for the Theme Customizer.
         *
         * @param WP_Customize_Manager $wp_customize Theme Customizer object.
         */
        function bonkers_addons_customize_register( $wp_customize ) {

            /*
            Get Sections order
            ------------------------------ */
            $sections_items = get_option( 'bonkers_addons_sortable_items' );
            $sections_sorted = array();

            if ( ! empty( $sections_items ) ) {

                foreach ( $sections_items as $key => $value ) {
                    $sections_sorted[$value] = ( $key + 1 ) * 10;
                }

            }else{

                //Default order
                $sections_sorted['bonkers_addons_welcome_section'] = 10;
                $sections_sorted['bonkers_addons_services_section'] = 20;
                $sections_sorted['bonkers_addons_image_section'] = 30;
                $sections_sorted['bonkers_addons_phone_section'] = 40;
                $sections_sorted['bonkers_addons_cta_section'] = 50;
                $sections_sorted['bonkers_addons_video_section'] = 60;
                $sections_sorted['bonkers_addons_team_section'] = 70;
                $sections_sorted['bonkers_addons_subscribe_section'] = 80;
                $sections_sorted['bonkers_addons_clients_section'] = 90;
                $sections_sorted['bonkers_addons_contact_section'] = 100;

            }


            /*
            Sections
            ===================================================== */
            $wp_customize->add_panel( 'bonkers_addons_front_page_sections', array(
                'title' => esc_attr__( 'Front Page Sections', 'bonkers-addons' ),
                'description' => '', // Include html tags such as <p>.
                'priority' => 160,
                'active_callback' => 'is_front_page',
            ) );

                /*
                Welcome
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_welcome_section', array(
                    'title' => esc_attr__( 'Welcome', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display a big image and welcome message.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_welcome_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_welcome_title', array( 'type' => 'option', 'default' => 'Every Great Company<br> Starts With An Idea', 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_welcome_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_welcome_section', // Required, core or custom.
                    'label' => esc_attr__( 'Welcome Message', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_welcome_link_title', array( 'type' => 'option', 'default' => esc_html__( 'View More', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( 'bonkers_addons_welcome_link_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_welcome_section', // Required, core or custom.
                    'label' => esc_attr__( "Link Title", 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_welcome_link_url', array( 'type' => 'option', 'default' => '#', 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_url', ) );
                $wp_customize->add_control( 'bonkers_addons_welcome_link_url', array(
                    'type' => 'url',
                    'section' => 'bonkers_addons_welcome_section', // Required, core or custom.
                    'label' => esc_attr__( "Link URL", 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_welcome_image', array( 'type' => 'option', 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'attachment_url_to_postid', ) );
                $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bonkers_addons_welcome_image', array(
                    'label'    => esc_attr__( 'Welcome Image', 'bonkers-addons' ),
                    'section'  => 'bonkers_addons_welcome_section',
                    'settings' => 'bonkers_addons_welcome_image',
                ) ) );

                /*
                Services
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_services_section', array(
                    'title' => esc_attr__( 'Services', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display services with icons.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_services_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_services_text', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( new bonkers_addons_Display_Text_Control( $wp_customize, 'bonkers_addons_services_text', array(
                    'section' => 'bonkers_addons_services_section', // Required, core or custom.
                    'label' => __( 'To add services go to: <br><a href="#" data-section="sidebar-widgets-services-section">Customize -> Widgets -> Front Page - Service Section</a>. <br>Then add the "<strong>Bonkers - Service widget</strong>"', 'bonkers-addons' ),
                ) ) );


                /*
                Image
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_image_section', array(
                    'title' => esc_attr__( 'Image', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display an image and text.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_image_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_image_title', array( 'type' => 'option', 'default' => 'Start Growing your Business', 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_image_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_image_section', // Required, core or custom.
                    'label' => esc_attr__( 'Title', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_image_text', array( 'type' => 'option', 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_image_text', array(
                    'type' => 'textarea',
                    'section' => 'bonkers_addons_image_section', // Required, core or custom.
                    'label' => esc_attr__( 'Text', 'bonkers-addons' ),
                    //'description' => esc_attr__( '', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_image_link_title', array( 'type' => 'option', 'default' => esc_html__( 'Learn More', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( 'bonkers_addons_image_link_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_services_section', // Required, core or custom.
                    'label' => esc_attr__( "Link Title", 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_image_link_url', array( 'type' => 'option', 'default' => '#', 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_url', ) );
                $wp_customize->add_control( 'bonkers_addons_image_link_url', array(
                    'type' => 'url',
                    'section' => 'bonkers_addons_services_section', // Required, core or custom.
                    'label' => esc_attr__( "Link URL", 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_image_image', array( 'type' => 'option', 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'attachment_url_to_postid', ) );
                $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bonkers_addons_image_image', array(
                    'label'    => esc_attr__( 'Image', 'bonkers-addons' ),
                    'section'  => 'bonkers_addons_image_section',
                    'settings' => 'bonkers_addons_image_image',
                ) ) );

                /*
                Phone
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_phone_section', array(
                    'title' => esc_attr__( 'Phone', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display phone screenshot with features.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_phone_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_phone_image', array( 'type' => 'option', 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'attachment_url_to_postid', ) );
                $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bonkers_addons_phone_image', array(
                    'label'    => esc_attr__( 'Image', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Recommended size: 640x1136px.', 'bonkers-addons' ),
                    'section'  => 'bonkers_addons_phone_section',
                    'settings' => 'bonkers_addons_phone_image',
                ) ) );

                $wp_customize->add_setting( 'bonkers_addons_phone_text_left', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( new bonkers_addons_Display_Text_Control( $wp_customize, 'bonkers_addons_phone_text_left', array(
                    'section' => 'bonkers_addons_phone_section', // Required, core or custom.
                    'label' => __( 'To add features go to: <br><a href="#" data-section="sidebar-widgets-phone-section-left">Customize -> Widgets -> Front Page - Phone Section Left</a>. <br>Then add the "<strong>Bonkers - Phone Feature</strong>"', 'bonkers-addons' ),
                ) ) );

                $wp_customize->add_setting( 'bonkers_addons_phone_text_right', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( new bonkers_addons_Display_Text_Control( $wp_customize, 'bonkers_addons_phone_text_right', array(
                    'section' => 'bonkers_addons_phone_section', // Required, core or custom.
                    'label' => __( 'To add features go to: <br><a href="#" data-section="sidebar-widgets-phone-section-right">Customize -> Widgets -> Front Page - Phone Section Right</a>. <br>Then add the "<strong>Bonkers - Phone Feature</strong>"', 'bonkers-addons' ),
                ) ) );

                $wp_customize->add_setting( 'bonkers_addons_phone_color', array( 'default' => '#f7f7f7', 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color', 'type' => 'theme_mod' ) );
                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bonkers_addons_phone_color', array(
                    'label'        => esc_attr__( 'Background Color', 'bonkers-addons' ),
                    'section'    => 'bonkers_addons_phone_section',
                ) ) );

                /*
                Call To Action
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_cta_section', array(
                    'title' => esc_attr__( 'Call To Action', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display an image and text.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_cta_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_cta_title', array( 'type' => 'option', 'default' => esc_html__( 'Start Creating Beautiful Sites Now!', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_cta_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_cta_section', // Required, core or custom.
                    'label' => esc_attr__( 'Title', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_cta_link_title', array( 'type' => 'option', 'default' => esc_html__( 'Sign Up', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( 'bonkers_addons_cta_link_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_cta_section', // Required, core or custom.
                    'label' => esc_attr__( "Link Title", 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_cta_link_url', array( 'type' => 'option', 'default' => '#', 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_url', ) );
                $wp_customize->add_control( 'bonkers_addons_cta_link_url', array(
                    'type' => 'url',
                    'section' => 'bonkers_addons_cta_section', // Required, core or custom.
                    'label' => esc_attr__( "Link URL", 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_cta_image', array( 'type' => 'option', 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'attachment_url_to_postid', ) );
                $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bonkers_addons_cta_image', array(
                    'label'    => esc_attr__( 'Image', 'bonkers-addons' ),
                    'section'  => 'bonkers_addons_cta_section',
                    'settings' => 'bonkers_addons_cta_image',
                ) ) );

                /*
                Video
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_video_section', array(
                    'title' => esc_attr__( 'Video', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display a video and text.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_video_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_video_title', array( 'type' => 'option', 'default' => esc_html__( 'Your success is our most important priority', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_video_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_video_section', // Required, core or custom.
                    'label' => esc_attr__( 'Title', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_video_text', array( 'type' => 'option', 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_video_text', array(
                    'type' => 'textarea',
                    'section' => 'bonkers_addons_video_section', // Required, core or custom.
                    'label' => esc_attr__( 'Text', 'bonkers-addons' ),
                    //'description' => esc_attr__( '', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_video_url', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'bonkers_addons_sanitize_url', ) );
                $wp_customize->add_control( 'bonkers_addons_video_url', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_video_section', // Required, core or custom.
                    'label' => esc_attr__( 'Video URL', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Example: https://vimeo.com/72661448', 'bonkers-addons' ),
                ) );

                /*
                Team
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_team_section', array(
                    'title' => esc_attr__( 'Team', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display a team members.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_team_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_team_title', array( 'type' => 'option', 'default' => esc_html__( 'The Team', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_team_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_team_section', // Required, core or custom.
                    'label' => esc_attr__( 'Title', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_team_text', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( new bonkers_addons_Display_Text_Control( $wp_customize, 'bonkers_addons_team_text', array(
                    'section' => 'bonkers_addons_team_section', // Required, core or custom.
                    'label' => __( 'To add a team member go to: <br><a href="#" data-section="sidebar-widgets-team-section">Customize -> Widgets -> Front Page - Team Section</a>. <br>Then add the "<strong>Bonkers - Team Member</strong>"', 'bonkers-addons' ),
                ) ) );

                /*
                Subscribe
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_subscribe_section', array(
                    'title' => esc_attr__( 'Subscribe', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display a Subscribe form.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_subscribe_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_subscribe_title', array( 'type' => 'option', 'default' => esc_html__( 'Subscribe', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_subscribe_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
                    'label' => esc_attr__( 'Title', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_subscribe_text', array( 'type' => 'option', 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_subscribe_text', array(
                    'type' => 'textarea',
                    'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
                    'label' => esc_attr__( 'Text', 'bonkers-addons' ),
                    //'description' => esc_attr__( '', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_subscribe_link_title', array( 'type' => 'option', 'default' => esc_html__( 'Subscribe', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( 'bonkers_addons_subscribe_link_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
                    'label' => esc_attr__( "Button Text", 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_subscribe_link_placeholder', array( 'type' => 'option', 'default' => esc_html__( 'Enter your email...', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( 'bonkers_addons_subscribe_link_placeholder', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
                    'label' => esc_attr__( "Placeholder", 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_subscribe_expl', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( new bonkers_addons_Display_Text_Control( $wp_customize, 'bonkers_addons_subscribe_expl', array(
                    'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
                    'label' => __( 'Make sure that you have Jetpack plugin installed and you can find your subscribers on your Admin Panel > Feedback', 'bonkers-addons' ),
                ) ) );

                /*
                Clients
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_clients_section', array(
                    'title' => esc_attr__( 'Clients', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display a clients logos.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_clients_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_clients_title', array( 'type' => 'option', 'default' => esc_html__( 'The Clients', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_clients_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_clients_section', // Required, core or custom.
                    'label' => esc_attr__( 'Title', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_clients_text', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( new bonkers_addons_Display_Text_Control( $wp_customize, 'bonkers_addons_clients_text', array(
                    'section' => 'bonkers_addons_clients_section', // Required, core or custom.
                    'label' => __( 'To add a client logo go to: <br><a href="#" data-section="sidebar-widgets-clients-section">Customize -> Widgets -> Front Page - Clients Section</a>. <br>Then add the "<strong>Bonkers - Client Logo</strong>"', 'bonkers-addons' ),
                ) ) );

                /*
                Contact
                ------------------------------ */
                $wp_customize->add_section( 'bonkers_addons_contact_section', array(
                    'title' => esc_attr__( 'Contact', 'bonkers-addons' ),
                    'description' => esc_attr__( 'Display a map and contact form.', 'bonkers-addons' ),
                    'panel' => 'bonkers_addons_front_page_sections',
                    'priority' => $sections_sorted['bonkers_addons_contact_section'],
                ) );

                $wp_customize->add_setting( 'bonkers_addons_contact_title', array( 'type' => 'option', 'default' => esc_html__( 'Contact', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text_html', ) );
                $wp_customize->add_control( 'bonkers_addons_contact_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_contact_section', // Required, core or custom.
                    'label' => esc_attr__( 'Title', 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_contact_key', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( 'bonkers_addons_contact_key', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_contact_section', // Required, core or custom.
                    'label' => esc_attr__( "Google Maps API Key", 'bonkers-addons' ),
                    'description' => sprintf( __("An API Key is required for Google Maps to work. <a href='%s'>Sign up for one here</a> (it's free for small usage)", 'bonkers-addons'), 'https://developers.google.com/maps/documentation/javascript/get-api-key' )
                ) );

                $wp_customize->add_setting( 'bonkers_addons_contact_lat_long', array( 'type' => 'option', 'default' => '40.725987, -74.002447', 'sanitize_callback' => 'bonkers_addons_sanitize_lat_long', ) );
                $wp_customize->add_control( 'bonkers_addons_contact_lat_long', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_contact_section', // Required, core or custom.
                    'label' => esc_attr__( "Latitude and Longitude", 'bonkers-addons' ),
                    'description' => esc_attr__( 'Comma separated. Example: 40.725987, -74.002447', 'bonkers-addons' )
                ) );

                $wp_customize->add_setting( 'bonkers_addons_contact_zoom', array( 'type' => 'option', 'default' => '13', 'sanitize_callback' => 'bonkers_addons_sanitize_integer', ) );
                $wp_customize->add_control( 'bonkers_addons_contact_zoom', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_contact_section', // Required, core or custom.
                    'label' => esc_attr__( "Zoom", 'bonkers-addons' ),
                ) );

                $wp_customize->add_setting( 'bonkers_addons_contact_link_title', array( 'type' => 'option', 'default' => esc_html__( 'Send', 'bonkers-addons' ), 'transport' => 'postMessage', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( 'bonkers_addons_contact_link_title', array(
                    'type' => 'text',
                    'section' => 'bonkers_addons_contact_section', // Required, core or custom.
                    'label' => esc_attr__( "Button Text", 'bonkers-addons' ),
                ) );

                $bonkers_addons_form_args = array( 'post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1 );
                $bonkers_addons_form_list = get_posts( $bonkers_addons_form_args );
                $bonkers_addons_forms = wp_list_pluck( $bonkers_addons_form_list , 'post_title', 'ID' );

                $wp_customize->add_setting( 'bonkers_addons_contact_form', array( 'type' => 'option', 'default' => '', 'sanitize_callback' => 'bonkers_addons_sanitize_text', ) );
                $wp_customize->add_control( 'bonkers_addons_contact_form', array(
                    'type' => 'select',
                    'section' => 'bonkers_addons_contact_section', // Required, core or custom.
                    'choices'     => $bonkers_addons_forms,
                    'label' => esc_attr__( "Select Form", 'bonkers-addons' ),
                    'description' => esc_attr__( 'Forms are obtained from the Contact Form 7 plugin.', 'bonkers-addons' ),
                ) );

        }
        add_action( 'customize_register', 'bonkers_addons_customize_register' );

        function bonkers_addons_kirki_register(){

            if ( ! class_exists( 'Kirki' ) ) {
                return;
            }

            /*
            Welcome
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_welcome_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_welcome_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_welcome_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_welcome_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );

            /*
            Services
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_services_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_services_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_services_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_services_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );

            /*
            Image
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_image_layout', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_image_layout', array(
                'type'        => 'radio-image',
                'settings'    => 'bonkers_addons_image_layout',
                'label'       => esc_attr__( 'Layout', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_image_section',
                'default'     => 'left',
                'choices'     => array(
                    'left' => BONKERS_ADDONS__PLUGIN_URL . '/images/image_layout_left.png',
                    'right' => BONKERS_ADDONS__PLUGIN_URL . '/images/image_layout_right.png',
                ),
            ) );

            Kirki::add_config( 'bonkers_addons_image_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_image_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_image_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_image_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );

            /*
            Phone
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_phone_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_phone_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_phone_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_phone_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );

            /*
            Call To Action
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_cta_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_cta_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_cta_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_cta_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );

            /*
            Video
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_video_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_video_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_video_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_video_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );

            /*
            Team
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_team_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_team_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_team_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_team_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );

            /*
            Subscribe
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_subscribe_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_subscribe_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_subscribe_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_subscribe_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );

            /*
            Clients
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_clients_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_clients_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_clients_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_clients_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );
            

            /*
            Contact
            ------------------------------ */
            Kirki::add_config( 'bonkers_addons_contact_enable', array(
                'capability'    => 'edit_theme_options',
                'option_type'   => 'option',
                'option_name'   => 'bonkers_addons',
            ) );
            Kirki::add_field( 'bonkers_addons_contact_enable', array(
                'type'        => 'switch',
                'transport'   => 'postMessage',
                'settings'    => 'bonkers_addons_contact_enable',
                'label'       => esc_html__( 'Use this section?', 'bonkers-addons' ),
                'section'     => 'bonkers_addons_contact_section',
                'default'     => '1',
                'priority'    => 60,
                'choices'     => array(
                    'on'  => esc_attr__( 'On', 'bonkers-addons' ),
                    'off' => esc_attr__( 'Off', 'bonkers-addons' ),
                ),
            ) );

        }
        add_action( 'init', 'bonkers_addons_kirki_register', 11 );

        /**
         * Sanitize Text
         */
        function bonkers_addons_sanitize_text( $str ) {
            return sanitize_text_field( $str );
        }

        /**
         * Sanitize Boolean
         */
        function bonkers_addons_sanitize_bool( $string ) {
            return (bool)$string;
        }

        /**
         * Sanitize URL
         */
        function bonkers_addons_sanitize_url( $url ) {
            return esc_url( $url );
        }

        /**
         * Sanitize Text with html
         */
        function bonkers_addons_sanitize_text_html( $str ) {
            return wp_kses_post( $str );
        }

        /**
         * Sanitize return an non-negative Integer
         */
        function bonkers_addons_sanitize_integer( $value ) {
            return absint( $value );
        }

        /**
         * Sanitize GPS Latitude and Longitud
         * http://stackoverflow.com/a/22007205
         */
        function bonkers_addons_sanitize_lat_long( $coords ) {
            if ( preg_match( '/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $coords ) ) {
                return $coords;
            } else {
                return '';
            }
        }


        /**
         * Display Text Control
         * Custom Control to display text
         */
        if ( class_exists( 'WP_Customize_Control' ) ) {
            class bonkers_addons_Display_Text_Control extends WP_Customize_Control {
                /**
                * Render the control's content.
                */
                public function render_content() {

                    $wp_kses_args = array(
                        'a' => array(
                            'href' => array(),
                            'title' => array(),
                            'data-section' => array(),
                        ),
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        'span' => array(),
                    );
                    $label = wp_kses( $this->label, $wp_kses_args );
                    ?>
                    <p><?php echo $label; ?></p>
                <?php
                }
            }
        }


        /*
        * AJAX call to save the order for Front Page Sections
        */
        add_action( 'wp_ajax_nopriv_bonkers_addons_save_sortable', 'bonkers_addons_save_sortable' );
        add_action( 'wp_ajax_bonkers_addons_save_sortable', 'bonkers_addons_save_sortable' );

        function bonkers_addons_save_sortable() {
            $items = $_POST['items'];
            if ( is_array( $items ) ) {
                update_option( 'bonkers_addons_sortable_items', $items );
                wp_send_json_success();
            }else{
                wp_send_json_error();
            }
            die();
        }



    }else{

        add_action( 'admin_notices', 'bonkers_addons_shownotice' );
        function bonkers_addons_shownotice() {
            $bonkers_addons_plugin_data = get_plugin_data( __FILE__ );
            echo '
            <div class="updated">
              <p>' . sprintf( __( '<strong>%s</strong> recommends <strong><a href="https://colorlib.com" target="_blank">Bonkers theme</a></strong>.', 'bonkers-addons' ), $bonkers_addons_plugin_data['Name'] ) . '</p>
            </div>';
        }

    }
}
add_action( 'plugins_loaded', 'bonkers_addons_init' );
