<?php
/**
 * Bonkers Addons
 *
 * @package   Bonkers_Addons
 * @author    Colorlib
 * @license   GPL-2.0+
 * @link      https://colorlib.com
 * @copyright 2017 Colorlib
 *
 * @wordpress-plugin
 * Plugin Name: Bonkers Addons
 * Plugin URI:  https://colorlib.com/wp/themes/bonkers/
 * Description: Addons for Bonkers theme.
 * Version:     1.0.1
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

function bonkers_addons_init() {

	$bonker_addons_current_theme = wp_get_theme();

	if ( 'Bonkers' == $bonker_addons_current_theme->get( 'Name' ) ) {

		require_once( BONKERS_ADDONS__PLUGIN_DIR . 'shortcodes/product-carousel.php' );

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function bonkers_addons_customize_register( $wp_customize ) {

			require_once( BONKERS_ADDONS__PLUGIN_DIR . 'custom-controls/class-bonkers-radio-image-control.php' );
			require_once( BONKERS_ADDONS__PLUGIN_DIR . 'custom-controls/class-bonkers-addons-display-text-control.php' );
			$wp_customize->register_control_type( 'Bonkers_Radio_Image_Control' );

			/*
            Get Sections order
            ------------------------------ */
			$sections_items = get_option( 'bonkers_addons_sortable_items' );
			$sections_sorted = array();

			if ( ! empty( $sections_items ) ) {

				foreach ( $sections_items as $key => $value ) {
					$sections_sorted[ $value ] = ( $key + 1 ) * 10;
				}
			} else {

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
			$wp_customize->add_panel(
				'bonkers_addons_front_page_sections', array(
					'title' => esc_attr__( 'Front Page Sections', 'bonkers-addons' ),
					'description' => esc_html__( 'Drag & Drop the sections to change order', 'bonkers-addons' ),
					'priority' => 160,
					'active_callback' => 'is_front_page',
				)
			);

				/*
                Welcome
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_welcome_section', array(
						'title' => esc_attr__( 'Welcome', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display a big image and welcome message.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_welcome_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_welcome_title', array(
						'type' => 'option',
						'default' => 'Every Great Company<br> Starts With An Idea',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);

			if ( class_exists( 'Epsilon_Control_Text_Editor' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Text_Editor(
						$wp_customize, 'bonkers_addons_welcome_title', array(
							'section' => 'bonkers_addons_welcome_section', // Required, core or custom.
						'label' => esc_attr__( 'Welcome Messag', 'bonkers-addons' ),
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_welcome_title', array(
						'type' => 'textarea',
						'section' => 'bonkers_addons_welcome_section', // Required, core or custom.
					'label' => esc_attr__( 'Welcome Messag', 'bonkers-addons' ),
					)
				);
			}
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_welcome_title', array(
						'selector' => '#bonkers-welcome-section .bonkers-intro-line',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_welcome_link_title', array(
						'type' => 'option',
						'default' => esc_html__( 'View More', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_welcome_link_title', array(
						'type' => 'text',
						'section' => 'bonkers_addons_welcome_section',
						'label' => esc_attr__( 'Link Title', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_welcome_link_title', array(
						'selector' => '#bonkers-welcome-section a.ql_border_btn',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_welcome_link_url', array(
						'type' => 'option',
						'default' => '#',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_url',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_welcome_link_url', array(
						'type' => 'url',
						'section' => 'bonkers_addons_welcome_section', // Required, core or custom.
					'label' => esc_attr__( 'Link URL', 'bonkers-addons' ),
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_welcome_image', array(
						'type' => 'option',
						'default' => esc_url( get_template_directory_uri() ) . '/images/StockSnap_1A3MXAT0M6.jpg',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_url',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Upload_Control(
						$wp_customize, 'bonkers_addons_welcome_image', array(
							'label'    => esc_attr__( 'Welcome Image', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_welcome_section',
						)
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_welcome_image', array(
						'selector' => '#bonkers-welcome-section',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_welcome_enable', array(
						'type' => 'option',
						'default' => 1,
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_welcome_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_welcome_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_welcome_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_welcome_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}

				/*
                Services
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_services_section', array(
						'title' => esc_attr__( 'Services', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display services with icons.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_services_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_services_enable', array(
						'type' => 'option',
						'default' => '1',
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_services_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_services_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_services_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_welcome_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_services_enable', array(
						'selector' => '#bonkers-services-section',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_services_text', array(
						'type' => 'option',
						'default' => '',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					new Bonkers_Addons_Display_Text_Control(
						$wp_customize, 'bonkers_addons_services_text', array(
							'section' => 'bonkers_addons_services_section', // Required, core or custom.
						 'description' => __( 'To add services go to: <br><a href="#" data-section="sidebar-widgets-services-section">Customize -> Widgets -> Front Page - Service Section</a>. <br>Then add the "<strong>Bonkers - Service widget</strong>"', 'bonkers-addons' ),
						)
					)
				);

				/*
                Image
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_image_section', array(
						'title' => esc_attr__( 'Image', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display an image and text.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_image_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_image_title', array(
						'type' => 'option',
						'default' => 'Start Growing your Business',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
			if ( class_exists( 'Epsilon_Control_Text_Editor' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Text_Editor(
						$wp_customize, 'bonkers_addons_image_title', array(
							'section' => 'bonkers_addons_image_section', // Required, core or custom.
						'label' => esc_attr__( 'Title', 'bonkers-addons' ),
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_image_title', array(
						'type' => 'textarea',
						'section' => 'bonkers_addons_image_section', // Required, core or custom.
					'label' => esc_attr__( 'Title', 'bonkers-addons' ),
					)
				);
			}
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_image_title', array(
						'selector' => '#bonkers-image-section .bonkers-image-section-title',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_image_text', array(
						'type' => 'option',
						'default' => '',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
			if ( class_exists( 'Epsilon_Control_Text_Editor' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Text_Editor(
						$wp_customize, 'bonkers_addons_image_text', array(
							'section' => 'bonkers_addons_image_section', // Required, core or custom.
						 'label' => esc_attr__( 'Text', 'bonkers-addons' ),
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_image_text', array(
						'type' => 'textarea',
						'section' => 'bonkers_addons_image_section', // Required, core or custom.
					'label' => esc_attr__( 'Text', 'bonkers-addons' ),
					)
				);
			}

								$wp_customize->selective_refresh->add_partial(
									'bonkers_addons_image_text', array(
										'selector' => '#bonkers-image-section .bonkers-image-section-content',
									)
								);

				$wp_customize->add_setting(
					'bonkers_addons_image_link_title', array(
						'type' => 'option',
						'default' => esc_html__( 'Learn More', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_image_link_title', array(
						'type' => 'text',
						'section' => 'bonkers_addons_image_section', // Required, core or custom.
					'label' => esc_attr__( 'Link Title', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_image_link_title', array(
						'selector' => '#bonkers-image-section a.ql_border_btn',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_image_link_url', array(
						'type' => 'option',
						'default' => '#',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_url',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_image_link_url', array(
						'type' => 'url',
						'section' => 'bonkers_addons_image_section', // Required, core or custom.
					'label' => esc_attr__( 'Link URL', 'bonkers-addons' ),
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_image_image', array(
						'type' => 'option',
						'default' => esc_url( get_template_directory_uri() ) . '/images/StockSnap_JBW2PXDOL6.jpg',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_url',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize, 'bonkers_addons_image_image', array(
							'label'    => esc_attr__( 'Image', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_image_section',
							'settings' => 'bonkers_addons_image_image',
						)
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_image_image', array(
						'selector' => '#bonkers-image-section .bonkers-image-section-image',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_image_layout', array(
						'type' => 'option',
						'sanitize_callback' => 'bonkers_saniteze_layout',
						'transport' => 'refresh',
						'default' => 'left',
					)
				);
				$wp_customize->add_control(
					new Bonkers_Radio_Image_Control(
						$wp_customize, 'bonkers_addons_image_layout', array(
							'section'     => 'bonkers_addons_image_section',
							'label'       => esc_attr__( 'Layout', 'bonkers-addons' ),
							'choices'     => array(
								'left' => BONKERS_ADDONS__PLUGIN_URL . '/images/image_layout_left.png',
								'right' => BONKERS_ADDONS__PLUGIN_URL . '/images/image_layout_right.png',
							),
						)
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_image_enable', array(
						'type' => 'option',
						'default' => '1',
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_image_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_image_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_image_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_image_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}

				/*
                Phone
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_phone_section', array(
						'title' => esc_attr__( 'Phone', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display phone screenshot with features.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_phone_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_phone_image', array(
						'type' => 'option',
						'default' => '',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_url',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize, 'bonkers_addons_phone_image', array(
							'label'    => esc_attr__( 'Image', 'bonkers-addons' ),
							'description' => esc_attr__( 'Recommended size: 640x1136px.', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_phone_section',
							'settings' => 'bonkers_addons_phone_image',
						)
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_phone_image', array(
						'selector' => '#bonkers-phone-section .bonkers-phone-screenshot',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_phone_text_left', array(
						'type' => 'option',
						'default' => '',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					new Bonkers_Addons_Display_Text_Control(
						$wp_customize, 'bonkers_addons_phone_text_left', array(
							'section' => 'bonkers_addons_phone_section', // Required, core or custom.
						 'description' => __( 'To add features go to: <br><a href="#" data-section="sidebar-widgets-phone-section-left">Customize -> Widgets -> Front Page - Phone Section Left</a>. <br>Then add the "<strong>Bonkers - Phone Feature</strong>"', 'bonkers-addons' ),
						)
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_phone_text_right', array(
						'type' => 'option',
						'default' => '',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					new Bonkers_Addons_Display_Text_Control(
						$wp_customize, 'bonkers_addons_phone_text_right', array(
							'section' => 'bonkers_addons_phone_section', // Required, core or custom.
						 'description' => __( 'To add features go to: <br><a href="#" data-section="sidebar-widgets-phone-section-right">Customize -> Widgets -> Front Page - Phone Section Right</a>. <br>Then add the "<strong>Bonkers - Phone Feature</strong>"', 'bonkers-addons' ),
						)
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_phone_color', array(
						'default' => '#f7f7f7',
						'transport' => 'postMessage',
						'sanitize_callback' => 'sanitize_hex_color',
						'type' => 'theme_mod',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Color_Control(
						$wp_customize, 'bonkers_addons_phone_color', array(
							'label'        => esc_attr__( 'Background Color', 'bonkers-addons' ),
							'section'    => 'bonkers_addons_phone_section',
						)
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_phone_enable', array(
						'type' => 'option',
						'default' => '1',
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_phone_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_phone_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_phone_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_phone_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}

				/*
                Call To Action
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_cta_section', array(
						'title' => esc_attr__( 'Call To Action', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display an image and text.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_cta_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_cta_title', array(
						'type' => 'option',
						'default' => esc_html__( 'Start Creating Beautiful Sites Now!', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
			if ( class_exists( 'Epsilon_Control_Text_Editor' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Text_Editor(
						$wp_customize, 'bonkers_addons_cta_title', array(
							'section' => 'bonkers_addons_cta_section', // Required, core or custom.
						'label' => esc_attr__( 'Title', 'bonkers-addons' ),
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_cta_title', array(
						'type' => 'textarea',
						'section' => 'bonkers_addons_cta_section', // Required, core or custom.
					'label' => esc_attr__( 'Title', 'bonkers-addons' ),
					)
				);
			}
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_cta_title', array(
						'selector' => '#bonkers-cta-section .bonkers-cta-title',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_cta_link_title', array(
						'type' => 'option',
						'default' => esc_html__( 'Sign Up', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_cta_link_title', array(
						'type' => 'text',
						'section' => 'bonkers_addons_cta_section', // Required, core or custom.
					'label' => esc_attr__( 'Link Title', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_cta_link_title', array(
						'selector' => '#bonkers-cta-section .bonkers-cta-button',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_cta_link_url', array(
						'type' => 'option',
						'default' => '#',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_url',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_cta_link_url', array(
						'type' => 'url',
						'section' => 'bonkers_addons_cta_section', // Required, core or custom.
					'label' => esc_attr__( 'Link URL', 'bonkers-addons' ),
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_cta_image', array(
						'type' => 'option',
						'default' => esc_url( get_template_directory_uri() ) . '/images/StockSnap_R7GVMRJWW9.jpg',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_url',
					)
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize, 'bonkers_addons_cta_image', array(
							'label'    => esc_attr__( 'Image', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_cta_section',
							'settings' => 'bonkers_addons_cta_image',
						)
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_cta_enable', array(
						'type' => 'option',
						'default' => '1',
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_cta_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_cta_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_cta_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_cta_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}

				/*
                Video
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_video_section', array(
						'title' => esc_attr__( 'Video', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display a video and text.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_video_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_video_title', array(
						'type' => 'option',
						'default' => esc_html__( 'Your success is our most important priority', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_video_title', array(
						'type' => 'text',
						'section' => 'bonkers_addons_video_section', // Required, core or custom.
					'label' => esc_attr__( 'Title', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_video_title', array(
						'selector' => '#bonkers-video-section .bonkers-video-title',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_video_text', array(
						'type' => 'option',
						'default' => '',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
			if ( class_exists( 'Epsilon_Control_Text_Editor' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Text_Editor(
						$wp_customize, 'bonkers_addons_video_text', array(
							'section' => 'bonkers_addons_video_section', // Required, core or custom.
						 'label' => esc_attr__( 'Text', 'bonkers-addons' ),
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_video_text', array(
						'type' => 'textarea',
						'section' => 'bonkers_addons_video_section', // Required, core or custom.
					'label' => esc_attr__( 'Text', 'bonkers-addons' ),
					)
				);
			}
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_video_text', array(
						'selector' => '#bonkers-video-section .bonkers-video-content',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_video_url', array(
						'type' => 'option',
						'default' => '',
						'sanitize_callback' => 'bonkers_addons_sanitize_url',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_video_url', array(
						'type' => 'text',
						'section' => 'bonkers_addons_video_section', // Required, core or custom.
					'label' => esc_attr__( 'Video URL', 'bonkers-addons' ),
					'description' => esc_attr__( 'Example: https://vimeo.com/72661448', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_video_url', array(
						'selector' => '#bonkers-video-section .bonkers-video-video',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_video_enable', array(
						'type' => 'option',
						'default' => '1',
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_video_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_video_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_video_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_video_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}

				/*
                Team
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_team_section', array(
						'title' => esc_attr__( 'Team', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display a team members.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_team_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_team_title', array(
						'type' => 'option',
						'default' => esc_html__( 'The Team', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_team_title', array(
						'type' => 'text',
						'section' => 'bonkers_addons_team_section', // Required, core or custom.
					'label' => esc_attr__( 'Title', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_team_title', array(
						'selector' => '#bonkers-team-section .bonkers-section-title',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_team_text', array(
						'type' => 'option',
						'default' => '',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					new Bonkers_Addons_Display_Text_Control(
						$wp_customize, 'bonkers_addons_team_text', array(
							'section' => 'bonkers_addons_team_section', // Required, core or custom.
						 'description' => __( 'To add a team member go to: <br><a href="#" data-section="sidebar-widgets-team-section">Customize -> Widgets -> Front Page - Team Section</a>. <br>Then add the "<strong>Bonkers - Team Member</strong>"', 'bonkers-addons' ),
						)
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_team_enable', array(
						'type' => 'option',
						'default' => '1',
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_team_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_team_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_team_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_team_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}

				/*
                Subscribe
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_subscribe_section', array(
						'title' => esc_attr__( 'Subscribe', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display a Subscribe form.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_subscribe_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_subscribe_title', array(
						'type' => 'option',
						'default' => esc_html__( 'Subscribe', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_subscribe_title', array(
						'type' => 'text',
						'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
					'label' => esc_attr__( 'Title', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_subscribe_title', array(
						'selector' => '#bonkers-subscribe-section .bonkers-subscribe-title',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_subscribe_text', array(
						'type' => 'option',
						'default' => '',
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
			if ( class_exists( 'Epsilon_Control_Text_Editor' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Text_Editor(
						$wp_customize, 'bonkers_addons_subscribe_text', array(
							'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
						 'label' => esc_attr__( 'Text', 'bonkers-addons' ),
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_subscribe_text', array(
						'type' => 'textarea',
						'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
					'label' => esc_attr__( 'Text', 'bonkers-addons' ),
					)
				);
			}
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_subscribe_text', array(
						'selector' => '#bonkers-subscribe-section .bonkers-subscribe-text',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_subscribe_link_title', array(
						'type' => 'option',
						'default' => esc_html__( 'Subscribe', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_subscribe_link_title', array(
						'type' => 'text',
						'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
					'label' => esc_attr__( 'Button Text', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_subscribe_link_title', array(
						'selector' => '#bonkers-subscribe-section .contact-submit',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_subscribe_link_placeholder', array(
						'type' => 'option',
						'default' => esc_html__( 'Enter your email...', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_subscribe_link_placeholder', array(
						'type' => 'text',
						'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
					'label' => esc_attr__( 'Placeholder', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_subscribe_link_placeholder', array(
						'selector' => '#bonkers-subscribe-section .contact-form > div',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_subscribe_mailchimp_link', array(
						'type' => 'option',
						'transport' => 'refresh',
						'sanitize_callback' => 'esc_url_raw',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_subscribe_mailchimp_link', array(
						'type' => 'text',
						'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
					'label' => esc_attr__( 'MailChimp Signup form URL', 'bonkers-addons' ),
					'description' => sprintf(
						esc_html__( 'In order to get this link please follow the tutorial from %s until step 4', 'bonkers-addons' ),
						'<a href="https://shopify.barrelny.com/where-do-i-find-the-mailchimp-signup-url/" target="_blank">' . esc_html__( 'here', 'bonkers-addons' ) . '</a>'
					),
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_subscribe_enable', array(
						'type' => 'option',
						'default' => '1',
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_subscribe_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_subscribe_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_subscribe_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_subscribe_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}

				/*
                Clients
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_clients_section', array(
						'title' => esc_attr__( 'Clients', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display a clients logos.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_clients_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_clients_title', array(
						'type' => 'option',
						'default' => esc_html__( 'The Clients', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_clients_title', array(
						'type' => 'text',
						'section' => 'bonkers_addons_clients_section', // Required, core or custom.
					'label' => esc_attr__( 'Title', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_clients_title', array(
						'selector' => '#bonkers-clients-section .bonkers-section-title',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_clients_text', array(
						'type' => 'option',
						'default' => '',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					new Bonkers_Addons_Display_Text_Control(
						$wp_customize, 'bonkers_addons_clients_text', array(
							'section' => 'bonkers_addons_clients_section', // Required, core or custom.
						 'description' => __( 'To add a client logo go to: <br><a href="#" data-section="sidebar-widgets-clients-section">Customize -> Widgets -> Front Page - Clients Section</a>. <br>Then add the "<strong>Bonkers - Client Logo</strong>"', 'bonkers-addons' ),
						)
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_clients_enable', array(
						'type' => 'option',
						'default' => '1',
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_clients_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_clients_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_clients_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_clients_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}

				/*
                Contact
                ------------------------------ */
				$wp_customize->add_section(
					'bonkers_addons_contact_section', array(
						'title' => esc_attr__( 'Contact', 'bonkers-addons' ),
						'description' => esc_attr__( 'Display a map and contact form.', 'bonkers-addons' ),
						'panel' => 'bonkers_addons_front_page_sections',
						'priority' => $sections_sorted['bonkers_addons_contact_section'],
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_contact_title', array(
						'type' => 'option',
						'default' => esc_html__( 'Contact', 'bonkers-addons' ),
						'transport' => 'postMessage',
						'sanitize_callback' => 'bonkers_addons_sanitize_text_html',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_contact_title', array(
						'type' => 'text',
						'section' => 'bonkers_addons_contact_section', // Required, core or custom.
					'label' => esc_attr__( 'Title', 'bonkers-addons' ),
					)
				);
				$wp_customize->selective_refresh->add_partial(
					'bonkers_addons_contact_title', array(
						'selector' => '#bonkers-contact-section .bonkers-contact-title',
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_contact_key', array(
						'type' => 'option',
						'default' => '',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_contact_key', array(
						'type' => 'text',
						'section' => 'bonkers_addons_contact_section', // Required, core or custom.
					'label' => esc_attr__( 'Google Maps API Key', 'bonkers-addons' ),
					'description' => sprintf( __( "An API Key is required for Google Maps to work. <a href='%s' target='_blank'>Sign up for one here</a> (it's free for small usage)", 'bonkers-addons' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' ),
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_contact_address', array(
						'type' => 'option',
						'default' => 'Central Park, New York, NY, United States',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					'bonkers_addons_contact_address', array(
						'type' => 'text',
						'section' => 'bonkers_addons_contact_section', // Required, core or custom.
					'label' => esc_attr__( 'Address', 'bonkers-addons' ),
					)
				);

				$wp_customize->add_setting(
					'bonkers_addons_contact_zoom', array(
						'type' => 'option',
						'default' => '13',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
				$wp_customize->add_control(
					new Epsilon_Control_Slider(
						$wp_customize, 'bonkers_addons_contact_zoom', array(
							'type' => 'epsilon-slider',
							'label' => esc_attr__( 'Zoom', 'bonkers-addons' ),
							'choices' => array(
								'min'  => 1,
								'max'  => 20,
								'step' => 1,
							),
							'section'     => 'bonkers_addons_contact_section',
						)
					)
				);

			if ( ! Bonkers_Helper::has_plugin( 'kali-forms' ) ) {
				$wp_customize->add_setting(
					'bonkers_addons_install_kali-forms', array(
						'type' => 'option',
						'default' => '',
						'sanitize_callback' => 'bonkers_addons_sanitize_text',
					)
				);
				$wp_customize->add_control(
					new Bonkers_Addons_Display_Text_Control(
						$wp_customize, 'bonkers_addons_install_kali-forms', array(
							'section' => 'bonkers_addons_contact_section', // Required, core or custom.
						 'label' => esc_attr__( 'Contact Form', 'bonkers-addons' ),
						 'description' => esc_attr__( 'Please install Kaliforms in order to add a contact form to this section', 'bonkers-addons' ),
						)
					)
				);
			} else {

				$bonkers_addons_form_args = array(
					'post_type' => 'kaliforms_forms',
					'posts_per_page' => -1,
				);
				$bonkers_addons_form_list = get_posts( $bonkers_addons_form_args );
				$bonkers_addons_forms = wp_list_pluck( $bonkers_addons_form_list , 'post_title', 'ID' );

				if ( empty( $bonkers_addons_forms ) ) {
					$wp_customize->add_setting(
						'bonkers_addons_create_form', array(
							'type' => 'option',
							'default' => '',
							'sanitize_callback' => 'bonkers_addons_sanitize_text',
						)
					);
					$wp_customize->add_control(
						new Bonkers_Addons_Display_Text_Control(
							$wp_customize, 'bonkers_addons_create_form', array(
								'section' => 'bonkers_addons_contact_section', // Required, core or custom.
							 'label' => esc_attr__( 'Contact Form', 'bonkers-addons' ),
							 'description' => esc_attr__( 'Please go and create a form in order to have something to show in this section', 'bonkers-addons' ),
							)
						)
					);
				} else {
					$bonkers_addons_forms[0] = esc_attr__( 'Select Form', 'bonkers-addons' );
					$wp_customize->add_setting(
						'bonkers_addons_contact_form', array(
							'type' => 'option',
							'default' => '0',
							'sanitize_callback' => 'bonkers_addons_sanitize_text',
						)
					);
					$wp_customize->add_control(
						'bonkers_addons_contact_form', array(
							'type' => 'select',
							'section' => 'bonkers_addons_contact_section', // Required, core or custom.
						'choices'     => $bonkers_addons_forms,
						'label' => esc_attr__( 'Contact Form', 'bonkers-addons' ),
						'description' => esc_attr__( 'Forms are obtained from the Kaliforms plugin.', 'bonkers-addons' ),
						)
					);
					$wp_customize->selective_refresh->add_partial(
						'bonkers_addons_contact_form', array(
							'selector' => '#bonkers-contact-section .bonkers-contact-form',
						)
					);
				}
			}// End if().

				$wp_customize->add_setting(
					'bonkers_addons_contact_enable', array(
						'type' => 'option',
						'default' => '1',
						'transport' => 'refresh',
						'sanitize_callback' => 'bonkers_addons_sanitize_integer',
					)
				);
			if ( class_exists( 'Epsilon_Control_Toggle' ) ) {
				$wp_customize->add_control(
					new Epsilon_Control_Toggle(
						$wp_customize, 'bonkers_addons_contact_enable', array(
							'label'    => esc_attr__( 'Use this section?', 'bonkers-addons' ),
							'section'  => 'bonkers_addons_contact_section',
						)
					)
				);
			} else {
				$wp_customize->add_control(
					'bonkers_addons_contact_enable', array(
						'type' => 'checkbox',
						'section' => 'bonkers_addons_contact_section', // Required, core or custom.
					'label' => esc_attr__( 'Use this section?', 'bonkers-addons' ),
					)
				);
			}

		}
		add_action( 'customize_register', 'bonkers_addons_customize_register' );

		/**
		 * Register widgets.
		 *
		 * @link https://codex.wordpress.org/Widgets_API
		 */
		function bonkers_addons_widgets_register() {

			require BONKERS_ADDONS__PLUGIN_DIR . 'widgets/class-bonkers-contact-info.php';
			require BONKERS_ADDONS__PLUGIN_DIR . 'widgets/class-bonkers-service.php';
			require BONKERS_ADDONS__PLUGIN_DIR . 'widgets/class-bonkers-phone-feature.php';
			require BONKERS_ADDONS__PLUGIN_DIR . 'widgets/class-bonkers-team-member.php';
			require BONKERS_ADDONS__PLUGIN_DIR . 'widgets/class-bonkers-client-logo.php';

		}
		add_action( 'widgets_init', 'bonkers_addons_widgets_register' );

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
			return (bool) $string;
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
		 * Sanitize layout control
		 */
		function bonkers_saniteze_layout( $layout ) {
			if ( in_array( $layout, array( 'left', 'right' ) ) ) {
				return $layout;
			}
			return 'left';
		}

		function bonkers_saniteze_sortable_sections( $sections ) {

			$allowed_sections = array(
				'bonkers_addons_welcome_section',
				'bonkers_addons_services_section',
				'bonkers_addons_image_section',
				'bonkers_addons_phone_section',
				'bonkers_addons_cta_section',
				'bonkers_addons_video_section',
				'bonkers_addons_team_section',
				'bonkers_addons_subscribe_section',
				'bonkers_addons_clients_section',
				'bonkers_addons_contact_section',
			);

			foreach ( $sections as $key => $section ) {
				if ( ! in_array( $section, $allowed_sections ) ) {
					unset( $sections[ $key ] );
				}
			}

			return $sections;

		}

		/*
        * AJAX call to save the order for Front Page Sections
        */
		add_action( 'wp_ajax_nopriv_bonkers_addons_save_sortable', 'bonkers_addons_save_sortable' );
		add_action( 'wp_ajax_bonkers_addons_save_sortable', 'bonkers_addons_save_sortable' );

		function bonkers_addons_save_sortable() {
			$items = $_POST['items'];
			if ( is_array( $items ) ) {
				update_option( 'bonkers_addons_sortable_items', bonkers_saniteze_sortable_sections( $items ) );
				wp_send_json_success();
			} else {
				wp_send_json_error();
			}
			die();
		}

		function bonkers_addons_customize_js() {

			wp_enqueue_style( 'bonkers_addons_customizer', BONKERS_ADDONS__PLUGIN_URL . '/assets/css/customizer.css', array(), '1.0', 'all' );
			wp_enqueue_script( 'bonkers_addons_customizer', BONKERS_ADDONS__PLUGIN_URL . '/assets/js/customizer.js', array( 'customize-controls', 'jquery' ), '1.0', true );

		}
		add_action( 'customize_controls_enqueue_scripts', 'bonkers_addons_customize_js' );

		// Validate option returned for toggle controler
		$bonkers_addons_options = array(
			'bonkers_addons_welcome_enable',
			'bonkers_addons_clients_enable',
			'bonkers_addons_services_enable',
			'bonkers_addons_image_enable',
			'bonkers_addons_phone_enable',
			'bonkers_addons_cta_enable',
			'bonkers_addons_video_enable',
			'bonkers_addons_team_enable',
			'bonkers_addons_subscribe_enable',
			'bonkers_addons_contact_enable',
		);

		foreach ( $bonkers_addons_options as $bonkers_addons_option ) {
			add_filter( "option_{$bonkers_addons_option}", 'bonkers_addons_sanitize_integer', 99, 1 );
		}
	} else {

		add_action( 'admin_notices', 'bonkers_addons_shownotice' );
		function bonkers_addons_shownotice() {
			$bonkers_addons_plugin_data = get_plugin_data( __FILE__ );
			echo '
            <div class="updated">
              <p>' . sprintf( __( '<strong>%s</strong> recommends <strong><a href="https://colorlib.com" target="_blank">Bonkers theme</a></strong>.', 'bonkers-addons' ), $bonkers_addons_plugin_data['Name'] ) . '</p>
            </div>';
		}
	}// End if().
}
add_action( 'plugins_loaded', 'bonkers_addons_init' );
