=== CKEditor WYSIWYG for Gravity Forms ===
Contributors: ovann86
Donate link: http://www.itsupportguides.com/
Tags: Gravity Forms, CKEditor, WYSIWYG, WCAG, accessibility
Requires at least: 4.0
Tested up to: 4.2.5
Stable tag: 1.4.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use the CKEditor WYSIWYG in your Gravity Forms

== Description ==

This plugin extends the [Gravity Forms](http://www.gravityforms.com/ "Gravity Forms website") plugin, giving you the option to use [CKEditor](http://ckeditor.com/ "CKEditor website") in your forms.

The CKEditor can be configured from the Gravity Forms settings menu, allowing you to select which individual buttons are used in the editor.

The CKEditor can be added to the 'Paragraph Text' field found in the 'Standard Fields' tab as well as the 'Body' and 'Custom Field - Paragraph Text' fields found in the 'Post Fields' tab.

**Why use CKEditor?**

[CKEditor](http://ckeditor.com/ "CKEditor website") is a feature packed WYSIWYG that meets the WCAG 2.0 requirements, providing the best accessibility for your users.

This plugin is compatible with the [Gravity Forms Data Persistence Add-On Reloaded](https://wordpress.org/plugins/gravity-forms-data-persistence-add-on-reloaded/ "Gravity Forms Data Persistence Add-On Reloaded website") and [Gravity PDF](https://wordpress.org/plugins/gravity-forms-pdf-extended/ "Gravity PDF website") plugins.

**Disclaimer**

*Gravity Forms is a trademark of Rocketgenius, Inc.*

*This plugins is provided “as is” without warranty of any kind, expressed or implied. The author shall not be liable for any damages, including but not limited to, direct, indirect, special, incidental or consequential damages or losses that occur out of the use or inability to use the plugin.*

== Installation ==

1. Install plugin from WordPress administration or upload folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in the WordPress administration
1. Open the Gravity Forms 'Forms' menu
1. Open the form editor for the form you want to add CKEditor to
1. Either add a new 'Paragraph Text' field or select an existing one
1. Tick the 'Enable WYSIWYG (CKEditor)' option to enable CKEditor for the field
1. CKEditor will now be used for the fields you selected
1. OPTIONAL: Open the Gravity Forms settings page, the WYSIWYG CKEditor menu to select the buttons you want used in CKEditor

== Screenshots ==

1. Shows CKEditor with the default buttons and no character limit applied. The character count appears below CKEditor, showing the number of characters typed (not including spaces).
2. Shows CKEditor with the default buttons and the character limit set to 456 characters. The character count appears below CKEditor, showing the number of characters typed (not including spaces) and the limit.
3. Shows a 'Paragraph Text' field without CKEditor applied. The Gravity Forms default character count has been modified to replicate what is used with CKEditor.
4. Shows the 'Paragraph Text' field in the form editor. The 'Enable WYSIWYG (CKEditor)' is enabled.
5. Shows the WYSIWYG CKEditor settings page where you can configure which buttons are used in CKEditor.
6. Shows the WYSIWYG CKEditor with all the buttons enabled.
7. Shows the WYSIWYG CKEditor where the maximum characters have been met. The character counter shows the limit has been reached by changing to red. The WYSIWYG CKEditor field does not allow more text to be entered when the limit has been reached.
8. Shows the WYSIWYG CKEditor in the entry editor screen.

== Frequently Asked Questions ==

= What are the default buttons =

By default a limited number of the CKEditor buttons are used. These are:

 * Bold
 * Italic
 * Underline
 * Paste as text
 * Paste from word
 * Numbered list
 * Bulleted list
 * Outdent
 * Indent
 * Link
 * Unlink
 * Format
 * Font
 * Font size

= How do I enable more buttons? =

Open the Gravity Forms settings menu and select the WYSIWYG CKEditor menu.

Here you will find a list of all the available buttons that can be added to the CKEditor.

Open the Gravity Forms settings menu and select the WYSIWYG CKEditor menu.

Here you will find a list of all the available buttons that can be added to the CKEditor.

= What version of CKEditor does this use? =

CKEditor 4.5.2

= How does the character counter work? =

The character counter works by using the 'wordcount' CKEditor plugin. 

The plugin will use the 'Maximum Characters' configured for the field in the Gravity Forms form editor.

Most importantly, the plugin **will only count characters** - not spaces and not formatting HTML markup.

= Does this support image uploading? =

No - not at this point in time.

== Changelog ==

= 1.4.0 =
* Feature: add CKeditor support for the form editor. textarea fields which are included in the field settings will automatically use the CKeditor - this can be disabled from the CKEditor WYSIWYG settings page.
* Maintenance: resolve various PHP errors that were appearing in debug mode, but did not affect functionality.
* Maintenance: improve performance of CKeditor enabled fields by only applying CKeditor to fields on the current displayed page.
* Maintenance: improve how plugin default settings are handled - changed so settings are automatically saved to the database when first running the plugin, instead defaults are stored in an array and combined with any existing settings.

= 1.3.1 =
* Maintenance: Upgrade CKEDITOR to Version 4.5.2 - FULL (4 Aug 2015). Version 1.3.0 inadvertently included the CKEDITOR STANDARD - which does not include some of the formatting options.

= 1.3.0 =
* Fix: Resolve issue with plugin attempting to load before Gravity Forms has loaded, making this plugin not function.
* Fix: Change JavaScript to allow CKEDITOR enabled fields to work in ajax enabled multi-page forms.
* Maintenance: Upgrade CKEDITOR to Version 4.5.2 (4 Aug 2015). See CKEDITOR change log for full detail http://ckeditor.com/whatsnew 
* Maintenance: Change plugin name from 'Gravity Forms - WYSIWYG CKEditor' to 'CKEditor WYSIWYG for Gravity Forms'

= 1.2.1 =
* Fix: Make CKEditor only apply to WYSIWYG enabled fields in the entry editor screen. Was previously incorrectly applied to all paragraph (textarea) form fields.

= 1.2.0 =
* Feature: Add CKEditor support for entry editor screen - seen when editing entries with WYSIWYG enabled paragraph fields.
* Fix: Added function to remove blank lines between paragraphs when field is saved. This improves how the field is displayed in the entry editor screen and improves compatibility with GravityPDF.
* Maintenance: General tidy up of PHP layout. Use wp_enqueue_script for JS files. 

= 1.1.2 =
* Fix: Moved jQuery script to footer to resolve errors where it loaded before jQuery was ready.

= 1.1.1 =
* Fix: add space in textarea between 'data-maxlen' and 'name' attributes. All browsers worked without the space, but was technically invalid HTML.

= 1.1.0 =
* Feature: extended to work with 'Body' field (found under 'Post Fields' tab) and 'Custom Field - Paragraph Text'  field (found under 'Post Fields' tab).

= 1.0.1 =
* Fix: resolve issue that caused the 'Strength Indicator' in the Gravity Forms 'Password' field to not work.

= 1.0 =
* First public release.