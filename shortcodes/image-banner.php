<?php
/**
* Function for Adding Colorlib Image Banner Component on vc_init hook
*
* @param void
*
* @return void
*/
function bonkers_addons_component_image_banner() {

	vc_map(
		array(
			'name' => esc_html__( 'ShopApp Image Banner', 'bonkers-addons' ),
			'description' => esc_html__( 'Display a simple Banner with overlay text..', 'bonkers-addons' ),
			'base' => 'shopapp_image_banner',
			'category' => esc_html__( 'Content', 'bonkers-addons' ),
			'icon' => BONKERS_ADDONS__PLUGIN_URL . '/images/vc_offer_banner.png',
			'params' => array(
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
					'value' => '<p>CATEGORY</p><h2>Kitchen</h2>',
				),
				array(
					'type' => 'vc_link',
					'class' => '',
					'heading' => esc_html__( 'Banner Link:', 'bonkers-addons' ),
					'param_name' => 'button_link',
					'value' => '',
				),
			),
		)
	);
}
add_action( 'vc_before_init', 'bonkers_addons_component_image_banner' );


/**
* Function for displaying Colorlib Image Banner functionality
*
* @return string $html - the HTML content for this shortcode.
*/
function bonkers_addons_offer_image_banner( $atts, $content = null ) {

	$atts = shortcode_atts(
		array(
			'image' => '',
			'button_link' => '',
		), $atts, 'shopapp_image_banner'
	);

	$content = wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); // fix unclosed/unwanted paragraph tags in $content

	$image = '';
	if ( $atts['image'] ) {
		$image = wp_get_attachment_image( $atts['image'], 'full' );
	}

	$link = vc_build_link( $atts['button_link'] );
	$target = '';
	if ( $link['target'] ) {
		$target = " target='" . esc_attr( $link['target'] ) . "' ";
	}
	if ( $atts['button_link'] ) {
		$button = "<a href='" . esc_url( $link['url'] ) . "' " . $target . " class='shopapp-image-banner-content-link'></a>";
	} else {
		$button = '';
	}

	$html = <<<EOD
            <div class="shopapp-image-banner">
                {$image}
                <div class="shopapp-image-banner-content">
                    {$button}
                    {$content}
                </div>
                <div class="clearfix"></div>
            </div>
EOD;

	return $html;
}
add_shortcode( 'shopapp_image_banner', 'bonkers_addons_offer_image_banner' );


