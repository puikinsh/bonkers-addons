<?php

/**
 * Radio Images customize control class.
 *
 * @since  1.0.0
 * @access public
 */
class Bonkers_Radio_Image_Control extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'bonkers-radio-image';

	/**
	 * @since  1.0.0
	 * @access public
	 * @var array
	 */
	public $choices = array();

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function json() {

		$json                 = parent::json();
		$json['id']           = $this->id;
		$json['link']         = $this->get_link();
		$json['value']        = $this->value();
		$json['choices']      = $this->choices;

		return $json;

	}

	public function render_content() { }

	public function content_template() {
	?>
		<label>
			<span class="customize-control-title">
				{{{ data.label }}}
				<# if( data.description ){ #>
					<i class="dashicons dashicons-editor-help" style="vertical-align: text-bottom; position: relative;">
						<span class="mte-tooltip">
							{{{ data.description }}}
						</span>
					</i>
				<# } #>
			</span>
			<ul>
				<# for ( choice in data.choices ) { #>
				<li class="bonkers-radio-image">
					<label>
						<input type="radio" name="{{ data.id }}" value="{{ choice }}" <# if( data.value === choice ){ #> checked="checked" <# } #> class="bonkers-radio-image" />
						<img src="{{ data.choices[choice] }}">
					</label>
				</li>
				<# } #>
			</ul>
		</label>



	<?php
	}

}
