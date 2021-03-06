<?php
/*
Plugin Name: CKEditor WYSIWYG for Gravity Forms
Description: Use the CKEditor WYSIWYG in your Gravity Forms
Version: 1.5.2
Author: Adrian Gordon
Author URI: http://www.itsupportguides.com 
License: GPL2

------------------------------------------------------------------------
Copyright 2015 Adrian Gordon

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/

add_action('admin_notices', array('ITSG_GF_WYSIWYG_CKEditor', 'admin_warnings'), 20);

require_once(plugin_dir_path( __FILE__ ).'gf_wysiwyg_ckeditor_settings.php');

if (!class_exists('ITSG_GF_WYSIWYG_CKEditor')) {
    class ITSG_GF_WYSIWYG_CKEditor
    {
	private static $name = 'CKEditor WYSIWYG for Gravity Forms';
    private static $slug = 'itsg_gf_wysiwyg_ckeditor';
	
        /**
         * Construct the plugin object
         */
        public function __construct()
        {	// register plugin functions through 'plugins_loaded' -
			// this delays the registration until all plugins have been loaded, ensuring it does not run before Gravity Forms is available.
            add_action( 'plugins_loaded', array(&$this,'register_actions') );
		}
		
		/*
         * Register plugin functions
         */
		function register_actions() {
		// register actions
            if (self::is_gravityforms_installed()) {
				$ur_settings = self::get_options();
				
				//start plug in

				add_action('gform_enqueue_scripts',  array(&$this, 'itsg_gf_wysiwyg_ckeditor_js'), 10, 2);
				add_filter('gform_save_field_value', array(&$this, 'itsg_gf_wysiwyg_ckeditor_save_values'), 10, 4);
				add_action('gform_field_standard_settings', array(&$this, 'itsg_gf_wysiwyg_ckeditor_field_settings'), 10, 2);
				add_filter('gform_tooltips', array(&$this, 'itsg_gf_wysiwyg_ckeditor_field_tooltips'));
				add_action('gform_editor_js', array(&$this, 'itsg_gf_wysiwyg_ckeditor_editor_js'));
				add_action('gform_field_css_class', array(&$this,'itsg_gf_wysiwyg_ckeditor_css_class'), 10, 3);
				add_filter('gform_field_content',  array(&$this,'itsg_gf_wysiwyg_ckeditor_add_max_char'), 10, 5);
				add_filter('gform_counter_script', array(&$this,'itsg_gf_wysiwyg_ckeditor_counter_script'), 10, 4);
				add_filter('gform_save_field_value',  array(&$this,'save_field_value'), 10, 4);
				
				// enqueue in entry editor and form editor
				if(RGForms::get("page") == "gf_entries" || (RGForms::get("page") == "gf_edit_forms" && $ur_settings['enable_in_form_editor'] == 'on' )) {
					add_action('admin_footer',  array(&$this, 'itsg_gf_wysiwyg_ckeditor_js_script'));
				}
				
				if(RGForms::get("page") == "gf_settings") {
					// add settings page
					RGForms::add_settings_page("WYSIWYG ckeditor", array("ITSG_GF_WYSIWYG_ckeditor_settings_page", "settings_page"), self::get_base_url() . "/images/user-registration-icon-32.png");
				}
			}
		} // END register_actions
		
		/* 
		 *   Handles the plugin options.
		 *   Default values are stored in an array.
		 */ 
		public static function get_options(){
			$defaults = array(
				'enable_in_form_editor' => 'on',
				'enable_bold' => 'on',
				'enable_italic' => 'on',
				'enable_underline' => 'on',
				'enable_pastetext' => 'on',
				'enable_pastefromword' => 'on',
				'enable_numberedlist' => 'on',
				'enable_bulletedlist' => 'on',
				'enable_outdent' => 'on',
				'enable_indent' => 'on',
				'enable_link' => 'on',
				'enable_unlink' => 'on',
				'enable_format' => 'on',
				'enable_font' => 'on',
				'enable_fontsize' => 'on'
			);
			$options = wp_parse_args(get_option('ITSG_gf_wysiwyg_ckeditor_settings'), $defaults);
			return $options;
		} // END get_options
		
		/*
         * Modifies the value before saved to the database - removes line spaces
         */    
		function save_field_value($value, $lead, $field, $form) {
			$field_type = $field['type'];
			if ('post_content' == $field_type  || 'textarea' == $field_type || ('post_custom_field' == $field_type  && 'textarea' == $field['inputType'])) {
				if (self::is_wysiwyg_ckeditor($field)) {
					$value = preg_replace( "/\r|\n/", "", $value );
				}
			}
			return $value;  // save to the database
		}  // END save_field_value
		
		/*
         * Place ckeditor JavaScript in footer, applies ckeditor to 'textarea' fields 
         */
		public static function itsg_gf_wysiwyg_ckeditor_js($form, $is_ajax) {
			if (is_array($form['fields']) || is_object($form['fields'])) {
				foreach ( $form['fields'] as $field ) {
					if (self::is_wysiwyg_ckeditor($field)) {
						add_action('wp_footer', array('ITSG_GF_WYSIWYG_CKEditor', 'itsg_gf_wysiwyg_ckeditor_js_script'));
					}
				}
			}
		} // END itsg_gf_wysiwyg_ckeditor_js
		
		public static function itsg_gf_wysiwyg_ckeditor_js_script() {
			$ur_settings = self::get_options();
			
			wp_enqueue_script('ITSG_gf_wysiwyg_ckeditor_js', plugin_dir_url( __FILE__ ) . 'ckeditor/ckeditor.js' );
			wp_enqueue_script('ITSG_gf_wysiwyg_ckeditor_jquery_adapter', plugin_dir_url( __FILE__ ) . 'ckeditor/adapters/jquery.js' );
			
				?>
				<script>
				
				function itsg_gf_wysiwyg_ckeditor_function(self){
					<?php if (RGForms::get("page") == "gf_edit_forms") { ?>
					// if in Gravity Forms form editor
					// destroy any active CKeditor instances first
					for(i in CKEDITOR.instances) {
						CKEDITOR.instances[i].destroy();
					}
					<?php } ?>
					
				(function( $ ) {
					"use strict";
					$(function(){
						$('.gform_wrapper .gform_wysiwyg_ckeditor textarea:not([disabled="disabled"]), .gform_wrapper .gform_page:not([style="display:none;"]) .gform_wysiwyg_ckeditor textarea:not([disabled=disabled]), #field_settings textarea:not([disabled=disabled]), .gf_entry_wrap .postbox .gform_wysiwyg_ckeditor textarea:not([disabled=disabled])').each(function() {
							$(this).ckeditor(CKEDITOR.tools.extend( {
							<?php if (RGForms::get("page") !== "gf_edit_forms") { ?>
							extraPlugins : 'wordcount', 
							wordcount : {
								showParagraphs : false,
								showWordCount: false,
								showCharCount: true, 
								charLimit: $(this).attr('data-maxlen'),
								hardLimit: true 
							},
							<?php } ?>
							toolbar: [
								<?php   /* SOURCE */
								if (rgar($ur_settings, 'enable_source')) { echo "{ name: 'source', items: [ 'Source' ] },";} ?>
								{ name: 'basicstyles', items: [ <?php  /* BASIC STYLES */ 
								if (rgar($ur_settings, 'enable_bold')) { echo "'Bold',";} 
								if (rgar($ur_settings, 'enable_italic')) { echo "'Italic',";} 
								if (rgar($ur_settings, 'enable_underline')) { echo "'Underline',";} 
								if (rgar($ur_settings, 'enable_strike')) { echo "'Strike',";} 
								if (rgar($ur_settings, 'enable_subscript')) { echo "'Subscript',";}   
								if (rgar($ur_settings, 'enable_superscript')) { echo "'Superscript',";}   
								if (rgar($ur_settings, 'enable_removeformat')) { echo "'-', 'RemoveFormat'";} ?> 
								] },
								{ name: 'clipboard',  items: [ <?php  /* CLIPBOARD */
								if (rgar($ur_settings, 'enable_cut')) { echo "'Cut',";} 
								if (rgar($ur_settings, 'enable_copy')) { echo "'Copy',";} 
								if (rgar($ur_settings, 'enable_paste')) { echo "'Paste',";} 
								if (rgar($ur_settings, 'enable_pastetext')) { echo "'PasteText',";} 
								if (rgar($ur_settings, 'enable_pastefromword')) { echo "'PasteFromWord',";}   
								if (rgar($ur_settings, 'enable_undo')) { echo "'-', 'Undo',";}   
								if (rgar($ur_settings, 'enable_redo')) { echo "'Redo'";} ?> 
								] },
								{ name: 'paragraph', items: [ <?php  /* PARAGRAPH */
								if (rgar($ur_settings, 'enable_numberedlist')) { echo "'NumberedList',";} 
								if (rgar($ur_settings, 'enable_bulletedlist')) { echo "'BulletedList',";} 
								if (rgar($ur_settings, 'enable_outdent')) { echo "'-', 'Outdent',";} 
								if (rgar($ur_settings, 'enable_indent')) { echo "'Indent',";} 
								if (rgar($ur_settings, 'enable_blockquote')) { echo "'-', 'Blockquote',";} 
								if (rgar($ur_settings, 'enable_creatediv')) { echo "'CreateDiv',";} 
								if (rgar($ur_settings, 'enable_justifyleft')) { echo "'-', 'JustifyLeft',";} 
								if (rgar($ur_settings, 'enable_justifycenter')) { echo "'JustifyCenter',";} 
								if (rgar($ur_settings, 'enable_justifyright')) { echo "'JustifyRight',";} 
								if (rgar($ur_settings, 'enable_justifyblock')) { echo "'JustifyBlock',";} 
								if (rgar($ur_settings, 'enable_bidiltr')) { echo "'-','BidiLtr',";}   
								if (rgar($ur_settings, 'enable_bidirtl')) { echo "'BidiRtl',";}   
								if (rgar($ur_settings, 'enable_language')) { echo "'Language'";} ?> 
								] },
								{ name: 'links', items: [ <?php  /* LINKS */
								if (rgar($ur_settings, 'enable_link')) { echo "'Link',";}   
								if (rgar($ur_settings, 'enable_unlink')) { echo "'Unlink',";}   
								if (rgar($ur_settings, 'enable_anchor')) { echo "'Anchor'";} ?> 
								] },
								{ name: 'document', items: [ <?php /* DOCUMENT */
								if (rgar($ur_settings, 'enable_preview')) { echo "'Preview',";}
								if (rgar($ur_settings, 'enable_print')) { echo "'Print',";} ?> 
								] },
								{ name: 'editing', items: [ <?php   /* EDITING */
								if (rgar($ur_settings, 'enable_find')) { echo "'Find',";}
								if (rgar($ur_settings, 'enable_replace')) { echo "'Replace',";}
								if (rgar($ur_settings, 'enable_selectall')) { echo "'-', 'SelectAll',";}
								if (rgar($ur_settings, 'enable_scayt')) { echo "'-', 'Scayt'";} ?>
								] },
								{ name: 'insert', items: [ <?php  /* INSERT */
								if (rgar($ur_settings, 'enable_image')) { echo "'Image',";}
								if (rgar($ur_settings, 'enable_flash')) { echo "'Flash',";}
								if (rgar($ur_settings, 'enable_table')) { echo "'Table',";}
								if (rgar($ur_settings, 'enable_horizontalrule')) { echo "'HorizontalRule',";}
								if (rgar($ur_settings, 'enable_smiley')) { echo "'Smiley',";}
								if (rgar($ur_settings, 'enable_specialchar')) { echo "'SpecialChar',";}
								if (rgar($ur_settings, 'enable_pagebreak')) { echo "'PageBreak',";}
								if (rgar($ur_settings, 'enable_iframe')) { echo "'Iframe'";} ?>
								] },
								'/',
								{ name: 'styles', items: [ <?php  /* STYLES */
								if (rgar($ur_settings, 'enable_styles')) { echo "'Styles',";}
								if (rgar($ur_settings, 'enable_format')) { echo "'Format',";}
								if (rgar($ur_settings, 'enable_font')) { echo "'Font',";}
								if (rgar($ur_settings, 'enable_fontsize')) { echo "'FontSize'";} ?>
								] },
								{ name: 'colors', items: [ <?php  /* COLOURS */
								if (rgar($ur_settings, 'enable_textcolor')) { echo "'TextColor',";}
								if (rgar($ur_settings, 'enable_bgcolor')) { echo "'BGColor'";} ?>
								] },
								{ name: 'tools', items: [ <?php  /* TOOLS */
								if (rgar($ur_settings, 'enable_maximize')) { echo "'Maximize',";}
								if (rgar($ur_settings, 'enable_showblocks')) { echo "'ShowBlocks'";} ?>
								] },
								{ name: 'about', items: [ <?php  /* ABOUT */
								if (rgar($ur_settings, 'enable_about')) { echo "'About'";} ?>
								] }],
							allowedContent: true
							}));

							<?php if (RGForms::get("page") == "gf_edit_forms") { ?>
							for (var i in CKEDITOR.instances) {
								// wrap in half second timeout provides performance improvement by stopping 'change' event from firing multiple times
								setTimeout(function(){	
									CKEDITOR.instances[i].on('change', function(event) {
										if (event.sender.name == 'field_description') {
											SetFieldDescription(this.getData());
										} else if (event.sender.name  == 'field_content') {
											SetFieldProperty('content', this.getData());
										} else if (event.sender.name  == 'infobox_more_info_field') {
											SetFieldProperty('infobox_more_info_field', this.getData());
										} else {
											CKEDITOR.instances[i].updateElement();  
										}
										
									});
								},500);
								CKEDITOR.instances[i].on('loaded', function(event) {
									if (event.sender.name == 'field_description') {
										SetFieldDescription(this.getData());
									} else if (event.sender.name  == 'field_content') {
										SetFieldProperty('content', this.getData());
									} else if (event.sender.name  == 'infobox_more_info_field') {
										SetFieldProperty('infobox_more_info_field', this.getData());
									} else {
										CKEDITOR.instances[i].updateElement();  
									}
									this.setData(this.getData())
								});
							}
							<?php } ?>
							
							<?php if (self::is_dpr_installed() && !is_admin() ) { ?>
							for (var i in CKEDITOR.instances) {
								CKEDITOR.instances[i].on('change', function() {
									CKEDITOR.instances[i].updateElement();    
									changed = true;
								});
							}
							<?php } ?>
						});

					});
				}(jQuery));
				
				}
				
				<?php if (RGForms::get("page") == "gf_edit_forms") { ?>
				
				// runs the main function when field settings have been opened in the form editor
				
				jQuery(document).bind('gform_load_field_settings', function($) {
					// wrap in half second timeout provides percieved performance inprovement by delaying the CKeditor load until the field settings has loaded
					// currently commented out due to issues during initial testing
					//setTimeout(function(){	
						itsg_gf_wysiwyg_ckeditor_function(jQuery(this));
					//},500);
					});
					
				// destory all existing CKEditor instances when field is delete in form editor - hooks into existing StartDeleteField function.
				// backup original StartDeleteField
				var StartDeleteFieldoldCK = StartDeleteField;
				StartDeleteField = function(field) {
					// destory all CKEditor instances
					for(name in CKEDITOR.instances) {
						CKEDITOR.instances[name].destroy(true);
					}
					// call original StartDeleteField
					StartDeleteFieldoldCK(field);
				};
				
				<?php } else { ?>
				
				// runs the main function when the page loads

				jQuery(document).bind('gform_post_render', function($) {itsg_gf_wysiwyg_ckeditor_function(jQuery(this));  });

				// runs the main function when the page loads (for ajax enabled forms)

				jQuery(document).ready(function($) {itsg_gf_wysiwyg_ckeditor_function(jQuery(this));  });
				
				<?php }  ?>
				</script>
				<?php
		} // END itsg_gf_wysiwyg_ckeditor_js_script
		
		/*
         * Customises 'Paragraph Text' field output to include character limit details and CSS class for admin area
         */
		public static function itsg_gf_wysiwyg_ckeditor_add_max_char($content, $field, $value, $lead_id, $form_id){
			$field_type = rgar($field,"type");
			if ('post_content' == $field_type  || 'textarea' == $field_type || ('post_custom_field' == $field_type  && 'textarea' == $field['inputType'])) {
				$label = rgar($field,"label");
				$limit = rgar($field,"maxLength");
				if ('' == $limit) {$limit = 'unlimited';};
				$content = str_replace("<textarea ","<textarea data-maxlen='".$limit."' ",$content);
				if (is_admin() && self::is_wysiwyg_ckeditor($field) ){
					$content = str_replace("class='","class='gform_wysiwyg_ckeditor ",$content);
				}
			}
			return $content;
		} // END itsg_gf_wysiwyg_ckeditor_add_max_char
				
		/*
         * Customises character limit count down for NON-CKEditor fields to match what CKEditor provides 
		 * - note that these fields DO count spaces, where as CKEDitor does NOT count spaces.
         */
		public static function itsg_gf_wysiwyg_ckeditor_counter_script($script, $form_id, $field_id, $max_length){
			$form = GFFormsModel::get_form_meta($form_id);
			$field_id_id = str_replace("input_".$form_id."_","",$field_id);
			$field = GFFormsModel::get_field($form, $field_id_id);
			if (true == rgar($field,"enable_wysiwyg_ckeditor"))
			{return "";} else {
				$script = "jQuery('#{$field_id}').textareaCount(" .
							"    {" .
							"    'maxCharacterSize': {$max_length}," .
							"    'originalStyle': 'ginput_counter'," .
							"    'displayFormat' : 'Characters: #input (Limit: $max_length)'" .
							"    });";	   
				return $script;
			}
		} // END itsg_gf_wysiwyg_ckeditor_counter_script
		
		/*
         * Applies CSS class to 'Paragraph text' fields when CKEditor is enabled
         */
		public static function itsg_gf_wysiwyg_ckeditor_css_class($classes, $field, $form) {
			$field_type = rgar($field,"type");
			if ('post_content' == $field_type  || 'textarea' == $field_type || ('post_custom_field' == $field_type  && 'textarea' == $field['inputType'])) {
				if (self::is_wysiwyg_ckeditor($field)) {
					 $classes .= " gform_wysiwyg_ckeditor";
				}
			}
            return $classes;
        } // END itsg_gf_wysiwyg_ckeditor_css_class
		
		/*
         * Applies 'Enable WYSIWYG CKEditor' option to 'Paragraph Text' field
         */
		public static function itsg_gf_wysiwyg_ckeditor_field_settings($position, $form_id) {
			if ($position == 25) {
				?>
				<li class="wysiwyg_field_setting_wysiwyg_ckeditor field_setting" style="display:list-item;">
					<input type="checkbox" id="field_enable_wysiwyg_ckeditor"/>
					<label for="field_enable_wysiwyg_ckeditor" class="inline">
						<?php _e("Enable WYSIWYG (CKEditor)", "gravityforms"); ?>
					</label>
					<?php gform_tooltip("form_field_enable_wysiwyg_ckeditor") ?><br/>
				</li>
			<?php
			}
		} // END itsg_gf_wysiwyg_ckeditor_field_settings
		
		/*
         * JavaScript for form editor
         */
		public static function itsg_gf_wysiwyg_ckeditor_editor_js() {
			?>
			<script type='text/javascript'>
				jQuery(document).bind("gform_load_field_settings", function (event, field, form) {
					var field_type = field['type'];
					if ('post_content' == field_type  || 'textarea' == field_type || ('post_custom_field' == field_type  && 'textarea' == field['inputType'])) {

						var $wysiwyg_container = jQuery(".wysiwyg_field_setting_wysiwyg_ckeditor");

						$wysiwyg_container.show();

						var enable_wysiwyg_ckeditor = (typeof field['enable_wysiwyg_ckeditor'] != 'undefined' && field['enable_wysiwyg_ckeditor'] != '') ? field['enable_wysiwyg_ckeditor'] : false;

						if (enable_wysiwyg_ckeditor != false) {
							//check the checkbox if previously checked
							$wysiwyg_container.find("input:checkbox").attr("checked", "checked");
						} else {
							$wysiwyg_container.find("input:checkbox").removeAttr("checked");
						}
					}
				});

				jQuery(".wysiwyg_field_setting_wysiwyg_ckeditor input").click(function () {
					if (jQuery(this).is(":checked")) {
						SetFieldProperty('enable_wysiwyg_ckeditor', 'true');
					} else {
						SetFieldProperty('enable_wysiwyg_ckeditor', '');
					}
				});
			</script>
		<?php
		} // END itsg_gf_wysiwyg_ckeditor_editor_js
		
		/*
         * Tooltip for field in form editor
         */
		public static function itsg_gf_wysiwyg_ckeditor_field_tooltips($tooltips){
			$tooltips["form_field_enable_wysiwyg_ckeditor"] = "<h6>Enable WYSIWYG</h6>Check this box to turn this field into a WYSIWYG editor, using ckeditor.";
			return $tooltips;
		} // END itsg_gf_wysiwyg_ckeditor_field_tooltips

		/*
         * Checks if field is CKEditor enabled
         */
		private static function is_wysiwyg_ckeditor($field) {
			$field_type = self::get_type($field);
			if ('post_content' == $field_type  || 'textarea' == $field_type || ('post_custom_field' == $field_type  && 'textarea' == $field['inputType'])) {
				if (array_key_exists('enable_wysiwyg_ckeditor', $field)) {
					return $field['enable_wysiwyg_ckeditor'] == 'true';
				}
			}
			return false;
		} // END is_wysiwyg_ckeditor
		
		/*
         * Get field type
         */
		private static function get_type($field) {
			$type = '';
			if (array_key_exists('type', $field)) {
				$type = $field['type'];
				if ($type == 'post_custom_field') {
					if (array_key_exists('inputType', $field)) {
						$type = $field['inputType'];
					}
				}
				return $type;
			}
		} // END get_type
	
		/*
         * Handles data from WYSIWYG when form is submitted
         */
		public static function itsg_gf_wysiwyg_ckeditor_save_values($value, $lead, $field, $form) {
			if (self::is_wysiwyg_ckeditor($field)) {
				$value = rgpost("input_{$field['id']}");
			}
			return $value;
		} // END itsg_gf_wysiwyg_ckeditor_save_values
		
		/*
         * Warning message if Gravity Forms is installed and enabled
         */
		public static function admin_warnings() {
			if ( !self::is_gravityforms_installed() ) {
				$message = __('requires Gravity Forms to be installed.', self::$slug);
			} 
			
			if (empty($message)) {
				return;
			}
			?>
			<div class="error">
				<h3>Warning</h3>
				<p>
					<?php _e('The plugin ', self::$slug); ?><strong><?php echo self::$name; ?></strong> <?php echo $message; ?><br />
					<?php _e('Please ',self::$slug); ?><a target="_blank" href="http://www.gravityforms.com/"><?php _e(' download the latest version',self::$slug); ?></a><?php _e(' of Gravity Forms and try again.',self::$slug) ?>
				</p>
			</div>
			<?php
		} // END admin_warnings
		
		/*
         * Check if GF is installed
         */
        private static function is_gravityforms_installed() {
			if ( !function_exists( 'is_plugin_active' ) || !function_exists( 'is_plugin_active_for_network' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}
			if (is_multisite()) {
				return (is_plugin_active_for_network('gravityforms/gravityforms.php') || is_plugin_active('gravityforms/gravityforms.php') );
			} else {
				return is_plugin_active('gravityforms/gravityforms.php');
			}
        } // END is_gravityforms_installed
		
		/*
         * Check if Gravity Forms - Data Persistence Reloaded is installed
         */
        private static function is_dpr_installed() {
            return function_exists('ri_gfdp_ajax');
        } // END is_dpr_installed
		
		/*
         * Get plugin url
         */
		 private static function get_base_url(){
			return plugins_url(null, __FILE__);
		} // END get_base_url

    }
    $ITSG_GF_WYSIWYG_CKEditor = new ITSG_GF_WYSIWYG_CKEditor();
}