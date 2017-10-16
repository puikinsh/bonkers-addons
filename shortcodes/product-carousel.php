<?php
/**
* Function for Adding Colorlib Carousel Component on vc_init hook
*
* @param void
*
* @return void
*/
function bonkers_addons_component_carousel() {

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
			'name' => esc_html__( 'ShopApp Product Carousel', 'bonkers-addons' ),
			'description' => esc_html__( 'Display a carousel of WooCommerce Products.', 'bonkers-addons' ),
			'base' => 'shopapp_products_carousel',
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
					'heading' => esc_html__( 'Title:', 'bonkers-addons' ),
					'param_name' => 'title',
					'value' => '',
				),
				array(
					'type' => 'textarea',
					'class' => '',
					'heading' => esc_html__( 'Description:', 'bonkers-addons' ),
					'param_name' => 'desc',
					'value' => '',
					'description' => esc_html__( 'Images for the carousel', 'bonkers-addons' ),
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
add_action( 'vc_before_init', 'bonkers_addons_component_carousel' );


/**
* Function for displaying Colorlib Carousel functionality
*
* @return string $html - the HTML content for this shortcode.
*/
function bonkers_addons_products_carousel_function( $atts, $content ) {

	$html = '';
	$atts = shortcode_atts(
		array(
			'display' => 'best-selling',
			'title' => '',
			'desc' => '',
			'amount' => '10',
		), $atts, 'shopapp_products_carousel'
	);

	$display = sanitize_title_with_dashes( $atts['display'] );
	if ( $display && in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

				$html .= '<div class="shopapp_force_fullwidth">';
		$html .= '<div class="shopapp_force_fullwidth_container">';
		$html .= '<div class="shopapp-products-carousel">';
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
		$second_desc = '';

		if ( 'best-selling' == $atts['display'] ) {

			$query_args['meta_key'] = 'total_sales';
			$query_args['orderby'] = 'meta_value_num';

			$second_title = esc_html__( 'Top Sellers', 'bonkers-addons' );

		} elseif ( 0 !== $term_exists && null !== $term_exists ) { //If is WoooCommerce Category

			$term_object = get_term_by( 'slug', $display, 'product_cat' );
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $display,
				),
			);
			$second_title = $term_object->name;
			$second_desc = $term_object->description;

		}

		if ( $atts['title'] ) {
			$title = wp_kses_post( $atts['title'] );
		} else {
			$title = wp_kses_post( $second_title );
		}

		if ( $atts['desc'] ) {
			$desc = wp_kses_post( $atts['desc'] );
		} elseif ( $second_desc ) {
			$desc = wp_kses_post( $second_desc );
		} else {
			$desc = '';
		}

		$html .= '<li class="shopapp-products-carousel-intro shopapp-products-carousel-slide product">';
		$html .= '<div class="shopapp-products-carousel-intro-inner">';

		$html .= '<h2 class="shopapp-products-carousel-title">' . $title . '</h2>';
		if ( $desc ) {
			$html .= '<p class="shopapp-products-carousel-text">' . $desc . '</p>';
		}

		$html .= '</div>';
		$html .= '</li>';

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
		$html .= '<hr class="shopapp-products-carousel-hr">';
		$html .= '</div><!-- /.shopapp_force_fullwidth -->';

	}// End if().
	return $html;
}
add_shortcode( 'shopapp_products_carousel', 'bonkers_addons_products_carousel_function' );
