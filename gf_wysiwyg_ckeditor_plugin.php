<?php
/*
Plugin Name: Gravity Forms - WYSIWYG CKEditor
Description: Use the CKEditor WYSIWYG in your Gravity Forms
Version: 1.1.1
Author: Adrian Gordon
Author URI: http://www.itsupportguides.com 
License: GPL2
*/

add_action('admin_notices', array('ITSG_GF_WYSIWYG_CKEditor', 'admin_warnings'), 20);

require_once(plugin_dir_path( __FILE__ ).'gf_wysiwyg_ckeditor_settings.php');

if (!class_exists('ITSG_GF_WYSIWYG_CKEditor')) {
    class ITSG_GF_WYSIWYG_CKEditor
    {
	private static $name = 'Gravity Forms - WYSIWYG CKEditor';
    private static $slug = 'itsg_gf_wysiwyg_ckeditor';
	
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
		// register actions
            if (self::is_gravityforms_installed()) {
				//start plug in
				add_action('gform_enqueue_scripts',  array(&$this, 'itsg_gf_wysiwyg_ckeditor_js_script'), 10, 2);
				add_filter('gform_save_field_value', array(&$this, 'itsg_gf_wysiwyg_ckeditor_save_values'), 10, 4);
				add_action('gform_field_standard_settings', array(&$this, 'itsg_gf_wysiwyg_ckeditor_field_settings'), 10, 2);
				add_filter('gform_tooltips', array(&$this, 'itsg_gf_wysiwyg_ckeditor_field_tooltips'));
				add_action('gform_editor_js', array(&$this, 'itsg_gf_wysiwyg_ckeditor_editor_js'));
				add_action('gform_field_css_class', array(&$this,'itsg_gf_wysiwyg_ckeditor_css_class'), 10, 3);
				add_filter('gform_field_content',  array(&$this,'itsg_gf_wysiwyg_ckeditor_add_max_char'), 10, 5);
				add_filter('gform_counter_script', array(&$this,'itsg_gf_wysiwyg_ckeditor_counter_script'), 10, 4);
				register_activation_hook( __FILE__, array(&$this,'itsg_gf_wysiwyg_ckeditor_plugin_defaults'));
				
				if(RGForms::get("page") == "gf_settings") {
					// add settings page
					RGForms::add_settings_page("WYSIWYG ckeditor", array("ITSG_GF_WYSIWYG_ckeditor_settings_page", "settings_page"), self::get_base_url() . "/images/user-registration-icon-32.png");
				}
			}
		}
		
		/*
         * Place ckeditor JavaScript in footer, applies ckeditor to 'textarea' fields 
         */
		public static function itsg_gf_wysiwyg_ckeditor_js_script($form, $is_ajax) {
			foreach ( $form['fields'] as $field ) {
				if (self::is_wysiwyg_ckeditor($field)) {

				$ur_settings = get_option('ITSG_gf_wysiwyg_ckeditor_settings') ? get_option('ITSG_gf_wysiwyg_ckeditor_settings') : array();
				?>
				<script type="text/javascript" src="<?php echo plugins_url( '/ckeditor/ckeditor.js', __FILE__ ) ?>"></script>
				<script type="text/javascript" src="<?php echo plugins_url( '/ckeditor/adapters/jquery.js', __FILE__ ) ?>"></script>
				<script type="text/javascript">
				(function( $ ) {
					"use strict";
					$(function(){
						$('.gform_wysiwyg_ckeditor textarea').each(function() {
							$(this).ckeditor(CKEDITOR.tools.extend( {
							extraPlugins : 'wordcount', 
							wordcount : {
								showParagraphs : false,
								showWordCount: false,
								showCharCount: true, 
								charLimit: $(this).attr('data-maxlen'),
								hardLimit: true 
							},
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
							
							<?php if (self::is_dpr_installed() ) { ?>
							for (var i in CKEDITOR.instances) {
								CKEDITOR.instances[i].on('change', function() {changed = true;});
							}
							<?php } ?>
						});

					});
				}(jQuery));
				</script>

				<?php
				}
			}
		}
		
		/*
         * Customises 'Paragraph Text' field output to include character limit details
         */
		public static function itsg_gf_wysiwyg_ckeditor_add_max_char($content, $field, $value, $lead_id, $form_id){
			$field_type = rgar($field,"type");
			if ('post_content' == $field_type  || 'textarea' == $field_type || ('post_custom_field' == $field_type  && 'textarea' == $field['inputType'])) {
				$label = rgar($field,"label");
				$limit = rgar($field,"maxLength");
				if ('' == $limit) {$limit = 'unlimited';};
				$content = str_replace("<textarea ","<textarea data-maxlen='".$limit."' ",$content);
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
		public static function itsg_gf_wysiwyg_ckeditor_css_class($classes, $field, $form)
        {
			$field_type = rgar($field,"type");
			if ('post_content' == $field_type  || 'textarea' == $field_type || ('post_custom_field' == $field_type  && 'textarea' == $field['inputType'])) {
				if (array_key_exists('enable_wysiwyg_ckeditor', $field)) {
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
		}
		
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
		}
		
		/*
         * Tooltip for field in form editor
         */
		public static function itsg_gf_wysiwyg_ckeditor_field_tooltips($tooltips){
			$tooltips["form_field_enable_wysiwyg_ckeditor"] = "<h6>Enable WYSIWYG</h6>Check this box to turn this field into a WYSIWYG editor, using ckeditor.";
			return $tooltips;
		}

		/*
         * Checks if field is CKEditor enabled
         */
		private static function is_wysiwyg_ckeditor($field) {
			$type = self::get_type($field);
			if ('post_content' == $type || 'textarea' == $type) {
				if (array_key_exists('enable_wysiwyg_ckeditor', $field)) {
					return $field['enable_wysiwyg_ckeditor'] == 'true';
				}
			}
			return false;
		}
		
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
		}
		
		/*
         * Handles data from WYSIWYG when form is submitted
         */
		public static function itsg_gf_wysiwyg_ckeditor_save_values($value, $lead, $field, $form) {
			if (self::is_wysiwyg_ckeditor($field)) {
				$value = rgpost("input_{$field['id']}");
			}
			return $value;
		}
		
		/*
         * Warning message if Gravity Forms is installed and enabled
         */
		public static function admin_warnings() {
			if ( !self::is_gravityforms_installed() ) {
				$message = __('Requires Gravity Forms to be installed.', self::$slug);
			} 
			
			if (empty($message)) {
				return;
			}
			?>
			<div class="error">
				<p>
					<?php _e('The plugin ', self::$slug); ?><strong><?php echo self::$name; ?></strong> <?php echo $message; ?><br />
					<?php _e('Please ',self::$slug); ?><a href="http://www.gravityforms.com/"><?php _e(' download the latest version',self::$slug); ?></a><?php _e(' of Gravity Forms and try again.',self::$slug) ?>
				</p>
			</div>
			<?php
		}
		
		/*
         * Called when plugin activated, sets default options 
         */
		public static function itsg_gf_wysiwyg_ckeditor_plugin_defaults() {
			//the default options
			$ITSG_gf_wysiwyg_ckeditor_settings = array(
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
				'enable_fontsize' => 'on',
			);
		 
			//check to see if present already
			if(!get_option('ITSG_gf_wysiwyg_ckeditor_settings')) {
				//option not found, add new
				add_option('ITSG_gf_wysiwyg_ckeditor_settings', $ITSG_gf_wysiwyg_ckeditor_settings);
			}
		} // END itsg_gf_wysiwyg_ckeditor_plugin_defaults
		
        /*
         * Check if GF is installed
         */
        private static function is_gravityforms_installed()
        {
            return class_exists('GFAPI');
        } // END is_gravityforms_installed
		
		/*
         * Check if Gravity Forms - Data Persistence Reloaded is installed
         */
        private static function is_dpr_installed() 
        {
            return function_exists('ri_gfdp_ajax');
        } // END is_dpr_installed
		
		/*
         * Get plugin url
         */
		 private static function get_base_url(){
			return plugins_url(null, __FILE__);
		}
		
		
		
    }
    $ITSG_GF_WYSIWYG_CKEditor = new ITSG_GF_WYSIWYG_CKEditor();
}