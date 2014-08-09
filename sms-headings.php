<?php
/*
Plugin Name: SMS Headings
Plugin URI: https://bitbucket.org/roadsidemultimedia/sms-headings/
Description: Add headings to the site using global styles to decide how they appear
Author: Roadside Multimedia
Version: 1.1.0
Bitbucket Plugin URI: https://bitbucket.org/roadsidemultimedia/sms-headings
Bitbucket Branch: master
PageLines: true
Section: true
Class Name: SMS_Heading
Filter: component, nav
Loading: active
Text Domain: dms-sms
*/

/**
 * IMPORTANT
 * This tells wordpress to not load the class as DMS will do it later when the main sections API is available.
 * If you want to include PHP earlier like a normal plugin just add it above here.
 */

if( ! class_exists( 'PageLinesSectionFactory' ) )
	return;

class SMS_Heading extends PageLinesSection {

	public function __construct(){
		parent::__construct();

		// add_action( 'pagelines_outer_'.$this->id, array(&$this, 'indicator_output') );

		// add_filter( 'pagelines_lesscode', array(&$this,'custom_less_output') );
		// add_filter( 'pless_vars', array(&$this,'custom_less_vars') );
	}

	// function before_section_template(){
	// 	// echo "<h1>before_section_template actually does something!</h1>";
	// }

	// function indicator_output($output){
	// 	echo $output;
	// }

	//Custom LESS markup
	function custom_less_output($less_output){
		// $less_output .= pl_file_get_contents( dirname( __FILE__ ) . '/file.less' );
		// $less_output .= '.shazbot-7-24{color:#f90; border: 5px dashed #fff;}';
		return $less_output;
	}
	// Custom LESS Vars
	public function custom_less_vars($less_vars){
		// $less_vars['sms_heading_path'] = sprintf( "\"%s\"", $this->base_url );
		// $less_vars['example_url_var'] = sprintf( "\"%s\"", "http://lorempixel.com/50/50/nature/" );
		// $less_vars['example_string_var'] = "superVar9000";
		return $less_vars;
	}


	public function section_head() {
		add_action( 'pl_scripts_on_ready', array( $this, 'script' ) );
	}

	public function script() {

		/*
		// Remove padding that Pagelines adds to all sections
		ob_start();
		?>jQuery( '.section-sms-headings div' ).removeClass('pl-section-pad fix');
		<?php
		return ob_get_contents();
		*/
		
	}

	public function section_opts(){


			global $sms_utils;
			$sms_options = get_option('sms_options');

			if( !$sms_utils ){
				return;
			}
			// $size_name_list = $sms_utils->convert_redux_choices_to_dms( $sms_options['fonts']['size-name-list'] );
			$weight_name_choices = $sms_utils->convert_redux_choices_to_dms( $sms_options['fonts']['weight-name-list'] );
			$text_align_choices = $sms_utils->convert_redux_choices_to_dms( $sms_options['fonts']['text-alignment-list'] );
			$text_transform_choices_raw = $sms_utils->filter_out_redundant_css_properties( $sms_options['fonts']['text-transform-list'] );
			$text_transform_choices = $sms_utils->convert_redux_choices_to_dms( $text_transform_choices_raw );
			$heading_type_choices = $sms_utils->convert_redux_choices_to_dms( $sms_options['fonts']['heading-type-list'] );
			$line_height_choices = $sms_utils->convert_redux_choices_to_dms( $sms_options['fonts']['line-height-classes'] );
			
			$tag_choices = array(
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
				// array(
				// 	'type'    => 'check',
				// 	'key'     => 'enable',
				// 	'title'   => 'Enable subheading?',
				// 	'default' => false
				// ),
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
					'title'         => 'HTML Tag',
					'key'           => 'tag',
					'opts'          => $tag_choices,
				),
				array(
					'type'          => 'text',
					'title'         => 'Link URL',
					'key'           => 'link_url',
				),
				array(
					'type'          => 'check',
					'key'           => 'link_target',
					'title'         => 'Open link in new window?',
					'default'       => false
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
					'title'         => 'Line Height (Override)',
					'key'           => 'line_height',
					'opts'          => $line_height_choices,
				),
				array(
					'type'          => 'select',
					'title'         => 'Text Transform (Override)',
					'key'           => 'text_transform',
					'opts'          => $text_transform_choices,
				),
				array(
					'type'          => 'count_select',
					'key'           => 'letter_spacing',
					'title'         => 'Letter Spacing',
					'count_start'   => 0,
					'count_number'  => 40,
				),
				array(
					'type'          => 'select_animation',
					'key'           => 'animation',
					'title'         => 'Viewport Animation',
					'help'          => 'Optionally animate the appearance of this section on view.',
				),
			);

			$options = array();

			$options[] = array(
				'title' => 'Heading Options',
				'type'  => 'multi',
				'col'   => 1,
				'opts'  => array(
					array(
						'title' => "Visual Indicators Enabled",
						'key'   => "sms_heading_indicators_enabled",
						'type'  => 'check',
						'scope'	=> 'global',
						'default' => true
					),
					array(
						'title' => "Enable Heading #2?",
						'key'   => "heading2_enable",
						'type'  => 'check',
						'default' => false
					)
				)
			);
			

			// Create Heading Fields from previously defined array
			$variations = 2;
			for ($i=1; $i <= $variations; $i++) { 

				$heading_fields = array();
				foreach ($field_array as $field) {

					$heading_fields[] = array(
						'title' => $field['title'],
						'key'   => "heading{$i}_{$field['key']}",
						'type'  => $field['type'],
						'opts'  => $field['opts'],
						'help'  => $field['help'],
						'count_start'  => $field['count_start'],
						'count_number'  => $field['count_number'],
					);

				} // end foreach loop

				$options[] = array(
					'title' => 'Heading #'.$i,
					'type'  => 'multi',
					'col'   => $i+1,
					'opts'  => $heading_fields
				);
				// $sms_utils->write_log('Heading #'.$i);
				// $sms_utils->write_log("====================");
				// $sms_utils->write_log($temp);

			}
			// echo "<pre>\$options: " . print_r($options, true) . "</pre>";

			return $options;
		}
		public function section_template(){

			global $sms_utils;

			$sms_options = get_option('sms_options');

			$heading1_type            = ($this->opt('heading1_type'))           ? $this->opt('heading1_type')                       : 'primary';
			$heading1_tag             = ($this->opt('heading1_tag'))            ? $this->opt('heading1_tag')                        : 'h2';
			$heading1_text            = ($this->opt('heading1_text'))           ? $this->opt('heading1_text')                       : '<div class="pl-editor-only">Default heading text</div>';
			$heading1_align           = ($this->opt('heading1_align'))          ? ' align-'.$this->opt('heading1_align').'i'        : '';
			$heading1_weight          = ($this->opt('heading1_weight'))         ? ' fw-'.$this->opt('heading1_weight').'i'          : '';
			$heading1_text_transform  = ($this->opt('heading1_text_transform')) ? ' text-'.$this->opt('heading1_text_transform')    : '';
			$heading1_letter_spacing  = ($this->opt('heading1_letter_spacing')) ? ' ls-'.($this->opt('heading1_letter_spacing'))    : '';
			$heading1_line_height     = ($this->opt('heading1_line_height'))    ? ' '.($this->opt('heading1_line_height')).'i'      : '';
			$heading1_italic          = ($this->opt('heading1_italic'))         ? ' text-italici'                                   : '';
			$heading1_link_url        = ($this->opt('heading1_link_url'))       ? $this->opt('heading1_link_url')                   : '';
			$heading1_link_target     = ($this->opt('heading1_target'))         ? ' target="' . $this->opt('heading1_target') . '"' : '';
			$heading1_animation       = ($this->opt('heading1_animation'))      ? ' pl-animation '.$this->opt('heading1_animation')                  : '';
	
			$heading2_type            = ($this->opt('heading2_type'))           ? $this->opt('heading2_type')                       : 'secondary';
			$heading2_tag             = ($this->opt('heading2_tag'))            ? $this->opt('heading2_tag')                        : 'h3';
			$heading2_text            = ($this->opt('heading2_text'))           ? $this->opt('heading2_text')                       : '<div class="pl-editor-only">Default subheading text</div>';
			$heading2_align           = ($this->opt('heading2_align'))          ? ' align-'.$this->opt('heading2_align').'i'        : '';
			$heading2_weight          = ($this->opt('heading2_weight'))         ? ' fw-'.$this->opt('heading2_weight').'i'          : '';
			$heading2_text_transform  = ($this->opt('heading2_text_transform')) ? ' text-'.$this->opt('heading2_text_transform')    : '';
			$heading2_letter_spacing  = ($this->opt('heading2_letter_spacing')) ? ' ls-'.($this->opt('heading2_letter_spacing'))    : '';
			$heading2_line_height     = ($this->opt('heading2_line_height'))    ? ' '.($this->opt('heading2_line_height')).'i'      : '';
			$heading2_italic          = ($this->opt('heading2_italic'))         ? ' text-italici'                                   : '';
			$heading2_link_url        = ($this->opt('heading2_link_url'))       ? $this->opt('heading2_link_url')                   : '';
			$heading2_link_target     = ($this->opt('heading2_target'))         ? ' target="' . $this->opt('heading2_target') . '"' : '';
			$heading2_animation       = ($this->opt('heading2_animation'))      ? ' pl-animation '.$this->opt('heading2_animation')                  : '';

			$heading1_classes = "{$heading1_align}{$heading1_weight}{$heading1_letter_spacing}{$heading1_text_transform}{$heading1_line_height}{$heading1_italic}{$heading1_animation}";
			$heading2_classes = "{$heading2_align}{$heading2_weight}{$heading2_letter_spacing}{$heading2_text_transform}{$heading2_line_height}{$heading2_italic}{$heading2_animation}";


			// WIP
			// automate indicator markup
			// 
			
			$indicator_setup = array(
				'row1' => array(
					'title' => "Header #1",
					'data'  => $this->opt('heading1_text'),
					'indicators' => array(
						array(
							'icon'    => 'fa-header',
							'data'    => $heading1_type,
							'content' => $sms_options['fonts']['heading-type-list'][$heading1_type],
							),
						array(
							'icon'    => 'fa-tag',
							'data'    => $heading1_tag,
							'content' => "tag: <strong>".strtoupper($heading1_tag)."</strong>",
							),
						array(
							'icon'    => "fa-align-{$this->opt('heading1_align')}",
							'data'    => $this->opt('heading1_align'),
							'content' => "align: <strong>".$this->opt('heading1_align')."</strong>",
							),
						array(
							'icon'    => "fa-italic",
							'data'    => $this->opt('heading1_italic'),
							'content' => "<strong>{$this->opt('heading1_italic')}</strong>",
							),
						array(
							'icon'    => "fa-bold",
							'data'    => $this->opt('heading1_weight'),
							'content' => "weight: <strong>{$this->opt('heading1_weight')}</strong>",
							),
						array(
							'icon'    => "fa-text-height",
							'data'    => $this->opt('heading1_text_transform'),
							'content' => "{$this->opt('heading1_text_transform')}",
							),
						),
					),

				'row2' => array(
					'title' => "Header #2",
					'data' => $this->opt('heading2_enable'),
					'indicators' => array(
						array(
							'icon'    => 'fa-header',
							'data'    => $heading2_type,
							'content' => $sms_options['fonts']['heading-type-list'][$heading2_type],
							),
						array(
							'icon'    => 'fa-tag',
							'data'    => $heading2_tag,
							'content' => "tag: <strong>".strtoupper($heading2_tag)."</strong>",
							),
						array(
							'icon'    => "fa-align-{$this->opt('heading2_align')}",
							'data'    => $this->opt('heading2_align'),
							'content' => "align: <strong>".$this->opt('heading2_align')."</strong>",
							),
						array(
							'icon'    => "fa-italic",
							'data'    => $this->opt('heading2_italic'),
							'content' => "<strong>{$this->opt('heading2_italic')}</strong>",
							),
						array(
							'icon'    => "fa-bold",
							'data'    => $this->opt('heading2_weight'),
							'content' => "weight: <strong>{$this->opt('heading2_weight')}</strong>",
							),
						array(
							'icon'    => "fa-text-height",
							'data'    => $this->opt('heading2_text_transform'),
							'content' => "{$this->opt('heading2_text_transform')}",
							),
						),
					),
			);
			
			$indicator_output = "";
			if ( current_user_can('administrator') && pl_setting('sms_heading_indicators_enabled') ){
				$indicator_output .= "<div class='pl-section-controls sms-indicator-wrap'>\n";
				$indicator_output .= "\t<div class='controls-left'>\n";
				foreach ($indicator_setup as $row => $value) {
					if(!$value['data'])
						continue;
					$indicator_output .= "\t\t<div class='row'>\n";
					$indicator_output .= "\t\t\t<div class='sms-indicator sms-indicator-title'>{$value['title']}</div>\n";
					foreach ($value['indicators'] as $key => $prop) {
						if($prop['data']){
							$indicator_output .= "\t\t\t<div class='sms-indicator'>\n";
							$indicator_output .= "\t\t\t\t<i class='fa {$prop['icon']}'></i>\n";
							$indicator_output .= "\t\t\t\t<span>{$prop['content']}</span>\n";
							$indicator_output .= "\t\t\t</div>\n";
						}
					}
					$indicator_output .= "\t\t</div>\n";
				}
					$indicator_output .= "\t</div>\n";
				$indicator_output .= "</div>\n";
			}

			$output_heading1 = sprintf('<%2$s class="sms-heading sms-heading--%1$s%4$s">%3$s</%2$s>', $heading1_type, $heading1_tag, $heading1_text, $heading1_classes);
			if( $heading1_link_url ){
				// Wrap in an A tag
				$output_heading1 = sprintf('<a href="%1s"%2s class="sms-heading-link">%3s</a>', $heading1_link_url, $heading1_target, $output_heading1);
			}


			$output_heading2 = sprintf('<%2$s class="sms-heading sms-heading--%1$s%4$s">%3$s</%2$s>', $heading2_type, $heading2_tag, $heading2_text, $heading2_classes);
			if( $heading2_link_url ){
				// Wrap in an A tag
				$output_heading2 = sprintf("<a href='%1s'%2s class='sms-heading-link'>%3s</a>", $heading2_link_url, $heading2_target, $output_heading2);
			}

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
