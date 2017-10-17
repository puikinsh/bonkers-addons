<?php
/**
* Function for Adding Colorlib Products Slider Component on vc_init hook
*
* @param void
*
* @return void
*/
function bonkers_addons_component_instagram_box() {

	$display_options = array( 'best-selling' );
	$terms = get_terms(
		array(
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
		)
	);
	foreach ( $terms as $term_object ) {
		$display_options[ $term_object->slug ] = $term_object->name;
	}

	vc_map(
		array(
			'name' => esc_html__( 'ShopApp Instagram Box', 'bonkers-addons' ),
			'description' => esc_html__( 'Promote your Instagram account..', 'bonkers-addons' ),
			'base' => 'shopapp_instagram_box',
			'category' => esc_html__( 'Content', 'bonkers-addons' ),
			'icon' => BONKERS_ADDONS__PLUGIN_URL . '/images/vc_instagram_box.png',
			'params' => array(
				array(
					'type' => 'textfield',
					'class' => '',
					'heading' => esc_html__( 'Instagram Account:', 'bonkers-addons' ),
					'param_name' => 'instagram_account',
					'value' => '',
					'description' => esc_html__( 'Your Instagram username.', 'bonkers-addons' ),
				),
				array(
					'type' => 'textfield',
					'class' => '',
					'heading' => esc_html__( 'Name to Display:', 'bonkers-addons' ),
					'param_name' => 'name_display',
					'value' => '',
				),
				array(
					'type' => 'textfield',
					'class' => '',
					'heading' => esc_html__( 'Button Text:', 'bonkers-addons' ),
					'param_name' => 'button_text',
					'value' => '',
				),
			),
		)
	);
}
add_action( 'vc_before_init', 'bonkers_addons_component_instagram_box' );


/**
* Function for displaying Colorlib Products Slider functionality
*
* @return string $html - the HTML content for this shortcode.
*/
function bonkers_addons_instagram_box_function( $atts, $content ) {

	$html = '';
	$atts = shortcode_atts(
		array(
			'instagram_account' => '',
			'name_display' => '',
			'button_text' => '',
		), $atts, 'shopapp_instagram_box'
	);

	if ( $atts['instagram_account'] ) {

			$html .= '<div class="shopapp_force_fullwidth">';
		$html .= '<div class="shopapp_force_fullwidth_container">';
		$html .= '<div class="shopapp-instagram-box">';

			$atts['instagram_account'] = sanitize_title_with_dashes( $atts['instagram_account'] );
			$shopapp_insta_images = shopapp_scrape_instagram( $atts['instagram_account'] );

		if ( ! is_wp_error( $shopapp_insta_images ) ) {

			$html .= "<div class='shopapp-instagram-box-images'>\n\n";
			$i = 0;
			foreach ( $shopapp_insta_images as $key => $shot ) {

				$insta_image = $shot['small'];
				$html .= '<div class="shopapp-instagram-box-image"><img class="shopapp-lazy" data-original="' . esc_url( $insta_image ) . '" src="' . esc_url( get_template_directory_uri() ) . '/images/lazyimage.png" /></div>';

				if ( 12 == ++$i ) {
					break;
				}
			}
			$i = 0;
			foreach ( $shopapp_insta_images as $key => $shot ) {

				$insta_image = $shot['small'];
				$html .= '<div class="shopapp-instagram-box-image"><img class="shopapp-lazy" data-original="' . esc_url( $insta_image ) . '" src="' . esc_url( get_template_directory_uri() ) . '/images/lazyimage.png" /></div>';

				if ( 6 == ++$i ) {
					break;
				}
			}
			$html .= "</div><!-- .shopapp-instagram-box-images -->\n\n";
		}
			$html .= '<div class="shopapp-instagram-box-content">';
				$html .= '<i class="fa fa-instagram"></i>';
				$html .= '<h4 class="shopapp-instagram-box-account">' . esc_html( $atts['name_display'] ) . '</h4>';
				$html .= '<a href="https://instagram.com/' . esc_attr( $atts['instagram_account'] ) . '" target="_blank" class="shopapp-instagram-box-btn">' . esc_html( $atts['button_text'] ) . '</a>';
			$html .= '</div>';
			$html .= '';

		$html .= '</div><!-- /.shopapp-instagram-box -->';
		$html .= '</div><!-- /.shopapp_force_fullwidth_container -->';
		$html .= '<div class="shopapp_force_fullwidth_height"></div>';
		$html .= '</div><!-- /.shopapp_force_fullwidth -->';
	}// End if().

	return $html;
}
add_shortcode( 'shopapp_instagram_box', 'bonkers_addons_instagram_box_function' );
