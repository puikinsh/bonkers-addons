<?php
/**
* Function for Adding Colorlib Products Slider Component on vc_init hook
*
* @param void
*
* @return void
*/
function bonkers_addons_component_products_slider() {

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
			'name' => esc_html__( 'ShopApp Products Slider', 'bonkers-addons' ),
			'description' => esc_html__( 'Display a slider of WooCommerce Products.', 'bonkers-addons' ),
			'base' => 'shopapp_products_slider',
			'category' => esc_html__( 'Content', 'bonkers-addons' ),
			'icon' => BONKERS_ADDONS__PLUGIN_URL . '/images/vc_product_carousel.png',
			'params' => array(
				array(
					'type' => 'dropdown',
					'class' => '',
					'heading' => esc_html__( 'Display:', 'bonkers-addons' ),
					'param_name' => 'display',
					'value' => $display_options,
					'description' => esc_html__( 'Select which category to display or the Top Sellers option.', 'bonkers-addons' ),
				),
				array(
					'type' => 'textfield',
					'class' => '',
					'heading' => esc_html__( 'Products ID:', 'bonkers-addons' ),
					'param_name' => 'products_id',
					'value' => '',
					'description' => esc_html__( 'Insert the ID of the products to display separated by comma. Example: 15, 72, 101, 45', 'bonkers-addons' ),
				),
				array(
					'type' => 'textfield',
					'class' => '',
					'heading' => esc_html__( 'Amount of Products:', 'bonkers-addons' ),
					'param_name' => 'amount',
					'value' => '10',
					'description' => esc_html__( 'How many products to display', 'bonkers-addons' ),
				),
			),
		)
	);
}
add_action( 'vc_before_init', 'bonkers_addons_component_products_slider' );


/**
* Function for displaying Colorlib Products Slider functionality
*
* @return string $html - the HTML content for this shortcode.
*/
function bonkers_addons_products_slider_function( $atts, $content ) {

	$html = '';
	$atts = shortcode_atts(
		array(
			'display' => 'best-selling',
			'products_id' => '',
			'amount' => '10',
		), $atts, 'shopapp_products_slider'
	);

	$display = sanitize_title_with_dashes( $atts['display'] );
	if ( $display && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

		$html .= '<div class="shopapp_force_fullwidth">';
		$html .= '<div class="shopapp_force_fullwidth_container">';
		$html .= '<div class="shopapp-products-slider">';
		$html .= '<ul class="products shopapp-shop-layout-2 layout-4-columns">';

		$ordering_args = WC()->query->get_catalog_ordering_args( 'menu_order title', 'asc' );
		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby'             => $ordering_args['orderby'],
			'order'               => $ordering_args['order'],
			'posts_per_page'      => $atts['amount'],
		);

		$term_exists = term_exists( $display, 'product_cat' );

		if ( $atts['products_id'] ) {

			$p_ids = preg_replace( '/\s+/', '', $atts['products_id'] );
			$p_ids = explode( ',', $p_ids );
			if ( is_array( $p_ids ) ) {
				$query_args['post__in'] = $p_ids;
				$query_args['orderby'] = 'post__in';
			}
		} elseif ( 'best-selling' == $atts['display'] ) {

			$query_args['meta_key'] = 'total_sales';
			$query_args['orderby'] = 'meta_value_num';

		} elseif ( 0 !== $term_exists && null !== $term_exists ) { //If is WoooCommerce Category

			$term_object = get_term_by( 'slug', $display, 'product_cat' );
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $display,
				),
			);

		}

		$shopapp_products = new WP_Query( $query_args );

		if ( $shopapp_products->have_posts() ) {
			while ( $shopapp_products->have_posts() ) {
				$shopapp_products->the_post();

				$html .= do_shortcode( '[product id="' . get_the_id() . '"]' );

			}
		} else { // if have posts
			esc_html_e( 'No items to show.', 'bonkers-addons' );
		}
		wp_reset_postdata();

		$html .= '</ul>';
		$html .= '</div><!-- /.shopapp-products-carousel -->';
		$html .= '</div><!-- /.shopapp_force_fullwidth_container -->';
		$html .= '<div class="shopapp_force_fullwidth_height"></div>';
		$html .= '</div><!-- /.shopapp_force_fullwidth -->';

	}// End if().
	return $html;
}
add_shortcode( 'shopapp_products_slider', 'bonkers_addons_products_slider_function' );
