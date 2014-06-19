<?php
/*
Plugin Name: SMS Headings
Plugin URI: http://www.roadsidemultimedia.com
Description: Add headings to the site using global styles to decide how they appear
Author: Roadside Multimedia
PageLines: true
Version: 1.0
Section: true
Class Name: SMS_Heading
Filter: component
Loading: active
*/

/**
 * IMPORTANT
 * This tells wordpress to not load the class as DMS will do it later when the main sections API is available.
 * If you want to include PHP earlier like a normal plugin just add it above here.
 */

if( ! class_exists( 'PageLinesSectionFactory' ) )
	return;

class SMS_Heading extends PageLinesSection {

	function section_head() {
		if( 1 == $this->opt( 'nextbox_divs' ) ) {
			add_action( 'pl_scripts_on_ready', array( $this, 'script' ) );
		}
	}

	function script() {

		// Remove padding that Pagelines adds to all sections
		$clone = $this->meta['clone'];		
		ob_start();
		?>jQuery( '#<?php echo $this->id.$clone; ?> div' ).removeClass('pl-section-pad fix')
		<?php
		return ob_get_contents();
		
	}

	function section_opts(){
			$options = array();

			$options[] = array(
				'title' => 'Heading #1',
				'type'  => 'multi',
				'opts'  => array(
					array(
						'type'          => 'select',
						'title'         => 'Type',
						'key'           => 'heading1_type',
						'opts'=> array(
							'primary'         => array( 'name' => 'Heading' ),
							'secondary'       => array( 'name' => 'Subheading' ),
							'tertiary'        => array( 'name' => 'Accent Heading' ),
						),
					),
					array(
						'type'          => 'select',
						'title'         => 'Selector',
						'key'           => 'heading1_selector',
						'opts'=> array(
							'h1'              => array( 'name' => 'H1 (use only one per page)' ),
							'h2'              => array( 'name' => 'H2' ),
							'h3'              => array( 'name' => 'H3' ),
							'h4'              => array( 'name' => 'H4' ),
							'h5'              => array( 'name' => 'H5' ),
							'h6'              => array( 'name' => 'H6' ),
						),
					),
					array(
						'label'         => 'Text',
						'key'           => 'heading1_text',
						'type'          => 'text',
						'default'       => 'Heading Text',
					),
				),
			);

			// echo "<pre>" . print_r($options, true) . "</pre>";

			$options[] = array(
				'title' => 'Heading #2',
				'type'  => 'multi',
				'col' => 2,
				'opts'  => array(
					array(
						'type'    => 'check',
						'key'     => 'heading2_enable',
						'label'   => 'Enable subheading?',
						'default' => false
					),
					array(
						'type'          => 'select',
						'title'         => 'Type',
						'key'           => 'heading2_type',
						'opts'=> array(
							'primary'         => array( 'name' => 'Heading' ),
							'secondary'       => array( 'name' => 'Subheading' ),
							'tertiary'        => array( 'name' => 'Accent Heading' ),
						),
					),
					array(
						'type'          => 'select',
						'title'         => 'Selector',
						'key'           => 'heading2_selector',
						'opts'=> array(
							'h1'              => array( 'name' => 'H1 (use only one per page)' ),
							'h2'              => array( 'name' => 'H2' ),
							'h3'              => array( 'name' => 'H3' ),
							'h4'              => array( 'name' => 'H4' ),
							'h5'              => array( 'name' => 'H5' ),
							'h6'              => array( 'name' => 'H6' ),
						),
					),
					array(
						'label'         => 'Text',
						'key'           => 'heading2_text',
						'type'          => 'text',
						'default'       => 'Heading Text',
					),
				),
			);

			return $options;
		}
		function section_template(){

			$heading1_type       = ($this->opt('heading1_type')) ? $this->opt('heading1_type') : 'primary';
			$heading1_selector   = ($this->opt('heading1_selector')) ? $this->opt('heading1_selector') : 'h2';
			$heading1_text       = ($this->opt('heading1_text')) ? $this->opt('heading1_text') : 'Default heading text';

			$heading2_type       = ($this->opt('heading2_type')) ? $this->opt('heading2_type') : 'secondary';
			$heading2_selector   = ($this->opt('heading2_selector')) ? $this->opt('heading2_selector') : 'h3';
			$heading2_text       = ($this->opt('heading2_text')) ? $this->opt('heading2_text') : 'Default subheading text';

			$output_heading1 = sprintf('<%2$s class="sms-heading heading--%1$s">%3$s</%2$s>', $heading1_type, $heading1_selector, $heading1_text);
			$output_heading2 = sprintf('<%2$s class="sms-heading heading--%1$s">%3$s</%2$s>', $heading2_type, $heading2_selector, $heading2_text);

			if( $this->opt('heading2_enable') ){
				$output = '<hgroup>'.$output_heading1.$output_heading2.'</hgroup>';
			} else {
				$output = $output_heading1;
			}

			echo $output;
		}
}
