<?php

/**
 * Display Text Control
 * Custom Control to display text
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class Bonkers_Addons_Display_Text_Control extends WP_Customize_Control {

		public $type = 'bonkers-addons-display-text';

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
			$description = wp_kses( $this->description, $wp_kses_args );
			?>
			<label>
				<span class="customize-control-title"><?php echo $label; ?></span>
				<span class="description customize-control-description"><?php echo $description; ?></span>
			</label>
		<?php
		}
	}
}
