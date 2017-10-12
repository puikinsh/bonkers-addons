<?php

/**
* Function for Adding Colorlib Offer Banner Component on vc_init hook
*
* @param void
*
* @return void
*/
function bonkers_addons_component_offer_banner() {

	vc_map(
		array(
			'name' => esc_html__( 'ShopApp Offer Banner', 'bonkers-addons' ),
			'description' => esc_html__( 'Display a Banner for Offer with a countdown.', 'bonkers-addons' ),
			'base' => 'shopapp_offer_banner',
			'category' => esc_html__( 'Content', 'bonkers-addons' ),
			'icon' => BONKERS_ADDONS__PLUGIN_URL . '/images/vc_offer_banner.png',
			'params' => array(
				array(
					'type' => 'bonkers_addons_datepicker',
					'class' => '',
					'heading' => esc_html__( 'Finish Date:', 'bonkers-addons' ),
					'param_name' => 'date',
					'value' => '',
					'description' => esc_html__( 'Date when the offer is over (Optional).', 'bonkers-addons' ),
				),
				array(
					'type' => 'attach_image',
					'class' => '',
					'heading' => esc_html__( 'Image:', 'bonkers-addons' ),
					'param_name' => 'image',
					'value' => '',
				),
				array(
					'type' => 'textarea_html',
					'class' => '',
					'holder' => 'div',
					'heading' => esc_html__( 'Content:', 'bonkers-addons' ),
					'param_name' => 'content',
					'value' => '<h5><span style="color: #999999">OFFER</span></h5><h2>This Product</h2><h3>20% OFF</h3><p>Limited time only, hurry up!</p>',
				),
				array(
					'type' => 'textfield',
					'class' => '',
					'heading' => esc_html__( 'Button Text:', 'bonkers-addons' ),
					'param_name' => 'button_text',
					'value' => '',
				),
				array(
					'type' => 'vc_link',
					'class' => '',
					'heading' => esc_html__( 'Button Link:', 'bonkers-addons' ),
					'param_name' => 'button_link',
					'value' => '',
				),
				array(
					'type' => 'colorpicker',
					'class' => '',
					'heading' => esc_html__( 'Background Color:', 'bonkers-addons' ),
					'param_name' => 'background_color',
					'value' => '',
				),
			),
		)
	);
}
add_action( 'vc_before_init', 'bonkers_addons_component_offer_banner' );


/**
* Function for displaying Colorlib Offer Banner functionality
*
* @return string $html - the HTML content for this shortcode.
*/
function bonkers_addons_offer_banner_function( $atts, $content = null ) {

	$atts = shortcode_atts(
		array(
			'date' => '',
			'image' => '',
			'button_text' => '',
			'button_link' => '',
			'background_color' => '',
		), $atts, 'shopapp_offer_banner'
	);

	$content = wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); // fix unclosed/unwanted paragraph tags in $content

	$image = '';
	if ( $atts['image'] ) {
		$image = wp_get_attachment_image_src( $atts['image'], 'full' );
		$image = $image[0];
	}

	$link = vc_build_link( $atts['button_link'] );
	$target = '';
	if ( $link['target'] ) {
		$target = " target='" . esc_attr( $link['target'] ) . "' ";
	}

	if ( $atts['button_text'] ) {
		$button = "<a href='" . esc_url( $link['url'] ) . "' " . $target . " class='ql_secundary_btn'>" . esc_html( $atts['button_text'] ) . '</a>';
	} else {
		$button = '';
	}

	$date = esc_attr( $atts['date'] );

	$days = esc_html__( 'Days', 'bonkers-addons' );
	$hours = esc_html__( 'Hours', 'bonkers-addons' );
	$minutes = esc_html__( 'Min', 'bonkers-addons' );
	$seconds = esc_html__( 'Seg', 'bonkers-addons' );

	if ( $date ) {
		$countdown = <<<EOD
        <div class="shopapp-offer-banner-countdown" data-date="{$date}">
            <div class="shopapp-offer-banner-time shopapp-days"><b>00</b> <span>{$days}</span></div>
            <div class="shopapp-offer-banner-time shopapp-hours"><b>00</b> <span>{$hours}</span></div>
            <div class="shopapp-offer-banner-time shopapp-minutes"><b>00</b> <span>{$minutes}</span></div>
            <div class="shopapp-offer-banner-time shopapp-seconds"><b>00</b> <span>{$seconds}</span></div>
        </div>
EOD;
	} else {
		$countdown = '';
	}

	$background_color = esc_attr( $atts['background_color'] );

	$html = <<<EOD
            <div class="shopapp-offer-banner" style="background-color: {$background_color};">
                <div class="shopapp-offer-banner-image" style="background-image: url({$image});">
                </div>
                <div class="shopapp-offer-banner-info">
                    {$content}
                    {$button}
                    {$countdown}
                </div>
                <div class="clearfix"></div>
            </div>
EOD;

	return $html;
}
add_shortcode( 'shopapp_offer_banner', 'bonkers_addons_offer_banner_function' );
