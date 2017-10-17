<?php
/**
* Function for Adding Colorlib Carousel Component on vc_init hook
*
* @param void
*
* @return void
*/
function bonkers_addons_component_gallery() {

	vc_map(
		array(
			'name' => esc_html__( 'ShopApp Gallery', 'bonkers-addons' ),
			'description' => esc_html__( 'Display a gallery.', 'bonkers-addons' ),
			'base' => 'shopapp_gallery',
			'category' => esc_html__( 'Content', 'bonkers-addons' ),
			'icon' => BONKERS_ADDONS__PLUGIN_URL . '/images/vc_gallery.png',
			'params' => array(
				array(
					'type' => 'dropdown',
					'class' => '',
					'heading' => esc_html__( 'Columns:', 'bonkers-addons' ),
					'param_name' => 'columns',
					'value' => array(
						'2' => '2 Columns',
						'3' => '3 Columns',
						'4' => '4 Columns',
						'5' => '5 Columns',
					),
					'description' => esc_html__( 'Select how many columns to display.', 'bonkers-addons' ),
				),
				array(
					'type' => 'attach_images',
					'class' => '',
					'heading' => esc_html__( 'Images', 'bonkers-addons' ),
					'param_name' => 'images',
					'value' => '',
					'description' => esc_html__( 'Images for the carousel', 'bonkers-addons' ),
				),
			),
		)
	);
}
add_action( 'vc_before_init', 'bonkers_addons_component_gallery' );


/**
* Function for displaying Colorlib Carousel functionality
*
* @return string $html - the HTML content for this shortcode.
*/
function bonkers_addons_gallery_function( $atts, $content ) {

	$html = '';
	$atts = shortcode_atts(
		array(
			'columns' => '2-columns',
			'images' => '',
		), $atts, 'shopapp_gallery'
	);

	$images_array = explode( ',', $atts['images'] );
	$columns = sanitize_title_with_dashes( $atts['columns'] );

	if ( $atts['images'] ) {
		$html .= '<div class="shopapp-gallery shopapp-' . esc_attr( $columns ) . ' masonry">';

		foreach ( $images_array as $image_id ) {

							$image_img = wp_get_attachment_image( $image_id, 'shopapp_portfolio' );
			$image_img_src = wp_get_attachment_image_src( $image_id, 'full' );
			$image_caption  = get_post( $image_id )->post_excerpt;

			$html .= "\t\t\t<div id='shopapp-gallery-item-" . esc_attr( $image_id ) . "' class='shopapp-gallery-item'>";
			$html .= "\t\t\t\t<a href='" . esc_url( $image_img_src[0] ) . "' data-width='" . esc_attr( $image_img_src['1'] ) . "' data-height='" . esc_attr( $image_img_src['2'] ) . "'></a>\n";
			if ( $image_caption ) {
				$html .= '<div class="shopapp-gallery-item-hover">';
				$html .= '<div class="shopapp-gallery-item-content">';
					$html .= '<h4 class="shopapp-gallery-item-title">' . esc_html( $image_caption ) . '</h4>';
				$html .= "\t\t\t</div>\n";
				$html .= "\t\t\t</div>\n";
			}
			$html .= wp_kses_post( $image_img );
			$html .= "</div>\n";

		}

		$html .= '</div><!-- /.shopapp-gallery-->';
	}

	return $html;
}
add_shortcode( 'shopapp_gallery', 'bonkers_addons_gallery_function' );
