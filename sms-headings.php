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
		add_action( 'pl_scripts_on_ready', array( $this, 'script' ) );
	}

	function script() {

		/*
		// Remove padding that Pagelines adds to all sections
		ob_start();
		?>jQuery( '.section-sms-headings div' ).removeClass('pl-section-pad fix');
		<?php
		return ob_get_contents();
		*/
		
	}

	function section_opts(){


			global $sms_utils;
			$sms_options = get_option('sms_options');

			// $size_name_list = $sms_utils->convert_redux_choices_to_dms( $sms_options['fonts']['size-name-list'] );
			$weight_name_choices = $sms_utils->convert_redux_choices_to_dms( $sms_options['fonts']['weight-name-list'] );
			$text_align_choices = $sms_utils->convert_redux_choices_to_dms( $sms_options['fonts']['text-alignment-list'] );
			$line_height_choices = $sms_utils->convert_redux_choices_to_dms( $sms_options['fonts']['line-height-list'] );

			$text_transform_choices = $sms_utils->filter_out_redundant_css_properties( $sms_options['fonts']['text-transform-list'] );
			$text_transform_choices = $sms_utils->convert_redux_choices_to_dms( $text_transform_choices );
			
			$selector_choices = array(
				'h1' => array( 'name' => 'H1 (use only one per page)' ),
				'h2' => array( 'name' => 'H2' ),
				'h3' => array( 'name' => 'H3' ),
				'h4' => array( 'name' => 'H4' ),
				'h5' => array( 'name' => 'H5' ),
				'h6' => array( 'name' => 'H6' ),
			);

			$value_group_array = array(
				'title' => 'Heading',
			);

			$field_array = array(
				array(
					'type'    => 'check',
					'key'     => 'enable',
					'title'   => 'Enable subheading?',
					'default' => false
				),
				array(
					'type'          => 'text',
					'title'         => 'Text',
					'key'           => 'text',
				),
				array(
					'type'          => 'select',
					'title'         => 'Type',
					'key'           => 'type',
					'opts'=> array(
						'primary'   => array( 'name' => 'Heading' ),
						'secondary' => array( 'name' => 'Subheading' ),
						'tertiary'  => array( 'name' => 'Accent Heading' ),
					),
				),
				array(
					'type'          => 'select',
					'title'         => 'Selector',
					'key'           => 'selector',
					'opts'          => $selector_choices,
				),
				array(
					'type'          => 'select',
					'title'         => 'Alignment (Override)',
					'key'           => 'align',
					'opts'          => $text_align_choices,
				),
				array(
					'type'          => 'check',
					'key'           => 'italic',
					'title'         => 'Italic?',
					'default'       => false
				),
				array(
					'type'          => 'select',
					'title'         => 'Font Weight (Override)',
					'key'           => 'weight',
					'opts'          => $weight_name_choices,
				),
				array(
					'type'          => 'select',
					'title'         => 'Text Transform (Override)',
					'key'           => 'text_transform',
					'opts'          => $text_transform_choices,
				),
			);

			$options = array();
			$variations = 2;
			for ($i=1; $i <= $variations; $i++) { 

				$temp = array();
				$j = 0;
				foreach ($field_array as $field) {

					// Only add enable option for subheading on second iteration
					if( $i == 1 ){
						if( $field['key'] == 'enable' )
							continue;
					}

					$temp[$j] = array(
						'title' => $field['title'],
						'key'   => "heading{$i}_{$field['key']}",
						'type'  => $field['type'],
						'opts'  => $field['opts'],
					);
					$j++;

				}

				$options[] = array(
					'title' => 'Heading #'.$i,
					'type'  => 'multi',
					'col'   => $i,
					'opts'  => $temp
				);

			}
			// echo "<pre>\$options: " . print_r($options, true) . "</pre>";

			return $options;
		}
		function section_template(){


			$heading1_type            = ($this->opt('heading1_type')) ? $this->opt('heading1_type') : 'primary';
			$heading1_selector        = ($this->opt('heading1_selector')) ? $this->opt('heading1_selector') : 'h2';
			$heading1_text            = ($this->opt('heading1_text')) ? $this->opt('heading1_text') : 'Default heading text';
			$heading1_align           = ($this->opt('heading1_align')) ? ' align-'.$this->opt('heading1_align') : '';
			$heading1_weight          = ($this->opt('heading1_weight')) ? ' fw-'.$this->opt('heading1_weight') : '';
			$heading1_text_transform  = ($this->opt('heading1_text_transform')) ? ' text-'.$this->opt('heading1_text_transform') : '';
			$heading1_italic          = ($this->opt('heading1_italic')) ? ' text-italic' : '';
  
			$heading2_type            = ($this->opt('heading2_type')) ? $this->opt('heading2_type') : 'secondary';
			$heading2_selector        = ($this->opt('heading2_selector')) ? $this->opt('heading2_selector') : 'h3';
			$heading2_text            = ($this->opt('heading2_text')) ? $this->opt('heading2_text') : 'Default subheading text';
			$heading2_align           = ($this->opt('heading2_align')) ? ' align-'.$this->opt('heading2_align') : '';
			$heading2_weight          = ($this->opt('heading2_weight')) ? ' fw-'.$this->opt('heading2_weight') : '';
			$heading2_text_transform  = ($this->opt('heading2_text_transform')) ? ' text-'.$this->opt('heading2_text_transform') : '';
			$heading2_italic          = ($this->opt('heading2_italic')) ? ' text-italic' : '';

			$heading1_classes = "{$heading1_align}{$heading1_weight}{$heading1_text_transform}{$heading1_italic}";
			$heading2_classes = "{$heading2_align}{$heading2_weight}{$heading2_text_transform}{$heading2_italic}";


			$indicator_output = '';
			// Only show indicators to administrators
			if (current_user_can('administrator')){
				
				$indicator_output .= "<div class='row'>";

				$indicator_output .= "<div class='sms-indicator sms-indicator-light'>Heading #1</div>";

				if( $this->opt('heading1_type') )
					$indicator_output .= "<div class='sms-indicator'><i class='fa fa-header'></i><span><strong>".ucfirst($this->opt('heading1_type'))."</strong></span></div>";
				if( $this->opt('heading1_selector') )
					$indicator_output .= "<div class='sms-indicator'><i class='fa fa-tag'></i><span>selector: <strong>".strtoupper($this->opt('heading1_selector'))."</strong></span></div>";
				if( $this->opt('heading1_align') )
					$indicator_output .= "<div class='sms-indicator'><i class='fa fa-align-{$this->opt('heading1_align')}'></i><span>align: <strong>{$this->opt('heading1_align')}</strong></span></div>";
				if( $this->opt('heading1_italic') )
					$indicator_output .= "<div class='sms-indicator'><i class='fa fa-italic'></i><span>italicized: <strong>yes</strong></span></div>";
				if( $this->opt('heading1_weight') )
					$indicator_output .= "<div class='sms-indicator'><i class='fa fa-bold'></i><span>weight: <strong>{$this->opt('heading1_weight')}</strong></span></div>";
				if( $this->opt('heading1_text_transform') )
					$indicator_output .= "<div class='sms-indicator sms-heading-text-transform-on'><i class='fa fa-text-height'></i><span>text transform: <strong>{$this->opt('heading1_text_transform')}</strong></span></div>";

				$indicator_output .= "</div>";

				if( $this->opt('heading2_enable') ){

					$indicator_output .= "<div class='row'>";
					
					$indicator_output .= "<div class='sms-indicator sms-indicator-light'>Heading #2</div>";

					if( $this->opt('heading2_type') )
						$indicator_output .= "<div class='sms-indicator'><i class='fa fa-header'></i><span><strong>".ucfirst($this->opt('heading2_type'))."</strong></span></div>";
					if( $this->opt('heading2_selector') )
						$indicator_output .= "<div class='sms-indicator'><i class='fa fa-tag'></i><span>selector: <strong>".strtoupper($this->opt('heading2_selector'))."</strong></span></div>";
					if( $this->opt('heading2_align') )
						$indicator_output .= "<div class='sms-indicator'><i class='fa fa-align-{$this->opt('heading2_align')}'></i><span>align: <strong>{$this->opt('heading2_align')}</strong></span></div>";
					if( $this->opt('heading2_italic') )
						$indicator_output .= "<div class='sms-indicator'><i class='fa fa-italic'></i><span>italicized: <strong>yes</strong></span></div>";
					if( $this->opt('heading2_weight') )
						$indicator_output .= "<div class='sms-indicator'><i class='fa fa-bold'></i><span>weight: <strong>{$this->opt('heading2_weight')}</strong></span></div>";
					if( $this->opt('heading2_text_transform') )
						$indicator_output .= "<div class='sms-indicator sms-heading-text-transform-on'><i class='fa fa-text-height'></i><span>text transform: <strong>{$this->opt('heading2_text_transform')}</strong></span></div>";

					$indicator_output .= "</div>";

				}

				// If anything was added, wrap it in a div
				if($indicator_output){
					// <span class='sms-heading-indicator-wrap-title'>Heading #1 Active Overrides</span>
					$indicator_output = "<div class='sms-heading-indicator-wrap'>".$indicator_output."</div>";

					$indicator_css = "

						.sms-heading-indicator-wrap{
							visibility: hidden;
							display: none;
							position: absolute;
							top: auto;
							bottom: 100%;
							margin-bottom: 2px;
							width: 100%;
							text-align: center;
							font-family: 'Gill Sans', 'Gill Sans MT', Calibri, sans-serif;
							background: rgba(0,0,0,0.5);
						}
						.section-sms-headings:hover .sms-heading-indicator-wrap{
							visibility: visible;
							display: block;
						}

						.sms-heading-indicator-wrap .row ~ .row{
							border-top: 1px solid rgba(255,255,255,.3);
						}

						.sms-heading-indicator-wrap-title{
							font-size: 12px;
							margin-right: 10px;
							text-transform: uppercase;
							color: #fff;
						}


						.sms-indicator {
							font-size: 12px;
							display: inline-block;
							margin-right: 5px;
							margin-top: 2px;
							margin-bottom: 2px;
							background: rgba(0,0,0,.2);
							border: solid 1px rgba(255,255,255,.4);
							color: #fff;
							padding-left: 8px;
							padding-right: 8px;
							border-radius: 10px;
						}
						.sms-indicator span{
							margin-left: 5px;
						}
						.sms-indicator-light {
							background: none;
							border: none;
							float: left;
						}
						";
					$indicator_output .= inline_css_markup('sms-heading-indicator-css', $indicator_css, false);
				}

			}


			$output_heading1 = sprintf('<%2$s class="sms-heading sms-heading--%1$s%4$s">%3$s</%2$s>', $heading1_type, $heading1_selector, $heading1_text, $heading1_classes);
			
		
			$output_heading2 = sprintf('<%2$s class="sms-heading sms-heading--%1$s%4$s">%3$s</%2$s>', $heading2_type, $heading2_selector, $heading2_text, $heading2_classes);

			if( $this->opt('heading2_enable') ){
				$output = '<hgroup>'.$output_heading1.$output_heading2.'</hgroup>';
			} else {
				$output = $output_heading1;
			}

			if($indicator_output){
				$output .= $indicator_output;
			}

			echo $output;
		}
}
