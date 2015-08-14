<?php

if (!class_exists('ITSG_GF_WYSIWYG_CKEditor_Settings_Page')) {
	class ITSG_GF_WYSIWYG_CKEditor_Settings_Page {

	/*
    * Settings page
    */
	 public static function settings_page(){

		   $settings = ITSG_GF_WYSIWYG_CKEditor::get_options();

			$is_submit = rgpost('itsg_gf_wysiwyg_ckeditor_settings_submit');

			if($is_submit){
			/* ENABLE IN FORM EDITOR */
				$settings['enable_in_form_editor'] = rgpost('in_form_editor');
			/* SOURCE */
				$settings['enable_source'] = rgpost('source');
			/* BASIC STYLES */ 
				$settings['enable_bold'] = rgpost('bold');
				$settings['enable_italic'] = rgpost('italic');
				$settings['enable_underline'] = rgpost('underline');
				$settings['enable_strike'] = rgpost('strike');
				$settings['enable_subscript'] = rgpost('subscript');
				$settings['enable_superscript'] = rgpost('superscript');
				$settings['enable_removeformat'] = rgpost('removeformat');							
			/* CLIPBOARD */
				$settings['enable_cut'] = rgpost('cut');
				$settings['enable_copy'] = rgpost('copy');
				$settings['enable_paste'] = rgpost('paste');
				$settings['enable_pastetext'] = rgpost('pastetext');
				$settings['enable_pastefromword'] = rgpost('pastefromword');
				$settings['enable_undo'] = rgpost('undo');
				$settings['enable_redo'] = rgpost('redo');	
			/* PARAGRAPH */
				$settings['enable_numberedlist'] = rgpost('numberedlist');
				$settings['enable_bulletedlist'] = rgpost('bulletedlist');
				$settings['enable_outdent'] = rgpost('outdent');
				$settings['enable_indent'] = rgpost('indent');
				$settings['enable_blockquote'] = rgpost('blockquote');
				$settings['enable_creatediv'] = rgpost('creatediv');
				$settings['enable_justifyleft'] = rgpost('justifyleft');	
				$settings['enable_justifycenter'] = rgpost('justifycenter');
				$settings['enable_justifyright'] = rgpost('justifyright');
				$settings['enable_justifyblock'] = rgpost('justifyblock');
				$settings['enable_bidiltr'] = rgpost('bidiltr');
				$settings['enable_bidirtl'] = rgpost('bidirtl');
				$settings['enable_language'] = rgpost('language');
			/* LINKS */
				$settings['enable_link'] = rgpost('link');
				$settings['enable_unlink'] = rgpost('unlink');
				$settings['enable_anchor'] = rgpost('anchor');
			/* DOCUMENT */
				$settings['enable_preview'] = rgpost('preview');
				$settings['enable_print'] = rgpost('print');
			/* EDITING */
				$settings['enable_find'] = rgpost('find');
				$settings['enable_replace'] = rgpost('replace');
				$settings['enable_selectall'] = rgpost('selectall');
				$settings['enable_scayt'] = rgpost('scayt');
			/* INSERT */
				$settings['enable_image'] = rgpost('image');
				$settings['enable_flash'] = rgpost('flash');
				$settings['enable_table'] = rgpost('table');
				$settings['enable_horizontalrule'] = rgpost('horizontalrule');
				$settings['enable_smiley'] = rgpost('smiley');
				$settings['enable_specialchar'] = rgpost('specialchar');
				$settings['enable_pagebreak'] = rgpost('pagebreak');
				$settings['enable_iframe'] = rgpost('iframe');
			/* STYLES */
				$settings['enable_styles'] = rgpost('styles');
				$settings['enable_format'] = rgpost('format');
				$settings['enable_font'] = rgpost('font');
				$settings['enable_fontsize'] = rgpost('fontsize');
			/* COLOURS */
				$settings['enable_textcolor'] = rgpost('textcolor');
				$settings['enable_bgcolor'] = rgpost('bgcolor');
			/* TOOLS */
				$settings['enable_maximize'] = rgpost('maximize');
				$settings['enable_showblocks'] = rgpost('showblocks');
			/* ABOUT */
				$settings['enable_about'] = rgpost('about');
			
				update_option('itsg_gf_wysiwyg_ckeditor_settings', $settings);
			}
			
			?>

			<form method="post">
				<?php wp_nonce_field("update", "itsg_gf_wysiwyg_ckeditor_update") ?>
				<input type="hidden" value="1" name="itsg_gf_wysiwyg_ckeditor_settings_submit" />
				<h3><?php _e("WYSIWYG CKEditor settings", "itsg_gf_wysiwyg_ckeditor") ?></h3>
				<h4><?php _e("Form editor settings", "itsg_gf_wysiwyg_ckeditor") ?></h4>
				<input type="checkbox" id="in_form_editor" name="in_form_editor" <?php echo rgar($settings, 'enable_in_form_editor') ? "checked='checked'" : "" ?>  >
				<label for="in_form_editor">Enable in form editor</label>
				<h4><?php _e("Toolbar settings", "itsg_gf_wysiwyg_ckeditor") ?></h4>
				<fieldset>
				 <legend>Source</legend>
					 <div>
						<ul>
						   <li>
							   <input type="checkbox" id="source" name="source" <?php echo rgar($settings, 'enable_source') ? "checked='checked'" : "" ?>  >
							   <label for="source">Source code</label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Basic styles</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="bold" name="bold" <?php echo rgar($settings, 'enable_bold') ? "checked='checked'" : "" ?> >
							<label for="bold">Bold</label>
						   </li>
						   <li>
							<input type="checkbox" id="italic" name="italic" <?php echo rgar($settings, 'enable_italic') ? "checked='checked'" : "" ?> >
							<label for="italic">Italic</label>
						   </li>
						   <li>
							<input type="checkbox" id="underline" name="underline" <?php echo rgar($settings, 'enable_underline') ? "checked='checked'" : "" ?> >
							<label for="underline">Underline</label>
						   </li>
						   <li>
							<input type="checkbox" id="strike" name="strike" <?php echo rgar($settings, 'enable_strike') ? "checked='checked'" : "" ?> >
							<label for="strike">Strike</label>
						   </li>
						   <li>
							<input type="checkbox" id="subscript" name="subscript" <?php echo rgar($settings, 'enable_subscript') ? "checked='checked'" : "" ?> >
							<label for="subscript">Subscript</label>
						   </li>
						   <li>
							<input type="checkbox" id="superscript" name="superscript" <?php echo rgar($settings, 'enable_superscript') ? "checked='checked'" : "" ?> >
							<label for="superscript">Superscript</label>
						   </li>
						   <li>
							<input type="checkbox" id="removeformat" name="removeformat" <?php echo rgar($settings, 'enable_removeformat') ? "checked='checked'" : "" ?> >
							<label for="removeformat">Remove format</label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Clipboard</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="cut" name="cut" <?php echo rgar($settings, 'enable_cut') ? "checked='checked'" : "" ?> >
							<label for="cut">Cut</label>
						   </li>
						   <li>
							<input type="checkbox" id="copy" name="copy" <?php echo rgar($settings, 'enable_copy') ? "checked='checked'" : "" ?> >
							<label for="copy">Copy</label>
						   </li>
						   <li>
							<input type="checkbox" id="paste" name="paste" <?php echo rgar($settings, 'enable_paste') ? "checked='checked'" : "" ?> >
							<label for="paste">Paste</label>
						   </li>
						   <li>
							<input type="checkbox" id="pastetext" name="pastetext" <?php echo rgar($settings, 'enable_pastetext') ? "checked='checked'" : "" ?> >
							<label for="pastetext">Paste as text</label>
						   </li>
						   <li>
							<input type="checkbox" id="pastefromword" name="pastefromword" <?php echo rgar($settings, 'enable_pastefromword') ? "checked='checked'" : "" ?> >
							<label for="pastefromword">Paste from word</label>
						   </li>
						   <li>
							<input type="checkbox" id="undo" name="undo" <?php echo rgar($settings, 'enable_undo') ? "checked='checked'" : "" ?> >
							<label for="undo">Undo</label>
						   </li>
						   <li>
							<input type="checkbox" id="redo" name="redo" <?php echo rgar($settings, 'enable_redo') ? "checked='checked'" : "" ?> >
							<label for="redo">Redo</label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Paragraph</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="numberedlist" name="numberedlist" <?php echo rgar($settings, 'enable_numberedlist') ? "checked='checked'" : "" ?> >
							<label for="numberedlist">Numbered list</label>
						   </li>
						   <li>
							<input type="checkbox" id="bulletedlist" name="bulletedlist" <?php echo rgar($settings, 'enable_bulletedlist') ? "checked='checked'" : "" ?> >
							<label for="bulletedlist">Bulleted list</label>
						   </li>
						   <li>
							<input type="checkbox" id="outdent" name="outdent" <?php echo rgar($settings, 'enable_outdent') ? "checked='checked'" : "" ?> >
							<label for="outdent">Outdent</label>
						   </li>
						   <li>
							<input type="checkbox" id="indent" name="indent" <?php echo rgar($settings, 'enable_indent') ? "checked='checked'" : "" ?> >
							<label for="indent">Indent</label>
						   </li>
						   <li>
							<input type="checkbox" id="blockquote" name="blockquote" <?php echo rgar($settings, 'enable_blockquote') ? "checked='checked'" : "" ?> >
							<label for="blockquote">Block quote</label>
						   </li>
						   <li>
							<input type="checkbox" id="creatediv" name="creatediv" <?php echo rgar($settings, 'enable_creatediv') ? "checked='checked'" : "" ?> >
							<label for="creatediv">Create div</label>
						   </li>
						   <li>
							<input type="checkbox" id="justifyleft" name="justifyleft" <?php echo rgar($settings, 'enable_justifyleft') ? "checked='checked'" : "" ?> >
							<label for="justifyleft">Justify left</label>
						   </li>
						   <li>
							<input type="checkbox" id="justifycenter" name="justifycenter" <?php echo rgar($settings, 'enable_justifycenter') ? "checked='checked'" : "" ?> >
							<label for="justifycenter">Justify center</label>
						   </li>
						   <li>
							<input type="checkbox" id="justifyright" name="justifyright" <?php echo rgar($settings, 'enable_justifyright') ? "checked='checked'" : "" ?> >
							<label for="justifyright">Justify right</label>
						   </li>
						   <li>
							<input type="checkbox" id="justifyblock" name="justifyblock" <?php echo rgar($settings, 'enable_justifyblock') ? "checked='checked'" : "" ?> >
							<label for="justifyblock">Justify block</label>
						   </li>
						   <li>
							<input type="checkbox" id="bidiltr" name="bidiltr" <?php echo rgar($settings, 'enable_bidiltr') ? "checked='checked'" : "" ?> >
							<label for="bidiltr">Bidirectional - left to right</label>
						   </li>
						   <li>
							<input type="checkbox" id="bidirtl" name="bidirtl" <?php echo rgar($settings, 'enable_bidirtl') ? "checked='checked'" : "" ?> >
							<label for="bidirtl">Bidirectional - right to left</label>
						   </li>
						   <li>
							<input type="checkbox" id="language" name="language" <?php echo rgar($settings, 'enable_language') ? "checked='checked'" : "" ?> >
							<label for="language">Language</label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Links</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="link" name="link" <?php echo rgar($settings, 'enable_link') ? "checked='checked'" : "" ?> >
							<label for="link">Link</label>
						   </li>
						   <li>
							<input type="checkbox" id="unlink" name="unlink" <?php echo rgar($settings, 'enable_unlink') ? "checked='checked'" : "" ?> >
							<label for="unlink">Unlink</label>
						   </li>
						   <li>
							<input type="checkbox" id="anchor" name="anchor" <?php echo rgar($settings, 'enable_anchor') ? "checked='checked'" : "" ?> >
							<label for="anchor">Anchor</label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Document</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="preview" name="preview" <?php echo rgar($settings, 'enable_preview') ? "checked='checked'" : "" ?> >
							<label for="preview">Preview</label>
						   </li>
						   <li>
							  <input type="checkbox" id="print" name="print" <?php echo rgar($settings, 'enable_print') ? "checked='checked'" : "" ?> >
							  <label for="print">Print</label>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Editing</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="find" name="find" <?php echo rgar($settings, 'enable_find') ? "checked='checked'" : "" ?> >
							<label for="find">Find</label>
						   </li>
						   <li>
							  <input type="checkbox" id="replace" name="replace" <?php echo rgar($settings, 'enable_replace') ? "checked='checked'" : "" ?> >
							  <label for="replace">Replace</label>
							</li>
							<li>
							<input type="checkbox" id="selectall" name="selectall" <?php echo rgar($settings, 'enable_selectall') ? "checked='checked'" : "" ?> >
							<label for="selectall">Select all</label>
						   </li>
						   <li>
							  <input type="checkbox" id="scayt" name="scayt" <?php echo rgar($settings, 'enable_scayt') ? "checked='checked'" : "" ?> >
							  <label for="scayt">Spell check as you type (SCAYT)</label>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Insert</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="image" name="image" <?php echo rgar($settings, 'enable_image') ? "checked='checked'" : "" ?> >
							<label for="image">Image</label>
						   </li>
						   <li>
							  <input type="checkbox" id="flash" name="flash" <?php echo rgar($settings, 'enable_flash') ? "checked='checked'" : "" ?> >
							  <label for="flash">Flash</label>
							</li>
							<li>
							<input type="checkbox" id="table" name="table" <?php echo rgar($settings, 'enable_table') ? "checked='checked'" : "" ?> >
							<label for="table">Table</label>
						   </li>
						   <li>
							  <input type="checkbox" id="horizontalrule" name="horizontalrule" <?php echo rgar($settings, 'enable_horizontalrule') ? "checked='checked'" : "" ?> >
							  <label for="horizontalrule">Horizontal rule</label>
							</li>
							<li>
							<input type="checkbox" id="smiley" name="smiley" <?php echo rgar($settings, 'enable_smiley') ? "checked='checked'" : "" ?> >
							<label for="smiley">Smiley</label>
						   </li>
						   <li>
							  <input type="checkbox" id="specialchar" name="specialchar" <?php echo rgar($settings, 'enable_specialchar') ? "checked='checked'" : "" ?> >
							  <label for="specialchar">Special character</label>
							</li>
							<li>
							<input type="checkbox" id="pagebreak" name="pagebreak" <?php echo rgar($settings, 'enable_pagebreak') ? "checked='checked'" : "" ?> >
							<label for="pagebreak">Page break</label>
						   </li>
						   <li>
							<input type="checkbox" id="iframe" name="iframe" <?php echo rgar($settings, 'enable_iframe') ? "checked='checked'" : "" ?> >
							<label for="iframe">iframe</label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Styles</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="styles" name="styles" <?php echo rgar($settings, 'enable_styles') ? "checked='checked'" : "" ?> >
							<label for="styles">Styles</label>
						   </li>
						   <li>
							  <input type="checkbox" id="format" name="format" <?php echo rgar($settings, 'enable_format') ? "checked='checked'" : "" ?> >
							  <label for="format">Format</label>
							</li>
							<li>
							<input type="checkbox" id="font" name="font" <?php echo rgar($settings, 'enable_font') ? "checked='checked'" : "" ?> >
							<label for="font">Font</label>
						   </li>
						   <li>
							  <input type="checkbox" id="fontsize" name="fontsize" <?php echo rgar($settings, 'enable_fontsize') ? "checked='checked'" : "" ?> >
							  <label for="fontsize">Font size</label>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Colours</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="textcolor" name="textcolor" <?php echo rgar($settings, 'enable_textcolor') ? "checked='checked'" : "" ?> >
							<label for="textcolor">Text color</label>
						   </li>
						   <li>
							  <input type="checkbox" id="bgcolor" name="bgcolor" <?php echo rgar($settings, 'enable_bgcolor') ? "checked='checked'" : "" ?> >
							  <label for="bgcolor">Background colour</label>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>Tools</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="maximize" name="maximize" <?php echo rgar($settings, 'enable_maximize') ? "checked='checked'" : "" ?> >
							<label for="maximize">Maximize</label>
						   </li>
						   <li>
							  <input type="checkbox" id="showblocks" name="showblocks" <?php echo rgar($settings, 'enable_showblocks') ? "checked='checked'" : "" ?> >
							  <label for="showblocks">Show blocks</label>
							</li>
						</ul>
					 </div>
				</fieldset>
				<fieldset>
				<legend>About</legend>
					 <div>
						<ul>
						   <li>
							<input type="checkbox" id="about" name="about" <?php echo rgar($settings, 'enable_about') ? "checked='checked'" : "" ?> >
							<label for="about">About CKEditor</label>
						   </li>
						</ul>
					 </div>
				</fieldset>
				
				<input type="submit" name="save settings" value="<?php _e("Save Settings", "itsg_gf_wysiwyg_ckeditor") ?>" class="button-primary" style="margin-top:40px;" />
			</form>

		   <?php

		}
		}

}